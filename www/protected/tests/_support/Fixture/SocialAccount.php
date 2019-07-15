<?php
namespace Fixture;

use Carbon\Carbon;

class SocialAccount
{

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function make($data = [])
    {
        $hash = substr(md5(microtime() . mt_rand()), 0, 8);

        $data = $data + [
            'provider_type' => \App\Modules\Auth\Models\SocialAccount::TYPE_FACEBOOK,
            'provider_user_id' => "_provider_user_id_{$hash}",
            'name' => "_first_{$hash} _last_{$hash}",
            'access_token' => "_access_token_{$hash}",
            'expire_at' => Carbon::now(),
        ];

        return new \App\Modules\Auth\Models\SocialAccount($data);
    }

    public function create($data = [])
    {
        // force to bind a user
        if (empty($data['user_id'])) {
            $userModel = $this->user->create();
            $data['user_id'] = $userModel->id;
        } else {
            $userModel = \App\Modules\Auth\Models\User::find($data['user_id']);
        }

        // force to use same email
        $data['email'] = $userModel->email;

        $socialAccount = $this->make($data);

        $socialAccount->save();

        return $socialAccount;
    }
}