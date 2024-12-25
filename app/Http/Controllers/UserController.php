<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($request->email)->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }


    public function signUp(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);
    
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        if ($user->save()) {
            $token = $user->createToken($user->email)->plainTextToken;
    
            return response()->json([
                'message' => 'You signed up correctly',
                'user' => $user,
                'token' => $token,
            ], 201); 
        } else {
            return response()->json([
                'message' => 'Something went wrong',
            ], 500); 
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


public function destroy($id)
{
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['message' => 'user not found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted successfully.']);

}


}