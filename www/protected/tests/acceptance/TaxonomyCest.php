<?php

abstract class TaxonomyCest extends _AcceptanceCest
{

    // term key
    protected $term = '';

    protected function prepareFormData($data)
    {
        return $data;
    }

    protected function addtionalCreateCheck(AcceptanceTester $I)
    {
        return;
    }

    protected function addtionalUpdateCheck(AcceptanceTester $I)
    {
        return;
    }

    public function iCanViewList(AcceptanceTester $I)
    {
        $I->amRegisteredAndLoggedInAsSampleAdmin();

        $term = $this->term;
        $model = $I->taxonomy->createTerm($term);

        $I->taxonomy->goToPage($term);
        $I->dataTable->searchForUnique($model->name);
        $I->dataTable->seeFirstItemInListInColumnNumber($model->name, 2);
    }

    public function iCanCreate(AcceptanceTester $I)
    {
        $I->amRegisteredAndLoggedInAsSampleAdmin();

        $term = $this->term;
        $I->taxonomy->goToPage($term);
        $I->taxonomy->followCreateLink($term);

        $hash = $I->generateHash();
        $termName = "_test_{$term}_$hash";
        $sortOrder = 1;
        $data = $this->prepareFormData([
            'name' => $termName,
            'sort_order' => $sortOrder
        ]);
        $I->taxonomy->submitForm($term, $data);
        $I->seeInputValue($termName, 'name');
        $I->seeInputValue($sortOrder, 'sort_order');

        $I->taxonomy->goToPage($term);
        $I->dataTable->searchForUnique($termName);
        $I->dataTable->seeFirstItemInListInColumnNumber($termName, 2);
    }

    public function iCanUpdate(AcceptanceTester $I)
    {
        $I->amRegisteredAndLoggedInAsSampleAdmin();

        $term = $this->term;
        $model = $I->taxonomy->createTerm($term);

        $I->taxonomy->goToPage($term);
        $I->dataTable->searchForUnique($model->name);
        $I->taxonomy->followEditLink($term, $model);

        $hash = $I->generateHash();
        $termName = "_update_{$term}_$hash";
        $sortOrder = 2;
        $data = $this->prepareFormData([
            'name' => $termName,
            'sort_order' => $sortOrder
        ]);
        $I->taxonomy->submitForm($term, $data);
        $I->seeInputValue($termName, 'name');
        $I->seeInputValue($sortOrder, 'sort_order');

        $I->taxonomy->goToPage($term);
        $I->dataTable->searchForUnique($termName);
        $I->dataTable->seeFirstItemInListInColumnNumber($termName, 2);
    }

    public function iCanDelete(AcceptanceTester $I)
    {
        $I->amRegisteredAndLoggedInAsSampleAdmin();

        $term = $this->term;
        $model = $I->taxonomy->createTerm($term);

        $I->taxonomy->goToPage($term);
        $I->dataTable->searchForUnique($model->name);
        $I->dataTable->clickDelete($model->id);

        $I->dataTable->searchForUnique($model->name);
        $I->dataTable->seeEmptyTable();
    }
}
