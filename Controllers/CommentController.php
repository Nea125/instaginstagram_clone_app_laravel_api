<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

class CommentController extends Controller
{
    public function showPostDetail($postId)
    {
        $post = Post::find($postId);
        if(!$post)
        {
            return response()->json([
               'message'=>'Post not found...',
               'status'=>'error'
            ]);
        }
        $comments = $post->comments()->with('user')->get();
        return response()->json([
            'comments'=>$comments,
            'status'=>'success....'
        ]);
    }
    public function store(Request $resquest,$id)
    {
        $user = auth()->user();// get information of login user
        $post = Post::find($id);
        if(!$id)
        {
            return response()->json([

                'message'=>'Post not found.....',
                'status'=>'error....'
            ],404);
        }
        $data = $resquest->all();// request data from user
        $data['user_id']=$user->id;// add user_id to   request data
        $comments = $post->comments()->create($data);
        return response()->json([
            'comment'=>$comments,
            'status'=>'success........'
        ]);
    }

    public function update(Request $request,$id)
    {
        $user = auth()->user();
        $comment = Comment::find($id);
        if(!$comment)
        {
            return response()->json([
                'message'=>'Comment not found....',
                'status'=>'error'
            ],404);
        }
        $data = $request->all();// request data from user
        $comment->update($data);//  update comment
        return response()->json([
            'comment'=>$comment,
            'statu'=>'success.......'
        ]);


    }



    public function destroy($id)
    {
        $comment = Comment::find($id);
        if(!$comment)
        {
            return response()->json([
                'message'=>'Comment Not Found...',
                'status'=>'erro...'
            ],404);
        }
        $user = auth()->user();// get information loggin user
        if($user->id != $comment->user_id)
        {
            return response()->json([
                'message'=>'You Are no Authorize to delete this comment',
                'status'=>'error'
            ],401);
        }
        $comment->delete();// delete comment
        return response()->json([
            'comment'=>$comment,
            'status'=>'success'
        ]);
    }

}
