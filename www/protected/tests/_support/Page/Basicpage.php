<?php

namespace Page;

class Basicpage
{

    public function __construct(\Codeception\Actor $tester)
    {
        $this->tester = $tester;
    }

    public function goToPage()
    {
        $this->tester->amOnPage("basicpage");
        $this->tester->seeTitleText('Basic Pages');
    }

    public function followCreateLink()
    {
        $createButton = "a[href$=\"/basicpage/create\"]";
        $this->tester->waitForElement($createButton);
        $this->tester->click($createButton);
        $this->tester->seeSectionHeaderText('Create Basic Page');
    }

    public function followEditLink($model)
    {
        $editButton = "a[href$=\"/basicpage/view/{$model->id}\"]";
        $this->tester->waitForElement($editButton);
        $this->tester->click($editButton);
        $this->tester->seeSectionHeaderText('Edit Basic Page');
    }

    public function goToEditPage($model)
    {
        $this->tester->amOnPage("basicpage/view/{$model->id}");
        $this->tester->seeSectionHeaderText('Edit Basic Page');
    }

    public function submitForm($data = [])
    {
        foreach ([
            'body'
        ] as $fieldId) {
            if (isset($data[$fieldId])) {
                $this->tester->fillInCkeditor($fieldId, $data[$fieldId]);
                unset($data[$fieldId]);
            }
        }

        $form = "form[action$='/basicpage/process']";
        $this->tester->submitForm($form, $data, 'button[type="submit"]');
    }

}