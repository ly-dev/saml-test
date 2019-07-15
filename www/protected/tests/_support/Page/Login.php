<?php
namespace Page;

class Login
{

    protected $tester;

    const emailField = 'input[name="email"]';

    const passwordField = 'input[name="password"]';

    const oldPasswordField = 'input[name="old_password"]';

    const newPasswordField = 'input[name="password"]';

    const confirmPasswordField = 'input[name="password_confirmation"]';

    const submitButton = 'button[type="submit"]';

    const loginForm = 'form[action$="auth/login"]';

    const loginSubmitButton = 'form[action$="auth/login"] button[type="submit"]';

    const changePasswordForm = 'form[action$="auth/password/save"]';

    const loginButton = 'a[href$="auth/login"]';

    const logoutButton = 'a[href$="auth/logout"]';

    const headerLogo = 'nav .navbar-brand';

    const userHeaderDropdownButton = 'nav ul:last-child li:last-child a.dropdown-toggle';

    const userHeaderDropdownOrganisation = 'nav ul:last-child li:last-child ul li:first-child a';

    const passwordResetEmailForm = 'form[action$="auth/password/email"]';

    const passwordResetForm = 'form[action$="auth/password/reset"]';

    const forgetPasswordLink = 'a[href$="auth/password/reset"]';

    const registerForm = 'form[action$="auth/register"]';

    const registerSubmitButton = 'form[action$="auth/register"] button[type="submit"]';

    const resendVerificationEmailLink = 'a[href$="auth/resend-email-verification"]';

    public function __construct(\Codeception\Actor $tester)
    {
        $this->tester = $tester;
    }

    public function goToPage()
    {
        $this->tester->amOnPage('/auth/login');
        $this->seeLoginForm();
    }

    public function seeLoginForm()
    {
        $this->tester->waitForElement(self::loginSubmitButton);
    }

    public function submitLoginForm($email, $password)
    {
        $credentials = [
            'email' => $email,
            'password' => $password
        ];

        $this->tester->submitForm(self::loginForm, $credentials, self::loginSubmitButton);
    }

    public function attemptLogin($email, $password)
    {
        $this->goToPage();
        $this->submitLoginForm($email, $password);
    }

    public function login($email, $password)
    {
        $this->attemptLogin($email, $password);
        $this->amLoggedIn();
    }

    public function amLoggedIn()
    {
        $this->tester->waitForElement(self::logoutButton);
    }

    public function amLoggedOut()
    {
        $this->tester->waitForElement(self::loginButton);
    }

    public function goToLogoutPage()
    {
        $this->tester->amOnPage('/auth/logout');
        $this->amLoggedOut();
    }

    public function clickHeaderDropdown()
    {
        $this->tester->waitForElement(self::userHeaderDropdownButton);
        $this->tester->click(self::userHeaderDropdownButton);
    }

    public function goToPasswordResetPage($token = '')
    {
        $uri = '/auth/password/reset' . (empty($token) ? '' : '/' . $token);
        $this->tester->amOnPage($uri);
    }

    public function submitPasswordResetEmailForm($data)
    {
        $this->tester->submitForm(self::passwordResetEmailForm, $data, self::submitButton);
    }

    public function submitPasswordResetForm($data)
    {
        $this->tester->submitForm(self::passwordResetForm, $data, self::submitButton);
    }

    public function clickForgetPasswordLink()
    {
        $this->tester->waitForElement(self::forgetPasswordLink);
        $this->tester->click(self::forgetPasswordLink);
        $this->tester->seeSectionHeaderText('Reset Password');
    }

    public function goToChangePasswordPage()
    {
        $this->tester->amOnPage('/auth/password/change');
    }

    public function seeChangePasswordForm()
    {
        $this->tester->waitForElement(self::oldPasswordField);
        $this->tester->waitForElement(self::newPasswordField);
        $this->tester->waitForElement(self::confirmPasswordField);
    }

    public function submitChangePasswordForm($data = [])
    {
        $this->tester->submitForm(self::changePasswordForm, $data, self::submitButton);
    }

    public function seeRegisterForm()
    {
        $this->tester->waitForElement(self::registerSubmitButton);
    }

    public function submitRegisterForm($data = [])
    {
        foreach ([
            'agree'
        ] as $fieldId) {
            if (isset($data[$fieldId])) {
                $this->tester->clickCheckboxInputOption($data[$fieldId], $fieldId);
                unset($data[$fieldId]);
            }
        }

        $this->tester->submitForm(self::registerForm, $data, self::registerSubmitButton);
    }

    public function gotoVerifyEmailPage($email)
    {
        $token = $this->tester->db->user->getPasswordResetToken($email);
        $uri = '/auth/verify-email/' . $token . '?email=' . urlencode($email);
        $this->tester->amOnPage($uri);
    }

    public function clickEmailVerificationContinueButton()
    {
        $this->tester->waitForElement(self::submitButton);
        $this->tester->click(self::submitButton);
    }

    public function clickResendEmailVerificationLink()
    {
        $this->tester->waitForElement(self::resendVerificationEmailLink);
        $this->tester->click(self::resendVerificationEmailLink);
    }

    public function gotoEmailVerificationPage()
    {
        $this->tester->amOnPage('/auth/email-verification');
    }
}
