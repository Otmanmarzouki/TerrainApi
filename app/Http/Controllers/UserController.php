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
    public function update(Request $request, $id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $request->validate([
        'name' => 'sometimes|required',
        'email' => 'sometimes|required|email',
        'password' => 'sometimes|required|min:6',
    ]);
    if ($request->has('name')) {
        $user->name = $request->name;
    }

    if ($request->has('email')) {
        $user->email = $request->email;
    }

    if ($request->has('password')) {
        $user->password = Hash::make($request->password);
    }
    $result = $user->save();

    if ($result) {
        return response()->json(['message' => 'User updated successfully']);
    } else {
        return response()->json(['message' => 'Failed to update user'], 500);
    }
}
public function delete($id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $result = $user->delete();

    if ($result) {
        return response()->json(['message' => 'User deleted successfully']);
    } else {
        return response()->json(['message' => 'Failed to delete user'], 500);
    }
}


   

}