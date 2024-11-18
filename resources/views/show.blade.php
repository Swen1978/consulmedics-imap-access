@extends(config('imap-access.layout'))
@section('content')
    <div class="row">
        <div class="col-md-12">
            @if ($errors)
                {{ $errors->first() }}
            @endif
            <div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="grid grid-cols-2">
                        <h3 class="mt-0">
                            {{ __('Read Message') }}
                        </h3>
                        <div>
                            <div class="flex justify-end">

                                <a href="{{ route('admin.imap-mail.list', [$folder, 'page' => $prevPage]) }}"
                                    class="btn btn-secondary">
                                    {{ __('Cancel') }}
                                </a>

                                @if ($imapMail->status == 0)
                                    <a href="{{ route('admin.imap-mail.status-change', [$imapMail->id, '1']) }}"
                                        class="ml-1 btn btn-primary mb-auto">{{ __('Mark readed') }}</a>
                                @else
                                    <a href="{{ route('admin.imap-mail.status-change', [$imapMail->id, '0']) }}"
                                        class="ml-1 btn btn-primary">{{ __('Mark unreaded') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-3 border-gray-300 rounded-xl">
                        {{ csrf_field() }}
                        <div class="grid grid-cols-2">
                            <div class="grid grid-cols-10">

                                <div class="col-span-2">
                                    <label for=""><strong>{{ __('Subject') }}:</strong></label>
                                </div>
                                <div class="col-span-8">
                                    {{ $imapMail->subject }}
                                </div>
                                <div class="col-span-2">
                                    <label for=""><strong>{{ __('From') }}:</strong></label>
                                </div>
                                <div class="col-span-8">
                                    {{ $imapMail->from }}
                                </div>
                                <div class="col-span-2">
                                    <label for=""><strong>{{ __('Recipients') }}:</strong></label>
                                </div>
                                <div class="col-span-8">
                                    {{ $imapMail->to }}
                                </div>
                                <div class="col-span-2">
                                    <label for=""><strong>{{ __('date') }}:</strong></label>
                                </div>
                                <div class="col-span-8">
                                    {{ $imapMail->date }}
                                </div>
                                <div class="col-span-2">
                                    <label for=""><strong>{{ __('Status') }}:</strong></label>
                                </div>
                                <div class="col-span-8">
                                    {!! match ($imapMail->status) {
                                        0 => '<i title="' . __('Status unchecked') . '" class="mdi mdi-bookmark" style="color: #C49102;"></i>',
                                        1 => '<i title="' . __('Status checked') . '" class="mdi mdi-bookmark-check" style="color: var(--primary);"></i>',
                                    } !!}
                                    {{ $imapMail->status == 1 ? __('marked') : __('not marked') }}
                                </div>
                            </div>
                            <div class="grid grid-cols-10">
                                <div class="col-span-2">
                                    <label for=""><strong>{{ __('Attachments') }}:</strong></label>
                                </div>
                                <div class="col-span-8">
                                    <ul class="ml-4">
                                        @foreach ($imapMail->attachments as $attachment)
                                            @php
                                                $attachmentUrl = route('admin.imap-mail.download-attachment', [
                                                    'message' => $imapMail->number,
                                                    'name' => $attachment['name'],
                                                ]);
                                            @endphp
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
