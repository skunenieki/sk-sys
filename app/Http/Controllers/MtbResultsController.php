<?php

namespace Skunenieki\System\Http\Controllers;

use Knp\Snappy\Pdf;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Mtb;

class MtbResultsController extends Controller
{
    public function resultsByGroups(Request $request, $eventYear)
    {
        $view = view('resultsByGroups', [
            'eventYear' => $eventYear,
            'results'   => $this->orderedResultsByGroups($eventYear, $request->input('gender', false)),
            'phrases'   => $this->phrases($eventYear),
        ]);

        if ($request->input('pdf', false) !== false) {
            $snappy = new Pdf(base_path().'/vendor/bin/wkhtmltopdf-amd64');
            header('Content-Type: application/pdf');
            $view = $snappy->getOutputFromHtml($view);
        }

        return $view;
    }

    protected function orderedResultsByGroups($eventYear, $gender = false)
    {
        $mtb = Mtb::where('eventYear', $eventYear);
        if (false !== $gender) {
            $mtb->where('gender', strtoupper($gender));
        }

        $results = [];
        foreach ($mtb->get() as $mtb) {
            $results[$mtb->gender][$mtb->group][$mtb->resultInSeconds][$mtb->id] = $mtb;
        }

        $this->ksortRecursive($results);
        return $results;
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
            'eventDate'                => '2020. gada 8. augusts', // @todo 202
            'eventDescription'         => 'Velokrosa sacensību protokols',
            'eventDistanceDescription' => '',
            'SParticipant'             => 'Dalībniece',
            'VParticipant'             => 'Dalībnieks',
            'SG'                       => 'Sieviešu rezultāti pa grupām',
            'VG'                       => 'Vīriešu rezultāti pa grupām',
            'V1'                       => 'jaunieši, kuri dzimuši 2005. gadā un vēlāk', // @todo 2020
            'V2'                       => 'vīrieši, kuri dzimuši no 1976. līdz 2004. gadam', // @todo 2020
            'V3'                       => 'vīrieši, kuri dzimuši līdz 1975. gadam', // @todo 2020
            'S1'                       => 'jaunietes, kuras dzimušas 2005. gadā un vēlāk', // @todo 2020
            'S2'                       => 'sievietes, kuras dzimušas no 1976. līdz 2004. gadam', // @todo 2020
            'S3'                       => 'sievietes, kuras dzimušas līdz 1975. gadam', // @todo 2020
        ];
    }
}
