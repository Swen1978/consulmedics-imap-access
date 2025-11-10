@extends(config('imap-access.layout'))
@section('content')
    <div class="row">
        <div class="col-md-12">
            @if ($errors)
                {{ $errors->first() }}
            @endif
            <style>
                div>a.btn {
                    text-decoration: none;
                    color: white;
                }
            </style>
            <div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="grid grid-cols-2">
                        <h3 class="mt-0">
                            {{ __('imap-access::imap-mail.Read Message') }}
                        </h3>
                        <div>
                            <div class="flex justify-end">

                                <a href="{{ route('admin.imap-mail.list', [$folder, 'page' => $prevPage]) }}"
                                    class="btn btn-secondary">
                                    {{ __('imap-access::imap-mail.Close') }}
                                </a>

                                @if ($imapMail->status == 0)
                                    <a href="{{ route('admin.imap-mail.status-change', [$imapMail->id, '1']) }}"
                                        class="ml-1 btn mb-auto"
                                        style="background-color:green">{{ __('imap-access::imap-mail.Mark read') }}</a>
                                    <a href="{{ route('admin.imap-mail.status-change', [$imapMail->id, '2']) }}"
                                        class="ml-1 btn mb-auto"
                                        style="background-color:red">{{ __('imap-access::imap-mail.Mark faulty attachment') }}</a>
                                @elseif($imapMail->status == 1)
                                    <a href="{{ route('admin.imap-mail.status-change', [$imapMail->id, '0']) }}"
                                        class="ml-1 btn btn-warning">{{ __('imap-access::imap-mail.Mark unread') }}</a>
                                @elseif ($imapMail->status == 2)
                                    <a href="{{ route('admin.imap-mail.status-change', [$imapMail->id, '0']) }}"
                                        class="ml-1 btn btn-warning">{{ __('imap-access::imap-mail.Mark unread') }}</a>
                                    <a href="{{ route('admin.imap-mail.status-change', [$imapMail->id, '1']) }}"
                                        class="ml-1 btn"
                                        style="background-color:green">{{ __('imap-access::imap-mail.Mark read') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-3 border-gray-300 rounded-xl">
                        {{ csrf_field() }}
                        <div class="grid grid-cols-2">
                            <div class="grid grid-cols-10">

                                <div class="col-span-2">
                                    <label for=""><strong>{{ __('imap-access::imap-mail.Subject') }}:</strong></label>
                                </div>
                                <div class="col-span-8">
                                    {{ $imapMail->subject }}
                                </div>
                                <div class="col-span-2">
                                    <label for=""><strong>{{ __('imap-access::imap-mail.From') }}:</strong></label>
                                </div>
                                <div class="col-span-8">
                                    {{ $imapMail->from }}
                                </div>
                                <div class="col-span-2">
                                    <label for=""><strong>{{ __('imap-access::imap-mail.Receipients') }}:</strong></label>
                                </div>
                                <div class="col-span-8">
                                    {{ $imapMail->to }}
                                </div>
                                <div class="col-span-2">
                                    <label for=""><strong>{{ __('imap-access::imap-mail.Date') }}:</strong></label>
                                </div>
                                <div class="col-span-8">
                                    {{ $imapMail->date }}
                                </div>
                                <div class="col-span-2">
                                    <label for=""><strong>{{ __('Status') }}:</strong></label>
                                </div>
                                <div class="col-span-8">
                                    {!! match ($imapMail->status) {
                                        0 => '<span style="color: #C49102;"><i class="mdi mdi-bookmark" ></i>' .
                                            __('imap-access::imap-mail.status.not marked') .
                                            '</span>',
                                        1 => '<span style="color: green;"><i class="mdi mdi-bookmark-check" ></i>' .
                                            __('imap-access::imap-mail.status.marked') .
                                            '</span>',
                                        2 => '<span style="color: red;"><i class="mdi mdi-bookmark-check" ></i>' .
                                            __('imap-access::imap-mail.status.attachment faulty') .
                                            '</span>',
                                    } !!}
                                </div>
                            </div>
                            <div class="grid grid-cols-10">
                                <div class="col-span-2">
                                    <label for=""><strong>{{ __('imap-access::imap-mail.Attachments') }}:</strong></label>
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
