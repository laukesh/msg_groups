{{-- =================================================
     GLA / NLA & AREA MANAGEMENT
================================================== --}}

<div class="sidebar-section-label">
    GLA / NLA & Areas
</div>


{{-- =================================================
     AREA REGISTER
================================================== --}}

@can('areas.view')
    <a href="{{ route('admin.assets.areas.index') }}"
       class="sidebar-sublink
              {{ request()->routeIs('admin.assets.areas.index')
                  || request()->routeIs('admin.assets.areas.show')
                  || request()->routeIs('admin.assets.areas.edit')
                  ? 'active'
                  : '' }}">

        <i class="ri-layout-grid-line"></i>
        <span>Area Register</span>

    </a>
@endcan


{{-- =================================================
     ADD AREA
================================================== --}}

@can('areas.create')
    <a href="{{ route('admin.assets.areas.create') }}"
       class="sidebar-sublink
              {{ request()->routeIs('admin.assets.areas.create')
                  ? 'active'
                  : '' }}">

        <i class="ri-add-circle-line"></i>
        <span>Add Area</span>

    </a>
@endcan


{{-- =================================================
     AREA TYPES
================================================== --}}

@can('area-types.view')
    <a href="{{ route('admin.assets.types.index') }}"
       class="sidebar-sublink
              {{ request()->routeIs('admin.assets.types.*')
                  ? 'active'
                  : '' }}">

        <i class="ri-ruler-line"></i>
        <span>Area Types</span>

    </a>
@endcan


{{-- =================================================
     AREA REVISIONS
================================================== --}}
@can('area-revisions.view')

    {{-- Revision register requires an area ID --}}
    @if(isset($area) && !empty($area->id))

        <a href="{{ route(
                'admin.assets.revisions.index',
                ['area' => $area->id]
            ) }}"
           class="sidebar-sublink
                  {{ request()->routeIs('admin.assets.revisions.*')
                      ? 'active'
                      : '' }}">

            <i class="ri-history-line"></i>
            <span>Area Revisions</span>

        </a>

    @endif

@endcan

{{-- =================================================
     GLA / NLA REPORTS
================================================== --}}

@can('area-reports.view')

    {{-- GLA --}}
    @if(Route::has('admin.assets.reports.gla'))
        <a href="{{ route('admin.assets.reports.gla') }}"
           class="sidebar-sublink
                  {{ request()->routeIs('admin.assets.reports.gla')
                      ? 'active'
                      : '' }}">

            <i class="ri-bar-chart-line"></i>
            <span>GLA Report</span>

        </a>
    @endif


    {{-- NLA --}}
    @if(Route::has('admin.assets.reports.nla'))
        <a href="{{ route('admin.assets.reports.nla') }}"
           class="sidebar-sublink
                  {{ request()->routeIs('admin.assets.reports.nla')
                      ? 'active'
                      : '' }}">

            <i class="ri-pie-chart-line"></i>
            <span>NLA Report</span>

        </a>
    @endif


    {{-- Occupancy --}}
    @if(Route::has('admin.assets.reports.occupancy'))
        <a href="{{ route('admin.assets.reports.occupancy') }}"
           class="sidebar-sublink
                  {{ request()->routeIs('admin.assets.reports.occupancy')
                      ? 'active'
                      : '' }}">

            <i class="ri-building-line"></i>
            <span>Occupancy Report</span>

        </a>
    @endif


    {{-- Summary --}}
    @if(Route::has('admin.assets.reports.summary'))
        <a href="{{ route('admin.assets.reports.summary') }}"
           class="sidebar-sublink
                  {{ request()->routeIs('admin.assets.reports.summary')
                      ? 'active'
                      : '' }}">

            <i class="ri-file-chart-line"></i>
            <span>Area Summary</span>

        </a>
    @endif

@endcan
