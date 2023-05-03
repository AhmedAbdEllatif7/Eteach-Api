<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiResponseTrait;
use App\Http\Resources\PostResource;
use Egulias\EmailValidator\Result\Reason\UnclosedQuotedString;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $posts  = PostResource::collection(Post::get());
        return $this->apiResponse($posts,'success',200);
    }




    public function show($id)
    {
        $post = Post::find($id);
        if($post)
        {
            return $this->apiResponse(new PostResource($post),'success',200);
        }

        return $this->apiResponse('null', 'the post not found', '404');
    }




    public function storePost(Request $request)
    {

        $rules = [
            'title'  => 'required',
            'body'  => 'required',
        ];
        
        $messages = [
            'title.required'  => 'Please Enter The Title',
            'body.required'  => 'Please Enter The Body',
        ];
        
        $validator = Validator::make($request->all(),$rules,$messages);

        if($validator->fails()){
            return $this->apiResponse(null, $validator->errors(), 400);
        }

        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
        ]);

        if($post){
            return $this->apiResponse(new PostResource($post), 'saved successfuly', 201);
        }
    }




    public function updatePost(Request $request)
    {
        $rules = [
            'title'  => 'required',
            'body'  => 'required',
        ];
        
        $messages = [
            'title.required'  => 'Please Enter The Title',
            'body.required'  => 'Please Enter The Body',
        ];
        
        $validator = Validator::make($request->all(),$rules,$messages);

        if($validator->fails()){
            return $this->apiResponse(null, $validator->errors(), 400);
        }

        $post = Post::find($request->id);
        
        if(!$post)
        {
            return $this->apiResponse(null, "Sorry This Post Not Found", 400);
        }
        
        $post->update([
            'title' => $request->title,
            'body' => $request->body,
        ]);

        if($post){
            return $this->apiResponse(new PostResource($post), 'Updated Successfuly', 200);
        }
    }





    public function deletePost($id)
    {
        $post = Post::find($id);
        if(!$post)
        {
            return $this->apiResponse(null, "Sorry This Post Not Found", 400);
        }

        else
        {
            $post->delete();
            return $this->apiResponse(null ,'Deleted Successfuly', 200);
        }
    }
}
