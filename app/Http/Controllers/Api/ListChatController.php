<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Database\QueryException;
use App\Models\Chat;
use App\Models\Pengetahuan;
use App\Models\Hasil;
use Validator;
use Custom;

class ListChatController extends Controller {
    public function index() {
        try {
            $result = Chat::where('to', 0)->with('user')->get();
            return response()->json(['status' => 'success', 'message'=>'proses success', 'data' => $result], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','message'=> $e->errorInfo ], 500);
        }
    }

    public function detail_perhitungan($id) {
        try {
            $result = Hasil::with('pengetahuan')->where('chat_id', $id)->first();
            return response()->json(['status' => 'success', 'message'=>'proses success', 'data' => $result], 201);
        } catch(QueryException $e) {
            return response()->json(['status'=>'error','message'=> $e->errorInfo ], 500);
        }
    }

    public function delete_data($id) {
		try {
            $result = Hasil::where('chat_id', $id)->delete();
            Chat::find($id)->delete();

			return response()->json(['status'=>'success','message'=>'proses success'], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','message'=> $e->errorInfo ], 500);
        }
    }
}
