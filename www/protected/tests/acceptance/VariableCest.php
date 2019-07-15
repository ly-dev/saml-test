<?php
require_once 'TaxonomyCest.php';

class VariableCest extends TaxonomyCest
{

    // term key
    protected $term = 'variable';

    public function iCanUpdateValue(AcceptanceTester $I)
    {
        $I->amRegisteredAndLoggedInAsSampleAdmin();

        $term = $this->term;
        $I->taxonomy->goToPage($term);
        $I->taxonomy->followCreateLink($term);

        $hash = $I->generateHash();
        $data = $this->prepareFormData([
            'name' => "_updated_name_{$term}_{$hash}",
            'value' => "_updated_value_{$term}_{$hash}",
        ]);
        $I->taxonomy->submitForm($term, $data);
        $I->seeInputTextarea($data['value'], 'value');

        $I->taxonomy->goToPage($term);
        $I->dataTable->searchForUnique($data['name']);
        $I->dataTable->seeFirstItemInListInColumnNumber($data['name'], 2);
        $I->dataTable->seeFirstItemInListInColumnNumber($data['value'], 3);
    }
}