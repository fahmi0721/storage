<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use App\Models\Chat;
use App\Models\Pengetahuan;
use App\Models\User;
use DB;

class HomeController extends Controller {
    public function index() {
        try {
            $result['user'] = User::count();
            $result['pengetahuan'] = Pengetahuan::count();
            $result['chat'] = Chat::where("user_id","!=",0)->count();

            $result['chat_pertahun'] = Chat::select(DB::raw('YEAR(created_at) tahun'), DB::raw('count(*) as total'))
            ->where("user_id","!=",0)
            ->groupby('tahun')
            ->get();
            
            return response()->json(['status' => 'success', 'message'=>'proses success', 'data' => $result], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','message'=> $e->errorInfo ], 500);
        }

    }
}
