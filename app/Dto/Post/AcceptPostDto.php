<?php

namespace App\Dto\Post;

class AcceptPostDto
{
    public function __construct(
        public readonly int $postId,
        public readonly int $userId,
        public readonly int $executorId,
    )
    {

    }

    public function getData(): array
    {
        return [
            'postId' => $this->postId,
            'userId' => $this->userId,
            '$executorId' => $this->executorId
        ];

    }
}
