<?php

class BasicPageCest extends _AcceptanceCest
{
    public function iCanViewList(AcceptanceTester $I)
    {
        $I->amRegisteredAndLoggedInAsSampleAdmin();

        $model = $I->db->basicPage->create();

        $I->basicPage->goToPage();
        $I->dataTable->searchForUnique($model->title);
        $I->dataTable->seeFirstItemInList($model->title);
    }

    public function iCanCreate(AcceptanceTester $I)
    {
        $I->amRegisteredAndLoggedInAsSampleAdmin();

        $I->basicPage->goToPage();
        $I->basicPage->followCreateLink();

        $hash = $I->generateHash();
        $data = [
            'title' => "_title_{$hash}",
            'body' => "_body_{$hash}"
        ];
        $I->basicPage->submitForm($data);

        $I->basicPage->goToPage();
        $I->dataTable->searchForUnique($data['title']);
        $I->dataTable->seeFirstItemInList($data['title']);
    }

    public function iCanUpdate(AcceptanceTester $I)
    {
        $I->amRegisteredAndLoggedInAsSampleAdmin();

        $model = $I->db->basicPage->create();

        $I->basicPage->goToPage();
        $I->dataTable->searchForUnique($model->title);
        $I->dataTable->seeFirstItemInList($model->title);

        $I->basicPage->followEditLink($model);

        $hash = $I->generateHash();
        $data = [
            'title' => "_title_{$hash}",
            'body' => "_body_{$hash}"
        ];
        $I->basicPage->submitForm($data);

        $I->basicPage->goToPage();
        $I->dataTable->searchForUnique($data['title']);
        $I->dataTable->seeFirstItemInList($data['title']);
    }

    public function iCanDelete(AcceptanceTester $I)
    {
        $I->amRegisteredAndLoggedInAsSampleAdmin();

        $model = $I->db->basicPage->create();

        $I->basicPage->goToPage();
        $I->dataTable->searchForUnique($model->title);
        $I->dataTable->clickDelete($model->id);

        $I->dataTable->searchForUnique($model->title);
        $I->dataTable->seeEmptyTable();
    }
}