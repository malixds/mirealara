<?php

namespace App\Services\User;

use App\Dto\User\FormCreateUserDto;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FormCreateUserService
{
    public function run(User $user, FormCreateUserDto $dto): void
    {
        $subjectsArr = $dto->subjectsArr;
        foreach ($subjectsArr as $subjectName) {
            $subjectId = Subject::where('name', $subjectName)->first()->id;
            $exists = DB::table('user_subjects')
                ->where('user_id', $user->id)
                ->where('subject_id', $subjectId)
                ->exists();
            if (!$exists) {
                $user->subjects()->attach($subjectId);
            }
        }
        $user->roles()->updateExistingPivot(2, ['role_id' => 3]);
        $user->update([
            ...$dto->getData()
        ]);
    }
}
