<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
		$users = [
			[
				'f_name' => 'Ishan',
				'l_name' => 'Gunasekara',
				'email' => 'ishansg@yahoo.com.sg',
				'password' => bcrypt('password'),
				'user_type' => 'BA',
				'login_attempt' => 1,
				'user_status' => 'Y'
			],
			[
				'f_name' => 'Peter',
				'l_name' => 'Thomas',
				'email' => 'test@test.com',
				'password' => bcrypt('password'),
				'user_type' => 'SA',
				'login_attempt' => 1,
				'user_status' => 'Y'
			],
			[
				'f_name' => 'Jason',
				'l_name' => 'Roy',
				'email' => 'suwantha.ig@gmail.com',
				'password' => bcrypt('password'),
				'user_type' => 'BA',
				'login_attempt' => 1,
				'user_status' => 'Y'
			]
		];
		
		// Looping and Inserting Array's Users into User Table
        foreach($users as $user){
            User::create($user);
        }
    }
}
