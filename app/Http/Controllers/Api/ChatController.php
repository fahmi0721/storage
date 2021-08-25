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
use TFIDF;

class ChatController extends Controller {
    public function index() {
        try {
            $result = Chat::where('user_id', auth()->user()->id)->orWhere('to',  auth()->user()->id)->get();
            return response()->json(['status' => 'success', 'message'=>'proses success', 'data' => $result], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','message'=> $e->errorInfo ], 500);
        }
    }

    public function send(Request $request) {
        $validates 	= ["chats" => "required"];
        $validation = Validator::make($request->all(), $validates, Custom::messages(), []);
        if($validation->fails()) {
            return response()->json([
                "status"    => "warning",
                "message"     => $validation->errors()->first()
            ], 422);
        }

        try {
            $data = [
                "chats"     => $request->chats,
                "to"        => "0",
                "user_id"   => auth()->user()->id,
            ];
            $result = Chat::create($data);

            //==========================================
            //==========================================
            $countpengetahuan = Pengetahuan::count();
            $balasan = [
                "chats"     => "pengetahuan sistem belum lengkap, kemungkinan jawaban anda tidak bisa di jawab",
                "to"        => auth()->user()->id,
                "user_id"   => "0",
            ];
            
            if($countpengetahuan > 0) {
                $pengetahuan = Pengetahuan::all();
                $tfidf = TFIDF::hasil_akhir($request->chats, $pengetahuan);
                $list_hasil = $tfidf['list_hasil'];
                arsort($list_hasil);
                $keys       = array_keys($list_hasil);
                $first_id   = $keys[0];
                $first_nilai= $list_hasil[$first_id];
               
                if($first_nilai>0) {
                    $pengetahuan = Pengetahuan::find($first_id);
                    $balasan = [
                        "chats"     => $pengetahuan->jawaban,
                        "to"        => auth()->user()->id,
                        "user_id"   => "0",
                    ];
                    $hasil = [
                        "chat_id"           => $result->id,
                        "pengetahuan_id"    => $pengetahuan->id,
                        "hasil_perhitungan" => json_encode($tfidf),
                        "skors"             => $first_nilai,
                    ];
                    Hasil::create($hasil);
                } else {
                    $balasan = [
                        "chats"     => "sistem tidak dapat mengelola pertanyaan anda.",
                        "to"        => auth()->user()->id,
                        "user_id"   => "0",
                    ];
                }
            }

            Chat::create($balasan);

            return response()->json(['status' => 'success', 'message'=>'proses success', 'data' => $result], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','message'=> $e->errorInfo ], 500);
        }
    }
    

    
}


