<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LegalRegulatoryFeasibility extends Model
{
    use HasFactory;

    protected $table =
        'legal_regulatory_feasibilities';


    protected $fillable = [

        'feasibility_assessment_id',

        'analysis_number',

        'title',

        'status',

        /*
        |--------------------------------------------------------------------------
        | Ownership
        |--------------------------------------------------------------------------
        */

        'ownership_status',
        'ownership_details',

        'title_verification_status',
        'title_verification_details',

        /*
        |--------------------------------------------------------------------------
        | Encumbrance
        |--------------------------------------------------------------------------
        */

        'encumbrance_status',
        'encumbrance_details',

        /*
        |--------------------------------------------------------------------------
        | Zoning
        |--------------------------------------------------------------------------
        */

        'zoning_status',
        'zoning_type',
        'zoning_details',

        /*
        |--------------------------------------------------------------------------
        | Development Permission
        |--------------------------------------------------------------------------
        */

        'development_permission_status',
        'development_permission_details',

        /*
        |--------------------------------------------------------------------------
        | Building Approval
        |--------------------------------------------------------------------------
        */

        'building_approval_status',
        'building_approval_details',

        /*
        |--------------------------------------------------------------------------
        | Environmental
        |--------------------------------------------------------------------------
        */

        'environmental_clearance_status',
        'environmental_clearance_details',

        /*
        |--------------------------------------------------------------------------
        | Fire
        |--------------------------------------------------------------------------
        */

        'fire_noc_status',
        'fire_noc_details',

        /*
        |--------------------------------------------------------------------------
        | Pollution
        |--------------------------------------------------------------------------
        */

        'pollution_clearance_status',
        'pollution_clearance_details',

        /*
        |--------------------------------------------------------------------------
        | RERA
        |--------------------------------------------------------------------------
        */

        'rera_applicability',
        'rera_status',
        'rera_details',

        /*
        |--------------------------------------------------------------------------
        | Other Approvals
        |--------------------------------------------------------------------------
        */

        'other_approval_status',
        'other_approval_details',

        /*
        |--------------------------------------------------------------------------
        | Legal / Compliance
        |--------------------------------------------------------------------------
        */

        'legal_risks',

        'compliance_status',
        'compliance_details',

        /*
        |--------------------------------------------------------------------------
        | Findings
        |--------------------------------------------------------------------------
        */

        'key_legal_findings',

        'recommendation',

        'overall_legal_score',

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        'created_by',
        'updated_by',

    ];


    /*
    |--------------------------------------------------------------------------
    | Feasibility Assessment
    |--------------------------------------------------------------------------
    */

    public function feasibilityAssessment()
    {
        return $this->belongsTo(
            FeasibilityAssessment::class
        );
    }
}