<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengetahuan extends Model {
    use HasFactory;
    protected $table 	= 'app_pengetahuan';
	protected $fillabel = ['pertanyaan','jawaban','user_id'];
	protected $guarded	= ['created_at','updated_at'];
}
