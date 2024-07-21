<?php

namespace App\Services\User;

use App\Dto\User\ExecutorsSearchUserDto;
use App\Models\Subject;
use App\Models\User;

class ExecutorsSearchUserService
{
    public function run(ExecutorsSearchUserDto $dto): array
    {
        $executors = [];
        $subjectsFromRequest = $dto->subjectsFromRequest;
        if ($subjectsFromRequest !== null) {
            $users = User::with('roles', 'subjects')
                ->whereHas('subjects', function ($query) use ($subjectsFromRequest) {
                    $query->whereIn('name', $subjectsFromRequest);
                })
                ->get();
            foreach ($users as $user) {
                if ($user->roles->first()->slug === 'worker') {
                    $executors[] = $user;
                }
            }
        } else {
            $executors = User::whereHas('roles', function ($query) {
                $query->where('slug', 'worker');
            })->with('subjects')->get();
        }
        return [
            'executors'=>$executors,
            'subjects'=>$dto->subjects,
            'user'=>auth()->user()
        ];
    }
}
