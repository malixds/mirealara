<?php

namespace App\Http\Controllers;

use App\Dto\Chat\SendMessageDto;
use App\Events\MessageSent;
use App\Http\Requests\MessageFormRequest;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Post;
use App\Models\User;
use App\Services\Chat\SendMessageService;


class ChatController extends Controller
{
    public function chatCreate(Post $post)
    {
        $userId = auth()->user()->id;
        $secondUser = $post->user->id;
        $chatExists = \DB::table('chats_users')
            ->where(function ($query) use ($userId, $secondUser) {
                $query->where('user_id', $userId)
                    ->where('buddy_id', $secondUser);
            })
            ->orWhere(function ($query) use ($userId, $secondUser) {
                $query->where('user_id', $secondUser)
                    ->where('buddy_id', $userId);
            })->exists();
        if ($chatExists) {
            $chatId = \DB::table('chats_users')
                ->where(function ($query) use ($userId, $secondUser) {
                    $query->where('user_id', $userId)
                        ->where('buddy_id', $secondUser);
                })
                ->orWhere(function ($query) use ($userId, $secondUser) {
                    $query->where('user_id', $secondUser)
                        ->where('buddy_id', $userId);
                })
                ->pluck('chat_id')
                ->first();

            $messages = Chat::find($chatId)->messages()->with('user')->get();
            return redirect()->route('chat', Chat::find($chatId));
        }
        $chat = Chat::create();
        auth()->user()->chatsOnlyUser()->attach($chat->id, [
            'buddy_id' => $post->user->id
        ]);
        return redirect()->route('chat', $chat);
    }

    public function chats()
    {
        $chats = auth()->user()->allChats()->all();
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
        $messages = $chat->messages()->with('user')->get();
        return view('pages.chat', [
            'user' => auth()->user(),
            'messages' => $messages,
        ]);
    }

    public function chatMessages(int $id): \Illuminate\Http\JsonResponse|array|\Illuminate\Database\Eloquent\Collection
    {
        return Message::query()
            ->with('user')
            ->where('chat_id', $id)
            ->get();
    }

    public function chatSend(int $id, MessageFormRequest $request, SendMessageService $service)
    {
        $dto = new SendMessageDto(
            chatId: $id,
            userId: $request->user()->id,
            message: $request->input('message'),
        );
        $chat = auth()->user()->allChats()->find($id);
        if ($chat) {
            $message = $service->run($dto, $request, $chat);
            $message = $chat->messages()->create([
                    'message' => $request->input('message'), // текст сообщения
                    'user_id' => $request->user()->id, // ID пользователя
                    'chat_id' => $id
                ] + $request->validated());
            // Дополнительные действия с сообщением
        } else {
            // Обработка ситуации, когда чат не найден
            return response()->json(['error' => 'Chat not found'], 404);
        }
//        dd($request->user()->chats()->messages());
//        $message = $request->user()
//            ->chats()
//            ->messages()
//            ->create($request->validated());
//        dd($message);
        broadcast(new MessageSent($request->user(), $message));
        return $message;
    }
}
