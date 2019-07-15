<?php

namespace Page;

class User
{
    protected $tester;

    public function __construct(\Codeception\Actor $tester)
    {
        $this->tester = $tester;
    }

    public function goToListPage()
    {
        $this->tester->amOnPage('/auth/user');
        $this->tester->seeTitleText('Users');
    }

    public function goToEditPage($user)
    {
        $this->tester->amOnPage('/auth/user/view/' . $user->id);
        $this->tester->seeSectionHeaderText("Edit User");
    }

    public function clickEditButton($user)
    {
        $link = "a[href$='/auth/user/view/$user->id']";
        $this->tester->waitForElement($link);
        $this->tester->click($link);
        $this->tester->seeSectionHeaderText("Edit User");
    }

    public function edit($data = [])
    {
        $this->tester->submitForm('form[action]', $data, 'button[type="submit"]');
    }
}
