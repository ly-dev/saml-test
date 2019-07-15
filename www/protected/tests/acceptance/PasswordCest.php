<?php

class PasswordCest extends _AcceptanceCest
{

    public function iCanSeeRequestNewTokenLinkWhenTokenIsInvalid(AcceptanceTester $I)
    {
        $user = $I->db->getSampleUserCommon();

        $I->login->goToPasswordResetPage('wrong-token');
        $password = str_random(8);
        $data = [
            'email' => $user->email,
            'password' => $password,
            'password_confirmation' => $password
        ];
        $I->login->submitPasswordResetForm($data);

        $I->waitForText('This password reset token is invalid.');
    }

    public function iCanRequestResetPasswordEmail(AcceptanceTester $I)
    {
        $user = $I->db->user->create();

        $I->login->goToPage();
        $I->login->clickForgetPasswordLink();

        $data = [
            'email' => $user->email
        ];
        $I->login->submitPasswordResetEmailForm($data);
        $I->seeSuccessStaticMessage('We have e-mailed your password reset link!');
    }

    public function iCanChangePassword(AcceptanceTester $I)
    {
        $I->amRegisteredAndLoggedInAsNewUser();
        $user = $I->user;
        $password =  $I->password;
        $newPassword = $I->generateHash();

        $I->login->goToChangePasswordPage();
        $I->login->seeChangePasswordForm();

        // no input
        $I->login->submitChangePasswordForm([]);
        $I->see('The old password field is required.', '.help-block');
        $I->see('The password field is required.', '.help-block');

        // wrong input
        $I->login->submitChangePasswordForm([
            'old_password' => $password . $newPassword,
            'password' => $newPassword,
            'password_confirmation' => '',
        ]);
        $I->see('The current password does not match.', '.help-block');
        $I->see('The password confirmation does not match.', '.help-block');

        //correct input
        $I->login->submitChangePasswordForm([
            'old_password' => $password,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $I->see('New password saved!', '.alert-success');

        $I->login->goToLogoutPage();

        $I->login->login($user->email, $newPassword);
    }
}
