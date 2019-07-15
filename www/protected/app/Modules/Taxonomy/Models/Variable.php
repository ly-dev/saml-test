<?php
namespace App\Modules\Taxonomy\Models;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{

    const FACEBOOK_APP_ID = 'FACEBOOK_APP_ID';

    const FACEBOOK_APP_SECRET = 'FACEBOOK_APP_SECRET';

    const GEOSERVER_API_URL = 'GEOSERVER_API_URL';

    const GEOCODER_API_URL = 'GEOCODER_API_URL';

    const GEOCODER_API_ID = 'GEOCODER_API_ID';

    const GEOCODER_API_CODE = 'GEOCODER_API_CODE';

    /**
     *
     * @var string
     */
    protected $table = 'variables';

    /**
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'value'
    ];

    public static function getValue($name, $default = NULL)
    {
        $result = $default;

        $model = self::where('name', $name)->first();
        if (! empty($model)) {
            $result = $model->value;
        }

        return $result;
    }
}
