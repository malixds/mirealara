@extends('layouts.default')

@section('content')
    <div class="chats">
        <div class="container">
            <div class="chats__inner">
                <div class="row">
                    @foreach($chats as $chat)
                        {{$chat->id}} <br>
                        <a href="{{route('chat', ['chat' => $chat])}}">{{$chat->id}}</a>
                        <div class="line col-12"></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
