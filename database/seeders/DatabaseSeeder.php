<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'nip' => '0',
            'fullname' => 'Administrator',
            'role' => 'admin',
            'email' => 'admin@pusatgrosirsidoarjo.com',
            //'password' => bcrypt('onlyforAdmin101083%')
            'password' => bcrypt('123456')
        ]);
    }
}
