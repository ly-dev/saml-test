<?php

namespace App\Modules\Basicpage\Models;

use Illuminate\Database\Eloquent\Model;

class Basicpage extends Model
{
    /**
     *
     * @var string
     */
    protected $table = 'basicpages';

    /**
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'body',
        'slug'
    ];
}
