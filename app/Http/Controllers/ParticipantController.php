<?php

namespace Skunenieki\System\Http\Controllers;

use Illuminate\Http\Request;
use Skunenieki\System\Models\Individual;

class ParticipantController extends Controller
{
    public function index(Request $request)
    {
        return Individual::all();
        // $name = $request->get('name', false);

        // $result = Individual::where('id', '>', 0);

        // if (false !== $name) {
        //     $result->where('name', 'like', "%{$name}%");
        // }

        // return $result->select('name', 'birthYear', 'gender')->distinct()->get();
    }
}
