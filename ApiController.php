<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Device;
use Validator;

//for sanctum
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class ApiController extends Controller
{
    //sanctum function starts from here
    public function index(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response(['message' => ['the credencials not match our records.'], 404]);
        }
        $token = $user->createToken('my-app-token')->plainTextToken;
        $response = ['user'=>$user, 'token'=>$token];

        return response($response, 201);
    }
    //sanctum function ends

    public function list()
    {
        return Test::all();
    }
    public function listparams($id=null)
    {
        return $id?Test::find($id):Test::all();
    }

    //post api function start
    public function postapi(Request $request)
    {
        $device = new Device;
        $device->name = $request->name;
        $device->member_id = $request->member_id;
        $results = $device->save();
        if($results)
        {
            return ["Results"=>"Data has been saved"];
        }
        else
        {
            return ["Results"=>"Operation Failed"];
        }
        
    }

    //update method or put api
    public function putapi(Request $request)
    {
        $device = Device::find($request->id);
        $device->name = $request->name;
        $device->member_id = $request->member_id;
        $results = $device->save();
        if($results)
        {
            return ["Results"=>"data has been updated successfully"];
        }
        else{
            return ["Results"=>"Operation Failed"];
        }

    }

    //search method or search api
    public function search($name){
        return Device::where('name','like','%'.$name.'%')->get();
    }

    //delete method or delete api
    public function delete($id)
    {
        $device = Device::find($id);
        $results = $device->delete();
        if ($results) {
            return ["results"=>"Record has been deleted of id No.".$id];
        }
        else{
            return ["results"=>"Record Not deleted something went wrong"];
        }
        
    }

    //Api validation function
    public function apiValidation(Request $request)
    {
        $rules = array(
            "member_id"=>"required|min:2|max:4"
        );
        $validate = Validator::make($request->all(),$rules);
        if ($validate->fails()) {
            return response()->json($validate->errors(),401);
        }else
        {
            //return ["resluts"=>"hello from api controller data is added"];
            $device = new Device;
            $device->name = $request->name;
            $device->member_id = $request->member_id;
            $results = $device->save();
            if ($results) {
                return ["results"=>"data has been added"];
            }
            else{
                return ["results"=>"something went wrong"];
            }
        }
       
    }
    
}
