<?php

abstract class _AcceptanceCest
{
    public $defaultWaitTime = 30;

    protected function hasLoggedIn(AcceptanceTester $I)
    {
        return $I->seePageHasElement('a[href$="auth/logout"]');
    }

    public function _before(AcceptanceTester $I)
    {
        $I->defaultWait = $this->defaultWaitTime;
    }

    public function _after(AcceptanceTester $I)
    {
        if ($this->hasLoggedIn($I))
        {
            $I->amOnPage('auth/logout');
        }
    }
}