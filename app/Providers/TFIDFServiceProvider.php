<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TFIDFServiceProvider extends ServiceProvider {
    public function register() {
        require_once app_path() . '/Helpers/TFIDF.php';
    }
    public function boot() {
        //
    }
}
