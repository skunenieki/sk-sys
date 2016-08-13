<?php

namespace Skunenieki\System\Http\Controllers;

use Knp\Snappy\Pdf;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Individual;

class IndividualResultsController extends Controller
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
        return [
            'eventDate'                => '2016. gada 13. augusts',
            'eventDescription'         => 'Riteņbraukšnas sacensību protokols',
            'eventDistanceDescription' => 'Distance: Lejasciems-Sinole-Lejasciems (10km)',
            'SParticipant'             => 'Dalībniece',
            'VParticipant'             => 'Dalībnieks',
            'SG'                       => 'Sieviešu rezultāti pa grupām',
            'VG'                       => 'Vīriešu rezultāti pa grupām',
            'SK'                       => 'Sieviešu rezultāti - kopvērtējums',
            'VK'                       => 'Vīriešu rezultāti - kopvērtējums',
            'AK'                       => 'Ārpus Konkurences',
            'BV'                       => 'zēni, kas dzimuši, sākot ar '.$this->ageRangesForGroup($eventYear, 'BS')['birthYears']['min'].'. gadu un jaunāki',
            'S'                        => '',
            // 'CV'                       => 'vīrieši, kas dzimuši no '.$this->ageRangesForGroup($eventYear, 'CV')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'CV')['birthYears']['max'].'. gadam; ceļa velosipēdi',
            'CV'                       => 'vīrieši, kas dzimuši no 1967. līdz 2003. gadam; ceļa velosipēdi',
            'CV 1'                     => 'vīrieši, kas dzimuši no '.$this->ageRangesForGroup($eventYear, 'CV 1')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'CV 1')['birthYears']['max'].'. gadam; ceļa velosipēdi',
            'CV 2'                     => 'vīrieši, kas dzimuši no '.$this->ageRangesForGroup($eventYear, 'CV 2')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'CV 2')['birthYears']['max'].'. gadam; ceļa velosipēdi',
            'CV 3'                     => 'vīrieši, kas dzimuši no '.$this->ageRangesForGroup($eventYear, 'CV 3')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'CV 3')['birthYears']['max'].'. gadam; ceļa velosipēdi',
            'CV 4'                     => 'vīrieši, kas dzimuši no '.$this->ageRangesForGroup($eventYear, 'CV 4')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'CV 4')['birthYears']['max'].'. gadam; ceļa velosipēdi',
            'CV 5'                     => 'vīrieši, kas dzimuši līdz '.$this->ageRangesForGroup($eventYear, 'CV 5')['birthYears']['max'].'. gadam; ceļa velosipēdi',
            'CV 9'                     => '',
            'CV 11'                    => '',
            'SV 1'                     => 'zēni, kas dzimuši no '.$this->ageRangesForGroup($eventYear, 'SV 1')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'SV 1')['birthYears']['max'].'. gadam; sporta velosipēdi',
            'SV 2'                     => 'jaunieši, kas dzimuši no '.$this->ageRangesForGroup($eventYear, 'SV 2')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'SV 2')['birthYears']['max'].'. gadam; sporta velosipēdi',
            'SV 3'                     => 'vīrieši, kas dzimuši no '.$this->ageRangesForGroup($eventYear, 'SV 3')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'SV 3')['birthYears']['max'].'. gadam; sporta velosipēdi',
            'SV 4'                     => 'vīrieši, kas dzimuši no '.$this->ageRangesForGroup($eventYear, 'SV 4')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'SV 4')['birthYears']['max'].'. gadam; sporta velosipēdi',
            'SV 5'                     => 'vīrieši, kas dzimuši no '.$this->ageRangesForGroup($eventYear, 'SV 5')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'SV 5')['birthYears']['max'].'. gadam; sporta velosipēdi',
            'SV 6'                     => 'vīrieši, kas dzimuši no '.$this->ageRangesForGroup($eventYear, 'SV 6')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'SV 6')['birthYears']['max'].'. gadam; sporta velosipēdi',
            'SV 7'                     => '',
            'SV 9'                     => '',
            'SV 11'                    => '',
            'TV 1'                     => 'zēni, kas dzimuši no '.$this->ageRangesForGroup($eventYear, 'TV 1')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'TV 1')['birthYears']['max'].'. gadam; tūristu, kalnu un ceļa velosipēdi',
            'TV 2'                     => 'jaunieši, kas dzimuši no '.$this->ageRangesForGroup($eventYear, 'TV 2')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'TV 2')['birthYears']['max'].'. gadam; tūristu un kalnu velosipēdi',
            'TV 3'                     => 'vīrieši, kas dzimuši no '.$this->ageRangesForGroup($eventYear, 'TV 3')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'TV 3')['birthYears']['max'].'. gadam; tūristu un kalnu velosipēdi',
            'TV 4'                     => 'vīrieši, kas dzimuši no '.$this->ageRangesForGroup($eventYear, 'TV 4')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'TV 4')['birthYears']['max'].'. gadam; tūristu un kalnu velosipēdi',
            'TV 5'                     => 'vīrieši, kas dzimuši no '.$this->ageRangesForGroup($eventYear, 'TV 5')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'TV 5')['birthYears']['max'].'. gadam; tūristu un kalnu velosipēdi',
            'TV 6'                     => 'vīrieši, kas dzimuši no '.$this->ageRangesForGroup($eventYear, 'TV 6')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'TV 6')['birthYears']['max'].'. gadam; tūristu, kalnu un ceļa velosipēdi',
            'TV 7'                     => '',
            'KV 1'                     => '',
            'KV 5'                     => '',
            'KV 7'                     => '',
            'PV 1'                     => '',
            'PV 3'                     => '',
            'BS'                       => 'meitenes, kas dzimušas, sākot ar '.$this->ageRangesForGroup($eventYear, 'BS')['birthYears']['min'].'. gadu un jaunākas',
            // 'CS'                       => 'sievietes, kas dzimušas no '.$this->ageRangesForGroup($eventYear, 'CS')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'CS')['birthYears']['max'].'. gadam; ceļa velosipēdi',
            'CS'                       => 'sievietes, kas dzimušas no 1967. līdz 2003. gadam; ceļa velosipēdi',
            'CS 1'                     => '',
            'CS 2'                     => '',
            'CS 3'                     => '',
            'CS 4'                     => '',
            'CS 5'                     => '',
            'CS 8'                     => '',
            'CS 10'                    => '',
            'CS 12'                    => '',
            'CS 14'                    => '',
            'SS 1'                     => 'meitenes, kas dzimušas no '.$this->ageRangesForGroup($eventYear, 'SS 1')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'SS 1')['birthYears']['max'].'. gadam; sporta velosipēdi',
            // 'SS 3'                     => 'sievietes, kas dzimušas no '.$this->ageRangesForGroup($eventYear, 'SS 3')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'SS 3')['birthYears']['max'].'. gadam; sporta velosipēdi',
            'SS 3'                     => 'sievietes, kas dzimušas no 1977. līdz 2003. gadam; sporta velosipēdi',
            // 'SS 5'                     => 'sievietes, kas dzimušas no '.$this->ageRangesForGroup($eventYear, 'SS 5')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'SS 5')['birthYears']['max'].'. gadam; sporta velosipēdi',
            'SS 5'                     => 'sievietes, kas dzimušas līdz 1976. gadam; sporta velosipēdi',
            'SS 6'                     => '',
            'TS 1'                     => 'meitenes, kas dzimušas no '.$this->ageRangesForGroup($eventYear, 'TS 1')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'TS 1')['birthYears']['max'].'. gadam; tūristu, kalnu un ceļa velosipēdi',
            'TS 2'                     => 'jaunietes, kas dzimušas no '.$this->ageRangesForGroup($eventYear, 'TS 2')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'TS 2')['birthYears']['max'].'. gadam; tūristu un kalnu velosipēdi',
            'TS 3'                     => 'sievietes, kas dzimušas no '.$this->ageRangesForGroup($eventYear, 'TS 3')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'TS 3')['birthYears']['max'].'. gadam; tūristu un kalnu velosipēdi',
            'TS 4'                     => 'sievietes, kas dzimušas no '.$this->ageRangesForGroup($eventYear, 'TS 4')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'TS 4')['birthYears']['max'].'. gadam; tūristu un kalnu velosipēdi',
            'TS 5'                     => 'sievietes, kas dzimušas no '.$this->ageRangesForGroup($eventYear, 'TS 5')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'TS 5')['birthYears']['max'].'. gadam; tūristu un kalnu velosipēdi',
            'TS 6'                     => 'sievietes, kas dzimušas no '.$this->ageRangesForGroup($eventYear, 'TS 6')['birthYears']['min'].'. līdz '.$this->ageRangesForGroup($eventYear, 'TS 6')['birthYears']['max'].'. gadam; tūristu, kalnu un ceļa velosipēdi',
            'KS 2'                     => '',
            'KS 4'                     => '',
            'KS 8'                     => '',
            'PS 2'                     => '',
            'vG'                       => '',
            'PS'                       => '',
            'PV'                       => '',
            'SS'                       => '',
        ];
    }
}
