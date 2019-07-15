<?php

/**
 * For App\Http\Controllers\AppApiController
 */
class AppCest extends _ApiCest
{

    public function iCanSyncApp(ApiTester $I)
    {
        $I->app->syncApp();
        $I->app->seeSyncAppSuccess();
    }
}