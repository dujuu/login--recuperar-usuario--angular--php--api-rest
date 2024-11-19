<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class Users extends Controller
{
    // login
    public function login(Request $request)
    {
        // validate
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];
        $validator = Validator::make($request->input(), $rules);
        if($validator->fails()){
            return response()->json([
                "success"=>false,
                "message" => $validator->errors()
            ], 400);
        }
        $email = $request->input('email');
        $password = $request->input('password');
        $user = User::where('email', $email)->first();
        if ($user && Auth::attempt(['email' => $email, 'password' => $password])) {  
            return response()->json([
                "success"=>true,
                "user" =>$user,
            ]);
        } else {
            return response()->json([
                "success"=>false,
                "message" => 'Inicio de sesión fallido'
            ], 401);
        }
    }

    public function register(Request $request)
    {
        // validate
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            
        ];
        $validator = Validator::make($request->input(), $rules);
        if($validator->fails()){
            return response()->json([
                "success"=>false,
                "message" => $validator->errors()
            ], 400);
        }
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();
        return response()->json([
            "success"=>true,
            "user"=> $user,
        ]);


    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "success"=>true,
            "message" => 'Cierre de sesión exitoso'
        ]);
    }

}
    