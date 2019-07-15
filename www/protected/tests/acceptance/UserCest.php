<?php
use App\Modules\Auth\Models\User as UserModel;
use Spatie\Permission\Models\Role;

class UserCest extends _AcceptanceCest
{

    public function iCanViewUsers(AcceptanceTester $I)
    {
        $I->amRegisteredAndLoggedInAsSampleAdmin();

        $I->userPage->goToListPage();
        $I->dataTable->searchForUnique('admin01');

        $I->dataTable->seeFirstItemInList('admin01');
    }

    public function iCanEditUser(AcceptanceTester $I)
    {
        $hash = $I->generateHash();
        $user = $I->db->user->create([
            'role' => UserModel::ROLE_USER
        ]);

        $I->amRegisteredAndLoggedInAsSampleAdmin();

        $I->userPage->goToListPage();
        $I->dataTable->searchForUnique($user->name);
        $I->userPage->clickEditButton($user);
        $newUserName = "_updated_user_$hash";
        $I->userPage->edit([
            'name' => $newUserName,
            'status' => UserModel::STATUS_INACTIVE,
            'roles' => [
                Role::findByName(UserModel::ROLE_MODERATOR)->id
            ]
        ]);
        $I->seeSuccessStaticMessage("User {$user->email} updated.");

        $I->userPage->goToListPage();
        $I->dataTable->searchForUnique($newUserName);

        $I->dataTable->seeFirstItemInList($newUserName);
        $I->dataTable->seeFirstItemInListInColumnNumber(UserModel::$STATUS_LABELS[UserModel::STATUS_INACTIVE], 3);
        $I->dataTable->seeFirstItemInListInColumnNumber(UserModel::ROLE_MODERATOR, 4);
    }

    public function iCanBlockAndUnblockUser(AcceptanceTester $I)
    {
        $hash = $I->generateHash();
        $user = $I->db->user->create([
            'role' => UserModel::ROLE_USER
        ]);
        $password = $I->db->user->getUserPassword($user);

        // block
        $I->amRegisteredAndLoggedInAsSampleAdmin();
        $I->userPage->goToEditPage($user);
        $I->userPage->edit([
            'status' => UserModel::STATUS_INACTIVE,
        ]);
        $I->seeSuccessStaticMessage("User {$user->email} updated.");

        $I->login->goToLogoutPage();
        $I->login->attemptLogin($user->email, $password);
        $I->seeInputErrorHelpBlock('These credentials do not match our records.');

        // unblock
        $I->amRegisteredAndLoggedInAsSampleAdmin();
        $I->userPage->goToEditPage($user);
        $I->userPage->edit([
            'status' => UserModel::STATUS_ACTIVE,
        ]);
        $I->seeSuccessStaticMessage("User {$user->email} updated.");

        $I->login->goToLogoutPage();
        $I->login->attemptLogin($user->email, $password);
        $I->login->amLoggedIn();
    }

}
