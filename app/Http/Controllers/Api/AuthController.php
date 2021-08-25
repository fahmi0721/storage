<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Database\QueryException;
use Validator;
use Custom;

class AuthController extends Controller {
    public function login(Request $request) {
        $validates 	= [
            "email"     => "required|email",
            "password"  => "required",
        ];
        $validation = Validator::make($request->all(), $validates, Custom::messages(), []);
        if($validation->fails()) {
            return response()->json([
                "status"    => "warning",
                "message"   => $validation->errors()->first()
            ], 422);
        }
        
        try {
            $credentials = $request->only('email', 'password');
            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['status'=>'error', 'message' => 'Unauthenticated'], 401);
            }

            $token  = auth()->user()->createToken('API Token')->plainTextToken;
            return response()->json(['status' => 'success', 'message'=>'proses success', 'token'=>$token], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','message'=> $e->errorInfo ], 500);
        }  
    }

    public function logout(Request $request) {
        try {
            $request->user()->tokens()->delete();
            return response()->json(['status' => 'success', 'message'=>'proses success' ], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','message'=> $e->errorInfo ], 500);
        } 
    }

    public function detail_user_login() {
        try {
            $result = auth()->user();
            return response()->json(['status' => 'success', 'message'=>'proses success', 'data'=>$result ], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','message'=> $e->errorInfo ], 500);
        } 
    }
    
}
