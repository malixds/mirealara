<?php

namespace App\Services\User;

use App\Models\Post;

class GeneralProfileService
{
    public function run(): array
    {
        return [
            'myPosts' => auth()->user()->posts,

        ];
    }
}
