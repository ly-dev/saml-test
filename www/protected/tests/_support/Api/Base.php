<?php
namespace Api;

use Carbon\Carbon;

abstract class Base
{

    const RESULT_STATUS_SUCCESS = 'success';

    const RESULT_STATUS_UPDATED = 'updated';

    const RESULT_STATUS_SAVED = 'saved';

    const RESULT_STATUS_ERROR = 'error';

    const URI_SIGN_IN = "/sign-in";

    protected $apiBase = '/api/v1';

    protected $tester;

    /**
     * Latest api result as json string decoded into associated array
     *
     * @var array
     */
    protected $apiResult;

    /**
     * Latest raw result as string
     *
     * @var string
     */
    protected $rawResult;

    /**
     * test data instance and preparation *
     */
    public $participant = NULL;

    public $socialAccount = NULL;

    public $password = NULL;

    public $user = NULL;

    /**
     * Api token after user logged in
     *
     * @var string
     */
    protected $apiToken = NULL;

    /**
     * Construct
     *
     * @param \ApiTester $tester
     */
    public function __construct(\ApiTester $tester)
    {
        $this->tester = $tester;

        // initialize user models according to test seeder
        $this->userAdmin = $this->findUserModel(\TestSeeder::$testUserMetas[0]);
        $this->userModerator = $this->findUserModel(\TestSeeder::$testUserMetas[1]);
        $this->userNormal = $this->findUserModel(\TestSeeder::$testUserMetas[2]);
    }

    /**
     * Generate hash
     *
     * @return string
     */
    public function generateHash()
    {
        return substr(md5(microtime() . mt_rand()), 0, 15);
    }

    /**
     * Find user model according to user array information
     *
     * @param array $user
     * @return \App\Modules\Auth\Models\User
     */
    protected function findUserModel($user)
    {
        return \App\Modules\Auth\Models\User::where('email', $user['email'])->first();
    }

    /**
     * Get api endpoint
     *
     * @param string $uri
     * @return string
     */
    protected function getEndPoint($uri)
    {
        return $this->apiBase . $uri;
    }

    /**
     * Set request headers
     *
     * @param string $token
     */
    protected function prepareRequestHeaders($token = null)
    {
        $this->tester->haveHttpHeader('Content-Type', 'application/json');
        $this->tester->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $this->tester->haveHttpHeader('Accept', 'application/json');

        if (isset($token)) {
            $this->tester->amBearerAuthenticated($token);
        } else if (isset($this->apiToken)) {
            $this->tester->amBearerAuthenticated($this->apiToken);
        }
    }

    /**
     * Use the model to generate expected value array
     *
     * @param Object $model
     * @param array $fields
     */
    protected function generateExpectedValue($model, $fields)
    {
        $result = [];
        foreach ($fields as $field) {
            $result[$field] = $model->{$field};
        }
        return $result;
    }

    /**
     * Invoke api post and accept any response
     *
     * @param string $uri
     * @param array|\JsonSerializable $params
     * @param array $files
     * @return string
     */
    public function apiPostForRawResponse($uri, $params = null, $files = [], $token = null)
    {
        $params = $this->toArray($params);

        $this->prepareRequestHeaders($token);
        $this->tester->sendPOST($this->getEndPoint($uri), $params, $files);
        $response = $this->tester->dumpResponse();
        $this->rawResult = $response;

        return $this->rawResult;
    }

    /**
     * Invoke api get and accept any response
     *
     * @param string $uri
     * @param array|\JsonSerializable $params
     *
     * @return string
     */
    public function apiGetForRawResponse($uri, $params = null, $token = null)
    {
        $params = $this->toArray($params);

        $this->prepareRequestHeaders($token);
        $this->tester->sendGET($this->getEndPoint($uri), $params);
        $response = $this->tester->dumpResponse();
        $this->rawResult = $response;

        return $this->rawResult;
    }

    /**
     * Invoke api post and expect successful json response
     *
     * @param string $uri
     * @param array|\JsonSerializable $params
     * @param array $files
     *
     * @return array
     */
    public function apiPost($uri, $params = null, $files = [], $token = null)
    {
        $params = $this->toArray($params);

        $this->prepareRequestHeaders($token);
        // @TODO disable debug
        // $uri = $uri . '?XDEBUG_SESSION_START=ECLIPSE_DBGP';
        $this->tester->sendPOST($this->getEndPoint($uri), $params, $files);
        $response = $this->tester->dumpResponse();
        // @TODO remove debug line below
        // print var_export($response, true);
        $this->tester->seeResponseCodeIs(200);
        $this->tester->seeResponseIsJson();

        $this->rawResult = $response;
        $this->apiResult = empty($response) ? [] : json_decode($response, true);

        return $this->apiResult;
    }

    /**
     * Invoke api get and expect successful json response
     *
     * @param string $uri
     * @param array|\JsonSerializable $params
     *
     * @return array
     */
    public function apiGet($uri, $params = null, $token = null)
    {
        $params = $this->toArray($params);

        $this->prepareRequestHeaders($token);
        $this->tester->sendGET($this->getEndPoint($uri), $params);
        $response = $this->tester->dumpResponse();
        $this->tester->seeResponseCodeIs(200);
        $this->tester->seeResponseIsJson();

        $this->rawResult = $response;
        $this->apiResult = empty($response) ? [] : json_decode($response, true);

        return $this->apiResult;
    }

    /**
     * Convert to array
     *
     * @param
     *            object or array
     *
     * @return array
     */
    public function toArray($params)
    {
        $result = [];

        if (! empty($params)) {
            $result = json_decode(json_encode($params), true);
        }

        return $result;
    }

    /**
     * get latest api call raw result
     *
     * @return string
     */
    public function getRawResult()
    {
        return $this->rawResult;
    }

    /**
     * get latest api call result
     *
     * @return array
     */
    public function getResult()
    {
        return $this->apiResult;
    }

    /**
     * get part from result
     *
     * @parem string $key, part key
     *
     * @return mixed
     */
    public function getResultPart($key)
    {
        return isset($this->apiResult[$key]) ? $this->apiResult[$key] : null;
    }

    /**
     * get status from result
     *
     * @return string
     */
    public function getResultStatus()
    {
        return $this->getResultPart('#status');
    }

    /**
     * get message from result
     *
     * @return string
     */
    public function getResultMessage()
    {
        return $this->getResultPart('#message');
    }

    /**
     * get data from result
     *
     * @return array
     */
    public function getResultData()
    {
        return $this->getResultPart('#data');
    }

    /**
     * get errors from result
     *
     * @return array
     */
    public function getResultErrors()
    {
        return $this->getResultPart('#errors');
    }

    /**
     * asert result status
     *
     * @param string $expected
     */
    public function seeResultStatus($expected)
    {
        $this->tester->assertEquals($expected, $this->getResultStatus());
    }

    /**
     * assert result message
     *
     * @param string $expected
     */
    public function seeResultMessage($expected)
    {
        $this->tester->assertContains($expected, $this->getResultMessage());
    }

    /**
     * asert result errors
     *
     * @param array $expected
     */
    public function seeResultErrors($expected)
    {
        $this->tester->assertEquals($expected, $this->getResultErrors());
    }

    /**
     * assert result success with message
     *
     * @param string $expected
     *            , default 'Data update available'
     */
    public function seeResultSuccessWithMessage($expected = 'Data update available')
    {
        $this->seeResultStatus(self::RESULT_STATUS_SUCCESS);
        $this->seeResultMessage($expected);
    }

    /**
     * assert result updated with message
     *
     * @param string $expected
     *            , default 'Data updated already'
     */
    public function seeResultUpdatedWithMessage($expected = 'Data updated already')
    {
        $this->seeResultStatus(self::RESULT_STATUS_UPDATED);
        $this->seeResultMessage($expected);
    }

    /**
     * assert result saved with message
     *
     * @param string $expected,
     *            default 'Data saved on server'
     */
    public function seeResultSavedWithMessage($expected = 'Data saved on server')
    {
        $this->seeResultStatus(self::RESULT_STATUS_SAVED);
        $this->seeResultMessage($expected);
    }

    /**
     * assert result error with message
     *
     * @param string $expected
     *            , default 'Oops! Something goes wrong in'
     */
    public function seeResultErrorWithMessage($expected = 'Oops! Something goes wrong in')
    {
        $this->seeResultStatus(self::RESULT_STATUS_ERROR);
        $this->seeResultMessage($expected);
    }

    /**
     * assert unauthorized error
     */
    public function seeUnauthorized()
    {
        $this->tester->seeResponseCodeIs(401);
        $this->tester->assertEquals('Unauthorized', $this->getRawResult());
    }

    /**
     * See all properties of the model in the data array
     *
     * @param array, $keys
     * @param object, $model
     * @param array, $data
     */
    public function seeModelProperties($keys, $model, $data)
    {
        foreach ($keys as $idx => $key) {
            // allow asscociated array for model and data key mapping
            $k1 = (is_numeric($idx) ? $key : $idx);
            $k2 = $key;

            $this->tester->assertEquals($model->{$k1}, $data[$k2]);
        }
    }

    /**
     * Sign in
     *
     * @param array $data
     *
     * @return array
     */
    public function signIn($data)
    {
        return $this->apiPost(self::URI_SIGN_IN, $data);
    }

    /**
     * prepare a user and logged in
     */
    public function prepareUserAndLoggedIn()
    {
        if (! isset($this->user)) {
            $this->prepareUser();
        }

        $resp = $this->signIn([
            'email' => $this->user->email,
            'password' => $this->password
        ]);
        $this->seeResultSuccessWithMessage('signed in successfully');

        $result = $this->getResultData();
        $this->apiToken = $result['api_token'];

        $this->user = $this->user->fresh();

        return $this->user;
    }

    /**
     * prepare the participant
     */
    public function prepareParticipant()
    {
        $this->participant = $this->tester->db->participant->create();
        return $this->participant;
    }

    /**
     * prepare the user
     */
    public function prepareUser()
    {
        if (! isset($this->participant)) {
            $this->prepareParticipant();
        }

        $this->password = str_random();
        $this->user = $this->tester->db->user->create([
            'password' => $this->password
        ]);

        // link user to participant
        $this->participant->user_id = $this->user->id;
        $this->participant->save();

        return $this->user;
    }

    /**
     * prepare social account
     */
    public function prepareSocialAccount()
    {
        if (! isset($this->user)) {
            $this->prepareUser();
        }

        $this->socialAccount = $this->tester->db->socialAccount->create([
            'user_id' => $this->user->id
        ]);

        return $this->socialAccount;
    }
}
