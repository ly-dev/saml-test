<?php
namespace Page;

class DataTable
{

    protected $tester;

    const searchInput = '.dataTables_filter input[type="search"]';

    const firstRowFirstColumn = 'table tbody tr:first-child td:first-child';

    const secondRow = 'table tbody tr:nth-child(2)';

    public function __construct(\Codeception\Actor $tester)
    {
        $this->tester = $tester;
    }

    public function searchFor($text)
    {
        $this->tester->waitForElement(self::searchInput);
        $this->tester->fillField(self::searchInput, $text);
        $this->waitForProcessingComplete();
    }

    public function searchForUnique($text)
    {
        $this->searchFor($text);
        $this->tester->waitForElementNotVisible(self::secondRow);
        $this->waitForProcessingComplete();
    }

    public function clickDelete($id)
    {
        // support multiple ids
        if (is_array($id)) {
            if (!is_numeric($id[0])) {
                $id = "'" . implode("','", $id) . "'";
            } else {
                $id = implode(',', $id);
            }
        } else {
            if (!is_numeric($id)) {
                $id = "'" . $id . "'";
            }
        }

        $this->tester->alertConfirmPreemptYes();
        $this->tester->click('a[onclick="return doDelete(' . $id . ')"]');
        $this->waitForProcessingComplete();
    }

    public function seeFirstItemInList($text)
    {
        $this->tester->waitForText("{$text}", $this->tester->defaultWait, self::firstRowFirstColumn);
    }

    public function seeFirstItemInListInColumnNumber($text, $column)
    {
        $this->tester->waitForText("{$text}", $this->tester->defaultWait, "table tbody tr:first-child td:nth-child($column)");
    }

    public function seeEmptyTable()
    {
        $this->tester->waitForText('No data available in table', $this->tester->defaultWait, self::firstRowFirstColumn);
    }

    public function waitForProcessingComplete()
    {
        $this->tester->waitForElementNotVisible('#data-table_processing');
    }
}