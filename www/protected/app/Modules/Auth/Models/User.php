<?php
namespace App\Modules\Auth\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{

    use HasRoles;

    const STATUS_INACTIVE = 0;

    const STATUS_ACTIVE = 1;

    // all permissions
    const PERMISSION_USER = 'user';

    // common permission for user role
    const PERMISSION_MODERATOR = 'moderator';

    // common permission for moderator role
    const PERMISSION_ADMIN = 'administrator';

    // common permission for admin role

    // all roles
    const ROLE_USER = 'User';

    const ROLE_MODERATOR = 'Moderator';

    const ROLE_ADMIN = 'Administrator';

    public static $STATUS_LABELS = [
        self::STATUS_INACTIVE => 'Blocked',
        self::STATUS_ACTIVE => 'Active'
    ];

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'email_verification',
        'password_updated_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token'
    ];

    /**
     * Whether user has moderator permission
     *
     * @return boolean
     */
    public function isModerator()
    {
        return $this->hasPermissionTo(User::PERMISSION_MODERATOR);
    }

    /**
     * Whether user has admin permission
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->hasPermissionTo(User::PERMISSION_ADMIN);
    }
}
