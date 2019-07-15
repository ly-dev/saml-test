<?php

namespace Page;

class AuditLog
{
    protected $tester;

    public function __construct(\Codeception\Actor $tester)
    {
        $this->tester = $tester;
    }

    public function goToPage()
    {
        $this->tester->amOnPage('/audit-log');
        $this->tester->seeTitleText('Audit Log');
        $this->tester->dataTable->waitForProcessingComplete();
    }
}
