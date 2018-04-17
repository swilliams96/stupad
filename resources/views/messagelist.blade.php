@extends('layouts.app')

@section('content')

    <div class="container fullwidth fullheight">
        <div class="row" style="margin: 0; height: 100%; display: table-row;">

            @include('common.dashboardsidebar', ['page' => 'messages'])


            <!-- MAIN PAGE -->
            <div class="col-lg-6 mainpage no-float no-select">
                <h1>Messages</h1>
                @if (count($chats) > 0)
                    @foreach ($chats as $chat)
                    <a href="/dashboard/messages/{{ $chat->first()->other }}" class="chat {{ is_null($chat->first()->seen_at) ? 'chat-unread' : 'chat-read' }}">
                        <h3>{{ $chat->first()->full_name }}</h3>
                        <p>{{ $chat->first()->message }}</p>
                    </a>
                    @endforeach
                @else
                <div>
                    <div class="listing">
                        <p>No messages found.</p>
                        <p>Send messages to property owners to ask questions about the property or to arrange a viewing.</p>
                        <p>Just <a href="{{ route('search') }}">search</a> for somewhere that you like, then click the red <i>Contact</i> button at the top of the listing page to get in touch!</p>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-3 no-float"></div>

        </div>
    </div>

@endsection