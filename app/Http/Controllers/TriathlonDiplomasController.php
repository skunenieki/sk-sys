<?php

namespace Skunenieki\System\Http\Controllers;

use Knp\Snappy\Pdf;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Triathlon;
use Skunenieki\System\Models\TriathlonTeam;
use Skunenieki\System\Models\Participant;

class TriathlonDiplomasController extends Controller
{
    public function diplomas($eventYear)
    {
        return $this->getDiplomas($eventYear);
    }

    public function prepare(Request $request, $eventYear)
    {
        return view('tri_diplomas', [
            'diplomas' => $this->getDiplomas(
                $eventYear,
                [
                    'place' => $request->input('place', null),
                    'number' => $request->input('number', null),
                ]
            ),
            'phrases' => $this->phrases($eventYear),
        ]);
    }

    protected function getDiplomas($eventYear, $attrs = null)
    {
        $results = [];

        $tri = Triathlon::where('eventYear', $eventYear);
        foreach ($tri->get() as $tri) {
            $results[$tri->group][$tri->resultInSeconds][$tri->id] = $tri;
        }

        $team = TriathlonTeam::where('eventYear', $eventYear);
        foreach ($team->get() as $team) {
            $results[$team->group][$team->resultInSeconds][$team->id] = $team;
        }

        $this->ksortRecursive($results);

        $namesInDative = [];
        foreach (Participant::all() as $participant) {
            $namesInDative[$participant->id] = $participant->nameInDative;
        }

        $diplomas = [];
        foreach ($results as $group => $resultInSeconds) {
            $i = 1;
            foreach ($resultInSeconds as $individual) {
                $last = end($individual);
                foreach ($individual as $participant) {
                    if ($i > 3) {
                        break;
                    }

                    $participant->nameInDative = $namesInDative[$participant->participantId] ??
                        'Komandai "' . $participant->name . '"';

                    $participant->place = $i;

                    if (null !== $attrs && null !== $attrs['place']) {
                        if ($attrs['place'] == $i) {
                            $diplomas[$group][] = $participant;
                        }
                    } elseif (null !== $attrs && null !== $attrs['number']) {
                        if ($attrs['number'] == $participant->number) {
                            $diplomas[$group][] = $participant;
                        }
                    } else {
                        $diplomas[$group][] = $participant;
                    }

                    if (count($individual) > 1 && $participant === $last) {
                        $i += count($individual);
                    }
                    if (count($individual) < 2) {
                        $i++;
                    }
                }
            }
        }

        return $diplomas;
    }

    protected function ksortRecursive(&$array, $sort_flags = SORT_REGULAR) {
        if (!is_array($array)) {
            return false;
        }

        ksort($array, $sort_flags);

        foreach ($array as &$arr) {
            $this->ksortRecursive($arr, $sort_flags);
        }

        return true;
    }

    protected function ageRangesForGroup($eventYear, $group)
    {
        static $yearRanges = [];
        if (true === empty($yearRanges)) {
            $yearRanges = require __DIR__.'/../../IndividualGroups.php';
        }

        static $groups = [];
        if (true === empty($groups)) {
            foreach ($yearRanges as $yearRange => $ageRanges) {
                if (false !== strpos($yearRange, '-')) {
                    $yearRange = explode('-', $yearRange);
                    for ($year = intval($yearRange[0]); $year <= intval($yearRange[1]); $year++) {
                        foreach ($ageRanges as $ageRange => $groupRanges) {
                            if ($year == $eventYear && false !== array_search($group, $groupRanges)) {
                                $ranges = explode('-', $ageRange);
                                return [
                                    'age' => [
                                        'min' => (int) $ranges[0],
                                        'max' => (int) $ranges[1],
                                    ],
                                    'birthYears' => [
                                        'min' => $eventYear - $ranges[1],
                                        'max' => $eventYear - $ranges[0],
                                    ],
                                ];
                            }
                        }
                    }
                }
            }
        }
    }

    protected function phrases($eventYear)
    {
        return [
            'K'  => '',
            'S1' => '',
            'S2' => '',
            'V1' => '',
            'V2' => '',
        ];
    }
}
