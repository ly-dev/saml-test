<?php
namespace Page;

class Home
{

    protected $tester;

    public function __construct(\Codeception\Actor $tester)
    {
        $this->tester = $tester;
    }

    public function goToPage()
    {
        $this->tester->amOnPage('/');
        $this->tester->waitForText('Welcome');
    }
}
