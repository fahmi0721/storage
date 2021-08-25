<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Database\QueryException;
use App\Models\Pengetahuan;
use Validator;
use Custom;


class PengetahuanController extends Controller {
    public function index() {
        try {
            $result = Pengetahuan::all();
            return response()->json(['status' => 'success', 'message'=>'proses success', 'data' => $result], 200);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','message'=> $e->errorInfo ], 500);
        }
    }

    public function find_data($id) {
        try {
            $result = Pengetahuan::find($id);
            return response()->json(['status' => 'success', 'message'=>'proses success', 'data'=>$result ], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','message'=> $e->errorInfo ], 500);
        }
    }

    public function create_data(Request $request) {
        $validates 	= ["pertanyaan" => "required|max:350", "jawaban" => "required|max:350"];

        $validation = Validator::make($request->all(), $validates, Custom::messages(), []);
        if($validation->fails()) {
            return response()->json([
                "status"    => "warning",
                "message"     => $validation->errors()->first()
            ], 422);
        }
        
        try {
            $data = [
                "pertanyaan"=> $request->pertanyaan, 
                "jawaban"   => $request->jawaban,
                "user_id"   => auth()->user()->id
            ];
            $result = Pengetahuan::create($data);
            return response()->json(['status' => 'success', 'message'=>'proses success', 'data' => $result], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','message'=> $e->errorInfo ], 500);
        }
    }

    public function update_data(Request $request, $id) {
		$validates 	= ["pertanyaan" => "required|max:350", "jawaban" => "required|max:350"];

		$validation = Validator::make($request->all(), $validates, Custom::messages(), []);
        if($validation->fails()) {
            return response()->json([
                "status"    => "warning",
                "message"     => $validation->errors()->first()
            ], 422);
        }

        try {

            $data = [
                "pertanyaan"=> $request->pertanyaan, 
                "jawaban"   => $request->jawaban,
                "user_id"   => auth()->user()->id
            ];

            $result = Pengetahuan::find($id)->update($data);
            return response()->json(['status' => 'success', 'message'=>'proses success'], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','message'=> $e->errorInfo ], 500);
        }
    }
    
    public function delete_data($id) {
		try {
            $result = Pengetahuan::find($id)->delete();
			return response()->json(['status'=>'success','message'=>'proses success'], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','message'=> $e->errorInfo ], 500);
        }
    }
}
