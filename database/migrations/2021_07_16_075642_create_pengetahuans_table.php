<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengetahuansTable extends Migration {
    public function up() {
        Schema::create('app_pengetahuan', function (Blueprint $table) {
            $table->id();
            $table->string('pertanyaan', 350);
            $table->string('jawaban', 350);
            $table->bigInteger('user_id');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('app_pengetahuan');
    }
}
