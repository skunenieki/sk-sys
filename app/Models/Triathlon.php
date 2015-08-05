<?php

namespace Skunenieki\System\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Triathlon extends Model
{
    protected $groups = [];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'triathlon';

    // protected $appends = ['result', 'group', 'start'];

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

    public function getGroupAttribute($group)
    {
        return 'GRUPA';

        // static $yearRanges = null;
        // if (true === is_null($yearRanges)) {
        //     $yearRanges = require __DIR__.'/../MtbGroups.php';
        // }

        // if (null !== $group) {
        //     return $group;
        // }

        // if (null === $this->gender) {
        //     return null;
        // }

        // static $groups = [];
        // if (true === empty($groups)) {
        //     foreach ($yearRanges as $yearRange => $ageRanges) {
        //         if (false !== strpos($yearRange, '-')) {
        //             $yearRange = explode('-', $yearRange);
        //             for ($year = intval($yearRange[0]); $year <= intval($yearRange[1]); $year++) {
        //                 foreach ($ageRanges as $ageRange => $groupRanges) {
        //                     $ageRange = explode('-', $ageRange);
        //                     for ($age = intval($ageRange[0]); $age <= intval($ageRange[1]); $age++) {
        //                         if (false === isset($groups[$year][$age])) {
        //                              $groups[$year][$age] = [];
        //                         }
        //                         $groups[$year][$age] = array_merge($groups[$year][$age], $groupRanges);
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }

        // try {
        //     return $groups[$this->eventYear][$this->eventYear-$this->birthYear][strtoupper($this->gender)];
        // } catch (\Exception $e) {
        //     return 'NaN';
        // }
    }

    public function getStartAttribute()
    {
        return '0:00:00';
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

    public function getGenderWeightAttribute()
    {
        return $this->gender === 'V' ? 'AMale' : 'BFemale';
    }
}
