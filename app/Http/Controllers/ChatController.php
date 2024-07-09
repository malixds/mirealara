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
        try {
            return Message::query()
                ->with('user')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching chat messages: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch chat messages'], 500);
        }
    }

    public function chatSend(MessageFormRequest $request): array
    {
        
        $user = auth()->user();
        $message = $request->user()
            ->messages()
            ->create($request->validated());
        broadcast(new MessageSent($request->user(), $message));
        return ['user'=> $user,
            'message'=> $message,
            ];
    }
}
