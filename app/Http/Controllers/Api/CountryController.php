<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
    public function index()
    {
        return Country::select(['id','name','iso2','iso3'])->orderBy('name')->paginate(50);
    }

    public function show($id)
    {
        return Country::with('states')->findOrFail($id);
    }
}
