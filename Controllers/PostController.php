<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    // function post
    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(10);
        foreach($posts as $post)
        {
            $post['like_count']=$post->likes->count();
            $post['comment_count']=$post->comments->count();
            $post['liked'] = $post->likes->contains('user_id',auth()->user()->id);//->id


        }
        return response()->json([
            'data'=>$posts,
            'status'=>'successful.......'
        ], 200 );

    }
    // function create post
    public function store(Request $request)
    {
       $user = auth()->user();
       $data = $request->all();
       $data['user_id']=$user->id;

       if($request->hasFile('photo'))
       {
           $photo = $request->file('photo');
           $name = time().'.'.$photo->getClientOriginalExtension();
           $destinationPath = public_path('/photo');
           $photo->move($destinationPath,$name);
           $data['photo']= $name;
       }
       $post = Post::create($data);
       return response()->json([
        'post'=>$post,
         'message'=>'success....'
       ]);


    }
    public function update(Request $request,$id)
    {
        $post = Post::find($id);
        if(!$post)
        {
            return response()->json([
               'message'=>'Post not found...',
               'status'=>'error'
            ],404);
        }
        $data = $request->all();
        if(auth()->user()->id != $post->user_id)
        {
            return response()->josn([
                'message'=>'You are not authorize to update this post',
                'status'=>'error'
            ],401);
        }
        if($request->hasFile('photo'))
        {
           $photo = $request->file('photo');
           $name = time().'.'.$photo->getClientOriginalExtension();
           $destinationPath = public_path('/photo');
           $photo->move($destinationPath,$name);
           $data['photo']= $name;

           $oldPhoto = public_path('/photo').$post->photo;
           if(file_exists($oldPhoto))
           {
              @unlink($oldPhoto);
           }

        }
        $post->update($data);
        return response()->json([
          'post'=>$post,
          'status'=>'success......'

        ]);
    }
    public function destroy($id)
    {
        $post = Post::find($id);
        if(!$post)
        {
            return response()->json([

                'message'=>'Post not found.........',
                'status'=>'error'
            ],404);
        }
        if(auth()->user()->id != $post->user_id)
        {
            return response()->josn([
                'message'=>'You are not authorize to delete this post',
                'status'=>'error'
            ],401);
        }
        $post->delete();
        return response()->json([
            'message'=>'Post delete Successfully........',
            'status'=>'success'
        ],200);
    }

}
