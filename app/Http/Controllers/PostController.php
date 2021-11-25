<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PostRequest;
use App\Http\Resources\UserPosts;

class PostController extends Controller
{



    //This Function For Create Post
    public function postCreate(Request $req)
    {
        $post = new Post;
        $post->text = $req->text;
        if (!empty($post->access)) {
            $post->access = $req->access;
        }
        $post->user_id = $req->user_data->id;
        if (!empty($req->file('file'))) {
            $result = $req->file('file')->store('userposts');
            $post->file = $result;
        }

        $post->save();
        if (!empty($post))
            return response()->error(200);
        else
            return response()->error(404);
    }

    //This Function Use For Check Which posts User Post
    public function userPosts(Request $req)
    {
        $userid = $req->user_data->id;
        $user = User::findorfail(5);
        $post =  Comment::with('Post')->where('id', $userid)->get();
        return new UserPosts($user);
    }
    //This Function For User Which Post User Want to Update
    public function postUpdate(PostRequest $req)
    {

        $postId = $req->pid;
        $post = Post::find($postId);

        if (!empty($post)) {
            $post->text = $req->text;
            $post->user_id = $req->user_data->id;
            if (!empty($post->access)) {
                $post->access = $req->access;
            }
            if (!empty($req->file('file'))) {
                $result = $req->file('file')->store('userposts');
                $post->file = $result;
            }

            $post->update();

            if (!empty($post->text))
                return response()->success(200);
        } else {

            return response()->error(404);
        }
    }
    //This Function For Delete Post
    public function postDelete(Request $req)
    {
        $id = $req->pid;
        $delete =  Post::find($id)->delete();
        if ($delete)
            return response()->error(200);
        else
            return response()->error(500);
    }
    //Post Search
    public function postSearch(Request $request)
    {
        // Get the search value from the request
        $search = $request->search;

        // Search in the title and body columns from the posts table
        $posts = Post::query()
            ->where('text', 'LIKE', "%{$search}%")->Where('access', 1)
            ->get();
        $count = Count($posts);
        // Return the search with the resluts
        if ($count > 0)
            return new UserPosts($posts);
        // // Return the if Not Found Any Post
        else
            return response()->error(404);
    }
}
