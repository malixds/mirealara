<?php

namespace App\Http\Controllers;

use App\Dto\User\ExecutorsSearchUserDto;
use App\Dto\User\ExecutorsUserDto;
use App\Dto\User\FormCreateUserDto;
use App\Dto\User\FormDeleteSubjectDto;
use App\Http\Requests\ProfileUpdateRequest;
use App\Services\User\ExecutorProfileUserService;
use App\Services\User\ExecutorsSearchUserService;
use App\Services\User\ExecutorsUserService;
use App\Services\User\FormCreateUserService;
use App\Services\User\FormDeleteSubjectUserService;
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


    public function profile()
    {
        return view('pages.profile', [
            'user' => auth()->user(),
            'myPosts' => auth()->user()->posts,
            'myTasks' => auth()->user()->tasks,
        ]);
    }

    public function formCreateShow(int $id)
    {
        $user = auth()->user();
        return view('pages.workerform', [
            'user' => $user,
            'subjectsArr' => $user->subjects()->get()
        ]);
    }

    public function formCreate(Request $request, int $id, FormCreateUserService $service)
    {
        $dto = new FormCreateUserDto(
            userId: $id,
            subjectsArr: $request->subjects,
            name: $request->name,
            description: $request->description,
            email: $request->email,
            contactLink: $request->contactLink,
        );
        $service->run(User::find($id), $dto);
        return redirect()->route('user.profile-form', $id);
    }

    public function formDeleteSubject(int $userSubjectId, FormDeleteSubjectUserService $service)
    {
        $dto = new FormDeleteSubjectDto(
            userId: auth()->user()->id,
            userSubjectId: $userSubjectId
        );
        $service->run($dto);
        return redirect()->route('user.profile-form', auth()->user()->id);
    }

    public function executors(ExecutorsUserService $service)
    {
        return view('pages.executors', $service->run());
    }

    public function executorSearch(Request $request, ExecutorsSearchUserService $service)
    {
        // dd('hello');
        $dto = new ExecutorsSearchUserDto(
            subjectsFromRequest:$request->input('subjects'),
            subjects: Subject::get(),
        );
        return view('pages.executorsearch', $service->run($dto));

    }

    public function executorProfile(int $id, ExecutorProfileUserService $service)
    {
        if ($service->run($id)) {
            return view(('pages.executorprofile'), [
                'executor' => User::find($id),
                'user' => auth()->user(),
            ]);
        } else {
            abort(404);
        }

    }

    public function inbox(int $id)
    {

        $user = User::find($id);
        $inbox = $user->inbox()->first();
        $chats = $inbox->chats()->get();
        $chat = $inbox->chats()->first();
        $messages = $chat->messages()->get();
//        dd($messages);

        return view('pages.inbox', [
            'user' => $user,
            'chats' => $chats
        ]);
    }
}
