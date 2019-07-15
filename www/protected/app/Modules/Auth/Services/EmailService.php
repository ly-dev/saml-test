<?php
namespace App\Modules\Auth\Services;

use Illuminate\Support\Facades\Mail;
use App\Modules\Auth\Models\User;
use Illuminate\Support\Facades\Password;

class EmailService
{

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Send verification email
     *
     * @param User $user
     */
    public function sendVerificationEmail(User $user)
    {
        // get default password broker
        $broker = Password::broker(null);
        $token = $broker->createToken($user);

        Mail::send('auth::emails.signup', [
            'name' => $user->name,
            'link' => url('auth/verify-email', $token) . '?email=' . urlencode($user->getEmailForPasswordReset())
        ], function ($message) use ($user) {
            $message->to($user->getEmailForPasswordReset())->subject('Email verification!');
        });
    }
}