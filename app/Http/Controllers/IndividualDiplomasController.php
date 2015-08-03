<?php

namespace Skunenieki\System\Http\Controllers;

use Knp\Snappy\Pdf;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Individual;

class IndividualDiplomasController extends Controller
{
    public function diplomas($eventYear)
    {
        return $this->getDiplomas($eventYear);
    }

    public function prepare(Request $request, $eventYear)
    {
        $view = view('diplomas', [
            'diplomas' => $this->getDiplomas($eventYear),
        ]);

        if ($request->input('pdf', false) !== false) {
            $snappy = new Pdf(base_path().'/vendor/bin/wkhtmltopdf-amd64');
            // $snappy->setOption('print-media-type', true);
            header('Content-Type: application/pdf');
            $view = $snappy->getOutputFromHtml($view);
        }

        return $view;
    }

    protected function getDiplomas($eventYear)
    {
        $individual = Individual::where('eventYear', $eventYear);

        $results = [];
        foreach ($individual->get() as $individual) {
            $results[$individual->group][$individual->resultInSeconds][$individual->id] = $individual;
            $individual->nameInDative = $individual->nameInDative;
        }

        $this->ksortRecursive($results);

        $diplomas = [];
        foreach ($results as $group => $resultInSeconds) {
            if ($group === 'AK') {
                continue;
            }

            $i = 1;
            foreach ($resultInSeconds as $individual) {
                $last = end($individual);
                foreach ($individual as $participant) {
                    if ($i > 3) {
                        break;
                    }
                    $participant['place'] = $i;
                    $diplomas[$group][] = $participant;

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
}
