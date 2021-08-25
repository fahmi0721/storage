<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $table 	= 'app_chats';
	protected $fillabel = ['chats','to','user_id'];
	protected $guarded	= ['created_at','updated_at'];

    public function user() {
		return $this->belongsTo("App\Models\User", "user_id","id");
	}
}
