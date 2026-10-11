<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssetType;
use App\Models\AssetPurpose;
use App\Models\IndustryProfile;
use App\Models\OperatingModel;
use App\Models\Ownership;
use App\Models\RevenueStatus;

class AssetClassificationSeeder extends Seeder
{
    public function run(): void
    {
        $this->seed(
            AssetType::class,
            [
                ['name' => 'Building', 'code' => 'BUILDING'],
                ['name' => 'Retail Unit', 'code' => 'RETAIL_UNIT'],
                ['name' => 'Office', 'code' => 'OFFICE'],
                ['name' => 'Warehouse', 'code' => 'WAREHOUSE'],
                ['name' => 'Parking', 'code' => 'PARKING'],
                ['name' => 'Land', 'code' => 'LAND'],
                ['name' => 'Infrastructure', 'code' => 'INFRASTRUCTURE'],
                ['name' => 'Equipment', 'code' => 'EQUIPMENT'],
                ['name' => 'Plant & Machinery', 'code' => 'PLANT_MACHINERY'],
                ['name' => 'Furniture & Fixtures', 'code' => 'FURNITURE_FIXTURES'],
                ['name' => 'IT Equipment', 'code' => 'IT_EQUIPMENT'],
                ['name' => 'Vehicle', 'code' => 'VEHICLE'],
            ]
        );

        $this->seed(
            AssetPurpose::class,
            [
                ['name' => 'Revenue Generating', 'code' => 'REVENUE_GENERATING'],
                ['name' => 'Owner Occupied', 'code' => 'OWNER_OCCUPIED'],
                ['name' => 'Operational', 'code' => 'OPERATIONAL'],
                ['name' => 'Investment', 'code' => 'INVESTMENT'],
                ['name' => 'Development', 'code' => 'DEVELOPMENT'],
                ['name' => 'Strategic Reserve', 'code' => 'STRATEGIC_RESERVE'],
                ['name' => 'Community Use', 'code' => 'COMMUNITY_USE'],
                ['name' => 'Support / Ancillary', 'code' => 'SUPPORT_ANCILLARY'],
            ]
        );

        $this->seed(
            IndustryProfile::class,
            [
                ['name' => 'Commercial / Retail', 'code' => 'COMMERCIAL_RETAIL'],
                ['name' => 'Hospitality', 'code' => 'HOSPITALITY'],
                ['name' => 'Residential / Housing', 'code' => 'RESIDENTIAL_HOUSING'],
                ['name' => 'Industrial / Logistics', 'code' => 'INDUSTRIAL_LOGISTICS'],
                ['name' => 'Community / Institutional', 'code' => 'COMMUNITY_INSTITUTIONAL'],
                ['name' => 'Mixed Use', 'code' => 'MIXED_USE'],
                ['name' => 'Office', 'code' => 'OFFICE'],
                ['name' => 'Healthcare', 'code' => 'HEALTHCARE'],
                ['name' => 'Education', 'code' => 'EDUCATION'],
            ]
        );

        $this->seed(
            OperatingModel::class,
            [
                ['name' => 'Owner Operated', 'code' => 'OWNER_OPERATED'],
                ['name' => 'Third-Party Operated', 'code' => 'THIRD_PARTY_OPERATED'],
                ['name' => 'Leased', 'code' => 'LEASED'],
                ['name' => 'Managed', 'code' => 'MANAGED'],
                ['name' => 'Joint Venture', 'code' => 'JOINT_VENTURE'],
                ['name' => 'Franchise', 'code' => 'FRANCHISE'],
                ['name' => 'Concession', 'code' => 'CONCESSION'],
                ['name' => 'Outsourced', 'code' => 'OUTSOURCED'],
                ['name' => 'Hybrid', 'code' => 'HYBRID'],
            ]
        );

        $this->seed(
            Ownership::class,
            [
                ['name' => 'Freehold', 'code' => 'FREEHOLD'],
                ['name' => 'Leasehold', 'code' => 'LEASEHOLD'],
                ['name' => 'Joint Ownership', 'code' => 'JOINT_OWNERSHIP'],
                ['name' => 'Government Owned', 'code' => 'GOVERNMENT_OWNED'],
                ['name' => 'Private Owned', 'code' => 'PRIVATE_OWNED'],
                ['name' => 'SPV Owned', 'code' => 'SPV_OWNED'],
                ['name' => 'REIT Owned', 'code' => 'REIT_OWNED'],
                ['name' => 'Third-Party Owned', 'code' => 'THIRD_PARTY_OWNED'],
            ]
        );

        $this->seed(
            RevenueStatus::class,
            [
                ['name' => 'Revenue Generating', 'code' => 'REVENUE_GENERATING'],
                ['name' => 'Non-Revenue Generating', 'code' => 'NON_REVENUE_GENERATING'],
                ['name' => 'Potential Revenue', 'code' => 'POTENTIAL_REVENUE'],
                ['name' => 'Under Development', 'code' => 'UNDER_DEVELOPMENT'],
                ['name' => 'Under Lease', 'code' => 'UNDER_LEASE'],
                ['name' => 'Vacant', 'code' => 'VACANT'],
                ['name' => 'Held for Investment', 'code' => 'HELD_FOR_INVESTMENT'],
            ]
        );
    }

    private function seed(string $model, array $records): void
    {
        foreach ($records as $index => $record) {

            $model::updateOrCreate(
                ['code' => $record['code']],
                [
                    'name' => $record['name'],
                    'status' => 'Active',
                    'sort_order' => $index + 1,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]
            );
        }
    }
}