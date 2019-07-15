<?php

class LoginCest extends _AcceptanceCest
{

    public function iCanLogIn(AcceptanceTester $I)
    {
        $user = $I->db->user->create([
            'password' => 'testing'
        ]);

        $I->amOnPage('/');
        $I->login->login($user->email, 'testing');

        $I->login->amLoggedIn();
    }

    public function iCantAccessLoginPageWhenLoggedIn(AcceptanceTester $I)
    {
        $user = $I->db->user->create([
            'password' => 'testing'
        ]);

        $I->amOnPage('/');
        $I->login->login($user->email, 'testing');
        $I->login->amLoggedIn();

        $I->amOnPage('/auth/login');
        $I->cantSee('Register', '.app-section-header ');
    }

    public function iCanLogOut(AcceptanceTester $I)
    {
        $I->amRegisteredAndLoggedInAsSampleUser();

        $I->login->goToLogoutPage();

        $I->login->amLoggedOut();
    }
}
