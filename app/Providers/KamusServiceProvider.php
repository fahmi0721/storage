<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class KamusServiceProvider extends ServiceProvider {
    public function register() {
        require_once app_path() . '/Helpers/Kamus.php';
    }
    public function boot() {
        //
    }
}
