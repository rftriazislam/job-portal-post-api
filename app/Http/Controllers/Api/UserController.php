<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function job_post_lists(Request $request)
   {
    $validate=$this->validate($request,[
        'user_id'=>'required|exists:users,id',
      ]);
    $posts=Post::latest()->paginate(20);
    if (count($posts)>0) {
        return response()->json(['success' => true, 'message' => 'Post data found successfully' ,'posts'=>$posts,'image_link'=>url('storage/thumbnail/')], 200);
        } else {
        return response()->json(['success' => false, 'message' => 'Failed! data found'], 400);
       }
   }
   public function single_post($id)
   {
    $post=Post::where('id',$id)->first();
    if ($post) {
        return response()->json(['success' => true, 'message' => 'Post data found successfully' ,'single-post'=>$post,'image_link'=>url('storage/thumbnail/')], 200);
        } else {
        return response()->json(['success' => false, 'message' => 'Failed! data found'], 400);
       }
   }

}
