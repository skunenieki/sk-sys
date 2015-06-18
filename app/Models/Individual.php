<?php

namespace Skunenieki\System\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Individual extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'individual';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * The teams that belong to the 10km participant.
     */
    public function teams()
    {
        return $this->belongsToMany('Skunenieki\System\Models\Team');
    }

    /**
     * Get the participant record associated with 10km record.
     */
    public function participant()
    {
        return $this->hasOne('Skunenieki\System\Models\Participant', 'participantId');
    }
}
