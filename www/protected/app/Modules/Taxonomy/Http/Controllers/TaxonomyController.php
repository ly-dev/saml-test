<?php
namespace App\Modules\Taxonomy\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use App\Modules\Auditlog\Models\Auditlog;
use App\Modules\Auth\Models\User;
use App\Services\BladeUtilService;
use App\Modules\Taxonomy\Models\Variable;

class TaxonomyController extends Controller
{

    public function __construct(BladeUtilService $bladeUtilService)
    {
        $this->middleware([
            'auth',
            'permission:' . User::PERMISSION_ADMIN
        ]);

        $this->bladeUtilService = $bladeUtilService;
    }

    /**
     * List view
     *
     * @param Request $request
     * @param string $term
     * @return view content
     */
    public function index(Request $request, $term)
    {
        $meta = $this->getMeta($term);

        return view('taxonomy::taxonomy.index', [
            'term' => $term,
            'meta' => $meta,
            'modelName' => $meta['modelName']
        ]);
    }

    /**
     * List view - datatable ajax call
     *
     * @param Request $request
     * @param string $term
     * @return json encoded data
     */
    public function grid(Request $request, $term)
    {
        $meta = $this->getMeta($term);

        $result = [];

        $result['draw'] = $_GET['draw'];

        $length = $_GET['length'];
        $start = $_GET['start'];

        $dbQuery = DB::table($meta['dbTable'] . ' AS t');
        $selectFields = [
            't.id',
            't.name',
            't.sort_order',
            't.updated_at'
        ];
        if (! empty($meta['selectFields'])) {
            $selectFields = array_merge($selectFields, $meta['selectFields']);
        }
        $dbQuery->select($selectFields);

        // handle where

        // handle search
        $search = $_GET['search']['value'];
        if ($search) {
            $dbQuery->where('t.name', 'like', '%' . $search . '%');
        }

        // get count
        $count = $dbQuery->count();

        // handle order
        $orderMap = [];
        foreach ([
            'id',
            'name'
        ] as $field) {
            $orderMap[] = $field;
        }
        if (! empty($meta['selectFields'])) {
            foreach ($selectFields as $field) {
                $orderMap[] = $field;
            }
        }
        foreach ([
            'sort_order',
            'updated_at'
        ] as $field) {
            $orderMap[] = $field;
        }
        $orders = $_GET['order'];
        foreach ($orders as $order) {
            $dbQuery->orderBy($orderMap[$order['column']], $order['dir']);
        }

        // handle limit
        $data = [];
        $dbResult = $dbQuery->skip($start)
            ->take($length)
            ->get();
        foreach ($dbResult as $row) {
            // process model specific fields in the row
            if (! empty($meta['rowProcess'])) {
                $func = $meta['rowProcess'];
                $row = $this->{$func}($row, $dbResult);
            }
            $data[] = $row;
        }

        $result['data'] = $data;
        $result['recordsTotal'] = $count;
        $result['iTotalDisplayRecords'] = $count;
        $result['recordsFiltered'] = count($data);

        return response()->json($result);
    }

    /**
     * Detail view
     *
     * @param Request $request
     * @param string $term
     * @param integer $id
     * @return view content
     */
    public function view(Request $request, $term, $id)
    {
        $meta = $this->getMeta($term);
        $modelClass = $meta['modelClass'];

        $model = $modelClass::find($id);

        // set default values for create
        if (empty($model)) {
            $model = new $modelClass();
            $model->name = strtolower($meta['modelName']);
            $model->sort_order = 0;
        }

        return view('taxonomy::taxonomy.view', [
            'term' => $term,
            'title' => $meta['modelName'],
            'model' => $model
        ]);
    }

    /**
     * Process
     *
     * @param Request $request
     * @param string $term
     * @return view content
     *
     */
    public function process(Request $request, $term)
    {
        Session::flash('alert-class', 'alert-warning');
        Session::flash('message', 'Oops! Something goes wrong in ' . __FUNCTION__);

        $meta = $this->getMeta($term);

        $validatorRules = $meta['validatorRules'];
        $validatorMessages = $meta['validatorMessages'];

        $modelClass = $meta['modelClass'];
        $model = $modelClass::find($request->id);
        // create or update?
        if (empty($model)) {
            // customize unique with model specific values
            $validatorRules['name'] = $validatorRules['name'] . 'NULL,id';
            $isCreate = TRUE;
            $model = new $modelClass();
        } else {
            // customize unique with model specific values
            $validatorRules['name'] = $validatorRules['name'] . $model->id . ',id';
            $isCreate = FALSE;
        }

        Session::flash('message', t('Please review and revise your input'));
        $this->validate($request, $validatorRules, $validatorMessages);

        // process validation request
        $model->name = $request->name;
        $model->sort_order = intval($request->sort_order);

        DB::transaction(function () use ($meta, $request, $model) {
            // process model specific fields
            if (! empty($meta['process'])) {
                $func = $meta['process'];
                $this->{$func}($request, $model);
            }
            $model->save();
        });

        $resultMessage = t(':action :modelName :name', [
            ':action' => ($isCreate ? 'Created' : 'Updated'),
            ':modelName' => $meta['modelName'],
            ':name' => $model->name
        ]);
        Auditlog::info(Auditlog::CATEGORY_TAXONOMY, $resultMessage, $model->id);

        Session::flash('alert-class', 'alert-success');
        Session::flash('message', $resultMessage);

        return redirect('taxonomy/view/' . $term . '/' . (isset($model->id) ? $model->id : 'create'));
    }

    /**
     * Delete a record
     *
     * @param Request $request
     * @param string $term
     * @param integer $id
     * @return json encoded data
     */
    public function delete(Request $request, $term, $id)
    {
        $meta = $this->getMeta($term);
        $modelClass = $meta['modelClass'];

        $result = [
            '#status' => 'error',
            '#message' => 'Oops! Something goes wrong in ' . __FUNCTION__
        ];

        $model = $modelClass::find($id);
        if ($model) {
            try {
                $model->delete();

                $resultMessage = t('Deleted :modelName :name', [
                    ':modelName' => $meta['modelName'],
                    ':name' => $model->name
                ]);
                Auditlog::info(Auditlog::CATEGORY_TAXONOMY, $resultMessage, $model->id);

                $result['#status'] = 'success';
                $result['#message'] = $resultMessage;
            } catch (QueryException $e) {
                switch ($e->getCode()) {
                    case 23000:
                        $result['#message'] = t('Cannot delete because recorded is referred by others.');
                        break;
                    default:
                        $result['#message'] = $e->getMessage();
                        break;
                }
            }
        } else {
            $result['#message'] = 'Not found';
        }

        return response()->json($result);
    }

    private function getMeta($term)
    {
        $meta = $this->taxonomyMetas[$term];
        if (empty($meta)) {
            abort(404, t('Meta of :term not found.', [
                ':term' => $term
            ]));
        }

        return $meta;
    }

    private $taxonomyMetas = [
        'variable' => [
            'modelClass' => Variable::class,
            'dbTable' => 'variables',
            'selectFields' => [
                't.value'
            ],
            'columnNames' => [
                'Value'
            ],
            'listFields' => [
                "\"orderable\": true, \"data\": \"value\"",
            ],
            'listTitle' => 'Variables',
            'modelName' => 'Variable',
            'validatorRules' => [
                'name' => 'required|max:255|unique:variables,name,',
                'value' => 'max:65535'
            ],
            'validatorMessages' => [
                'name.required' => 'Name can\'t be empty',
                'value.required' => 'Value can\'t be empty'
            ],
            'process' => 'processVariable'
        ],
    ];

    /**
     * Process variable
     *
     * @param Request $request
     * @param Variable $model
     */
    private function processVariable(Request $request, Variable &$model)
    {
        $model->value = $request->value;
    }
}
