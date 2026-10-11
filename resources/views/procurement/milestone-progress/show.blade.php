@if(
    $contract->status === 'Active' &&
    $milestone->status !== 'Completed'
)

    <a
        href="{{ route(
            'admin.procurement.tenders.contracts.milestones.progress.index',
            [
                'procurementTender' => $procurementTender,
                'contract' => $contract,
                'milestone' => $milestone,
            ]
        ) }}"
        class="btn btn-info"
    >
        Progress Updates
    </a>

@endif