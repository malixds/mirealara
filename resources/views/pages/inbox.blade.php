@extends('layouts.default')

@section('content')
    <div class="chats">
        <div class="container">
            <div class="chats__inner">
                <div class="row">
                    @foreach($chats as $chat)
                        <a class="chat col-12" href="{{route('chat', $chat->id)}}">
                            {{$chat->sender_id}}
                            <br>
                            {{$chat->last_message}}
                        </a>
                        <div class="line col-12"></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
