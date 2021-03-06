<?php

namespace Skunenieki\System\Models;

use Illuminate\Database\Eloquent\Model;

class IndividualStart extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'individual_start';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];
}
