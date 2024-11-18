@extends(config('imap-access.layout'))
@section('content')
    <div>
        <div class="grid grid-cols-2">
            <h3 class="mt-0">
                {{ $label }}
            </h3>
            <livewire:imap-access:imap-table-action-bar />
        </div>
        <div class="card-body">
            <livewire:imap-access:imap-table :folder="$folder" />
        </div>
    </div>
@endsection
