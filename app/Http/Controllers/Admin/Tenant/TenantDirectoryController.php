<?php

namespace App\Http\Controllers\Admin\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenantDirectoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::query()
            ->with(['parentGroup'])
            ->withCount(['contacts', 'leases']);

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('brand_name', 'like', "%{$search}%")
                    ->orWhere('tenant_code', 'like', "%{$search}%")
                    ->orWhere('company_registration_no', 'like', "%{$search}%")
                    ->orWhere('gst_number', 'like', "%{$search}%")
                    ->orWhere('pan_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('business_category_id', $request->category);
        }

        if ($request->filled('parent_group_id')) {
            $query->where('parent_group_id', $request->parent_group_id);
        }

        $tenants = $query
            ->orderBy('company_name')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => Tenant::count(),
            'active' => Tenant::where('status', 'Active')->count(),
            'inactive' => Tenant::where('status', 'Inactive')->count(),
            'parent_groups' => Tenant::whereHas('childGroups')->count(),
        ];

        $categories = DB::table('business_categories')
            ->orderBy('category_name')
            ->get();

        $parentGroups = Tenant::query()
            ->whereNull('parent_group_id')
            ->orderBy('company_name')
            ->get(['id', 'company_name', 'brand_name']);

        return view('admin.tenants.directory.index', compact(
            'tenants',
            'stats',
            'categories',
            'parentGroups'
        ));
    }

    public function show(Tenant $tenant)
    {
        $tenant->load([
            'parentGroup',
            'childGroups',
            'contacts',
            'addresses',
            'bankAccounts',
            'documents',
            'leases',
        ]);

        $category = DB::table('business_categories')
            ->where('id', $tenant->business_category_id)
            ->value('category_name');

        return view('admin.tenants.directory.show', compact(
            'tenant',
            'category'
        ));
    }
}