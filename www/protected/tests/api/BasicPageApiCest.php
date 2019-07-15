<?php

/**
 * For App\Modules\Basicpage\Http\Controllers\BasicPageApiController
 */
class BasicPageApiCest extends _ApiCest
{

    public function iCanLoadBasicPage(ApiTester $I)
    {
        $slug = '_slug_' . $I->generateHash();
        $model = $I->db->basicPage->create([
            'slug' => $slug
        ]);

        $I->basicPage->load($slug);
        $I->basicPage->seeLoadSuccess($model);
    }
}