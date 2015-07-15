<?php

namespace Skunenieki\System\Models;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class IndividualTurn extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'individual_turns';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];
}
