<?php

class TooltipCest extends _AcceptanceCest
{

    public function iCanViewList(AcceptanceTester $I)
    {
        $I->amRegisteredAndLoggedInAsSampleAdmin();

        $model = $I->db->tooltip->create();

        $I->tooltip->goToPage();
        $I->dataTable->searchForUnique($model->page_id);
        $I->dataTable->seeFirstItemInList($model->page_id);
        $I->dataTable->seeFirstItemInListInColumnNumber($model->tooltip_id, 2);
    }

    public function iCanCreate(AcceptanceTester $I)
    {
        $I->amRegisteredAndLoggedInAsSampleAdmin();

        $I->tooltip->goToPage();
        $I->tooltip->followCreateLink();

        $hash = $I->generateHash();
        $data = [
            'page_id' => "_page_id_$hash",
            'tooltip_id' => "_tooltip_id_$hash",
            'title' => "_title_$hash",
            'description' => "_description_$hash"
        ];
        $I->tooltip->submitForm($data);

        $I->tooltip->goToPage();
        $I->dataTable->searchForUnique($data['page_id']);
        $I->dataTable->seeFirstItemInList($data['page_id']);
        $I->dataTable->seeFirstItemInListInColumnNumber($data['tooltip_id'], 2);
    }

    public function iCanUpdate(AcceptanceTester $I)
    {
        $I->amRegisteredAndLoggedInAsSampleAdmin();

        $model = $I->db->tooltip->create();

        $I->tooltip->goToPage();
        $I->dataTable->searchForUnique($model->page_id);
        $I->dataTable->seeFirstItemInList($model->page_id);

        $I->tooltip->followEditLink($model);

        $hash = $I->generateHash();
        $title = "_updated_title_$hash";
        $content = "_updated_content_$hash";
        $I->tooltip->submitForm([
            'title' => $title,
            'content' => $content
        ]);

        $I->tooltip->goToPage();
        $I->dataTable->searchForUnique($model->page_id);
        $I->dataTable->seeFirstItemInListInColumnNumber($title, 3);
    }

    public function iCanDelete(AcceptanceTester $I)
    {
        $I->amRegisteredAndLoggedInAsSampleAdmin();

        $model = $I->db->tooltip->create();

        $I->tooltip->goToPage();
        $I->dataTable->searchForUnique($model->page_id);
        $I->dataTable->clickDelete([$model->page_id, $model->tooltip_id]);

        $I->dataTable->searchForUnique($model->page_id);
        $I->dataTable->seeEmptyTable();
    }

    public function iCanSeeTooltipEditLinkAsAdmin(AcceptanceTester $I)
    {
        $I->amRegisteredAndLoggedInAsSampleAdmin();

        $model = \App\Modules\Tooltip\Models\Tooltip::find([
            'page_id' => "tooltip_view",
            'tooltip_id' => "page_id",
        ]);

        // remove any avaialable model
        if (!empty($model)) {
            $model->delete();
        }

        $model = new stdClass();
        $model->page_id = "tooltip_view";
        $model->tooltip_id = "page_id";

        $I->tooltip->goToEditPage($model);
        $I->moveMouseOver( '.app-tooltip[data-pageid="' . $model->page_id . '"][data-tooltipid="' . $model->tooltip_id . '"]' );

        $I->waitForElement('.app-tooltip a[href$="/tooltip/view/' . $model->page_id .  '/'. $model->tooltip_id . '"]', $I->defaultWait);
    }
}
