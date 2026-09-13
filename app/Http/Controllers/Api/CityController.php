<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $q = City::query();
        if ($request->has('country_id')) {
            $q->where('country_id', $request->get('country_id'));
        }
        if ($request->has('state_id')) {
            $q->where('state_id', $request->get('state_id'));
        }
        return $q->orderBy('name')->paginate(50);
    }

    public function show($id)
    {
        return City::findOrFail($id);
    }
}
