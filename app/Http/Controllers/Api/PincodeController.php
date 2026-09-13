<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pincode;

class PincodeController extends Controller
{
    public function index(Request $request)
    {
        $q = Pincode::query();
        if ($request->has('country_id')) {
            $q->where('country_id', $request->get('country_id'));
        }
        if ($request->has('state_id')) {
            $q->where('state_id', $request->get('state_id'));
        }
        if ($request->has('city_id')) {
            $q->where('city_id', $request->get('city_id'));
        }
        if ($request->has('code')) {
            $q->where('code', $request->get('code'));
        }
        return $q->orderBy('code')->paginate(50);
    }

    public function show($id)
    {
        return Pincode::findOrFail($id);
    }
}
