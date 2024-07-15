<?php

namespace App\Services\Post;

use App\Dto\Post\AcceptPostDto;
use App\Models\Post;
use Exception;
use Illuminate\Support\Facades\DB;

class AcceptPostService
{
    public function run(AcceptPostDto $dto, Post $post): Post
    {
        try {
            DB::table('post_accept')->insert([$dto->getData()]);
            $post->increment('responce', 1);
        } catch (Exception $exception) {
            dd($exception);
        }
        return $post;
    }
}
