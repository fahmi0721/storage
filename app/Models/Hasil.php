<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hasil extends Model
{
    use HasFactory;
    protected $table 	= 'app_hasil';
	protected $fillabel = ['chat_id','pengetahuan_id','skors','hasil_perhitungan','user_id'];
	protected $guarded	= ['created_at','updated_at'];

    public function pengetahuan() {
		return $this->belongsTo("App\Models\Pengetahuan", "pengetahuan_id","id");
	}
}


