<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeasibilityAssessment extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'land_id',

        'assessment_number',

        'title',

        'project_concept',

        'development_type',

        'status',

        'assessment_date',

        'target_completion_date',

        'summary',

        'key_assumptions',

        'key_risks',

        'recommendation',

        'created_by',

        'updated_by',

    ];


    protected $casts = [

        'assessment_date' =>
            'date',

        'target_completion_date' =>
            'date',

    ];


    public function land()
    {
        return $this->belongsTo(
            Land::class,
            'land_id'
        );
    }


    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    public function updatedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    public function marketStudies()
	{
	    return $this->hasMany(
	        MarketStudy::class,
	        'feasibility_assessment_id'
	    );
	}

    public function locationAnalyses()
    {
        return $this->hasMany(
            LocationAnalysis::class
        );
    }

    public function demandSupplyAnalyses()
    {
        return $this->hasMany(
            DemandSupplyAnalysis::class
        );
    }
    
    public function financialFeasibilities()
    {
        return $this->hasMany(
            FinancialFeasibility::class
        );
    }

    public function legalRegulatoryFeasibilities()
    {
        return $this->hasMany(
            LegalRegulatoryFeasibility::class
        );
    }

    public function technicalFeasibilities()
    {
        return $this->hasMany(
            TechnicalFeasibility::class
        );
    }

    public function environmentalFeasibilities()
    {
        return $this->hasMany(
            EnvironmentalFeasibility::class
        );
    }

    public function riskAssessments()
    {
        return $this->hasMany(
            RiskAssessment::class
        );
    }

    public function investmentAnalyses()
    {
        return $this->hasMany(
            InvestmentAnalysis::class
        );
    }

    public function investmentDecisions()
    {
        return $this->hasMany(
            InvestmentDecision::class
        );
    }

    public function projects()
    {
        return $this->hasMany(
            Project::class,
            'feasibility_assessment_id'
        );
    }
}