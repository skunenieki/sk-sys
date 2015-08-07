<?php

namespace Skunenieki\System\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Kids extends Model
{
    protected $groups = [];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'kids';

    protected $appends = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Get the participant record associated with 10km record.
     */
    public function participant()
    {
        return $this->hasOne('Skunenieki\System\Models\Participant', 'participantId');
    }
}
