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
        $user = new \App\User();

        $user->name = "Super Admin";
        $user->email = "superadmin@admin.com";
        $user->email_verified_at = new DateTime();
        $user->password = \Illuminate\Support\Facades\Hash::make('password');

        $user->save();
    }
}
