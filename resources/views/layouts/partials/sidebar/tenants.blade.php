
@php
    $isTenantsGroupActive = request()->is('admin/tenants*');

    $tenantMenuItems = [
        [
            'label' => 'Dashboard',
            'url' => '/admin/tenants/dashboard',
            'pattern' => 'admin/tenants/dashboard',
            'icon' => 'ri-dashboard-line',
        ],
        [
            'label' => 'All Tenants',
            'url' => '/admin/tenants',
            'pattern' => 'admin/tenants',
            'icon' => 'ri-group-line',
        ],
        [
            'label' => 'Active Tenants',
            'url' => '/admin/tenants?status=Active',
            'pattern' => null,
            'icon' => 'ri-user-follow-line',
        ],
        [
            'label' => 'Inactive Tenants',
            'url' => '/admin/tenants?status=Inactive',
            'pattern' => null,
            'icon' => 'ri-user-unfollow-line',
        ],
        [
            'label' => 'Tenant Leases',
            'url' => '/admin/tenants/leases',
            'pattern' => 'admin/tenants/leases',
            'icon' => 'ri-file-text-line',
        ],
        [
            'label' => 'Lease Expiry',
            'url' => '/admin/tenants/leases/expiry',
            'pattern' => 'admin/tenants/leases/expiry',
            'icon' => 'ri-calendar-close-line',
        ],
        [
            'label' => 'Contacts',
            'url' => '/admin/tenants/contacts',
            'pattern' => 'admin/tenants/contacts',
            'icon' => 'ri-contacts-line',
        ],
        [
            'label' => 'Emergency Contacts',
            'url' => '/admin/tenants/emergency-contacts',
            'pattern' => 'admin/tenants/emergency-contacts',
            'icon' => 'ri-phone-line',
        ],
        [
            'label' => 'Documents',
            'url' => '/admin/tenants/documents',
            'pattern' => 'admin/tenants/documents',
            'icon' => 'ri-file-copy-line',
        ],
    ];

    $tenantCommercialItems = [
        [
            'label' => 'Negotiations',
            'url' => '/admin/tenants/negotiations',
            'pattern' => 'admin/tenants/negotiations*',
            'icon' => 'ri-exchange-line',
            'permission' => 'tenders.negotiations.view',
        ],
        [
            'label' => 'Letter of Intent (LOI)',
            'url' => '/admin/tenants/lois',
            'pattern' => 'admin/tenants/lois*',
            'icon' => 'ri-draft-line',
            'permission' => 'lois.view',
        ],
        [
            'label' => 'Lease Approval',
            'url' => '/admin/tenants/lease-approvals',
            'pattern' => 'admin/tenants/lease-approvals*',
            'icon' => 'ri-shield-check-line',
            'permission' => 'lease-approvals.view',
        ],
        [
            'label' => 'Lease Agreements',
            'url' => '/admin/tenants/lease-agreements',
            'pattern' => 'admin/tenants/lease-agreements*',
            'icon' => 'ri-file-list-3-line',
            'permission' => 'lease-agreements.view',
        ],
        [
            'label' => 'Tenant Directory',
            'url' => '/admin/tenants/directory',
            'pattern' => 'admin/tenants/directory*',
            'icon' => 'ri-community-line',
            'permission' => 'tenant-directory.view',
        ],
        [
            'label' => 'Tenant Profile 360°',
            'url' => '/admin/tenants/profiles',
            'pattern' => 'admin/tenants/profiles*',
            'icon' => 'ri-user-settings-line',
            'permission' => 'tenant-profiles.view',
        ],
        [
            'label' => 'Fit-Out Coordination',
            'url' => 'admin/fitout/requests',
            'pattern' => 'admin/fitout/requests*',
            'icon' => 'ri-tools-line',
            'permission' => 'fit-outs.view',
        ],
        [
            'label' => 'Opening / Handover',
            'url' => '/admin/tenants/openings',
            'pattern' => 'admin/tenants/openings*',
            'icon' => 'ri-key-2-line',
            'permission' => 'tenant-openings.view',
        ],
        [
            'label' => 'Rent & Charges',
            'url' => 'admin/revenue/rent-schedules',
            'pattern' => 'admin/revenue/rent-schedules*',
            'icon' => 'ri-money-dollar-circle-line',
            'permission' => 'rent-charges.view',
        ],
    ];

    $isTenantCommercialActive = collect($tenantCommercialItems)
        ->contains(fn ($item) => request()->is($item['pattern']));

    $isTenantsGroupActive = $isTenantsGroupActive
        || $isTenantCommercialActive;
@endphp

<details class="sidebar-group"
    {{ $isTenantsGroupActive ? 'open' : '' }}>

    <summary class="sidebar-link {{ $isTenantsGroupActive ? 'active' : '' }}">
        <i class="ri-group-line"></i>
        <span>Tenants</span>
        <i class="ri-arrow-right-s-line sidebar-arrow"></i>
    </summary>

    <div class="sidebar-submenu">

        {{-- Tenant Management --}}
        @foreach ($tenantMenuItems as $item)
            @php
                $active = $item['pattern']
                    ? request()->is($item['pattern'])
                    : (
                        request()->is('admin/tenants')
                        && request()->query('status') === (
                            $item['label'] === 'Active Tenants'
                                ? 'Active'
                                : 'Inactive'
                        )
                    );

                if ($item['label'] === 'All Tenants') {
                    $active = request()->is('admin/tenants')
                        && !request()->has('status');
                }

                if ($item['label'] === 'Tenant Leases') {
                    $active = request()->is('admin/tenants/leases')
                        && !request()->is('admin/tenants/leases/expiry');
                }
            @endphp

            <a href="{{ url($item['url']) }}"
               class="sidebar-sublink {{ $active ? 'active' : '' }}">
                <i class="{{ $item['icon'] }}"></i>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach

        {{-- Commercial, Legal & Tenant Operations --}}
        <div class="sidebar-subheading">
            <span>Commercial & Lease Operations</span>
        </div>

        @foreach ($tenantCommercialItems as $item)
            @can($item['permission'])
                <a href="{{ url($item['url']) }}"
                   class="sidebar-sublink
                       {{ request()->is($item['pattern']) ? 'active' : '' }}">
                    <i class="{{ $item['icon'] }}"></i>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endcan
        @endforeach



</details>
