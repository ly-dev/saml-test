<?php
namespace Api;

/**
 * For App\Http\Controllers\AppApiController
 */
class App extends Base
{

    const URI_SYNC_APP = "/sync/app";

    public function __construct(\ApiTester $tester)
    {
        parent::__construct($tester);
    }

    public function syncApp()
    {
        $uri = self::URI_SYNC_APP;

        $this->apiPost($uri);

        return $this->getResult();
    }

    public function seeSyncAppSuccess()
    {
        $this->seeResultSuccessWithMessage();

        $data = $this->getResultData();
    }
}
