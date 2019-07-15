<?php
namespace Fixture;

/**
 *
 * @property \Fixture\ManagedFile $managedFile
 * @property \Fixture\Variable $variable
 * @property \Fixture\Tooltip $tooltip
 * @property \Fixture\User $user
 * @property \Fixture\Basicpage $basicPage
 * @property \Fixture\SocialAccount $socialAccount
 */
class Database
{

    public function __construct()
    {
        $this->managedFile = new ManagedFile();
        $this->variable = new Variable();
        $this->tooltip = new Tooltip();
        $this->user = new User();
        $this->basicPage = new Basicpage();
        $this->socialAccount = new SocialAccount($this->user);
    }

    public function getSampleUserAdmin()
    {
        return (object) \TestSeeder::$testUserMetas[0];
    }

    public function getSampleUserModerator()
    {
        return (object) \TestSeeder::$testUserMetas[1];
    }

    public function getSampleUserCommon()
    {
        return (object) \TestSeeder::$testUserMetas[2];
    }
}
