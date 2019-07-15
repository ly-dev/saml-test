<?php
namespace App\Modules\Auth\Http\Middleware;

use Closure;

class EmailVerification
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // avoid ajax, before login and infinite redirect; allow logout
        $targetPath = $request->path();
        if (! $request->ajax() && $request->user() && ! in_array($targetPath, [
            'auth/email-verification',
            'auth/resend-email-verification',
            'auth/logout'
        ]) && (strpos($targetPath, 'auth/verify-email/') === FALSE)) {
            $user = $request->user();
            // force to complete email verification
            if (empty($user->email_verification)) {
                return redirect('/auth/email-verification');
            }
        }

        return $next($request);
    }
}