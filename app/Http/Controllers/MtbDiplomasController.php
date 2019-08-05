<?php

namespace Skunenieki\System\Http\Controllers;

use Knp\Snappy\Pdf;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Mtb;
use Skunenieki\System\Models\Participant;

class MtbDiplomasController extends Controller
{
    public function diplomas($eventYear)
    {
        return $this->getDiplomas($eventYear);
    }

    public function prepare(Request $request, $eventYear)
    {
        return view('mtb_diplomas', [
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
        $mtb = Mtb::where('eventYear', $eventYear);

        $results = [];
        foreach ($mtb->get() as $mtb) {
            $results[$mtb->group][$mtb->resultInSeconds][$mtb->id] = $mtb;
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

                    $participant->nameInDative = $namesInDative[$participant->participantId];
                    $participant->place        = $i;

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
            'S1' => 'jaunietes, kuras dzimušas ' . ($eventYear - 15) . '. gadā un vēlāk',
            'S2' => 'sievietes, kuras dzimušas no ' . ($eventYear - 44) . '. līdz ' . ($eventYear - 16) . '. gadam',
            'S3' => 'sievietes, kuras dzimušas līdz ' . ($eventYear - 45) . '. gadam',
            'V1' => 'jaunieši, kuri dzimuši ' . ($eventYear - 15) . '. gadā un vēlāk',
            'V2' => 'vīrieši, kuri dzimuši no ' . ($eventYear - 44) . '. līdz ' . ($eventYear - 16) . '. gadam',
            'V3' => 'vīrieši, kuri dzimuši līdz ' . ($eventYear - 45) . '. gadam',
        ];
    }
}
