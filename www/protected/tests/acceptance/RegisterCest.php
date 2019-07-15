<?php

class RegisterCest extends _AcceptanceCest
{

    public function iCantRegisterWithoutCorrectInput(AcceptanceTester $I)
    {
        $I->login->goToPage();
        $I->login->seeRegisterForm();

        $data = [];
        $I->login->submitRegisterForm($data);
        $I->see('The your name field is required.');
        $I->see('The your email field is required.');
        $I->see('The new password field is required.');
        $I->see('The agree field is required.');

        $hash = $I->generateHash();
        $data = [
            'new_password' => $hash
        ];
        $I->login->submitRegisterForm($data);
        $I->see('The new password confirmation does not match.');

        $sampleUser = $I->db->getSampleUserCommon();
        $data['your_email'] = $sampleUser->email;
        $I->login->submitRegisterForm($data);
        $I->see('The your email has already been taken.');
    }

    public function iCanRegisterAndVerifyEmail(AcceptanceTester $I)
    {
        $I->login->goToPage();
        $I->login->seeRegisterForm();

        $hash = $I->generateHash();
        $data = [
            'your_name' => "_user_$hash",
            'your_email' => "{$hash}@example.com",
            'new_password' => $hash,
            'new_password_confirmation' => $hash,
            'agree' => 1
        ];

        $I->login->submitRegisterForm($data);
        $I->seeSectionHeaderText('Email Verification');

        $I->login->gotoVerifyEmailPage($data['your_email']);
        $I->seeSuccessStaticMessage('Your email has been verified.');

        $I->login->goToLogoutPage();

        $I->login->login($data['your_email'], $data['new_password']);
        $I->login->amLoggedIn();
    }

    public function iCanRegisterAndLogoutAndVerifyEmail(AcceptanceTester $I)
    {
        $I->login->goToPage();
        $I->login->seeRegisterForm();

        $hash = $I->generateHash();
        $data = [
            'your_name' => "_user_$hash",
            'your_email' => "{$hash}@example.com",
            'new_password' => $hash,
            'new_password_confirmation' => $hash,
            'agree' => 1
        ];

        $I->login->submitRegisterForm($data);
        $I->seeSectionHeaderText('Email Verification');

        $I->login->goToLogoutPage();

        $I->login->gotoVerifyEmailPage($data['your_email']);
        $I->seeSuccessStaticMessage('Your email has been verified.');

        $I->login->login($data['your_email'], $data['new_password']);
        $I->login->amLoggedIn();
    }

    public function iCanRegisterAndResendVerificationEmailAndVerifyEmail(AcceptanceTester $I)
    {
        $I->login->goToPage();
        $I->login->seeRegisterForm();

        $hash = $I->generateHash();
        $data = [
            'your_name' => "_user_$hash",
            'your_email' => "{$hash}@example.com",
            'new_password' => $hash,
            'new_password_confirmation' => $hash,
            'agree' => 1
        ];

        $I->login->submitRegisterForm($data);
        $I->seeSectionHeaderText('Email Verification');

        $I->login->clickEmailVerificationContinueButton();
        $I->seeWarningStaticMessage('Please follow the below instruction and get your email verified.');

        $I->login->clickResendEmailVerificationLink();
        $I->seeSuccessStaticMessage('Email with the new verification link has been sent');

        $I->login->gotoVerifyEmailPage($data['your_email']);
        $I->seeSuccessStaticMessage('Your email has been verified.');

        $I->login->gotoEmailVerificationPage();
        $I->login->clickEmailVerificationContinueButton();
        $I->seeSuccessStaticMessage('Your email has been verified.');
    }
}
