<?php

namespace App\Http\Controllers;

use App\Enums\ReviewEnum;
use App\Models\Review;
use App\Models\User;
use App\Repositories\ReviewRepository;
use Illuminate\Http\Request;

class ReviewController
{
    protected ReviewRepository $repository;
    public function __construct(ReviewRepository $repository)
    {
        $this->repository = $repository;
    }

    public function reviewSend(Request $request, User $executor)
    {
        $this->repository->create([
            'reviewer_id' => auth()->user()->id,
            'reviewed_id' => $executor->id,
            'comment' => $request->input('comment'),
            'reviewable_type' => ReviewEnum::REVIEW,
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);

        return redirect()->back();
    }

}
