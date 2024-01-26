<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use CommonTrait;

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name'=>'required|max:250',
                'email'=>'required|email|unique:users,email',
                'password'=>'required|min:6',
                'phone'=>'required',
                'place'=>'required'
            ]);
            $request->all()['password'] = bcrypt($request->password);
            $user = User::create($request->all());
            $token = $user->createToken('myapptoken')->plainTextToken;
            $response = [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "phone" => $user->phone,
                "place" => $user->place,
                "token" => $token
            ];
            return $this->sendSuccess("User created successfully.", $response);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), null);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|exists:users,email',
            'password'=>'required'
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'Invalid login details'
            ], 401);
        }
        $token = $user->createToken('myapptoken')->plainTextToken;
        $response = [
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
            "phone" => $user->phone,
            "place" => $user->place,
            "token" => $token
        ];

        return $this->sendSuccess("success", $response);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return $this->sendSuccess('Logout Successfully', true);
    }

}
