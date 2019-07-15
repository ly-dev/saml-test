<?php
namespace App\Modules\Auth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Modules\Auth\Models\User;
use App\Modules\Auditlog\Models\Auditlog;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware([
            'auth',
            'permission:' . User::PERMISSION_ADMIN
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
        return view('auth::user.index', []);
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

        $dbQuery = DB::table('users AS t');
        $dbQuery->select('t.id', 't.name', 't.email', 't.status', 't.updated_at');

        // handle where
        $dbQuery->where('t.id', '>', 0);

        // handle search
        $search = $_GET['search']['value'];
        if ($search) {
            $dbQuery->where(function ($query) use ($search) {
                $query->where('t.email', 'like', '%' . $search . '%')
                    ->orWhere('t.name', 'like', '%' . $search . '%');
            });
        }

        // get count
        $count = $dbQuery->count();

        // handle order
        $orderMap = [
            0 => 'name',
            1 => 'email',
            2 => 'status',
            4 => 'updated_at'
        ];
        $orders = $_GET['order'];
        foreach ($orders as $order) {
            $dbQuery->orderBy($orderMap[$order['column']], $order['dir']);
        }

        // handle limit
        $data = [];
        $userIds = [];
        $dbResult = $dbQuery->skip($start)
            ->take($length)
            ->get();
        foreach ($dbResult as $row) {
            $userIds[] = $row->id;
            $row->roles = NULL;
            $row->status = User::$STATUS_LABELS[$row->status];
            $data[$row->id] = $row;
        }

        // add roles and centres
        if (! empty($userIds)) {
            $dbQuery = DB::table('user_has_roles AS t');
            $dbQuery->select('t.user_id', DB::raw('GROUP_CONCAT(r.name SEPARATOR ",") AS roles'))
                ->leftJoin('roles AS r', 't.role_id', '=', 'r.id')
                ->whereIn('t.user_id', $userIds)
                ->groupBy('t.user_id');

            $dbResult = $dbQuery->get();
            foreach ($dbResult as $row) {
                $data[$row->user_id]->roles = $row->roles;
            }
        }

        $data = array_values($data);

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
     * @param
     *            $id
     * @return view content
     */
    public function view(Request $request, $id)
    {
        $model = User::findOrFail($id);

        return view('auth::user.view', [
            'model' => $model
        ]);
    }

    /**
     * Process
     *
     * @param Request $request
     */
    public function process(Request $request)
    {
        $myself = $request->user();

        Session::flash('alert-class', 'alert-warning');
        Session::flash('message', 'Oops! Something goes wrong in ' . __FUNCTION__);

        $model = User::findOrFail($request->id);
        $validatorRules = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $model->id . ',id',
            'status' => 'required|numeric',
            'roles' => 'required'
        ];

        $validatorMessages = [
            'email.required' => 'Email can\'t be empty',
            'roles.required' => 'Roles can\'t be empty'
        ];

        Session::flash('message', t('Please review and revise your input'));
        $this->validate($request, $validatorRules, $validatorMessages);

        // process validation request
        $model->name = $request->name;
        $model->email = $request->email;
        // not allow change own status to prevent lock out self
        if ($myself->id != $model->id) {
            $model->status = $request->status;
        }

        DB::transaction(function () use ($model, $request, $myself) {
            // not allow change own roles to prevent lock out self
            if ($myself->id != $model->id) {
                $model->roles()->sync((empty($request->roles) ? [] : $request->roles));
            }
            $model->save();
        });

        $resultMessage = t('User :email updated.', [
            ':email' => $model->email
        ]);
        Auditlog::info(Auditlog::CATEGORY_USER, $resultMessage, $model->id);

        Session::flash('alert-class', 'alert-success');
        Session::flash('message', $resultMessage);

        return redirect('auth/user/view/' . $model->id);
    }
}
