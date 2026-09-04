<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ProcurementPrequalification extends Model
{
    protected $table = 'procurement_prequalifications';

    protected $fillable = [
        'procurement_tender_id',
        'procurement_tender_bidder_id',
        'prequalification_no',
        'submission_date',
        'evaluation_date',
        'evaluator_user_id',
        'evaluator_name',
        'status',
        'evaluation_summary',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'submission_date' => 'date',
        'evaluation_date' => 'date',
    ];


    public function tender(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementTender::class,
            'procurement_tender_id'
        );
    }


    public function tenderBidder(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementTenderBidder::class,
            'procurement_tender_bidder_id'
        );
    }

    public function criteria(): HasMany
	{
	    return $this->hasMany(
	        ProcurementPrequalificationCriterion::class,
	        'procurement_prequalification_id'
	    )->orderBy('criterion_no');
	}


    public function calculateResult(): string
    {
        $criteria = $this->criteria()->get();

        if ($criteria->isEmpty()) {
            return 'Draft';
        }

        if ($criteria->contains(function ($criterion) {
            return $criterion->evaluation_result === 'Non-Compliant';
        })) {
            return 'Not Qualified';
        }

        if ($criteria->contains(function ($criterion) {
            return in_array(
                $criterion->evaluation_result,
                [
                    'Pending',
                    'Partially Compliant',
                ],
                true
            );
        })) {
            return 'Under Evaluation';
        }

        if ($criteria->every(function ($criterion) {
            return in_array(
                $criterion->evaluation_result,
                [
                    'Compliant',
                    'Not Applicable',
                ],
                true
            );
        })) {
            return 'Qualified';
        }

        return 'Under Evaluation';
    }


}