@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="text-muted small">Design Management</div>
            <h4 class="mb-1">{{ $project->project_name ?? 'Project' }}</h4>
            @if(!empty($project->project_code))
                <div class="text-muted">{{ $project->project_code }}</div>
            @endif
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.design-management.index') }}" class="btn btn-outline-secondary">Back</a>
        <div class="d-flex gap-2">
    </div>

    @include('design-management.partials.alerts')

  {{-- Summary cards --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">Design Packages</div>
                    <div class="fs-3 fw-semibold">{{ number_format($dashboard['packages']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">Drawings</div>
                    <div class="fs-3 fw-semibold">{{ number_format($dashboard['drawings']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">Open RFIs</div>
                    <div class="fs-3 fw-semibold">{{ number_format($dashboard['open_rfis']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">Pending Approvals</div>
                    <div class="fs-3 fw-semibold">{{ number_format($dashboard['pending_approvals']) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Design Management modules (same pattern as Construction Management) --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Design Management</strong>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @php
                    $modules = [
                        [
                            'title' => 'Project Brief',
                            'description' => 'Design-specific requirements and objectives for the project.',
                            'route' => 'admin.projects.design-management.briefs.index',
                            'active' => true,
                        ],
                        [
                            'title' => 'Consultants',
                            'description' => 'Assign and manage design consultants by discipline.',
                            'route' => 'admin.projects.design-management.consultants.index',
                            'active' => true,
                        ],
                        [
                            'title' => 'Design Packages',
                            'description' => 'Group related design deliverables and submissions.',
                            'route' => 'admin.projects.design-management.packages.index',
                            'active' => true,
                        ],
                        [
                            'title' => 'Drawing Register',
                            'description' => 'Controlled register of drawings, revisions and approvals.',
                            'route' => 'admin.projects.design-management.drawings.index',
                            'active' => true,
                        ],
                        [
                            'title' => 'Submittals',
                            'description' => 'Consultant design submittals and review workflow.',
                            'route' => 'admin.projects.design-management.submittals.index',
                            'active' => true,
                        ],
                        [
                            'title' => 'Reviews',
                            'description' => 'Review history and decisions on design submittals.',
                            'route' => 'admin.projects.design-management.reviews.index',
                            'active' => true,
                        ],
                        [
                            'title' => 'Comments',
                            'description' => 'Individual review comments, responses and resolution.',
                            'route' => 'admin.projects.design-management.comments.index',
                            'active' => true,
                        ],
                        [
                            'title' => 'RFIs',
                            'description' => 'Requests for information and clarifications.',
                            'route' => 'admin.projects.design-management.rfis.index',
                            'active' => true,
                        ],
                        [
                            'title' => 'Design Changes',
                            'description' => 'Formal design change control with cost and time impact.',
                            'route' => 'admin.projects.design-management.changes.index',
                            'active' => true,
                        ],
                        [
                            'title' => 'Approvals',
                            'description' => 'Pending briefs, submittals and design changes.',
                            'route' => 'admin.projects.design-management.approvals.index',
                            'active' => true,
                        ],
                    ];
                @endphp

                @foreach($modules as $module)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="fw-semibold mb-1">{{ $module['title'] }}</div>
                            <div class="small text-muted mb-3">{{ $module['description'] }}</div>
                            @if($module['active'])
                                <a href="{{ route($module['route'], $project) }}" class="btn btn-sm btn-outline-primary">
                                    Open Module
                                </a>
                            @else
                                <span class="badge bg-secondary">Coming Soon</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white fw-semibold">Recent Submittals</div>
                <div class="card-body p-0">
                    @forelse($recentSubmittals as $submittal)
                        <div class="px-3 py-3 border-bottom">
                            <div class="fw-semibold">{{ $submittal->submittal_number }} — {{ $submittal->subject }}</div>
                            <div class="small text-muted">{{ $submittal->discipline?->name ?? '—' }} · {{ $submittal->status }}</div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">No submittals yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white fw-semibold">Recent RFIs</div>
                <div class="card-body p-0">
                    @forelse($recentRfis as $rfi)
                        <div class="px-3 py-3 border-bottom">
                            <div class="fw-semibold">{{ $rfi->rfi_number }} — {{ $rfi->subject }}</div>
                            <div class="small text-muted">{{ $rfi->discipline?->name ?? '—' }} · {{ $rfi->priority }} · {{ $rfi->status }}</div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">No RFIs yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
