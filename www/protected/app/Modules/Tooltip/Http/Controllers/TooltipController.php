<?php
namespace App\Modules\Tooltip\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use App\Modules\Auditlog\Models\Auditlog;
use App\Modules\Auth\Models\User;
use App\Modules\Tooltip\Models\Tooltip;

class TooltipController extends Controller
{

    public function __construct()
    {
        $this->middleware([
            'auth',
            'permission:' . User::PERMISSION_ADMIN
        ], [
            'except' => 'ajaxView'
        ]);
    }

    /**
     * List view
     *
     * @param Request $request
     * @return view content
     */
    public function index(Request $request)
    {
        return view('tooltip::tooltip.index', []);
    }

    /**
     * List view - datatable ajax call
     *
     * @param Request $request
     * @return json encoded data
     */
    public function grid(Request $request)
    {
        $result = [];

        $result['draw'] = $_GET['draw'];

        $length = $_GET['length'];
        $start = $_GET['start'];

        $dbQuery = DB::table('tooltips AS t');
        $dbQuery->select('t.page_id', 't.tooltip_id', 't.title', 't.updated_at');

        // handle where

        // handle search
        $search = $_GET['search']['value'];
        if ($search) {
            $dbQuery->where('t.page_id', 'like', '%' . $search . '%')->orWhere('t.tooltip_id', 'like', '%' . $search . '%')->orWhere('t.title', 'like', '%' . $search . '%');
        }

        // get count
        $count = $dbQuery->count();

        // handle order
        $orderMap = [
            0 => 'page_id',
            1 => 'tooltip_id',
            2 => 'title'
        ];
        $orders = $_GET['order'];
        foreach ($orders as $order) {
            $dbQuery->orderBy($orderMap[$order['column']], $order['dir']);
        }

        // handle limit
        $dbResult = $dbQuery->skip($start)
            ->take($length)
            ->get();

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
     * Create
     *
     * @param Request $request
     * @return view content
     */
    public function create(Request $request)
    {
        // set default values for create
        $model = new Tooltip();

        return view('tooltip::tooltip.view', [
            'model' => $model
        ]);
    }

    /**
     * Detail view
     *
     * @param Request $request
     * @param string $page_id
     * @param string $tooltip_id
     * @return view content
     */
    public function view(Request $request, $page_id, $tooltip_id)
    {
        $model = Tooltip::find([
            'page_id' => $page_id,
            'tooltip_id' => $tooltip_id
        ]);

        // add with default page_id and tooltip_id
        if (empty($model)) {
            $model = new Tooltip();
            $model->page_id = $page_id;
            $model->tooltip_id = $tooltip_id;
        }

        return view('tooltip::tooltip.view', [
            'model' => $model
        ]);
    }

    /**
     * Process
     *
     * @param Request $request
     * @return view content
     *
     */
    public function process(Request $request)
    {
        Session::flash('alert-class', 'alert-warning');
        Session::flash('message', 'Oops! Something goes wrong in ' . __FUNCTION__);

        $validatorRules = [
            'page_id' => 'required|max:30|alpha_dash',
            'tooltip_id' => 'required|max:30|alpha_dash',
            'title' => 'required|max:255',
            'content' => 'max:65535'
        ];
        $validatorMessages = [];

        $model = Tooltip::find([
            'page_id' => $request->page_id,
            'tooltip_id' => $request->tooltip_id
        ]);

        // create or update?
        if (empty($model)) {
            $isCreate = TRUE;
            $model = new Tooltip();
        } else {
            $isCreate = FALSE;
        }

        Session::flash('message', t('Please review and revise your input'));
        $this->validate($request, $validatorRules, $validatorMessages);

        // process validation request
        $model->page_id = $request->page_id;
        $model->tooltip_id = $request->tooltip_id;
        $model->title = $request->title;
        $model->description = $request->content;
        $model->save();

        $resultMessage = t(':action  tooltip :page_id/:tooltip_id', [
            ':action' => ($isCreate ? 'Created' : 'Updated'),
            ':page_id' => $model->page_id,
            ':tooltip_id' => $model->tooltip_id
        ]);
        Auditlog::info(Auditlog::CATEGORY_TOOLTIP, $resultMessage, $model->id);

        Session::flash('alert-class', 'alert-success');
        Session::flash('message', $resultMessage);

        return redirect('tooltip/view/' . (isset($model->page_id) ? urlencode($model->page_id) : 'create') . '/' . (isset($model->tooltip_id) ? urlencode($model->tooltip_id) : 'create'));
    }

    /**
     * Delete a record
     *
     * @param Request $request
     * @param string $page_id
     * @param string $tooltip_id
     * @return json encoded data
     */
    public function delete(Request $request, $page_id, $tooltip_id)
    {
        $result = [
            '#status' => 'error',
            '#message' => 'Oops! Something goes wrong in ' . __FUNCTION__
        ];

        $model = Tooltip::find([
            'page_id' => $page_id,
            'tooltip_id' => $tooltip_id
        ]);
        if ($model) {
            try {
                $model->delete();

                $resultMessage = t('Deleted tooltip :page_id/:tooltip_id', [
                    ':page_id' => $model->page_id,
                    ':tooltip_id' => $model->tooltip_id
                ]);
                Auditlog::info(Auditlog::CATEGORY_TOOLTIP, $resultMessage, $model->id);

                $result['#status'] = 'success';
                $result['#message'] = $resultMessage;
            } catch (QueryException $e) {
                $result['#message'] = 'Fail to delete. Record may be referred by others';
            }
        } else {
            $result['#message'] = 'Not found';
        }

        return response()->json($result);
    }

    /**
     * Ajax view
     *
     * @param Request $request
     * @param string $page_id
     * @param string $tooltip_id
     * @return view content
     */
    public function ajaxView(Request $request, $page_id, $tooltip_id)
    {
        $response = [];

        $model = Tooltip::find([
            'page_id' => $page_id,
            'tooltip_id' => $tooltip_id
        ]);

        // add with default page_id and tooltip_id
        if (empty($model)) {
            abort(404, 'not found');
        }

        $response['title'] = $model->title;
        $response['description'] = $model->description;

        return response()->json($response);
    }

}
