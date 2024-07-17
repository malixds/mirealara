<?php

namespace App\Http\Controllers;


use App\Dto\Post\AcceptPostDto;
use App\Dto\Post\CreatePostDto;
use App\Dto\Post\DeletePostDto;
use App\Dto\Post\EditPostDto;
use App\Models\Post;
use App\Models\Subject;
use App\Models\User;
use App\Services\Post\AcceptPostService;
use App\Services\Post\DeletePostService;
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
            'user' => $user,
            'posts' => $posts,
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
            'user' => $user,
            'posts' => $posts,
        ]);
    }

    public function postFull(int $id)
    {
        $user = auth()->user();
        $post = Post::with('user.roles', 'subject')->find($id);

        return view('pages.postfull', [
            'post' => $post,
            'post_id' => $id,
            'user' => $user
        ]);
    }

    public function postCreateShow()
    {
        return view('pages.postcreate', [
            'user' => auth()->user(),
            'subjects' => Subject::query()->get()
        ]);
    }

    public function postCreate(Request $request)
    {
//        dd($request);
        $dto = new CreatePostDto(
            subjectId: $request->get('subject_id'),
            userId: auth()->user()->id,
            title: $request->get('title'),
            description: $request->get('description'),
            price: $request->get('price'),
            deadline: $request->get('deadline'),
        );
        $post = Post::create([
            ...$dto->getData()
        ]);

        return redirect()->route('post.show-full', ['id' => $post->id]);
    }

    public function postEditShow(int $id)
    {
        $post = Post::find($id);
        $subjects = Subject::get();
        return view('pages.postedit', [
            'user' => auth()->user(),
            'post' => $post,
            'subject_name' => $post->subject->name,
            'subjects' => $subjects
        ]);
    }

    public function postEdit(Request $request, int $id, EditPostService $service)
    {
        $dto = new EditPostDto(
            subjectId: $request->get('subject_id'),
            title: $request->get('title'),
            description: $request->get('description'),
            price: $request->get('price'),
            deadline: $request->get('deadline'),
        );
        $post = $service->run(Post::find($id), $dto);
        return redirect()->route('post.show-full', ['id' => $post->id]);
    }

    public function postDelete(int $id)
    {
        // make policy logic -> we should make dto for this and some service
        $post = Post::find($id);
        $this->authorize('delete', $post);
        $post->delete();

        return redirect()->route('post.show');
    }


    public function postAccept(Post $post, AcceptPostService $service)
    {

        //dto
        $dto = new AcceptPostDto(
            postId: $post->id,
            userId: $post->user_id,
            executorId: auth()->user()->id,
        );

        $post = $service->run($dto, $post);
        return redirect()->route('post.show-full', $post->id);
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
