<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }


    public function register(Request $request)
    {
       $request->validate([
           'name' => 'required',
           'email' => 'required|email',
           'password' => 'required|min:8',
       ]);

       $user = User::create([
           'name' => $request->name,
           'email' => $request->email,
           'password' => bcrypt($request->password)
       ]);

       $token = Auth::login($user);

        return response()->json([
            'status' => 'success',
            'message' => 'Utilisateur créer avec succès',
            'user' => $user,
            'token' => $token


       ]);
    }

    public function login(Request $request){

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);

        if(!$token){
            return response()->json([
                'status' => 'error',
                'message' => 'Utilisateur ou mot de passe incorrect',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Utilisateur connecté avec succès',
            'token' => $token
        ]);

    }
}

