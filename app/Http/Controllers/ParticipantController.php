<?php

namespace Skunenieki\System\Http\Controllers;

use Illuminate\Http\Request;
use Skunenieki\System\Models\Individual;

class ParticipantController extends Controller
{
    public function index(Request $request)
    {
        return Individual::all();
    }
}
