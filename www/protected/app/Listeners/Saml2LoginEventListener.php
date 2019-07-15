<?php

namespace App\Listeners;

use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use App\Modules\Auth\Models\SocialAccount;
use App\Modules\Auth\Models\User;
use Illuminate\Support\Facades\DB;

class Saml2LoginEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Saml2LoginEvent  $event
     * @return void
     */
    public function handle(Saml2LoginEvent $event)
    {
        // $messageId = $event->getSaml2Auth()->getLastMessageId();
        // your own code preventing reuse of a $messageId to stop replay attacks
        $samlUser = $event->getSaml2User();
        $samlIdp = $event->getSaml2Idp();
        $attributes = $samlUser->getAttributes();
        $typeMap = [
            'samltest' => SocialAccount::TYPE_SAMLTEST
        ];
        $samlUserData = [
            'provider_type' => $typeMap[$samlIdp],
            'provider_user_id' => $samlUser->getUserId(),
            'uid' => $attributes['urn:oid:0.9.2342.19200300.100.1.1'][0],
            'email' => $attributes['urn:oid:0.9.2342.19200300.100.1.3'][0],
            'telephoneNumber' => $attributes['urn:oid:2.5.4.20'][0],
            'surName' => $attributes['urn:oid:2.5.4.4'][0],
            'givenName' => $attributes['urn:oid:2.5.4.42'][0],
            'displayName' => $attributes['urn:oid:2.16.840.1.113730.3.1.241'][0],
            'assertion' => $samlUser->getRawSamlAssertion()
        ];

        $socialAccount = SocialAccount::find([
            'provider_type' => $samlUserData['provider_type'],
            'provider_user_id' => $samlUserData['provider_user_id'],
        ]);
        if (empty($socialAccount)) {
            DB::transaction(function() use ($samlUserData, &$socialAccount) {
                $laravelUser = User::where('email', $samlUserData['email'])->first();
                if (empty($laravelUser)) {
                    $laravelUser = User::create([
                        'name' => $samlUserData['displayName'],
                        'email'=> $samlUserData['email'],
                        'password' => bcrypt(str_random()),
                        'status' => User::STATUS_ACTIVE,
                        'email_verification' => time(),
                        'password_updated_at' => date('Y-m-d H:i:s')
                    ]);
                    $laravelUser->assignRole(User::ROLE_USER);
                }

                $socialAccount = SocialAccount::create([
                    'provider_type' => $samlUserData['provider_type'],
                    'provider_user_id' => $samlUserData['provider_user_id'],
                    'name' => $samlUserData['displayName'],
                    'email' => $samlUserData['email'],
                    'access_token' => $samlUserData['assertion'],
                    'user_id' => $laravelUser->id                  
                ]);    
            });
        }
        
        $laravelUser = $socialAccount->user;
        Auth::login($laravelUser);
    }
}
