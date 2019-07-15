<?php
namespace Page;

class Tooltip
{

    public function __construct(\Codeception\Actor $tester)
    {
        $this->tester = $tester;
    }

    public function goToPage()
    {
        $this->tester->amOnPage("tooltip");
        $this->tester->seeTitleText('Tooltips');
    }

    public function followCreateLink()
    {
        $createButton = "a[href$=\"/tooltip/create\"]";
        $this->tester->waitForElement($createButton);
        $this->tester->click($createButton);
        $this->tester->seeSectionHeaderText('Create Tooltip');
    }

    public function followEditLink($model)
    {
        $editButton = "a[href$=\"/tooltip/view/{$model->page_id}/{$model->tooltip_id}\"]";
        $this->tester->waitForElement($editButton);
        $this->tester->click($editButton);
        $this->tester->seeSectionHeaderText('Edit Tooltip');
    }

    public function goToEditPage($model)
    {
        $this->tester->amOnPage("tooltip/view/{$model->page_id}/{$model->tooltip_id}");
        $this->tester->seeSectionHeaderText('Tooltip');
    }


    public function seeTooltip($page_id, $tooltip_id, $title, $description) {
        $selector = '.app-tooltip[data-pageid="' . $page_id . '"][data-tooltipid="' . $tooltip_id . '"]';
        $selector2 = $selector . ' .app-tooltip-content';
        $this->tester->waitForElement($selector, $this->tester->defaultWait);
        $this->tester->moveMouseOver( $selector );
        $this->tester->waitForElementVisible($selector2, $this->tester->defaultWait);
        $this->tester->waitForText($title, $this->tester->defaultWait, $selector2 . ' .title');
        $this->tester->waitForText($description, $this->tester->defaultWait, $selector2 . ' .description');
    }

    public function submitForm($data = [])
    {
        $form = "form[action$=\"/tooltip/process\"]";
        $this->tester->submitForm($form, $data, 'button[type="submit"]');
    }
}
