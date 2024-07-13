<?php

namespace App\Services\Post;

use App\Dto\Post\EditPostDto;
use App\Models\Post;

class EditPostService
{
    public function run(Post $post, EditPostDto $dto): Post
    {
        $post->update([$dto->getData()]);
        return $post;
    }

}
