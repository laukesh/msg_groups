<?php

namespace App\Http\Controllers\Admin\Leasing;

use App\Http\Controllers\Controller;
use App\Models\LeaseTerm;
use App\Models\LeaseAgreement;
use Illuminate\Http\Request;
use App\Models\LeaseTermination;
use App\Models\LeaseHistory;
class LeaseTermController extends Controller
{
    /**
     * Display lease terms.
     */
    public function index()
    {
        $terms = LeaseTerm::with([
            'agreement.tenant'
        ])
        ->latest('id')
        ->paginate(20);

        return view(
            'admin.leasing.terms.index',
            compact('terms')
        );
    }


    /**
     * Show create form.
     */
    public function create()
    {
        $agreements = LeaseAgreement::with('tenant')
            ->where('agreement_status', 'Active')
            ->whereDoesntHave('leaseTerm')
            ->orderBy('agreement_no')
            ->get();

        return view(
            'admin.leasing.terms.create',
            compact('agreements')
        );
    }


    /**
     * Store lease terms.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'lease_agreement_id' => [
                'required',
                'integer',
                'exists:lease_agreements,id',
                'unique:lease_terms,lease_agreement_id',
            ],

            'lock_in_period_months' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'notice_period_days' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'escalation_frequency' => [
                'nullable',
                'in:Yearly,Every 3 Years,Custom',
            ],

            'escalation_percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'billing_cycle' => [
                'nullable',
                'in:Monthly,Quarterly,Half-Yearly,Yearly',
            ],

            'payment_due_days' => [
                'nullable',
                'integer',
                'min:1',
                'max:31',
            ],

            'grace_period_days' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'late_fee_type' => [
                'nullable',
                'in:Fixed,Percentage',
            ],

            'late_fee_value' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'cam_calculation_method' => [
                'nullable',
                'in:Fixed,Per Sq Ft,Percentage',
            ],

            'utility_billing_method' => [
                'nullable',
                'in:Meter Reading,Fixed,Actual',
            ],

            'maintenance_responsibility' => [
                'nullable',
                'in:Mall,Tenant,Shared',
            ],

            'insurance_required' => [
                'nullable',
                'in:Yes,No',
            ],

            'subletting_allowed' => [
                'nullable',
                'in:Yes,No',
            ],

            'termination_clause' => [
                'nullable',
                'string',
            ],

            'special_terms' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        LeaseTerm::create($validated);


        return redirect()
            ->route('admin.leasing.terms.index')
            ->with(
                'success',
                'Lease terms created successfully.'
            );
    }


    /**
     * Show lease terms.
     */
    public function show($id)
    {
        $term = LeaseTerm::with([
            'agreement.tenant',
            'agreement.proposal'
        ])->findOrFail($id);

        return view(
            'admin.leasing.terms.show',
            compact('term')
        );
    }


    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $term = LeaseTerm::with(
            'agreement.tenant'
        )->findOrFail($id);

        $agreements = LeaseAgreement::with('tenant')
            ->where('agreement_status', 'Active')
            ->where(function ($query) use ($term) {

                $query->whereDoesntHave('leaseTerm')
                    ->orWhere('id', $term->lease_agreement_id);

            })
            ->orderBy('agreement_no')
            ->get();

        return view(
            'admin.leasing.terms.edit',
            compact(
                'term',
                'agreements'
            )
        );
    }


    /**
     * Update lease terms.
     */
    public function update(
        Request $request,
        $id
    ) {
        $term = LeaseTerm::findOrFail($id);

        $validated = $request->validate([

            'lease_agreement_id' => [
                'required',
                'integer',
                'exists:lease_agreements,id',
                'unique:lease_terms,lease_agreement_id,' . $term->id,
            ],

            'lock_in_period_months' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'notice_period_days' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'escalation_frequency' => [
                'nullable',
                'in:Yearly,Every 3 Years,Custom',
            ],

            'escalation_percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'billing_cycle' => [
                'nullable',
                'in:Monthly,Quarterly,Half-Yearly,Yearly',
            ],

            'payment_due_days' => [
                'nullable',
                'integer',
                'min:1',
                'max:31',
            ],

            'grace_period_days' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'late_fee_type' => [
                'nullable',
                'in:Fixed,Percentage',
            ],

            'late_fee_value' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'cam_calculation_method' => [
                'nullable',
                'in:Fixed,Per Sq Ft,Percentage',
            ],

            'utility_billing_method' => [
                'nullable',
                'in:Meter Reading,Fixed,Actual',
            ],

            'maintenance_responsibility' => [
                'nullable',
                'in:Mall,Tenant,Shared',
            ],

            'insurance_required' => [
                'nullable',
                'in:Yes,No',
            ],

            'subletting_allowed' => [
                'nullable',
                'in:Yes,No',
            ],

            'termination_clause' => [
                'nullable',
                'string',
            ],

            'special_terms' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        $validated['updated_by'] = auth()->id();

        $term->update($validated);


        return redirect()
            ->route(
                'admin.leasing.terms.show',
                $term->id
            )
            ->with(
                'success',
                'Lease terms updated successfully.'
            );
    }


    /**
     * Delete lease terms.
     */
    public function destroy($id)
    {
        $term = LeaseTerm::findOrFail($id);

        $term->update([
            'updated_by' => auth()->id(),
        ]);

        $term->delete();

        return redirect()
            ->route('admin.leasing.terms.index')
            ->with(
                'success',
                'Lease terms deleted successfully.'
            );
    }


}