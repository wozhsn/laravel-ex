<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::truncate();

        $faker = \Faker\Factory::create();

        $password = \Hash::make('password');

        \App\User::create([
            'name' => 'admin',
            'email' => 'admin@api.test',
            'password' => $password
        ]);

        for ($i = 0; $i < 50; $i++) {
            \App\User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => $password
            ]);
        }
    }
}
