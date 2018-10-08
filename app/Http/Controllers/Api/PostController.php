<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;
use App\Http\Resources\PostResource;
use Validator;

class PostController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get all post resource from the database
        $posts = PostResource::Collection(Post::paginate(10));
        return $this->apiResponse($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = $this->validation($request);

        if($validation instanceOf Response){
            return $validation;
        }

        //if validation is true proceed to save to database
        $post = Post::create($request->all());

        if($post){
            return $this->createdResponse($post);
        }else{
            return $this->notFoundResponse();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //find post by id
        $post = Post::find($id);
        //if post id is found return the post content
        if($post){
            return $this->apiResponse(new PostResource($post));
        }else{
            return $this->notFoundResponse();
        }
    
    }

    public function validation($request)
    {
        return $this->apiValidation($request, [
            'title' => 'required|min:3|max:295',
            'body' => 'required|min:10'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validation = $this->validation($request);

        if($validation instanceOf Response){
            return $validation;
        }
        
        $post = Post::find($id);
        
        //if post content not found
        if(!$post){
            return $this->notFoundResponse();
        }

        //update the post content
        $post = $post->update($request->all());
        // dd($post);

        //if post is found proceed to display content
        if($post){
            return $this->createdResponse($post);
        }else{
            return $this->notFoundResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        
        if($post){
            $post->delete();
            return $this->deleteResponse($post);
        }
        return $this->notFoundResponse();
    }

}
