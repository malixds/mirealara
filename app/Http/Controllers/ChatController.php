<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\MessageFormRequest;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Ramsey\Collection\Collection;

class ChatController extends Controller
{
    public function chat(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $user = auth()->user();
        return view('pages.chat', [
            'user' => $user,
        ]);
    }
    public function chatMessages(): \Illuminate\Http\JsonResponse|array|\Illuminate\Database\Eloquent\Collection
    {
        return Message::query()
            ->with('user')
            ->get();
    }

    public function chatSend(MessageFormRequest $request): array
    {

        $message = $request->user()
            ->messages()
            ->create($request->validated());

        broadcast(new MessageSent($request->user(), $message));
        dump($message);
        return $message;
    }
}
