<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $users = [
            [
               'name' => 'Mansi Shah',
               'email' => 'mansi@gmail.com',
               'password' => Hash::make('123456'),
            ],
            [
               'name' => 'Viresh Panchal',
               'email' => 'viresh@gmail.com',
               'password' => Hash::make('123456'),
            ],
            [
               'name' => 'Rahul Panchal',
               'email' => 'rahul@gmail.com',
               'password' => Hash::make('123456'),
            ]
        ];


        foreach ($users as $user) {
            User::create($user);
        }

    }
}
