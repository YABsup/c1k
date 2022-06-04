<?php

use Illuminate\Database\Seeder;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = array(
            array(
                'name' => 'Roman',
                'email'=>'3617904@gmail.com',
                'password'=>'$2y$10$tS2JqeZ0XJ.ISNMIIVuhCeQ8y4Vhcs3Csvsly5a/kh02TcBA9uGN6',
                'role'=>'superadmin',
                'telegram'=>'RVKovalchuk',
                'verified'=>1,
                'verified_send'=>1,
                'ref_code'=>'6eda7017e20d34040d00cf930d2c5c0cd0e270dc5fce1517f523ff97e4734801',
                'telegram_id'=>912132457,
                'api_token'=>bin2hex(random_bytes(32)),
                'api_secret'=>bin2hex(random_bytes(32)),
            ),
        );

        DB::table('users')->insert($data);
    }
}
