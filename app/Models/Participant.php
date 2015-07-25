<?php

namespace Skunenieki\System\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'participants';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    public function individual()
    {
        return $this->hasMany('Skunenieki\System\Models\Individual', 'participantId');
    }
}
