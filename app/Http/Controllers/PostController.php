<?php

namespace App\Http\Controllers;


use App\Dto\Post\EditPostDto;
use App\Models\Post;
use App\Models\Subject;
use App\Models\User;
use App\Services\Post\EditPostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function posts(Request $request)
    {
        $user = auth()->user();
        $posts = Post::with('user.roles', 'subject')->get();
        $subjects = Subject::get();
        // dd($subjects->first());
        return view('pages.posts', [
            'user'     => $user,
            'posts'    => $posts,
            'subjects' => $subjects,
        ]);
    }


    public function postSearch(Request $request)
    {
        $user = auth()->user();
        $subjectsArr = $request->input('subjects');
        if ($subjectsArr !== null) {
            $posts = Post::with('user.roles', 'subject')
                ->whereHas('subject', function ($query) use ($subjectsArr) {
                    $query->whereIn('name', $subjectsArr);
                })
                ->get();
        } else {
            $user = auth()->user();
            $posts = Post::with('user.roles', 'subject')->get();
        }
        return view('pages.postsearch', [
            'user'  => $user,
            'posts' => $posts,
        ]);
    }

    public function postFull(int $id)
    {
        $user = auth()->user();
        $post = Post::with('user.roles', 'subject')->find($id);

        return view('pages.postfull', [
            'post'    => $post,
            'post_id' => $id,
            'user'    => $user
        ]);
    }

    public function postCreateShow()
    {
        return view('pages.postcreate', [
            'user'     => auth()->user(),
            'subjects' => Subject::query()->get()
        ]);
    }

    public function postCreate(Request $request)
    {
        $subjectName = $request->get('subject_name');
        $subject = Subject::where('name', $subjectName)->first();
        $userId = auth()->user()->id;
        $post = Post::create([
            ...$request->except(['_token', 'subject_name']),
            'subject_id' => $subject->id,
            'user_id'    => $userId,
        ]);

        return redirect()->route('post.show-full', ['id' => $post->id]);
    }

    public function postEditShow(int $id)
    {
        $post = Post::find($id);
        $subjects = Subject::get();
        return view('pages.postedit', [
            'user'         => auth()->user(),
            'post'         => $post,
            'subject_name' => $post->subject->name,
            'subjects'     => $subjects
        ]);
    }

    public function postEdit(Request $request, Post $post, EditPostService $service)
    {
        $this->authorize('update', $post);

        $dto = new EditPostDto(
            subjectId: $request->get('subject_id'),
            title: $request->get('title'),
            description: $request->get('description'),
            price: $request->get('price'),
            deadline: $request->get('deadline'),
            responce: $request->get('responce'),
        );
        $post = $service->run($post, $dto);

        return redirect()->route('post.show-full', ['id' => $post->id]);
    }

    public function postDelete(int $id)
    {
        //

        $userId = auth()->user()->id;
        $user = auth()->user()->with('roles')->find($userId);
        $roleId = $user->roles->first()->name;
        $post = Post::find($id);
        $creatorId = $post->user_id;
        if ($user->id === $creatorId || $roleId === 'admin') {
            $post->delete();
            return redirect()->route('post.show');
        } else {
            abort(403);
        }
    }


    public function postAccept(int $id)
    {
        $user = auth()->user();
        $userRole = $user->roles->first()->slug;
//        dd($user->roles->first()->slug==='worker');
        if ($userRole === 'worker' || $userRole === 'admin') {
            $userId = $user->id;  // executor
            $post = Post::find($id);  // пост, на который делают отклик
            DB::table('post_accept')->insert([
                'post_id'     => $id,
                'user_id'     => $post->user_id,
                'executor_id' => $userId,
            ]);
            $post->increment('responce', 1);
            return redirect()->route('post.show-full', $id);
        } else {
            return redirect()->route('user.profile-form', $user->id);
        }
        // $post->update
    }










    // public function postSearch(Request $request)
    // {
    //     $user = auth()->user();
    //     $requestData = json_decode($request->getContent(), true);
    //     $selectedValues = $requestData['value'];
    //     $posts = Post::with('user.roles', 'subject')
    //         ->whereHas('subject', function ($query) use ($selectedValues) {
    //             $query->whereIn('name', $selectedValues);
    //         })
    //         ->first();
    //     return view('pages.posts', [
    //         'user' => $user,
    //         'subjectsValues' => $selectedValues,
    //         'posts'=> $posts
    //     ]);
    // }


    // public function posts() {
    //     $posts = Post::with('user.userRoles.role')->get();
    //     $userRole = Auth::user()->userRoles()->first()->role->name;


    //     return view('pages.posts', [
    //         'posts' => $posts,
    //         'user_role' => $userRole
    //     ]);
    // }

    // public function postCreate(Request $request) {
    //     Post::create([
    //         ...$request->except(['_token']),
    //         'user_id' => Auth::id()
    //     ]);
    // }

    // public function postEdit(Request $request, int $id) {

    //     $post = Post::find($id);
    //     $post->update(
    //         $request->except(['_token']),
    //     );

    //     return redirect()->route('post.list');
    // }
    // public function postEditShow(int $id)
    // {
    //     $post = Post::find($id);

    //     return view('pages.edit', ['post'=> $post]);
    // }

    // public function postDelete(int $id)
    // {

    //     $post = Post::find($id);
    //     if ($post->user_id !== auth()->id() && Auth::user()->userRoles()->first()->role->name != "Admin") {
    //         abort(403);
    //     }
    //     $post->delete();

    //     return redirect()->route('post.list');
    // }
}
