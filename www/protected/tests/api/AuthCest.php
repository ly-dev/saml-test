<?php

use Carbon\Carbon;

/**
 * For App\Modules\Participant\Http\Controllers\ParticipantApiController
 */
class AuthCest extends _ApiCest
{

    public function iCanSignIn(ApiTester $I)
    {
        $userModel = $I->participant->prepareUser();

        $I->participant->signIn([
            "email" => $userModel->email,
            "password" => $I->participant->password
        ]);


        $respData = $I->participant->getResultData();

        $I->participant->seeSyncSuccess($respData, TRUE, 'signed in successfully');
    }

    public function iCanSignInAndSeeChangePasswordAction(ApiTester $I)
    {
        $userModel = $I->participant->prepareUser();
        $userModel->password_updated_at = Carbon::now()->subDays(181);
        $userModel->save();

        $I->participant->signIn([
            "email" => $userModel->email,
            "password" => $I->participant->password
        ]);

        $I->assertEquals('change_password', $I->participant->getResultPart('#action'));
    }

    public function iCantSignInWithEmptyInput(ApiTester $I)
    {
        $I->participant->signIn(null);
        $I->participant->seeResultErrors([
            "email" => [
                "The email field is required."
            ],
            "password" => [
                "The password field is required."
            ]
        ]);
    }

    public function iCantSignInWithWrongInput(ApiTester $I)
    {
        $I->participant->signIn([
            "email" => '123',
            "password" => '123'
        ]);
        $I->participant->seeResultErrors([
            "email" => [
                "The email must be a valid email address."
            ]
        ]);
    }

    public function iCantSignInWithBlockedUser(ApiTester $I)
    {
        $userModel = $I->participant->prepareUser();
        $userModel->status = App\Modules\Auth\Models\User::STATUS_INACTIVE;
        $userModel->save();

        $I->participant->signIn([
            "email" => $userModel->email,
            "password" => $I->participant->password
        ]);

        $I->participant->seeResultErrorWithMessage('has been blocked');
    }

    public function iCantSignInWithWrongPassword(ApiTester $I)
    {
        $userModel = $I->participant->prepareUser();

        $I->participant->signIn([
            "email" => $userModel->email,
            "password" => $I->participant->password . 'wrong'
        ]);

        $I->participant->seeResultErrorWithMessage('Fail to authenticate the user');
    }

    public function iCantSignInWithoutLinkToAParticipant(ApiTester $I)
    {
        $userModel = $I->db->user->create([
            'role' => App\Modules\Auth\Models\User::ROLE_MODERATOR
        ]);

        $I->participant->signIn([
            "email" => $userModel->email,
            "password" => $I->db->user->getUserPassword($userModel)
        ]);

        $I->participant->seeResultErrorWithMessage('Fail to authenticate the participant');
    }

    public function iCanSignUp(ApiTester $I)
    {
        $participantModel = $I->participant->prepareParticipant();

        $hash = $I->generateHash();
        $data = [
            "participant" => [
                "id" => $participantModel->id
            ],
            'user' => [
                "first_name" => "_first_name_{$hash}",
                "last_name" => "_last_name_{$hash}",
                "email" => "{$hash}@example.com"
            ]
        ];
        $I->participant->signUp($data);

        $I->participant->seeResultSuccessWithMessage('signed up successfully');

        $participantModel = $participantModel->fresh();
        $I->assertEquals($data['user']['first_name'], $participantModel->first_name);
        $I->assertEquals($data['user']['last_name'], $participantModel->last_name);

        $userModel = $participantModel->user;
        $I->assertNotNull($userModel->email_verification);
        $I->assertEquals($data['user']['email'], $userModel->email);
        // force password change
        $I->assertTrue((Carbon::now()->diffInDays($userModel->password_updated_at, false) < -180));
    }

    public function iCantSignUpWithWrongInput(ApiTester $I)
    {
        // empty input
        $I->participant->signUp(null);
        $I->participant->seeResultErrorWithMessage('Participant not found');

        // no user fields
        $participantModel = $I->participant->prepareParticipant();
        $data = [
            "participant" => [
                "id" => $participantModel->id
            ]
        ];
        $I->participant->signUp($data);
        $I->participant->seeResultErrors([
            "email" => [
                "The email field is required."
            ],
            "first_name" => [
                "The first name field is required."
            ],
            "last_name" => [
                "The last name field is required."
            ]
        ]);

        // wrong email field
        $hash = $I->generateHash();
        $data['user'] = [
            "first_name" => "_first_name_{$hash}",
            "last_name" => "_last_name_{$hash}",
            "email" => "{$hash}"
        ];
        $I->participant->signUp($data);
        $I->participant->seeResultErrors([
            "email" => [
                "The email must be a valid email address."
            ]
        ]);
    }

    public function iCantSignUpParticipantTwice(ApiTester $I)
    {
        $userModel = $I->participant->prepareUser();
        $participantModel = $userModel->participant;

        $hash = $I->generateHash();
        $data = [
            "participant" => [
                "id" => $participantModel->id
            ],
            'user' => [
                "first_name" => "_first_name_{$hash}",
                "last_name" => "_last_name_{$hash}",
                "email" => "{$hash}@example.com"
            ]
        ];
        $I->participant->signUp($data);
        $I->participant->seeResultErrorWithMessage("already signed up as {$userModel->email}");
    }

    public function iCantSignUpEmailTwice(ApiTester $I)
    {
        $userModel = $I->participant->prepareUser();

        $hash = $I->generateHash();
        $data = [
            "participant" => [
                "id" => $I->db->participant->create()->id
            ],
            'user' => [
                "first_name" => "_first_name_{$hash}",
                "last_name" => "_last_name_{$hash}",
                "email" => $userModel->email
            ]
        ];
        $I->participant->signUp($data);
        $I->participant->seeResultErrors([
            "email" => [
                "The email has already been taken."
            ]
        ]);
    }

    public function iCantChangePasswordBeforeSignIn(ApiTester $I)
    {
        $I->participant->changePasswordBeforeSignIn([
            "current_password" => 'current_password',
            "new_password" => 'new_password'
        ]);
        $I->participant->seeUnauthorized();
    }

    public function iCanChangePassword(ApiTester $I)
    {
        $userModel = $I->participant->prepareUserAndLoggedIn();
        $currentPassword = $I->participant->password;

        $newPassword = $I->generateHash();
        $I->participant->changePassword([
            "current_password" => $currentPassword,
            "new_password" => $newPassword
        ]);

        $I->participant->seeChangePasswordSuccee($userModel->fresh(), $newPassword);
    }

    public function iCantChangePasswordWithWrongInput(ApiTester $I)
    {
        $userModel = $I->participant->prepareUserAndLoggedIn();

        $I->participant->changePassword(null);
        $I->participant->seeResultErrors([
            "current_password" => [
                "The current password field is required."
            ],
            "new_password" => [
                "The new password field is required."
            ]
        ]);

        $currentPassword = $I->participant->password;
        $newPassword = $I->generateHash();
        $data = [
            "current_password" => 'wrong',
            "new_password" => $newPassword
        ];
        $I->participant->changePassword($data);

        $I->participant->seeResultErrorWithMessage("You entered an incorrect current password");
    }

    public function iCanSendPassword(ApiTester $I)
    {
        $userModel = $I->participant->prepareUser();
        $currentPassword = $I->participant->password;

        $I->participant->sendPassword([
            "email" => $userModel->email
        ]);

        $I->participant->seeSendPasswordSuccee($userModel->fresh(), $currentPassword);
    }

    public function iCantSendPasswordWithWrongInput(ApiTester $I)
    {
        $I->participant->sendPassword(null);
        $I->participant->seeResultErrors([
            "email" => [
                "The email field is required."
            ]
        ]);

        $I->participant->sendPassword([
            "email" => 'wrong email'
        ]);
        $I->participant->seeResultErrors([
            "email" => [
                "The email must be a valid email address."
            ]
        ]);
    }

    public function iCanFacebookLogin(ApiTester $I)
    {
        $socialAccountModel = $I->participant->prepareSocialAccount();
        $userModel = $socialAccountModel->user;
        $participantModel = $userModel->participant;

        $data = [
            'participant' => [
                'id' => $participantModel->id
            ],
            'social' => [
                "type" => $socialAccountModel->provider_type,
                "user_id" => $socialAccountModel->provider_user_id,
                "token" => $socialAccountModel->access_token
            ]
        ];
        $I->participant->socialLogin($data);

        $respData = $I->participant->getResultData();

        $I->participant->seeSyncSuccess($respData, TRUE, 'signed in successfully');
    }

    public function iCanFacebookLoginAndLinkParticipant(ApiTester $I)
    {
        $socialAccountModel = $I->participant->prepareSocialAccount();
        $userModel = $socialAccountModel->user;

        // break participant user link
        $participantModel = $userModel->participant;
        $participantModel->user_id = null;
        $participantModel->save();

        $data = [
            'participant' => [
                'id' => $participantModel->id
            ],
            'social' => [
                "type" => $socialAccountModel->provider_type,
                "user_id" => $socialAccountModel->provider_user_id,
                "token" => $socialAccountModel->access_token
            ]
        ];
        $I->participant->socialLogin($data);

        $respData = $I->participant->getResultData();

        $I->participant->seeSyncSuccess($respData, TRUE, 'signed in successfully');

        // check link established and name changed
        $participantModel = $participantModel->fresh();
        $I->assertEquals($participantModel->user_id, $userModel->id);
        $I->assertContains($participantModel->first_name, $socialAccountModel->name);
        $I->assertContains($participantModel->last_name, $socialAccountModel->name);
    }

    public function iCantFacebookLoginWithWrongInput(ApiTester $I)
    {
        $I->participant->socialLogin(null);
        $I->participant->seeResultErrorWithMessage('Participant not found');

        $socialAccountModel = $I->participant->prepareSocialAccount();
        $userModel = $socialAccountModel->user;
        $participantModel = $userModel->participant;

        $data = [
            'participant' => [
                'id' => $participantModel->id
            ]
        ];
        $I->participant->socialLogin($data);

        $I->participant->seeResultErrors([
            "type" => [
                "The type field is required."
            ],
            "user_id" => [
                "The user id field is required."
            ],
            "token" => [
                "The token field is required."
            ]
        ]);
    }

    public function iCantFacebookLoginWithBlockedUser(ApiTester $I)
    {
        $socialAccountModel = $I->participant->prepareSocialAccount();
        $userModel = $socialAccountModel->user;
        $participantModel = $userModel->participant;

        // block user
        $userModel->status=App\Modules\Auth\Models\User::STATUS_INACTIVE;
        $userModel->save();

        $data = [
            'participant' => [
                'id' => $participantModel->id
            ],
            'social' => [
                "type" => $socialAccountModel->provider_type,
                "user_id" => $socialAccountModel->provider_user_id,
                "token" => $socialAccountModel->access_token
            ]
        ];
        $I->participant->socialLogin($data);

        $I->participant->seeResultErrorWithMessage('has been blocked');
    }

}