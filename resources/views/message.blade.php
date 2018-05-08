@extends('layouts.app')

@section('content')

    <div class="container fullwidth fullheight">
        <div class="row" style="margin: 0; height: 100%; display: table-row;">

            @include('common.dashboardsidebar', ['page' => 'viewmessage'])


            <!-- MAIN PAGE -->
            <div class="col-lg-6 mainpage no-float" style="padding-bottom: 74px;">
                <h1>Messages - {{ $other->first_name . ' ' . $other->last_name }}</h1>
                <div id="message-container">
                @foreach ($messages as $message)
                    <div class="message message-{{ $message->from == $user->id ? 'sent' : 'received' }}">
                        <h3>{{ $message->from == $user->id ? 'You' : ($message->from == $other->id ? ($other->first_name . ' ' . $other->last_name) : 'Unknown') }}:</h3>
                        @foreach ($message->message as $paragraph)<p>{{ $paragraph }}</p>@endforeach
                        {{--<p>{{ $message->message }}</p>--}}
                    </div>
                @endforeach
                </div>
                <div class="message-textbox-container">
                    <textarea id="message-input-text" class="message-textbox" name="message" placeholder="Type a message..." autofocus></textarea>
                    <button id="send-message-btn" class="btn btn-primary btn-send">Send</button>
                </div>
            </div>

            <div class="col-lg-3 no-float"></div>

        </div>
    </div>

    @include('scripts.csrftoken')

    @include('scripts.messages')

    <script>
        $(document).ready(function() {
            $('#message-container').scrollTop($('#message-container')[0].scrollHeight - $('#message-container')[0].clientHeight);
        });
    </script>

@endsection