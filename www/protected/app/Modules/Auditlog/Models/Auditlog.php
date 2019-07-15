<?php
namespace App\Modules\Auditlog\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Auth\Models\User;

class Auditlog extends Model
{

    // CONSTANT - SEVERITY
    const SEVERITY_EMERGENCY = 0;

    const SEVERITY_ALERT = 1;

    const SEVERITY_CRITICAL = 2;

    const SEVERITY_ERROR = 3;

    const SEVERITY_WARNING = 4;

    const SEVERITY_NOTICE = 5;

    const SEVERITY_INFO = 6;

    const SEVERITY_DEBUG = 7;

    public static $SEVERITY_LABELS = [
        self::SEVERITY_EMERGENCY => 'Emergency',
        self::SEVERITY_ALERT => 'Alert',
        self::SEVERITY_CRITICAL => 'Critical',
        self::SEVERITY_ERROR => 'Error',
        self::SEVERITY_WARNING => 'Warning',
        self::SEVERITY_NOTICE => 'Notice',
        self::SEVERITY_INFO => 'Info',
        self::SEVERITY_DEBUG => 'Debug'
    ];

    // CONSTANT - CATEGORY
    const CATEGORY_USER = 'User';
    const CATEGORY_TAXONOMY = 'Taxonomy';
    const CATEGORY_TOOLTIP = 'Tooltip';
    const CATEGORY_PROFILE = 'Profile';
    const CATEGORY_CAUSE = 'Cause';
    const CATEGORY_DONATION = 'Donation';
    const CATEGORY_BASICPAGE = 'Basicpage';
    const CATEGORY_CONTACT = 'Contact';

    // Data Input related

    /**
     *
     * @var string
     */
    protected $table = 'audit_logs';

    /**
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'ip_address',
        'severity',
        'category',
        'activity',
        'target_id',
        'data'
    ];

    /**
     * Relation - user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log web request activities
     *
     * @param integer $severity
     * @param string $category
     * @param string $activity
     * @param integer $targetId
     * @param string $data
     */
    static public function log($severity, $category, $activity = NULL, $targetId = NULL, $data = NULL)
    {
        $user = Auth::user();
        if (empty($user)) {
            $user = Auth::guard('api')->user();
            if (empty($user)) {
                $user = User::find(0); // use the system anonymous user
            }
        }
        $ipaddress = '127.0.0.1';
        if (! empty($_SERVER) && ! empty($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        }
        $log = [
            'user_id' => $user->id,
            'ip_address' => $ipaddress,
            'severity' => $severity,
            'category' => $category,
            'activity' => $activity,
            'target_id' => $targetId,
            'data' => $data
        ];
        self::create($log);
    }

    static public function info($category, $activity = NULL, $targetId = NULL, $data = NULL)
    {
        self::log(self::SEVERITY_INFO, $category, $activity, $targetId, $data);
    }

    static public function warning($category, $activity = NULL, $targetId = NULL, $data = NULL)
    {
        self::log(self::SEVERITY_WARNING, $category, $activity, $targetId, $data);
    }

    static public function critical($category, $activity = NULL, $targetId = NULL, $data = NULL)
    {
        self::log(self::SEVERITY_CRITICAL, $category, $activity, $targetId, $data);
    }
}