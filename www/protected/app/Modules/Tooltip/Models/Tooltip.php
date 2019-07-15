<?php
namespace App\Modules\Tooltip\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasCompositePrimaryKey;

class Tooltip extends Model
{
    use HasCompositePrimaryKey;

    /**
     *
     * @var string
     */
    protected $table = 'tooltips';

    protected $primaryKey = [
        'page_id',
        'tooltip_id'
    ];

    /**
     *
     * @var array
     */
    protected $fillable = [
        'page_id',
        'tooltip_id',
        'title',
        'description'
    ];
}
