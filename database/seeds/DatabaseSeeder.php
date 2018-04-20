<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
    }
}

class UserTableSeeder extends Seeder {
    public function run() {

        $param = array(
            'email'=> env('SUPER_EMAIL', ''),
            'password'=> bcrypt( env('SUPER_PASSWORD', '') ),
            'role'=> 'super',
            'created_at'=>date( 'Y-m-d H:i:s' ),
            'updated_at'=>date( 'Y-m-d H:i:s' ),
        );

        DB::table('cr_users')->insert($param);
    }
}