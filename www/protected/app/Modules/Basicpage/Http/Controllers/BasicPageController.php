<?php
namespace App\Modules\Basicpage\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Modules\Basicpage\Models\Basicpage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Modules\Auth\Models\User;
use App\Modules\Auditlog\Models\Auditlog;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class BasicPageController extends Controller
{

    public function __construct()
    {
        $this->middleware(
            [
                'auth',
                'permission:' . User::PERMISSION_ADMIN
            ],
            [
                'except' => [
                    'show'
                ]
            ]);
    }

    /**
     * List view
     *
     * @param Request $request
     * @return Response view content
     */
    public function index(Request $request)
    {
        return view('basicpage::basicpage.index', []);
    }

    /**
     * List view - datatable ajax call
     *
     * @param Request $request
     * @return Response json encoded data
     */
    public function grid(Request $request)
    {
        $result = [];

        $result['draw'] = $_GET['draw'];

        $length = $_GET['length'];
        $start = $_GET['start'];

        $dbQuery = DB::table('basicpages AS bp');
        $dbQuery->select('bp.id', 'bp.title', 'bp.slug', 'bp.updated_at');

        // handle where

        // handle search
        $search = $_GET['search']['value'];
        if ($search) {
            $dbQuery->where('bp.title', 'like', '%' . $search . '%');
        }

        // clone for count, before basicpage after group
        $dbQuery2 = clone $dbQuery;

        // handle basicpage
        $orderMap = [
            0 => 'title',
            1 => 'slug',
            2 => 'updated_at'
        ];
        $orders = $_GET['order'];
        foreach ($orders as $order) {
            $dbQuery->orderBy($orderMap[$order['column']], $order['dir']);
        }

        // handle limit
        $dbResult = $dbQuery->skip($start)
            ->take($length)
            ->get();

        // count total trick
        $dbQuery2->select(DB::raw('SQL_CALC_FOUND_ROWS *'))->get();
        $total = DB::select('SELECT FOUND_ROWS() as count');
        $count = $total[0]->count;

        // prepare result
        $data = [];
        foreach ($dbResult as $row) {
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
     * @param integer $id
     * @return Response view content
     */
    public function view(Request $request, $id)
    {
        $basicpageModel = Basicpage::findOrFail($id);
        return view('basicpage::basicpage.view',
            [
                'basicpageModel' => $basicpageModel
            ]);
    }

    /**
     * Process
     *
     * @param Request $request
     * @return Response view content
     *
     */
    public function process(Request $request)
    {
        Session::flash('alert-class', 'alert-warning');
        Session::flash('message',
            'Oops! Something goes wrong in ' . __FUNCTION__);

        // validate input
        $validatorRules = [
            'title' => 'required|max:255|unique:basicpages,title,',
            'body' => 'max:65535',
            'slug' => 'alpha_dash|unique:basicpages,slug,'
        ];
        $validatorMessages = [];

        // customize unique with model specific values
        $basicpageModel = Basicpage::find($request->id);
        if (empty($basicpageModel)) {
            $basicpageModel = new Basicpage();
            $validatorRules['title'] = $validatorRules['title'] . 'NULL';
            $validatorRules['slug'] = $validatorRules['slug'] . 'NULL';
            $isCreate = TRUE;
        } else {
            $validatorRules['title'] = $validatorRules['title'] .
                 $basicpageModel->id;
            $validatorRules['slug'] = $validatorRules['slug'] .
                 $basicpageModel->id;
            $isCreate = FALSE;
        }

        Session::flash('message', t('Please review and revise your input'));
        $this->validate($request, $validatorRules, $validatorMessages);

        // process validation request
        DB::transaction(
            function () use ($request, &$basicpageModel) {
                $basicpageModel->title = $request->title;
                $basicpageModel->body = $request->body;

                // if not manually set, generate slug for page so we can use BasicPage routes in a generic way
                if (isset($request->slug) && $request->slug != "") {
                    $basicpageModel->slug = $request->slug;
                } else {
                    $slug = Str::slug($request->title, '_');
                    $basicpageModel->slug = $slug;
                }

                $basicpageModel->save();
            });

        $resultMessage = t(':action the basic page: :name',
            [
                ':action' => ($isCreate ? 'Created' : 'Updated'),
                ':name' => $basicpageModel->title
            ]);
        Auditlog::info(Auditlog::CATEGORY_BASICPAGE, $resultMessage,
            $basicpageModel->id);

        Session::flash('alert-class', 'alert-success');
        Session::flash('message', $resultMessage);

        return redirect(
            'basicpage/view/' .
                 (isset($basicpageModel->id) ? $basicpageModel->id : 'create'));
    }

    /**
     * Create
     *
     * @param Request $request
     * @return Response view content
     */
    public function create(Request $request)
    {

        // set default values for create
        $basicpageModel = new Basicpage();
        return view('basicpage::basicpage.view',
            [
                'basicpageModel' => $basicpageModel
            ]);
    }

    /**
     * Delete a record
     *
     * @param Request $request
     * @param integer $id
     * @return Response json encoded data
     */
    public function delete(Request $request, $id)
    {
        $result = [
            '#status' => 'error',
            '#message' => 'Oops! Something went wrong in ' . __FUNCTION__
        ];

        $basicpageModel = Basicpage::findOrFail($id);
        try {
            $basicpageModel->delete();

            $resultMessage = t('Deleted basicpage: :name',
                [
                    ':name' => $basicpageModel->title
                ]);
            Auditlog::info(Auditlog::CATEGORY_BASICPAGE, $resultMessage,
                $basicpageModel->id);

            $result['#status'] = 'success';
            $result['#message'] = $resultMessage;
        } catch (QueryException $e) {
            switch ($e->getCode()) {
                default:
                    $result['#message'] = $e->getMessage();
                    break;
            }
        }

        return response()->json($result);
    }

    /**
     * Show basic page
     *
     * @param Request $request
     * @param integer $slug
     * @return Response view content
     */
    public function show(Request $request, $slug)
    {
        $basicpageModel = Basicpage::where('slug', $slug)->orWhere('id', $slug)->first();
        if (empty($basicpageModel)) {
            abort(404, "Page \"{$slug}\" not found");
        }

        return view('basicpage::basicpage.show',
            [
                'model' => $basicpageModel
            ]);
    }
}
