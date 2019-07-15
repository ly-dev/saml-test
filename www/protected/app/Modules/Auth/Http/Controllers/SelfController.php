<?php
namespace App\Modules\Auth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Modules\Auditlog\Models\Auditlog;
use App\Modules\Auth\Models\User;
use App\Modules\Auth\Services\EmailService;
use Carbon\Carbon;

class SelfController extends Controller
{

    public function __construct(EmailService $emailService)
    {
        $this->middleware([
            'auth',
            'permission:' . User::PERMISSION_USER
        ]);

        $this->emailService = $emailService;
    }

    /**
     * Change password view
     *
     * @param Request $request
     * @return view content
     */
    public function changePassword(Request $request)
    {
        return view('auth::self.password', []);
    }

    /**
     * Process change password
     *
     * @param Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function savePassword(Request $request)
    {
        $model = $request->user();

        Session::flash('alert-class', 'alert-warning');
        Session::flash('message', 'Please review and revise your input');
        $this->validate($request, [
            'old_password' => 'required|max:255|current_password:' . $model->id,
            'password' => 'required|min:6|max:255|confirmed'
        ]);

        $model->password = bcrypt($request->password);
        $model->password_updated_at = Carbon::now();
        $model->save();

        Auditlog::info(Auditlog::CATEGORY_USER, t('Password changed'));

        Session::flash('alert-class', 'alert-success');
        Session::flash('message', 'New password saved!');

        return redirect('auth/password/change');
    }

    /**
     * Email verification view
     *
     * @param Request $request
     * @return view content
     */
    public function showEmailVerificationForm(Request $request)
    {
        $model = $request->user();

        return view('auth::self.email-verification', [
            'model' => $model
        ]);
    }

    /**
     * Process email verification
     *
     * @param Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function processEmailVerification(Request $request)
    {
        $model = $request->user();

        if (empty($model->email_verification)) {
            Session::flash('alert-class', 'alert-warning');
            Session::flash('message', 'Please follow the below instruction and get your email verified.');

            return redirect('auth/email-verification');
        } else {
            Session::flash('alert-class', 'alert-success');
            Session::flash('message', 'Your email has been verified.');

            return redirect('/');
        }
    }

    /**
     * Resend email verification
     *
     * @param Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function resendEmailVerification(Request $request)
    {
        $model = $request->user();

        $this->emailService->sendVerificationEmail($model);

        Session::flash('alert-class', 'alert-success');
        Session::flash('message', 'Email with the new verification link has been sent to ' . $model->email);

        return redirect('auth/email-verification');
    }
}
