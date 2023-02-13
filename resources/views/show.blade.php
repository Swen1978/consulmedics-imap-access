@extends(config("imap-access.layout"))
@section('content')
    <div class="row">
        <div class="col-md-12">
            @if ($errors)
                {{ $errors->first() }}
            @endif
            <div class="card">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="card-header">
                        <div class="float-right">
                            <a href="{{ route('admin.imap-mail.list', [$folder]) }}" class="btn btn-github mr-2">
                                {{ __('Cancel') }}
                            </a>

                            @if ($imapMail->status == 0)
                                <a href="{{ route('admin.imap-mail.status-change', [$imapMail->id, "1"]) }}"
                                   class="btn btn-github">{{ __('Mark readed') }}</a>
                            @else
                                <a href="{{ route('admin.imap-mail.status-change', [$imapMail->id, "0"]) }}"
                                   class="btn btn-github">{{ __('Mark unreaded') }}</a>
                            @endif
                        </div>

                        <div class="py-2">
                            {{ __('Read Message') }}
                        </div>
                    </div>
                    <div class="card-body">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for=""><strong>{{ __('Subject') }}:</strong></label>
                                    </div>
                                    <div class="col-md-10">
                                        {{ $imapMail->subject }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for=""><strong>{{ __('From') }}:</strong></label>
                                    </div>
                                    <div class="col-md-10">
                                        {{ $imapMail->from }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for=""><strong>{{ __('Recipients') }}:</strong></label>
                                    </div>
                                    <div class="col-md-10">
                                        {{ $imapMail->to }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for=""><strong>{{ __('date') }}:</strong></label>
                                    </div>
                                    <div class="col-md-10">
                                        {{ $imapMail->date }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for=""><strong>{{ __('Status') }}:</strong></label>
                                    </div>
                                    <div class="col-md-10">
                                        {!! match($imapMail->status) {
                                            0 => '<i title="'.__('Status unchecked').'" class="mdi mdi-bookmark" style="color: #C49102;"></i>',
                                            1 => '<i title="'.__('Status checked').'" class="mdi mdi-bookmark-check" style="color: var(--primary);"></i>',
                                        } !!}
                                        {{ $imapMail->status == 1 ? __('marked') : __('not marked') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-2">
                                    <label for=""><strong>{{ __('Attachments') }}:</strong></label>
                                </div>
                                <div class="col-md-10">
                                    <ul class="list-disc ml-4">
                                        @foreach ($imapMail->attachments as $attachment)
                                            @php($attachmentUrl = route('admin.imap-mail.download-attachment', [
                                                "message" => $imapMail->number,
                                                "name" => $attachment['name']
                                            ]))
                                            <li>
                                                <a href="{{ $attachmentUrl }}">
                                                    {{ $attachment['name'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                {!! $imapMail->html ? $imapMail->text : nl2br($imapMail->text) !!}
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
