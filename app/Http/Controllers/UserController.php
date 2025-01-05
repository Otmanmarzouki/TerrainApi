<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;






class UserController extends Controller
{



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
        ], 201)->cookie(
            'auth_token',
            $token,
            60 * 24 * 7,
            '/',
            null,
            false,
            true,
            false,
            'Lax'
        );
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
            ], 201)->cookie(
                'auth_token',
                $token,
                60 * 24 * 7,
                '/',
                null,
                false,
                true,
                false,
                'Lax'
            );
        } else {
            return response()->json([
                'message' => 'Something went wrong',
            ], 500);
        }
    }


    public function update(Request $request)
    {

        $user = $request->user();
        Log::info($user);
        if (!$user) {
            return response()->json(['message' => 'You are not logged in'], 401);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'dob' => 'sometimes|required|date|before:today',
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $user->id,
            'tel' => 'sometimes|required|string|min:10|max:15',
            'adresse' => 'sometimes|required|string|max:255',
            'gender' => 'sometimes|required|string|max:255',
        ]);
        if ($request->hasFile('avatar')) {

            $validatedAvatar = $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->update([
                'avatar' => $avatarPath,
            ]);
        }
        $user->update($validatedData);
        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ], 200);
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

    public function getUser(Request $request)
    {
        $user = $request->user();
        return response()->json($user);
    }
}
