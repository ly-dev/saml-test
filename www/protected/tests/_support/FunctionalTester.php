<?php
class FunctionalTester extends BaseDriverTester
{
    use _generated\FunctionalTesterActions;

    public function waitForElement()
    {
        $reflectionMethod = new \ReflectionMethod($this, 'seeElement');
        return $reflectionMethod->invokeArgs($this, func_get_args());
    }

    public function waitForText($text, $timeout, $selector = null)
    {
        $reflectionMethod = new \ReflectionMethod($this, 'see');
        return $reflectionMethod->invokeArgs($this, [$text, $selector]);
    }

    protected function performLogin($user)
    {
        $this->user = $this->getUserModel($user->email);
        $this->password = $user->password;

        $this->amLoggedAs($this->user);
        $this->seeAuthentication();
    }

    /**
     * Login as sample admin
     */
    public function amRegisteredAndLoggedInAsSampleAdmin()
    {
        $user = $this->db->getSampleUserAdmin();
        $this->performLogin($user);
    }

    /**
     * Login as sample moderator
     */
    public function amRegisteredAndLoggedInAsSampleModerator()
    {
        $user = $this->db->getSampleUserModerator();
        $this->performLogin($user);
    }

    /**
     * Login as sample user
     */
    public function amRegisteredAndLoggedInAsSampleUser()
    {
        $user = $this->db->getSampleUserCommon();
        $this->performLogin($user);
    }

    /**
     * Create a new user and login
     *
     * @param array $data
     */
    public function amRegisteredAndLoggedIn($data = [])
    {
        $this->user = $this->db->user->create($data);
        $this->performLogin($user);
    }

}
