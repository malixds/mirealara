<?php

namespace App\Services\User;

use App\Models\Post;

class GeneralProfileService
{
    public function run(int $id): array
    {
        $myPosts = Post::all()->where('user_id', $id);
        $myTasks = Post::whereHas('post_accept', function ($query) {
            $query->where('executor_id', auth()->user()->id);
        })->with(['post_accept' => function ($query) {
            $query->where('executor_id', auth()->user()->id);
        }])->get();
        return [$myPosts, $myTasks];

    }
}
