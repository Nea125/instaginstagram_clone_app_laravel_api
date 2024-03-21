<?php

namespace App\Http\Controllers;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function getLikes($postId)
    {
        $post = Like::where('post_id',$postId)->with('user')->get();

        $users = $post->pluck('user');

        return response()->json([
            'users'=>$users,
            'status'=>'success.........'
        ]);


    }

    // like and dislike
    public function toggleLike($postId)
    {
        $user = auth()->user();
        $post = Post::find($postId);
        $liked = $post->likes->contains('user_id',$user->id);
        if($liked)
        {
            $post->likes()->where('user_id',$user->id)->delete();

        }
        else{
            $post->likes()->create([

                'user_id'=>$user->id,
                'post_id'=>$postId
            ]);
        }
        return response()->json([
           'satuse'=>'success....'
        ]);

    }
}
