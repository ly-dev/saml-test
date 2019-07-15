<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Modules\Auth\Models\User;

/**
 * Initialize for the test installation
 */
class TestSeeder extends Seeder
{

    static $testUserMetas = [
        [
            'email' => 'a01@gitest.uk',
            'name' => 'admin01',
            'password' => 'a01@gitest.uk',
            'status' => User::STATUS_ACTIVE,
            'roles' => [
                User::ROLE_ADMIN
            ]
        ],
        [
            'email' => 'm01@gitest.uk',
            'name' => 'moderator01',
            'password' => 'm01@gitest.uk',
            'status' => User::STATUS_ACTIVE,
            'roles' => [
                User::ROLE_MODERATOR
            ]
        ],
        [
            'email' => 'u01@gitest.uk',
            'name' => 'user01',
            'password' => 'u01@gitest.uk',
            'status' => User::STATUS_ACTIVE,
            'roles' => [
                User::ROLE_USER
            ]
        ]
    ];

    static $testUsers = [];

    /**
     * see users
     */
    private function seedUsers($metas)
    {
        foreach ($metas as $meta) {
            $model = User::where('email', $meta['email'])->first();
            if (! $model) {
                echo $meta['name'] . "\n";

                $model = new User();
                $model->name = $meta['name'];
                $model->email = $meta['email'];
                $model->password = (isset($meta['password']) ? bcrypt($meta['password']) : bcrypt(str_random(64)));
                $model->status = $meta['status'];
                $model->email_verification = Carbon::now()->getTimestamp();
                $model->password_updated_at = Carbon::now();

                DB::transaction(function () use (&$model, $meta) {
                    $model->save();
                    foreach ($meta['roles'] as $role) {
                        if (! $model->hasRole($role)) {
                            $model->assignRole($role);
                        }
                    }
                    $model->fresh();
                });
            }
            self::$testUsers[$meta['email']] = $model;
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (env('APP_ENV') === 'local') {
            echo "seed test users\n";
            $this->seedUsers(self::$testUserMetas);
        } else {
            echo "TestSeeder only applies to local enviroment for development and testing purpose\n";
        }
    }
}
