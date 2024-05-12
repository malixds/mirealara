<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use App\Models\Post;
use App\Models\Subject;
use App\Enums\UserRolesEnum;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }


    public function profile(int $id)
    {
        $user = auth()->user();
        $myPosts = Post::get()->where('user_id', $id);
        if ($user->id !== $id) {
            return redirect()->route('user.profile', $user->id);
        } else {
            return view('pages.profile', [
                'user' => $user,
                'posts' => $myPosts,
            ]);
        }
    }

    public function formCreateShow(int $id)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('main');
        }
        if ($user->id === $id) {
            $subjectsArr = $user->subjects()->get();
            // dd($subjectsArr);
            return view('pages.workerform', [
                'user' => $user,
                'subjectsArr' => $subjectsArr
            ]);
        } else {
            return redirect()->route('user.profile-form', $id);
        }
    }

    public function formCreate(Request $request, int $id)
    {
        $user = User::find($id);
        $subjectsArr = $request->subjects;
        foreach ($subjectsArr as $subjectName) {
            $subjectId = Subject::where('name', $subjectName)->first()->id;
            $exists = DB::table('user_subjects')
                ->where('user_id', $id)
                ->where('subject_id', $subjectId)
                ->exists();
            if (!$exists) {
                $user->subjects()->attach($subjectId);
            }
        }
        $user->roles()->updateExistingPivot(2, ['role_id' => 3]);
        $user->update([

            ...$request->except(['_token', 'subjects'])
        ]);
        return redirect()->route('user.profile-form', $id);
    }

    public function formDeleteSubject(int $userSubjectId)
    {
        // dd($userSubjectId);
        $userId = auth()->user()->id;
        DB::table('user_subjects')
            ->where('user_id', $userId)
            ->where('subject_id', $userSubjectId)
            ->delete();
        return redirect()->route('user.profile-form', $userId);
    }

    public function executors()
    {

        // $executors = [];
        $user = auth()->user();
        $users = User::with('roles', 'subjects')->get();
        $subjects = Subject::get();
        $executors = [];
        // dd($users);
        foreach ($users as $user) {
            if ($user->roles->first()->slug === 'worker') {
                $executors[] = $user;
            }
        }
        return view('pages.executors', [
            'executors' => $executors,
            'subjects' => $subjects,
            'user' => $user,
        ]);
    }
    public function executorSearch(Request $request)
    {
        // dd('hello');
        $user = auth()->user();
        $subjectsRequest = $request->input('subjects');
        $subjects = Subject::get();
        $executors = [];
        if ($subjectsRequest !== null) {
            $users = User::with('roles', 'subjects')
                ->whereHas('subjects', function ($query) use ($subjectsRequest) {
                    $query->whereIn('name', $subjectsRequest);
                })
                ->get();
            foreach ($users as $user) {
                if ($user->roles->first()->slug === 'worker') {
                    $executors[] = $user;
                }
            }
        } else {
            $users = User::with('roles', 'subjects')->get();
            $executors = [];
            // dd($users);
            foreach ($users as $user) {
                if ($user->roles->first()->slug === 'worker') {
                    $executors[] = $user;
                }
            }
        }
        return view('pages.executorsearch', [
            'executors' => $executors,
            'subjects' => $subjects,
            'user' => $user,
        ]);

    }
}
