<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\MessageFormRequest;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;


class ChatController extends Controller
{
    public function chats()
    {
        auth()->login(User::find(2));
        $chats = auth()->user()->chats;
        return view('pages.inbox', [
            'chats' => $chats,
            'user' => auth()->user()
        ]);
    }

    public function chat(Chat $chat)
    {

//        dd($chat->messages()->with('user')->get());
//        $messages = $chat->messages()->with('user')->paginate();
//        return view('pages.chat', compact('messages'));
        auth()->login(User::find(2));
        $messages = $chat->messages()->with('user')->get();
        return view('pages.chat', [
            'user' => auth()->user(),
            'messages' => $messages
        ]);
    }

    public function chatMessages(int $id): \Illuminate\Http\JsonResponse|array|\Illuminate\Database\Eloquent\Collection
    {
        return Message::query()
            ->with('user')
            ->where('chat_id', $id)
            ->get();
    }

    public function chatSend(MessageFormRequest $request): array
    {

        $message = $request->user()
            ->chats()
            ->messages()
            ->create($request->validated());


        broadcast(new MessageSent($request->user(), $message));
        dump($message);
        return $message;
    }
}
