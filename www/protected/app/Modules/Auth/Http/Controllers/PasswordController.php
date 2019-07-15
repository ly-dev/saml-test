<?php
namespace App\Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends Controller
{
    /*
     * |--------------------------------------------------------------------------
     * | Password Reset Controller
     * |--------------------------------------------------------------------------
     * |
     * | This controller is responsible for handling password reset requests
     * | and uses a simple trait to include this behavior. You're free to
     * | explore this trait and override any methods you wish to tweak.
     * |
     */

    use ResetsPasswords;

    /**
     * Override view
     */
    protected $linkRequestView = 'auth::passwords.email';

    protected $resetView = 'auth::passwords.reset';

    protected $redirectTo = '/';

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
}
