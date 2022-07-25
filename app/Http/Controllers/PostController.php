<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $post = Post::all();
        return response()->json([
            'message' => 'success',
            'posts' => $post
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        # code...
        $this->validate(
            $request,
            [
                'title' => 'required|max:200',
                'content' => 'required|min:10',
                'image' => 'required'
            ]
        );
        $imagePath = $request->image->store('/uploads', 'public');
        $post = $request->user()->posts()->create(
            [
                'title' => $request->title,
                'content' => $request->content,
                'image' => $imagePath
            ]
        );

        return response()->json([
            'message' => 'success',
            'post' => $post
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post = Post::findOrFail($id);
        return response()->json([
            'message' => 'success',
            'post' => $post
        ], 200);
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
        //
        $this->validate(
            $request,
            [
                'title' => 'required|max:200',
                'content' => 'required|min:10',
            ]
        );
        if (auth()->user()->role !== 'admin') {
            if (auth()->user()->id !== Post::findOrFail($id)->user_id) {
                return response()->json([
                    'message' => 'Unauthorized to update this post'
                ], 401);
            }
        }
        $post = Post::findOrFail($id);
        // if ($request->hasFile('image')) {
        //     $imagePath = $request->image->store('/uploads', 'public');
        //     $post->image = $imagePath;
        // }
        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();
        return response()->json([
            'message' => 'success',
            'post' => $post
        ], 200);
        return response()->json([
            'message' => 'success',
            'post' => $post
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_id = Post::findOrFail($id)->user_id;
        $user = auth()->user()->id;
        //
        if (auth()->user()->role !== 'admin') {
            if (auth()->user()->id !== Post::findOrFail($id)->user_id) {
                return response()->json([
                    'message' => 'Unauthorized to delete this post'
                ], 401);
            }
        }
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json([
            'message' => 'Post Deleted successfuly',
            'post' => $post
        ], 200);
        return response()->json([
            'message' => 'success',
            'post' => $post
        ], 200);
    }
}
