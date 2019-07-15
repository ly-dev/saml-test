<?php
namespace App\Modules\Auditlog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Modules\Auth\Models\User;
use App\Modules\Auditlog\Models\Auditlog;

class AuditlogController extends Controller
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
        return view('auditlog::auditlog.index', []);
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

        $dbQuery = DB::table('audit_logs AS t');
        $dbQuery->select('t.updated_at', 't.severity', 't.category', 't.activity', 't.ip_address', 'u.email AS user')->leftJoin('users AS u', 't.user_id', '=', 'u.id');

        // handle search
        $search = $_GET['search']['value'];
        if ($search) {
            $dbQuery->where(function ($query) use ($search) {
                $query->where('u.email', 'like', '%' . $search . '%')
                    ->orWhere('t.category', 'like', '%' . $search . '%')
                    ->orWhere('t.activity', 'like', '%' . $search . '%')
                    ->orWhere('t.ip_address', 'like', '%' . $search . '%');
            });
        }

        // get count
        $count = $dbQuery->count();

        // handle order
        $orderMap = [
            0 => 'updated_at',
            1 => 'severity',
            2 => 'category',
            4 => 'ip_address',
            5 => 'user'
        ];
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
            $row->severity = Auditlog::$SEVERITY_LABELS[$row->severity];
            $data[] = $row;
        }

        $result['data'] = $data;
        $result['recordsTotal'] = $count;
        $result['iTotalDisplayRecords'] = $count;
        $result['recordsFiltered'] = count($data);

        return response()->json($result);
    }
}
