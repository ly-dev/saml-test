<?php
namespace App\Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasCompositePrimaryKey;

class SocialAccount extends Model
{
    use HasCompositePrimaryKey;

    const TYPE_FACEBOOK = 1;
    const TYPE_SAMLTEST = 101;

    public static $TYPE_LABELS = [
        self::TYPE_FACEBOOK => 'Facebook',
        self::TYPE_SAMLTEST => 'samltest.id'
    ];

    protected $table = 'social_accounts';

    protected $primaryKey = [
        'provider_type',
        'provider_user_id'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider_type',
        'provider_user_id',
        'name',
        'email',
        'access_token',
        'expire_at',
        'user_id'
    ];

    /**
     * Relation - user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Attribute - firstName
     *
     * @return string
     */
    public function getFirstNameAttribute()
    {
        $result = '';

        if (! empty($this->name)) {
            $names = explode(' ', $this->name);
            $result = $names[0];
        }

        return $result;
    }

    /**
     * Attribute - lastName
     *
     * @return string
     */
    public function getLastNameAttribute()
    {
        $result = '';

        if (! empty($this->name)) {
            $names = explode(' ', $this->name);
            $result = $names[count($names) - 1];
        }

        return $result;
    }
}
