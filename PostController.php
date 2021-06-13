<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
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
        return view('blog.index')->with('posts', Post::orderBy('updated_at','ASC')->get());
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
        //
        $request->validate([
            'name' => 'required | max: 10',
            'password' => 'required | max: 8',
            'image' => 'required | mimes:png,jpeg,jpg | max: 5040'
        ]);

        $filename = $request->file('image')->getClientOriginalName();
        $request->file('image')->storeAs('public/docs/',$filename);

        //dd($filename);
        Post::create([
            'name' => $request->input('name'),
            'password' => $request->input('password'),
            'image' => $filename,
            'user_id' => auth()->user()->id
        ]);
        return redirect('/cruddata')->with('message','Your data has been added!');
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	//$data = Post::find($id);
       // return view('blog.edit', compact(['data']));
        //dd($data);
        return view('blog.edit')->with('data',Post::where('id', $id)->first());
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
        $request->validate([
            'name' => 'required | max: 10',
            'password' => 'required | max: 8',
            'image' => 'required | mimes:png,jpeg,jpg | max: 5040'
        ]);
        $update = Post::find($id);
        $update->name=$request->name;
        $update->password=$request->password;
        $update->user_id= auth()->user()->id;
        if($request->hasfile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            //$filename = time() . '.' . $extension;
            $filename = $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public/docs/',$filename);
            $update->image= $filename;

        }
        $update->save();
    
        return redirect('/cruddata')->with('message', 'Your data has been updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $post = Post::where('id', $id);
        $post->delete();
        return redirect('/cruddata')->with('message', 'Your post has been deleted');
    }

    




}
