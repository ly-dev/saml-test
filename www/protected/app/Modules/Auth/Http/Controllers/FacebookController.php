<?php
namespace App\Modules\Auth\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\FacebookService;
use App\Modules\Auth\Models\SocialAccount;
use Illuminate\Support\Facades\DB;
use App\Modules\Auth\Models\User;
use Carbon\Carbon;

/**
 *
 * @property FacebookService $fbService
 *
 */
class FacebookController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FacebookService $fbService)
    {
        $this->fbService = $fbService;
    }

    /**
     * login
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $loginUrl = $this->fbService->getLoginUrl();

        return redirect($loginUrl);
    }

    /**
     * login callback
     *
     * @return \Illuminate\Http\Response
     */
    public function loginCallback(Request $request)
    {
        $result = [
            'status' => 'error',
            'message' => 'Oops! Something goes wrong in ' . __FUNCTION__
        ];

        $accessToken = $this->fbService->getAccessToken();

        if ($accessToken) {

            $oResponse = $this->fbService->graphGet('/me?fields=id,name,email');
            $graphUser = $oResponse->getGraphUser();

            $socialAccount = SocialAccount::find([
                'provider_type' => SocialAccount::TYPE_FACEBOOK,
                'provider_user_id' => $graphUser->getId()
            ]);
            if (empty($socialAccount)) {
                $socialAccount = new SocialAccount();
                $socialAccount->provider_type = SocialAccount::TYPE_FACEBOOK;
                $socialAccount->provider_user_id = $graphUser->getId();
            }
            $socialAccount->email = $graphUser->getEmail();
            $socialAccount->name = $graphUser->getName();
            $socialAccount->access_token = $accessToken->getValue();
            $socialAccount->expire_at = $accessToken->getExpiresAt();

            $userModel = User::where('email', $socialAccount->email)->first();
            try {
                DB::transaction(function () use (&$socialAccount, &$userModel) {
                    if (empty($userModel)) {
                        $userModel = new User();
                        $userModel->name = $socialAccount->name;
                        $userModel->email = $socialAccount->email;
                        $userModel->password = bcrypt(str_random(32));
                        $userModel->status = User::STATUS_ACTIVE;
                        $userModel->email_verification = Carbon::now()->getTimestamp();
                        $userModel->save();
                        $userModel->assignRole(User::ROLE_USER);
                    }

                    $socialAccount->user_id = $userModel->id;
                    $socialAccount->save();
                });

                $result['status'] = 'success';
                unset($result['message']);
                $result['type'] = $socialAccount->provider_type;
                $result['user_id'] = $socialAccount->provider_user_id;
                $result['token'] = $socialAccount->access_token;
                $result['expire_at'] = $socialAccount->expire_at;
            } catch (\Exception $e) {
                $result['message'] = $e->getMessage();
            }
        }

        $url = url('facebook/login-result?' . http_build_query($result));

        return redirect($url);
    }

    /**
     * login callback
     *
     * @return \Illuminate\Http\Response
     */
    public function loginResult(Request $request)
    {
        return view('auth::facebook.result', [
            'result' => $request->all()
        ]);
    }
}
