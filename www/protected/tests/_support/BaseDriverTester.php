<?php
use Codeception\Scenario;
use App\Modules\Auth\Models\User as UserModel;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @property \App\Modules\Auth\Models\User $user
 *
 * @property \Page\DataTable $dataTable
 * @property \Page\Home $home
 * @property \Page\Login $login
 * @property \Page\User $user
 * @property \Page\AuditLog $auditLog
 * @property \Page\Taxonomy $taxonomy
 * @property \Page\Tooltip $tooltip
 * @property \Page\Basicpage $basicPage
 */
class BaseDriverTester extends _AbstractTester
{
    // current active user
    public $user;

    // password clear text of current active user
    public $password;

    // default wait before timeout
    public $defaultWait;

    /**
     * construction
     *
     * @param Scenario $scenario
     */
    public function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);

        $this->dataTable = new \Page\DataTable($this);
        $this->home = new \Page\Home($this);
        $this->login = new \Page\Login($this);
        $this->userPage = new \Page\User($this);
        $this->auditLog = new \Page\AuditLog($this);
        $this->taxonomy = new \Page\Taxonomy($this);
        $this->tooltip = new \Page\Tooltip($this);
        $this->basicPage = new \Page\Basicpage($this);
    }

    /**
     * Get user model instance based on email
     *
     * @param string $email
     * @return App\Modules\Auth\Models\User
     */
    protected function getUserModel($email)
    {
        return UserModel::where('email', $email)->first();
    }

    /**
     * Register a user role new user and login
     */
    public function amRegisteredAndLoggedInAsNewUser()
    {
        $this->amRegisteredAndLoggedIn([
            'role' => UserModel::ROLE_USER
        ]);
    }

    /**
     * Check page header 1 message
     *
     * @param string $text
     */
    public function seeTitleText($text)
    {
        $this->waitForText($text, $this->defaultWait, 'h1');
    }

    /**
     * Check form section header message
     *
     * @param string $text
     */
    public function seeSectionHeaderText($text)
    {
        $this->waitForText($text, $this->defaultWait, '.app-section-header');
    }

    /**
     * Check page header 2 message
     *
     * @param string $text
     */
    public function seeHeader2Text($text)
    {
        $this->waitForText($text, $this->defaultWait, '.app-header-2');
    }

    public function assertChecked($selector)
    {
        $checked = $this->executeJS("return $('$selector').is(':checked')");
        $this->assertTrue($checked);
    }

    public function assertNotChecked($selector)
    {
        $checked = $this->executeJS("return $('$selector').is(':checked')");
        $this->assertFalse($checked);
    }

    /**
     * Check page error message
     *
     * @param string $text
     */
    public function seeErrorStaticMessage($text)
    {
        $this->waitForText($text, $this->defaultWait, '.alert-error');
    }

    /**
     * Check page warning message
     *
     * @param string $text
     */
    public function seeWarningStaticMessage($text)
    {
        $this->waitForText($text, $this->defaultWait, '.alert-warning');
    }

    /**
     * Check page success message
     *
     * @param string $text
     */
    public function seeSuccessStaticMessage($text)
    {
        $this->waitForText($text, $this->defaultWait, '.alert-success');
    }

    /**
     * Check input help block message
     *
     * @param string $text
     * @param string $selector
     */
    public function seeInputHelpBlockAjax($text, $selector = '.form-group .help-block')
    {
        $this->waitForElementVisible($selector, $this->defaultWait);
        $this->waitForText($text, $this->defaultWait, $selector);
    }

    /**
     * Check input help block error message for ajax call
     *
     * @param string $text
     * @param string $selector
     */
    public function seeInputErrorHelpBlockAjax($text, $selector = '.form-group.has-error .help-block')
    {
        $this->seeInputHelpBlockAjax($text, $selector);
    }

    /**
     * Check input help block message for ajax call
     *
     * @param string $text
     * @param string $selector
     */
    public function seeInputHelpBlock($text, $selector = '.form-group .help-block')
    {
        $this->canSee($text, $selector);
    }

    /**
     * Check input help block error message
     *
     * @param string $text
     * @param string $selector
     */
    public function seeInputErrorHelpBlock($text, $selector = '.form-group.has-error .help-block')
    {
        $this->seeInputHelpBlock($text, $selector);
    }

    /**
     * Check file input
     *
     * @param string $filename
     * @param string $fieldId
     */
    public function seeInputFilename($filename, $fieldId)
    {
        $selector = ".app-file-input[data-id=\"{$fieldId}\"] .file-name";
        $this->canSee($filename, $selector);
    }

    /**
     * Check common input
     *
     * @param mixed $value
     * @param string $fieldId
     */
    public function seeInputValue($value, $fieldId)
    {
        $selector = "input[name=\"{$fieldId}\"][value=\"{$value}\"]";
        $this->waitForElement($selector, $this->defaultWait);
    }

    /**
     * Check textarea input
     *
     * @param mixed $value
     * @param string $fieldId
     */
    public function seeInputTextarea($value, $fieldId)
    {
        $selector = "textarea[name=\"{$fieldId}\"]";
        $this->see($value, $selector);
    }

    /**
     * Check select option
     *
     * @param mixed $value
     * @param string $fieldId
     */
    public function seeInputOptionSelected($value, $fieldId)
    {
        $selector = "select[name=\"{$fieldId}\"] option[value=\"{$value}\"][selected]";
        $this->waitForElement($selector, $this->defaultWait);
    }

    /**
     * Select checkbox
     *
     * @param mixed $value
     * @param string $fieldId
     */
    public function clickCheckboxInputOption($value, $fieldId)
    {
        $selector = "label[for=\"{$fieldId}_{$value}\"] .app-checkbox-mark";
        $this->waitForElement($selector, $this->defaultWait);
        $this->click($selector);
    }

    /**
     * Select radio
     *
     * @param mixed $value
     * @param string $fieldId
     */
    public function clickRadioInputOption($value, $fieldId)
    {
        $selector = "label[for=\"{$fieldId}_{$value}\"]";
        $this->waitForElement($selector, $this->defaultWait);
        $this->click($selector);
    }

    /**
     * Check checkbox or radio
     *
     * @param mixed $value
     * @param string $fieldId
     */
    public function seeInputOptionChecked($value, $fieldId)
    {
        $selector = "input[name=\"{$fieldId}\"][value=\"{$value}\"][checked]";
        $this->waitForElement($selector, $this->defaultWait);
    }

    /**
     * Select yes for confirm dialog
     */
    public function alertConfirmPreemptYes()
    {
        $this->executeJS('window.confirm = function(){return true;}');
    }

    /**
     * Select no for confirm dialog
     */
    public function alertConfirmPreemptNo()
    {
        $this->executeJS('window.confirm = function(){return false;}');
    }

    /**
     * Fill CKEDITOR
     *
     * @param string $fieldId
     * @param string $value
     */
    public function fillInCkeditor($fieldId, $value)
    {
        $selector = '#cke_' . $fieldId;
        $this->waitForElementVisible($selector, $this->defaultWait);
        $this->executeJS("CKEDITOR.instances['{$fieldId}'].setData('{$value}');");
    }
}
