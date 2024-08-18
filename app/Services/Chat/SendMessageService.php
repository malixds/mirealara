<?php

namespace App\Services\Chat;
use App\Http\Requests\MessageFormRequest;

use App\Dto\Chat\SendMessageDto;
use App\Models\Chat;
use App\Models\Message;

class SendMessageService
{
    public function run(SendMessageDto $dto, MessageFormRequest $request, Chat $chat): \Illuminate\Database\Eloquent\Model
    {
        $dto = $dto->getData();
        return $chat->messages()->create($dto + $request->validated());
    }

}
