@extends(config('imap-access.layout'))
@section('content')
    <div>
        <h3 class="mt-0">
            {{ $label }}
        </h3>
        <div class="card-body">
            <livewire:imap-access:imap-table-action-bar />
            <livewire:imap-access:imap-table :folder="$folder" />
        </div>
    </div>
@endsection
