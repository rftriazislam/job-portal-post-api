<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function signup(Request $request){

        $validate=$this->validate($request,[
            'role'=>'required',
            'name' => 'required|min:4',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);
        $validate['password'] = bcrypt($request->password);
        $user = User::create($validate);

        if ($user) {
        return response()->json(['success' => true, 'message' => 'Registration save successfully'], 201);
        } else {
        return response()->json(['success' => false, 'message' => 'Registration failed'], 400);
    }
    }
    public function signin(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'required|exists:users,email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['success' => false, 'message' => 'Email or Password Invalid'], 400);
        } else {
            $accessToken = auth()->user()->createToken('authToken')->accessToken;
            return response([ 'success' => true, 'message' => 'successfull signin','user' => auth()->user(),'token' => $accessToken], 200);
        }
    }
    public function logout(Request $request){
          $accessToken = auth()->user()->token();
          $token= $request->user()->tokens->find($accessToken);
          $token->revoke();
        return response([ 'success' => true, 'message' => ' Logout successful'], 200);

    }
}
