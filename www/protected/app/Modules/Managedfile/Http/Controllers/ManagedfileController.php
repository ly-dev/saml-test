<?php
namespace App\Modules\Managedfile\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use App\Modules\Auditlog\Models\Auditlog;
use App\Modules\Auth\Models\User;
use App\Modules\Managedfile\Models\Managedfile;

class ManagedfileController extends Controller
{

    public function __construct()
    {
        $this->middleware([
            'auth',
            'permission:' . User::PERMISSION_USER
        ]);
    }

    /**
     * Private file content
     *
     * @param Request $request
     * @return string content
     */
    public function privateFile(Request $request, $id)
    {
        $managedFile = Managedfile::find($id);

        if (empty($managedFile)) {
            abort(404, "File {$id} not found");
        }

        $managedFile->showFile($request->download);
    }
}
