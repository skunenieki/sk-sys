<?php

namespace Skunenieki\System\Models;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class IndividualFinishTime extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'individual_finish_times';

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
