<?php

namespace App\Dto\Post;

class EditPostDto
{
    public function __construct(
        public readonly string $subjectId,
        public readonly string $title,
        public readonly string $description,
        public readonly int    $price,
        public readonly string $deadline,
        public readonly int    $responce,
    )
    {
    }

    public function getData(): array
    {
        return [
            'subjectId'   => $this->subjectId,
            'title'       => $this->title,
            'description' => $this->description,
            'price'       => $this->price,
            'deadline'    => $this->deadline,
            'responce'    => $this->responce,
        ];
    }
}
