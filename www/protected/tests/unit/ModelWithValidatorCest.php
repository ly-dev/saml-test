<?php

use Codeception\Util\Stub;
use App\Models\ModelWithValidator;

class ModelWithValidatorCest
{
    public function testValidation(UnitTester $I)
    {
        // Rules not set
        $model = Stub::make('\App\Models\ModelWithValidator');
        $I->expectException(\Exception::class, function() use ($model) {
            $model->validate();
        });

        // Valid single scenario
        $model = Stub::make('\App\Models\ModelWithValidator', [
            'rules' => ['name' => 'required'],
            'attributes' => ['name' => 'lorem ipsum']
        ]);
        $I->assertFalse($model->validate()->fails());

        // Valid multiple scenarios
        $model = Stub::make('\App\Models\ModelWithValidator', [
            'scenarios' => ['create', 'update'],
            'rules' => [
                'create' => ['name' => 'required', 'email' => 'required'],
                'update' => ['name' => 'required']
            ],
            'attributes' => ['name' => 'lorem ipsum']
        ]);
        $I->assertTrue($model->validate(null, 'create')->fails());
        $I->assertFalse($model->validate(null, 'update')->fails());

        // Valid input as Array
        $model = Stub::make('\App\Models\ModelWithValidator', [
            'rules' => ['name' => 'required']
        ]);
        $I->assertFalse($model->validate(['name' => 'lorem ipsum'])->fails());

        // Valid input as Model
        $model = Stub::make('\App\Models\ModelWithValidator', [
            'rules' => ['name' => 'required']
        ]);
        $I->assertFalse($model->validate(
            Stub::make('\App\Models\ModelWithValidator', [ 'attributes' => ['name' => 'lorem ipsum'] ])
        )->fails());
    }
}
