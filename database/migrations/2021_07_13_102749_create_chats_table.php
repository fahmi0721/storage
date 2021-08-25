<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration {
    public function up() {
        Schema::create('app_chats', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('to')->nullable();
            $table->string('chats', 300);
            $table->bigInteger('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_chats');
    }
}
