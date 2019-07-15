<?php
namespace App\Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PermissionCheck
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if (! $request->user() || ! $request->user()->can($permission)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized', 403);
            } else {
                // force logout
                Auth::guard()->logout();
                return redirect()->guest('/auth/login');
            }
        }

        return $next($request);
    }
}
