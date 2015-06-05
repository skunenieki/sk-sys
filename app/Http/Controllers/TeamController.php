<?php

namespace Skunenieki\System\Http\Controllers;

use Illuminate\Http\Request;
use Skunenieki\System\Models\Team;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $name = $request->get('name', false);

        $result = Team::where('id', '>', 0);

        if (false !== $name) {
            $result->where('name', 'like', "%{$name}%");
        }

        return $result->get();
    }
}
