<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">{{ $title }}</h5>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    @foreach($columns as $column)
                        <th>{{ \Illuminate\Support\Str::headline($column) }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @forelse($rows as $row)
                    <tr>
                        @foreach($columns as $column)
                            <td>
                                @if(isset($row->{$column}) && is_numeric($row->{$column})
                                    && str_contains($column, 'amount'))
                                    {{ number_format((float) $row->{$column}, 2) }}
                                @else
                                    {{ $row->{$column} ?? '—' }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) }}"
                            class="text-center text-muted py-4">
                            No records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>