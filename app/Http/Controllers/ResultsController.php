<?php

namespace Skunenieki\System\Http\Controllers;

use Knp\Snappy\Pdf;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Individual;

class ResultsController extends Controller
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

    public function resultsSummary(Request $request, $eventYear)
    {
        $view = view('resultsSummary', [
            'eventYear' => $eventYear,
            'results'   => $this->orderedSummaryResults($eventYear, $request->input('gender', false)),
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
        $individual = Individual::where('eventYear', $eventYear);
        if (false !== $gender) {
            $individual->where('gender', strtoupper($gender));
        }

        $results = [];
        foreach ($individual->get() as $individual) {
            $results[$individual->gender][$individual->group][$individual->resultInSeconds][$individual->id] = $individual;
        }

        $this->ksortRecursive($results);
        return $results;
    }

    protected function orderedSummaryResults($eventYear, $gender = false)
    {
        $individual = Individual::where('eventYear', $eventYear);
        if (false !== $gender) {
            $individual->where('gender', strtoupper($gender));
        }

        $results = [];
        foreach ($individual->get() as $individual) {
            $results[$individual->gender][$individual->resultInSeconds][$individual->id] = $individual;
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

    protected function getGroupsInYear($eventYear)
    {
        $individual = Individual::where('eventYear', $eventYear)->get();

    }

    protected function phrases($eventYear)
    {
        // dd($this->ageRangesForGroup($eventYear, 'CV 3'));

        return [
            'SParticipant' => 'Dalībniece',
            'VParticipant' => 'Dalībnieks',
            'SG' => 'Sieviešu rezultāti pa grupām',
            'VG' => 'Vīriešu rezultāti pa grupām',
            'SK' => 'Sieviešu rezultāti - kopvērtējums',
            'VK' => 'Vīriešu rezultāti - kopvērtējums',
            'AK' => 'Arpus Konkurences',
            'BV' => 'zēni, kas dzimušas, sākot ar '.$this->ageRangesForGroup($eventYear, 'BS')['birthYears']['min'].'. gadu un jaunāki',
            'S' => '',
            'CV 1' => '',
            'CV 2' => '',
            'CV 3' => 'vīrieši, kas dzimuši no '.$this->ageRangesForGroup($eventYear, 'CV 3')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'CV 3')['birthYears']['max'].'. gadam; ceļa velosipēdi',
            'CV 4' => '',
            'CV 5' => 'vīrieši, kas dzimuši līdz '.$this->ageRangesForGroup($eventYear, 'CV 5')['birthYears']['max'].'. gadam; ceļa velosipēdi',
            'CV 9' => '',
            'CV 11' => '',
            'SV 1' => '',
            'SV 2' => '',
            'SV 3' => '',
            'SV 5' => '',
            'SV 7' => '',
            'SV 9' => '',
            'SV 11' => '',
            'TV 1' => '',
            'TV 2' => '',
            'TV 3' => '',
            'TV 4' => '',
            'TV 5' => '',
            'TV 6' => '',
            'TV 7' => '',
            'KV 1' => '',
            'KV 5' => '',
            'KV 7' => '',
            'PV 1' => '',
            'PV 3' => '',
            'BS' => 'meitenes, kas dzimušas, sākot ar '.$this->ageRangesForGroup($eventYear, 'BS')['birthYears']['min'].'. gadu un jaunākas',
            'CS 1' => '',
            'CS 2' => '',
            'CS 3' => '',
            'CS 4' => '',
            'CS 5' => '',
            'CS 8' => '',
            'CS 10' => '',
            'CS 12' => '',
            'CS 14' => '',
            'SS 1' => '',
            'SS 3' => '',
            'SS 5' => '',
            'SS 6' => '',
            'TS 1' => '',
            'TS 2' => '',
            'TS 3' => '',
            'TS 4' => '',
            'TS 5' => '',
            'TS 6' => '',
            'KS 2' => '',
            'KS 4' => '',
            'KS 8' => '',
            'PS 2' => '',
            'vG' => '',
            'PS' => '',
            'PV' => '',
            'SS' => '',
        ];
    }
}
