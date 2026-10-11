@php
    $badgeClass = match ($status ?? '') {
        'Approved' => 'bg-success',
        'Under Review', 'Submitted' => 'bg-warning text-dark',
        'Rejected' => 'bg-danger',
        'Superseded' => 'bg-secondary',
        default => 'bg-secondary',
    };
@endphp
<span class="badge {{ $badgeClass }}">{{ $status }}</span>
