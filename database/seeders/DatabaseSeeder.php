<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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
       User::create([
           'name'=> 'Admin',
           'email'=> 'admin@gmail.com',
           'password'=> Hash::make('12345678'),
           'remember_token'=> Str::random(10),
       ]);

        Teacher::create([
            'name'=> 'Teacher',
            'email'=> 'teacher@gmail.com',
            'password'=> Hash::make('12345678'),
            'phone'=> '0591234567',
            'image'=> '',
        ]);

        Student::create([
            'name'=> 'Student',
            'email'=> 'student@gmail.com',
            'password'=> Hash::make('12345678'),
            'phone'=> '0591234567',
            'image'=> '',
        ]);
    }
}
