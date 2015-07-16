<?php

namespace Skunenieki\System\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'events';

    /**
     * Primary key database table field.
     *
     * @var string
     */
    protected $primaryKey = 'eventYear';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'array',
    ];
}


