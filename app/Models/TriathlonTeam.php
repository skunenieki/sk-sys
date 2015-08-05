<?php

namespace Skunenieki\System\Models;

use Illuminate\Database\Eloquent\Model;

class TriathlonTeam extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'triathlon_teams';

    protected $appends = ['result', 'group', 'start'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    public function getResultAttribute()
    {
        return '1:00:01';
    }

    public function getGroupAttribute()
    {
        return 'K';
    }

    public function getStartAttribute()
    {
        return '0:00:00';
    }

}
