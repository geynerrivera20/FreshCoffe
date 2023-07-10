<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegistroRequest;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    public function register(RegistroRequest $request){
        //Validar el registro
        $data = $request->validated();

        //Crear el usuario
        $user = User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>bcrypt($data['password'])
        ]);

        //Retornar Respuesta al registro
        return [
            'token'=> $user->createToken('token')->plainTextToken,
            'user'=> $user
        ];
    }

    public function login(LoginRequest $request){
        //Validar el login
        $data = $request->validated();

        //Revisar el password
        //En caso que no pueda autenticar el usuario
        if(!Auth::attempt($data)){
            return response([
                'errors' => ['El email o el password son incorrectos']
            ],422);
        }

        $user = Auth::user();
        return [
            'token'=>$user->createToken('token')->plainTextToken,
            'user' =>$user
        ];
    }

    public function logout(Request $request){
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return [
            'user' => null
        ];
    }
}
