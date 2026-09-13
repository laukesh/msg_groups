<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;

class StateController extends Controller
{
    public function index(Request $request)
    {
        $q = State::query();
        if ($request->has('country_id')) {
            $q->where('country_id', $request->get('country_id'));
        }
        return $q->orderBy('name')->paginate(50);
    }

    public function show($id)
    {
        return State::with('cities')->findOrFail($id);
    }
}
