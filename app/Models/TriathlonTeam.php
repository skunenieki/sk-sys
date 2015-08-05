<?php

namespace Skunenieki\System\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TriathlonTeam extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'triathlon_teams';

    protected $appends = ['group', 'start', 'result'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    public function getResultAttribute()
    {
        if (null !== $this->start && null !== $this->finish) {
            $start = new Carbon($this->start);
            $finish = new Carbon($this->finish);

            if (null !== $this->penalty) {
                $finish->addSeconds(
                    (new Carbon($this->penalty))->diffInSeconds(new Carbon('0:00:00'))
                );
            }

            return $start->diff($finish)->format('%H:%I:%S');;
        }

        return null;
    }

    public function getResultInSecondsAttribute()
    {
        if (null !== $this->start && null !== $this->finish) {
            return (new Carbon($this->start))->diffInSeconds(
                    (new Carbon($this->finish))->addSeconds(
                        (new Carbon($this->penalty))->diffInSeconds(new Carbon('0:00:00')))
                );
        }

        return PHP_INT_MAX;
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
