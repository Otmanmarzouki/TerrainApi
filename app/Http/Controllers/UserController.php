<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function create(Request $request)
    {
        $user = new User();
        $request->validate([
            'name'=>'required',
            'email'=>'required',
            'password'=>'required',
       
        ]);
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=Hash::make($request->password);
        $result= $user->save();
        if($result) {
          
            return response()->json(['message' =>'You signed up correctly']);
        }else {
            return response()->json(['errors'=>$request->validate->errors()]);
        }
    }
   

}
