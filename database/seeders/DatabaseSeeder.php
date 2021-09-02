<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $newUser = new \App\Models\User;
        $newUser->name = 'Admin';
        $newUser->email = 'admin@admin.com';
        $newUser->email_verified_at = now();
        $newUser->phone = '11 999999999';
        $newUser->adress = 'Rua Admin';
        $newUser->remember_token = Str::random(10);
        $newUser->password = bcrypt( 'admin' );
        $newUser->save();
    }
}
