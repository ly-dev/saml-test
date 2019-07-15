<?php
namespace Api;

/**
 * For App\Modules\Basicpage\Http\Controllers\BasicPageApiController
 */
class BasicPage extends Base
{

    const URI_LOAD = "/basic-page/load";

    public function __construct(\ApiTester $tester)
    {
        parent::__construct($tester);
    }

    public function load($slug)
    {
        $uri = self::URI_LOAD . '/' . $slug;

        $this->apiGet($uri);

        return $this->getResult();
    }

    public function seeLoadSuccess(\App\Modules\Basicpage\Models\Basicpage $model)
    {
        $this->seeResultSuccessWithMessage();

        $data = $this->getResultData();
        $this->tester->assertEquals($model->title, $data['title']);
        $this->tester->assertEquals($model->body, $data['body']);
    }
}
