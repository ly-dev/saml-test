<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Services\FacebookService;
use App\Modules\Basicpage\Models\Basicpage;
use App\Modules\Taxonomy\Models\Variable;

/**
 *
 * @property FacebookService $fbService
 *
 */
class AppApiController extends Controller
{

    use AuthenticatesUsers, ThrottlesLogins;

    /**
     * Create A New Controller Instance.
     *
     * @return Void
     */
    public function __construct(FacebookService $fbService)
    {
        $this->fbService = $fbService;
    }

    /**
     * Provide a list of api settings.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function syncApp(Request $request)
    {
        $response = [
            '#status' => 'error',
            '#message' => 'Oops! Something goes wrong in ' . __FUNCTION__,
            '#data' => []
        ];

        $response['#data'][Variable::GEOSERVER_API_URL] = Variable::getValue(
            Variable::GEOSERVER_API_URL, url('/'));
        $response['#data'][Variable::GEOCODER_API_URL] = Variable::getValue(
            Variable::GEOCODER_API_URL,
            'https://geocoder.api.here.com/6.2/geocode.json');
        $response['#data'][Variable::GEOCODER_API_ID] = Variable::getValue(
            Variable::GEOCODER_API_ID, 'PSWX411pMLAiYotlreL4');
        $response['#data'][Variable::GEOCODER_API_CODE] = Variable::getValue(
            Variable::GEOCODER_API_CODE, '_21HbSgD5QnFyUJgmz1Hzw');

        $response['#data']['META'] = [];
        $response['#data']['META']['FACEBOOK_LOGIN_URL'] = $this->fbService->getLoginUrl();

        $response['#status'] = 'success';
        $response['#message'] = 'Data update available';

        return response()->json($response);
    }
}
