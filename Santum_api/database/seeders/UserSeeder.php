<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!DB::table('users')->where('email', 'mickvm3@gmail.com')->exists()) {

            DB::table('users')->insert([
                'fname' => 'Mahesh ',
                'lname' => 'Verma ',
                'email' => 'mickvm3@gmail.com',
                'password' => Hash::make('password'),
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }
}
