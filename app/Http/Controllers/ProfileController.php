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
use App\Enums\UserRolesEnum;

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


    public function profile(string $name)
    {
        $user = auth()->user();
        return view('pages.profile', [
            'user' => $user,

        ]);
    }

    public function formCreateShow(int $id)
    {
        $user = auth()->user();
        if ($user->id === $id) {
            return view('pages.workerform', [
                'user' => $user,
            ]);
        } else {
            return redirect()->route('user.profile-form', $id);
        }
    }

    public function formCreate(Request $request, int $id)
    {
        $user = User::find($id);
        // dd($user);
        $user->roles()->updateExistingPivot(2, ['role_id' => 3]);
        // dd($request->except(['_token', 'subjects']));
        $user->update($request->except(['_token', 'subjects']));
        return redirect()->route('user.profile-form', $id);
    }
}
