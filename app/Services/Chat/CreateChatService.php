<?php

namespace App\Services\Chat;

use App\Dto\Chat\CreateChatDto;
use App\Models\Chat;
use App\Models\Post;
use App\Repositories\ChatRepository;
use Illuminate\Database\Eloquent\Collection;

class CreateChatService
{
    protected ChatRepository $repository;
    public function __construct(ChatRepository $repository)
    {
        $this->repository = $repository;
    }
    public function run(array $dto)
    {
        if ($this->getChatInfo($dto['user_id'], $dto['buddy_id'])->exists()) {
            $chatId = $this->getChatId($dto['user_id'], $dto['buddy_id']);
            return redirect()->route('chat', $this->repository->find($chatId));
        }
        $chat = $this->repository->create();
        auth()->user()->chatsOnlyUser()->attach($chat->id, [
            'buddy_id' => $dto['buddy_id']
        ]);
        return $chat;
    }

    public function getChatInfo($userId, $secondUser): \Illuminate\Database\Query\Builder
    {
        return \DB::table('chats_users')
            ->where(function ($query) use ($userId, $secondUser) {
                $query->where('user_id', $userId)
                    ->where('buddy_id', $secondUser);
            })
            ->orWhere(function ($query) use ($userId, $secondUser) {
                $query->where('user_id', $secondUser)
                    ->where('buddy_id', $userId);
            });
    }

    public function getChatId($userId, $secondUser): int
    {
        return $this->getChatInfo($userId, $secondUser)
            ->pluck('chat_id')
            ->first();
    }

}
