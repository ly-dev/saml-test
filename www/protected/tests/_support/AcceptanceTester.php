<?php

class AcceptanceTester extends BaseDriverTester
{
    use _generated\AcceptanceTesterActions;

    /**
     * Login as sample admin
     */
    public function amRegisteredAndLoggedInAsSampleAdmin()
    {
        $user = $this->db->getSampleUserAdmin();
        $this->user = $this->getUserModel($user->email);
        $this->password = $user->password;
        $this->login->login($this->user->email, $this->password);
    }

    /**
     * Login as sample moderator
     */
    public function amRegisteredAndLoggedInAsSampleModerator()
    {
        $user = $this->db->getSampleUserModerator();
        $this->user = $this->getUserModel($user->email);
        $this->password = $user->password;
        $this->login->login($this->user->email, $this->password);
    }

    /**
     * Login as sample user
     */
    public function amRegisteredAndLoggedInAsSampleUser()
    {
        $user = $this->db->getSampleUserCommon();
        $this->user = $this->getUserModel($user->email);
        $this->password = $user->password;
        $this->login->login($this->user->email, $this->password);
    }

    /**
     * Create a new user and login
     *
     * @param array $data
     */
    public function amRegisteredAndLoggedIn($data = [])
    {
        $this->user = $this->db->user->create($data);
        $this->password = $this->db->user->getUserPassword($this->user);
        $this->login->login($this->user->email, $this->password);
    }
}
