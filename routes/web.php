<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;

use App\Models\Post;
use App\Models\User;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $user = auth()->user();
    $posts = Post::get();
    $users = User::get();
    // dd($users->roles);
    return view('pages.welcome', [
        'user' => $user,
        'posts' => $posts,
        'users' => $users
    ]);
})->name('main');


// Route::get('/profile/orders')
Route::get('/profile/{id}', [ProfileController::class, 'profile'])->name('user.profile');
Route::get('/profile/form/{id}', [ProfileController::class, 'formCreateShow'])->name('user.profile-form');
Route::post('/profile/form/{id}', [ProfileController::class, 'formCreate'])->name('user.profile-form-create');
Route::post('/delete/subject/{id}', [ProfileController::class, 'formDeleteSubject'])->name('user.profile-form-delete-subject');





Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::get('/posts', [PostController::class, 'posts'])->name('post.show');
Route::post('/posts/search', [PostController::class, 'postSearch'])->name('post.search');

// Route::get('/posts/search', [PostController::class, 'showPostSearch'])->name('post.search-show');


Route::get('/posts/full/{id}', [PostController::class, 'postFull'])->name('post.show-full');


Route::get('/posts/create', [PostController::class, 'postCreateShow'])->name('post.create-show');
Route::post('/posts/create', [PostController::class, 'postCreate'])->name('post.create');

Route::get('/posts/edit/{id}', [PostController::class, 'postEditShow'])->name('post.edit-show');
Route::post('/posts/edit/{id}', [PostController::class, 'postEdit'])->name('post.edit');

Route::post('/posts/delete/{id}', [PostController::class, 'postDelete'])->name('post.delete');

Route::put('/posts/accept/{id}', [PostController::class, 'postAccept'])->name('post.accept');

// Route::get('/posts/search', [PostController::class, 'postSearch'])->name('post.search');


Route::get('/executors', [ProfileController::class, 'executors'])->name('executors');
Route::post('/executors/search', [ProfileController::class, 'executorSearch'])->name('executor.search');




Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('main');
})->name('logout');



require __DIR__ . '/auth.php';
