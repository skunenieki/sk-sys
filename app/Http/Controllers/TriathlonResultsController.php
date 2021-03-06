<?php

namespace Skunenieki\System\Http\Controllers;

use Knp\Snappy\Pdf;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Triathlon;
use Skunenieki\System\Models\TriathlonTeam;

class TriathlonResultsController extends Controller
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
        $tri = Triathlon::where('eventYear', $eventYear);
        if (false !== $gender) {
            $tri->where('gender', strtoupper($gender));
        }

        $results = [];
        foreach ($tri->get() as $tri) {
            $results[$tri->gender][$tri->group][$tri->resultInSeconds][$tri->id] = $tri;
        }

        if (false !== $gender && $gender === 'K') {
            $team = TriathlonTeam::where('eventYear', $eventYear);
            foreach ($team->get() as $team) {
                $results[$team->gender][$team->group][$team->resultInSeconds][$team->id] = $team;
            }
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

    protected function phrases($eventYear)
    {
        return [
            'eventDate'                => '2020. gada 9. augusts', // @todo 2020
            'eventDescription'         => 'Triatlona sacensību protokols',
            'eventDistanceDescription' => 'Peldēšana 200m + velo 7km + skriešana 2km',
            'SParticipant'             => 'Dalībniece',
            'VParticipant'             => 'Dalībnieks',
            'Participant'              => 'Komanda',
            'SG'                       => 'Sieviešu rezultāti pa grupām',
            'VG'                       => 'Vīriešu rezultāti pa grupām',
            'G'                        => 'Komandu rezultāti',
            'V1'                       => 'jaunieši, kuri dzimuši 2004. gadā un vēlāk', // @todo 2020
            'V2'                       => 'vīrieši, kuri dzimuši līdz 2003. gadam', // @todo 2020
            'S1'                       => 'jaunietes, kuras dzimušas 2004. gadā un vēlāk', // @todo 2020
            'S2'                       => 'sievietes, kuras dzimušas līdz 2003. gadam', // @todo 2020
            'K'                        => 'komandas',
        ];
    }
}
