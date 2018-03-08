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
            DB::table('users')->insert([
                'name' => 'seed_test',
                'email' => 'seed_test@gmail.com',
                'password' => bcrypt('secret'),
            ]);
    }
}
