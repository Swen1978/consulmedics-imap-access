@extends(config("imap-access.layout"))
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ $label }}
                </div>
                <div class="card-body">
                    <livewire:imap-access:imap-table-action-bar />
                    <livewire:imap-access:imap-table :folder="$folder" />
                </div>
            </div>
        </div>
    </div>
@endsection
