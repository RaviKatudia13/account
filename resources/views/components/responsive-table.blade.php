@props(['headers', 'rows', 'mobileCardView' => true, 'tableClass' => ''])

@if($mobileCardView)
    <!-- Mobile Card View -->
    <div class="d-lg-none">
        @forelse ($rows as $row)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="flex-grow-1">
                        @foreach($headers as $key => $header)
                            @if($key !== 'actions')
                                <div class="mb-1">
                                    <small class="text-muted">{{ $header }}:</small>
                                    <div class="fw-medium">{{ $row[$key] ?? '' }}</div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    @if(isset($row['actions']))
                        <div class="d-flex gap-2">
                            {!! $row['actions'] !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center text-muted py-4">
            <i class="fas fa-inbox fa-3x mb-3"></i>
            <p>No data found.</p>
        </div>
        @endforelse
    </div>

    <!-- Desktop Table View -->
    <div class="table-responsive d-none d-lg-block">
        <table class="table table-striped align-middle {{ $tableClass }}">
            <thead class="table-light">
                <tr>
                    @foreach($headers as $key => $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $row)
                <tr>
                    @foreach($headers as $key => $header)
                        <td>
                            @if($key === 'actions')
                                {!! $row[$key] ?? '' !!}
                            @else
                                {{ $row[$key] ?? '' }}
                            @endif
                        </td>
                    @endforeach
                </tr>
                @empty
                <tr>
                    <td colspan="{{ count($headers) }}" class="text-center text-muted">No data found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@else
    <!-- Always Table View -->
    <div class="table-responsive">
        <table class="table table-striped align-middle {{ $tableClass }}">
            <thead class="table-light">
                <tr>
                    @foreach($headers as $key => $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $row)
                <tr>
                    @foreach($headers as $key => $header)
                        <td>
                            @if($key === 'actions')
                                {!! $row[$key] ?? '' !!}
                            @else
                                {{ $row[$key] ?? '' }}
                            @endif
                        </td>
                    @endforeach
                </tr>
                @empty
                <tr>
                    <td colspan="{{ count($headers) }}" class="text-center text-muted">No data found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endif 