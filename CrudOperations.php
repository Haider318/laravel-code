<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;

use Illuminate\Support\Facades\DB;

class CrudOperations extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth', ['except' => ['first','fetch']]);
    }


    public function get(){
        return view('new.first')->with('message', 'Data has been added successfully');
    }

    public function insertData(Request $request)
    {
        $request->validate([
            'name' => 'required | max : 10',
            'email_id' => 'required',
            'password' => 'required | max : 8',
            'image' => 'required | mimes:png,jpeg,jpg | max : 5048',
            'phone' => 'required'
        ]);
        $filename = $request->file('image')->getClientOriginalName();
        $request->file('image')->storeAs('public/deem',$filename);
        Test::create([
            'name' => $request->input('name'),
            'email_id' => $request->input('email_id'),
            'password' => $request->input('password'),
            'phone' => $request->input('phone'),
            'image' => $filename,
            'gender' =>$request->input('gender'),
            'user_id' => auth()->user()->id
        ]);
        
        return redirect('/')->with('message', 'Your Data Has Been Added!!');
    }

    public function fetch(){
        
        return view('new.fetch')->with('tests', Test::orderBy('id', 'ASC')->get());
    }

    function edit($id){
    	// $data= Test::find($id);
    	// return view('new.update',['data'=>$data]);
        return view('new.update')->with('data',Test::where('id',$id)->first());

    }
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required | max : 10',
            'email_id' => 'required',
            'password' => 'required | max : 8',
            'image' => 'required | mimes:png,jpeg,jpg | max : 5048',
            'phone' => 'required'
        ]);
        $get = Test::find($request->id);
    	$get->name=$request->name;
    	$get->email_id=$request->email_id;
        $get->password=$request->password;
        $get->phone=$request->phone;
        $get->gender=$request->gender;
    	$get->user_id= auth()->user()->id;
        if($request->hasfile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            //$filename = time() . '.' . $extension;
            $filename = $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public/deem',$filename);
            $get->image= $filename;
        }
    	$get->save();
    	return redirect('/fetch')->with('message', 'Your Data has successfully update');
    }

    public function destroy($id)
    {
        $delete = Test::where('id',$id);
        $delete->delete();
        return redirect('/fetch')->with('message','Data is deleted successefully!');

    }

    //search method
   public function search(Request $request)
   {
       if (isset($_GET['query'])){
           $search_text = $_GET['query'];
           $search_email = $_GET['query'];
            $name = DB::table('tests')->where('name','LIKE','%'.$search_text.'%')->orWhere('phone','LIKE','%'.$search_email.'%')->orWhere('email_id','LIKE','%'.$search_email.'%')->paginate(2);
            $name->appends($request->all());
            return view('new.search',['name'=>$name]);
       }
       else
       {
            return view('new.search');
       }
   }

}
