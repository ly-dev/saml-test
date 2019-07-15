<?php
namespace App\Services;

use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use App\Modules\Taxonomy\Models\Variable;

class FacebookService
{

    private $fb = null;

    private $accessToken = null;

    private $oAuth2Client = null;

    private $tokenMetadata = null;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->fb = new Facebook(
            [
                'app_id' => Variable::getValue(Variable::FACEBOOK_APP_ID, '_'),
                'app_secret' => Variable::getValue(
                    Variable::FACEBOOK_APP_SECRET, '_'),
                'default_graph_version' => 'v3.2'
            ]);
    }

    /**
     * Get facebook login url
     *
     * @param string $path
     * @param array $parameters
     * @param boolean $secure
     *
     * @return string
     */
    public function getLoginUrl($path = 'facebook/login-callback', $parameters = [], $secure = true)
    {
        $helper = $this->fb->getRedirectLoginHelper();

        $callbackUrl = url($path, $parameters, $secure);

        // Optional permissions
        $permissions = [
            'email'
        ];

        $loginUrl = $helper->getLoginUrl($callbackUrl, $permissions);

        return $loginUrl;
    }

    /**
     * Get facebook access token in login the call back
     *
     * @return \Facebook\Authentication\AccessToken|NULL
     */
    public function getAccessToken()
    {
        $accessToken = NULL;

        $helper = $this->fb->getRedirectLoginHelper();

        // bypass CSRF check
        if (isset($_GET['state'])) {
            $helper->getPersistentDataHandler()->set('state', $_GET['state']);
        }

        try {
            $accessToken = $helper->getAccessToken();
        } catch (FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit();
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit();
        }

        if (! isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() .
                     "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit();
        }
        $this->accessToken = $accessToken;

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $this->fb->getOAuth2Client();
        $this->oAuth2Client = $oAuth2Client;

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        $this->tokenMetadata = $tokenMetadata;

        if (! $accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken(
                    $accessToken);
            } catch (FacebookSDKException $e) {
                echo "<p>Error getting long-lived access token: " .
                     $helper->getMessage() . "</p>\n\n";
                exit();
            }

            $this->accessToken = $accessToken;
        }

        return $accessToken;
    }

    function graphGet($endpoint)
    {
        return $this->fb->get($endpoint, $this->accessToken);
    }
}