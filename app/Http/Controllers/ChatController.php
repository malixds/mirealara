<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\MessageFormRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Ramsey\Collection\Collection;

class ChatController extends Controller
{
    public function chat(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $user = auth()->user();
//        dd($user);
        $inbox = $user->inbox()->first();
//        dd($inbox);
        $chats = $inbox->chats()->get();
        $chat = $inbox->chats()->first();
//        dd($chat);
        $messages = $chat->messages()->get();
//        dd($messages);
        return view('pages.chat', [
            'user' => $user,
            'chatId' => $chat->id
//            'messages' => $messages
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
