<?php

namespace Skunenieki\System\Http\Controllers;

use Illuminate\Http\Request;
use Skunenieki\System\Models\Option;

class OptionController extends Controller
{
    public function index(Request $request)
    {
        return Option::all();
    }

    public function show($optionName)
    {
        return Option::where('key', $optionName)->first();
    }

    public function update(Request $request, $optionName)
    {
        $option = Option::where('key', $optionName)->first();

        if (null === $option || 0 === $option->count()) {
            $option = new Option;
            $option->key = $optionName;
        }

        $option->value = $request->value;
        $option->save();

        return $option;
    }
}
