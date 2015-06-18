<?php

namespace Skunenieki\System\Models;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Individual extends Model
{
    protected $groups = [];

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

    public function getGroupAttribute($group)
    {
        $yearRanges = require __DIR__.'/../groups.php';

        if (null !== $group) {
            return $group;
        }

        if (null === $this->gender && null === $this->bikeType) {
            return null;
        }

        if (true === empty($this->groups)) {
            $this->groups = [];
            foreach ($yearRanges as $yearRange => $ageRanges) {
                if (false !== strpos($yearRange, '-')) {
                    $yearRange = explode('-', $yearRange);
                    for ($year = intval($yearRange[0]); $year <= intval($yearRange[1]); $year++) {
                        foreach ($ageRanges as $ageRange => $groupRanges) {
                            $ageRange = explode('-', $ageRange);
                            for ($age = intval($ageRange[0]); $age <= intval($ageRange[1]); $age++) {
                                if (false === isset($this->groups[$year][$age])) {
                                     $this->groups[$year][$age] = [];
                                }
                                $this->groups[$year][$age] = array_merge($this->groups[$year][$age], $groupRanges);
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
            return $this->groups[$this->eventYear][$this->eventYear-$this->birthYear][strtoupper($this->bikeType.$this->gender)];
        } catch (\Exception $e) {
            return 'NaN';
        }
    }

    public function getResultAttribute($value)
    {
        if (null !== $this->start && null !== $this->finish) {
            return (new Carbon($this->start))->diff(
                    (new Carbon($this->finish))->addSeconds(
                        (new Carbon($this->penalty))->diffInSeconds(new Carbon('0:00:00')))
                )->format('%H:%I:%S');
        }

        return null;
    }
}
