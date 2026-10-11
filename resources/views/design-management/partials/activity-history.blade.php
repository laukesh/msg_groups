<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Approval & Activity History</strong>
        <span class="text-muted small">{{ $histories->count() }} record(s)</span>
    </div>
    <div class="card-body p-0">
        @if($histories->count())
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Action</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Remarks</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($histories as $history)
                            <tr>
                                <td>{{ $history->performed_at?->format('d M Y H:i') ?? '—' }}</td>
                                <td><span class="badge bg-secondary">{{ $history->action }}</span></td>
                                <td>{{ $history->old_status ?? '—' }}</td>
                                <td>{{ $history->new_status ?? '—' }}</td>
                                <td>{{ $history->remarks ?? '—' }}</td>
                                <td>{{ $history->performer?->name ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-4 text-muted text-center">No activity history recorded.</div>
        @endif
    </div>
</div>
