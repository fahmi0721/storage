<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Database\QueryException;
use App\Models\User;
use App\Models\Chat;
use App\Models\Hasil;

use Validator;
use Custom;

class UserController extends Controller {
    public function index() {
        try {
            $result = User::all();
        return response()->json(['status' => 'success', 'message'=>'proses success', 'data' => $result], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','message'=> $e->errorInfo ], 500);
        }
    }
    public function registrasi(Request $request) {
        $validates 	= [
            "email"     => "required|email",
            "nm_lengkap"=> "required",
            "password"  => "required",
        ];
        
        $validation = Validator::make($request->all(), $validates, Custom::messages(), []);

        if($validation->fails()) {
            return response()->json([
                "status"    => "warning",
                "message"     => $validation->errors()->first()
            ], 422);
        }

        try {
            $data = [
                "email"     => $request->email,
                "nm_lengkap"=> $request->nm_lengkap,
                "password"  => bcrypt($request->password),
                "level"     => 'user',
                "user_id"   => '1',
            ];
            $result = User::create($data);
            return response()->json(['status' => 'success', 'message'=>'proses success', 'data' => $result], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','message'=> $e->errorInfo ], 500);
        }  
    }

    public function delete_data($id) {
		try {
            $result     = User::find($id)->delete();
            $chat       = Chat::where('user_id', $id)->orWhere('to', $id);
            $loadChat   = $chat->get();
            foreach($loadChat as $item) {
                Hasil::where('chat_id', $item->id)->delete();
            }
            $chat->delete();
            
			return response()->json(['status'=>'success','message'=>'proses success'], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','message'=> $e->errorInfo ], 500);
        }
    }
}
