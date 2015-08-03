<?php

namespace Skunenieki\System\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Skunenieki\System\Models\Participant;

class Individual extends Model
{
    protected $groups = [];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'individual';

    protected $appends = ['result'];

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
        return $this->hasOne(Participant::class, 'participantId');
    }

    public function getNameInDativeAttribute()
    {
        return Participant::find($this->participantId)->nameInDative;
    }

    public function getGroupAttribute($group)
    {
        static $yearRanges = null;
        if (true === is_null($yearRanges)) {
            $yearRanges = require __DIR__.'/../IndividualGroups.php';
        }

        if (null !== $group) {
            return $group;
        }

        if (null === $this->gender || null === $this->bikeType) {
            return null;
        }

        static $groups = [];
        if (true === empty($groups)) {
            foreach ($yearRanges as $yearRange => $ageRanges) {
                if (false !== strpos($yearRange, '-')) {
                    $yearRange = explode('-', $yearRange);
                    for ($year = intval($yearRange[0]); $year <= intval($yearRange[1]); $year++) {
                        foreach ($ageRanges as $ageRange => $groupRanges) {
                            $ageRange = explode('-', $ageRange);
                            for ($age = intval($ageRange[0]); $age <= intval($ageRange[1]); $age++) {
                                if (false === isset($groups[$year][$age])) {
                                     $groups[$year][$age] = [];
                                }
                                $groups[$year][$age] = array_merge($groups[$year][$age], $groupRanges);
                            }
                        }
                    }
                }
            }
        }

        if (false !== strpos($this->bikeType, 'AK') || false !== strpos($this->gender, 'AK')) {
            return 'AK';
        }

        try {
            return $groups[$this->eventYear][$this->eventYear-$this->birthYear][strtoupper($this->bikeType.$this->gender)];
        } catch (\Exception $e) {
            return 'NaN';
        }
    }

    public function getStartInSecondsAttribute($value)
    {
        if (null !== $this->start) {
            return (new Carbon($this->start))->diffInSeconds(new Carbon('0:00:00'));
        }

        return null;
    }

    public function getTurnInSecondsAttribute($value)
    {
        if (null !== $this->turn) {
            return (new Carbon($this->turn))->diffInSeconds(new Carbon('0:00:00'));
        }

        return null;
    }

    public function getTempResultInSecondsAttribute($value)
    {
        return $this->turnInSeconds - $this->startInSeconds;
    }

    public function getResultInSecondsAttribute($value)
    {
        if (null !== $this->start && null !== $this->finish) {
            return (new Carbon($this->start))->diffInSeconds(
                    (new Carbon($this->finish))->addSeconds(
                        (new Carbon($this->penalty))->diffInSeconds(new Carbon('0:00:00')))
                );
        }

        return null;
    }

    public function getResultAttribute($value)
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
}
