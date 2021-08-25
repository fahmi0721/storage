<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    public function run() {
        \App\Models\User::create([
            "email"     => "admin@admin.com",
            "password"  => bcrypt("admin"),
            "nm_lengkap"=> "Super User",
            "level"     => "admin",
            "user_id"   => "0"
        ]);
    }
}
