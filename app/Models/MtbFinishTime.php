<?php

namespace Skunenieki\System\Models;

use Illuminate\Database\Eloquent\Model;

class MtbFinishTime extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mtb_finish_times';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'disabled' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];
}
