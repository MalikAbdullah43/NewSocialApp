<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    public function commentCreate(CreateCommentRequest $req)
    {
        //If User Want To Post Comment Own Post Then They Directly Post With No Restrictions
        $id = $req->user_data->id;
        $ownPost = DB::table('posts')->where(['user_id' => $id, 'id' => $req->postId])->whereNull('deleted_at')->get();
        $count = Count($ownPost);
        if ($count > 0) {
            $comment =   DB::table('comments')->insert([
                'comment' => $req->comment,
                'post_id' => $req->postId,
                'user_id' => $id,
            ]);
            if ($comment)

                return response()->success(200);
            else
                return response()->error(500);
        }
        ///User Own Post End
        //Check Other Conditions
        $access = DB::table('posts')->where(['id' => $req->postId, 'access' => 1])->whereNull('deleted_at')->get();
        $count = Count($access);

        if ($count > 0)    //If Post Is Public And Not Deleted Then Just Friends Comments on Post
        {
            $user    = $req->user_data->id;
            $friends = $access[0]->user_id;
            $postid  = $req->postId;


            $friend = DB::table("friends")->where([
                ['user_id', $user],
                ['friend_id', $friends],
            ])->first();

            if (!empty($friend))    //If Log in User is Friend of Which user own This Post Then They Allow For Comment
            {
                if (empty($req->file)) {
                    $comment =   DB::table('comments')->insert([
                        'comment' => $req->comment,
                        'post_id' => $postid,
                        'user_id' => $user,
                    ]);
                    if ($comment)

                        return response()->success(200);
                    else
                        return response()->error(500);
                } else {
                    $results = $req->file('file')->store('commentfiles');
                    $comment =   DB::table('comments')->insert([
                        'comment' => $req->comment,
                        'post_id' => $postid,
                        'user_id' => $user,
                        'file'    => $results,
                    ]);
                    if ($comment)

                        return response()->success(200);
                    else
                        return response()->error(500);
                }
            } else {  //If user Not a Friend Then Generate a Error
                return response()->error(404);
            }
        } else {
            return response()->error(404);
        }
    }
    public function commentDelete(Request $req)
    {

        $user = $req->user_data->id;

        $cid = $req->cid;
        $pid = $req->pid;
        $select = DB::table('comments')->where(["id" => $cid, 'user_id' => $user, 'post_id' => $pid])->whereNull('deleted_at')->get();

        $count = Count($select);

        if ($count > 0) {
            $delete =  Comment::find(17)->delete();

            if ($delete)
                return response()->success(200);
            else
                return response()->error(500);
        } else
            return response()->error(404);
    }

    public function postComments(Request $req)
    {
        $pId = $req->pid;
        $post = DB::table('comments')->select('id', 'comment', 'file', 'user_id as user')->where('post_id', $pId)->get();
        return new CommentResource($post);
    }
}
