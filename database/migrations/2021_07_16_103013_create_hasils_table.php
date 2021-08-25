<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHasilsTable extends Migration {
    public function up() {
        Schema::create('app_hasil', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('chat_id');
            $table->bigInteger('pengetahuan_id');
            $table->text('hasil_perhitungan');
            $table->string('skors', 20);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('app_hasil');
    }
}
