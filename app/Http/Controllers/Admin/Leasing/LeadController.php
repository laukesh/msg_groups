<?php

namespace App\Http\Controllers\Admin\Leasing;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%")
                    ->orWhere('brand_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leads = $query
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.leasing.leads.index', compact('leads'));
    }

    public function create()
    {
        return view('admin.leasing.leads.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name'     => 'required|string|max:255',
            'contact_name'     => 'nullable|string|max:255',
            'email'            => 'nullable|email|max:255',
            'phone'            => 'nullable|string|max:50',
            'brand_name'       => 'nullable|string|max:255',
            'category'         => 'nullable|string|max:255',
            'area_need'        => 'nullable|numeric|min:0',
            'budget'           => 'nullable|numeric|min:0',
            'preferred_location'=> 'nullable|string|max:255',
            'source'           => 'nullable|string|max:100',
            'status'           => 'required|string|max:50',
            'notes'            => 'nullable|string',
        ]);

        Lead::create($validated);

        return redirect()
            ->route('admin.leasing.leads.index')
            ->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead)
    {
        return view('admin.leasing.leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        return view('admin.leasing.leads.edit', compact('lead'));
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'company_name'      => 'required|string|max:255',
            'contact_name'      => 'nullable|string|max:255',
            'email'             => 'nullable|email|max:255',
            'phone'             => 'nullable|string|max:50',
            'brand_name'        => 'nullable|string|max:255',
            'category'          => 'nullable|string|max:255',
            'area_need'         => 'nullable|numeric|min:0',
            'budget'            => 'nullable|numeric|min:0',
            'preferred_location'=> 'nullable|string|max:255',
            'source'            => 'nullable|string|max:100',
            'status'             => 'required|string|max:50',
            'notes'              => 'nullable|string',
        ]);
//dd($validated);
        $lead->update($validated);

        return redirect()
            ->route('admin.leasing.leads.index')
            ->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()
            ->route('admin.leasing.leads.index')
            ->with('success', 'Lead deleted successfully.');
    }
}