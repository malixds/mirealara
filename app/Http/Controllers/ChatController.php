<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\MessageFormRequest;
use App\Models\Message;
use Illuminate\Http\Request;
use Ramsey\Collection\Collection;

class ChatController extends Controller
{
    public function chat()
    {
        $user = auth()->user();
        return view('pages.chat', [
            'user' => $user,
        ]);
    }
    public function chatMessages(): Collection|array
    {
        return Message::query()
            ->with('user')
            ->get();
    }

    public function chatSend(MessageFormRequest $request)
    {
        dump('check', $request);


        $message = $request->user()
            ->messages()
            ->create($request->validated());

        broadcast(new MessageSent($request->user(), $message));

        return $message;
    }
}
