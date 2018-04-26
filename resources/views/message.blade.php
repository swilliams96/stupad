@extends('layouts.app')

@section('content')

    <div class="container fullwidth fullheight">
        <div class="row" style="margin: 0; height: 100%; display: table-row;">

            @include('common.dashboardsidebar', ['page' => 'viewmessage'])


            <!-- MAIN PAGE -->
            <div class="col-lg-6 mainpage no-float" style="padding-bottom: 74px;">
                <h1>Messages - {{ $other->first_name . ' ' . $other->last_name }}</h1>
                @foreach ($messages as $message)
                    <div class="message message-{{ $message->from == $user->id ? 'sent' : 'received' }}">
                        <h3>{{ $message->from == $user->id ? 'You' : ($message->from == $other->id ? ($other->first_name . ' ' . $other->last_name) : 'Unknown') }}:</h3>
                        <p>{{ $message->message }}</p>
                    </div>
                @endforeach

                <div class="message-textbox-container">
                    <textarea class="message-textbox" placeholder="Type a message..." autofocus></textarea>
                    <button class="btn btn-primary btn-send">Send</button>
                </div>
            </div>

            <div class="col-lg-3 no-float"></div>

        </div>
    </div>

@endsection