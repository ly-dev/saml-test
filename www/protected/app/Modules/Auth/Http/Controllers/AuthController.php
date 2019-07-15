<?php
namespace App\Modules\Auth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Modules\Auth\Models\User;
use App\Modules\Auditlog\Models\Auditlog;
use App\Modules\Auth\Services\EmailService;
use Aacotroneo\Saml2\Facades\Saml2Auth;

class AuthController extends Controller
{
    /*
     * |--------------------------------------------------------------------------
     * | Registration & Login Controller
     * |--------------------------------------------------------------------------
     * |
     * | This controller handles the registration of new users, as well as the
     * | authentication of existing users. By default, this controller uses
     * | a simple trait to add these behaviors. Why don't you explore it?
     * |
     */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Override view
     */
    protected $loginView = 'auth::login';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(EmailService $emailService)
    {
        $this->middleware($this->guestMiddleware(), [
            'except' => 'logout'
        ]);

        $this->emailService = $emailService;
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        $credential = $request->only($this->loginUsername(), 'password');
        $credential['status'] = User::STATUS_ACTIVE;

        return $credential;
    }

    /**
     * After logged in to write audit log
     *
     * @param Request $request
     * @param User $authenticatedUser
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticated(Request $request, User $authenticatedUser)
    {
        Auditlog::info(Auditlog::CATEGORY_USER, t('Logged in'), $authenticatedUser->id);

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Log the user out of the application and write audit log
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $authenticatedUser = Auth::guard($this->getGuard())->user();
        if ($authenticatedUser) {
            Auditlog::info(Auditlog::CATEGORY_USER, t('Logged out'));
            Auth::guard($this->getGuard())->logout();
        }

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'your_name' => 'required|max:255',
            'your_email' => 'required|email|max:255|unique:users,email',
            'new_password' => 'required|min:6|max:255|confirmed',
            'agree' => 'required'
        ]);

        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }

        $userModel = $this->create($request->all());
        Auth::guard($this->getGuard())->login($userModel);

        Auditlog::info(Auditlog::CATEGORY_USER, t(':email signed up', [
            ':email' => $userModel->email
        ]), $userModel->id);

        return redirect('auth/email-verification');
    }

    /**
     * Create user model
     *
     * @param array $data
     * @return \App\Modules\Auth\Models\User
     */
    protected function create($data, $ignoreEmailVerification = false)
    {
        $user = new User();
        $user->name = $data['your_name'];
        $user->email = $data['your_email'];
        $user->password = bcrypt($data['new_password']);
        $user->status = User::STATUS_ACTIVE;
        $user->email_verification = ($ignoreEmailVerification ? Carbon::now()->getTimestamp() : NULL);

        DB::transaction(function () use ($user) {
            $user->save();
            $user->assignRole(User::ROLE_USER);
        });

        if (! $ignoreEmailVerification) {
            $this->emailService->sendVerificationEmail($user);
        }

        return $user;
    }

    /**
     * Handle a email verification request for the application.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $token
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function verifyEmail(Request $request, $token)
    {
        $verified = FALSE;
        $email = $request->email;

        if (! empty($email) && ! empty($token)) {
            $user = User::where('email', $email)->first();
            if (isset($user) && $user->id > 0) {
                if (empty($user->email_verification)) {
                    $broker = Password::broker(null);
                    if ($broker->tokenExists($user, $token)) {
                        // remove token and update user record
                        $broker->deleteToken($token);
                        $user->email_verification = Carbon::now()->getTimestamp();
                        $user->save();

                        $verified = TRUE;
                    }
                } else {
                    $verified = TRUE;
                }
            }
        }

        if ($verified) {
            Session::flash('alert-class', 'alert-success');

            if (Auth::guard()->check()) {
                Session::flash('message', 'Your email has been verified.');
                return redirect('/');
            } else {
                Session::flash('message', 'Your email has been verified. Please login and continue');
                return redirect('auth/login');
            }
        } else {
            Session::flash('alert-class', 'alert-warning');
            Session::flash('message', 'Failed to verify your email. Please try again.');

            if (Auth::guard()->check()) {
                return redirect('auth/email-verification');
            } else {
                return redirect('auth/login');
            }
        }
    }
}
