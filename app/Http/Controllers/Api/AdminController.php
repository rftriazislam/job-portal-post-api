<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
   public function post_create(Request $request)
   {
      $validate=$this->validate($request,[
        'user_id'=>['required',Rule::exists('users','id','role')
                    ->where('id',$request->user_id)
                    ->where('role','admin')
            ],
       'title'=>'required',
       'description'=>'required',
       'thumbnail'=>'required|image|mimes:jpeg,png,jpg',
      ]);

      if ($request->file("thumbnail")) {
        $thumbnail = $request->file('thumbnail');
        $filename =  time() . '.' . $thumbnail->getClientOriginalExtension();
        $thumbnail->move(public_path('/storage/thumbnail/'), $filename);
        $validate['thumbnail'] = $filename;
    }
      $post=Post::create($validate);
      if ($post) {
        return response()->json(['success' => true, 'message' => 'Post data save successfully'], 201);
        } else {
        return response()->json(['success' => false, 'message' => 'Failed'], 400);
       }
   }


   public function post_lists(Request $request)
   {
   $this->validate($request,[
        'user_id'=>['required',Rule::exists('users','id','role')
                    ->where('id',$request->user_id)
                    ->where('role','admin')
            ],
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
   public function single_post_delete(Request $request,$id)
   {
    $this->validate($request,[
        'user_id'=>['required',Rule::exists('users','id','role')
                    ->where('id',$request->user_id)
                    ->where('role','admin')
            ],
      ]);
    $post=Post::where('id',$id)->delete();
    if ($post) {
        return response()->json(['success' => true, 'message' => 'Post data Delete successfully' ], 200);
        } else {
        return response()->json(['success' => false, 'message' => 'Failed! data found'], 400);
       }
   }
public function single_post_update(Request $request,$id)
{

    $this->validate($request,[
        'user_id'=>['required',Rule::exists('users','id','role')
                    ->where('id',$request->user_id)
                    ->where('role','admin')
            ],
       'title'=>'nullable',
       'description'=>'nullable',
       'new_thumbnail'=>'nullable|image|mimes:jpeg,png,jpg',
      ]);

      $post=Post::where('id',$id)->first();
      if($post){
      $post->update($request->all());
    if ($request->file("new_thumbnail")) {
        $thumbnail = $request->file('new_thumbnail');
        $filename =  time() . '.' . $thumbnail->getClientOriginalExtension();
        $thumbnail->move(public_path('/storage/thumbnail/'), $filename);
        $post->update([
            'thumbnail'=>$filename
        ]);
    }
    return response()->json(['success' => true, 'message' => 'Post data update successfully' ], 200);
 }else{
    return response()->json(['success' => false, 'message' => 'Failed! data found'], 400);
 }

}


}
