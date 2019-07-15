<?php
namespace Fixture;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Modules\Auth\Models\User as UserModel;
use Carbon\Carbon;

/**
 *
 * @property Organisation $organisation
 *
 */
class User
{

    protected $passwords = [];

    public function getUserPassword($user)
    {
        if (empty($this->passwords[$user->email])) {
            throw new \Exception("Never heard of user [{$user->email}]");
        }

        return $this->passwords[$user->email];
    }

    public function make($data = [])
    {
        $hash = substr(md5(microtime() . mt_rand()), 0, 8);

        $data = $data + [
            'name' => "_user_$hash",
            'email' => "{$hash}@global-initiative.com",
            'password' => $hash,
            'status' => UserModel::STATUS_ACTIVE,
            'email_verification' => Carbon::now()->getTimestamp(),
            'password_updated_at' => Carbon::now()
        ];

        $this->passwords[$data['email']] = $data['password'];
        $data['password'] = bcrypt($data['password']);

        return new UserModel($data);
    }

    public function create($data = [])
    {
        $user = $this->make($data);

        DB::transaction(function () use (&$user, &$data) {
            $user->save();

            // default role
            if (empty($data['role'])) {
                $data['role'] = UserModel::ROLE_USER;
            }

            $this->addRole($user, $data['role']);
        });

        return $user->fresh();
    }

    /**
     * Get the hash code in the user name, which is usually the passsword
     *
     * @param string $username
     *
     * @return string
     */
    public function stripHashFromName($username)
    {
        $result = NULL;

        if (! empty($username)) {
            $parts = explode('_', $username);
            if (! empty($parts)) {
                $result = end($parts);
            }
        }

        return $result;
    }

    public function getPasswordResetToken($email)
    {
        $token = NULL;

        $dbResult = DB::table('password_resets')->select('token')
            ->where('email', $email)
            ->first();
        if ($dbResult) {
            $token = $dbResult->token;
        }

        return $token;
    }

    private function addRole(UserModel $user, $role)
    {
        // convert role id to name
        if (is_numeric($role)) {
            $role = $this->getRoleName($role);
        }

        $user->assignRole($role);
    }

    private function getRoleName($roleId)
    {
        $role = Role::find($roleId);
        return (empty($role) ? NULL : $role->name);
    }
}