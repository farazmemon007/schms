<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UsersTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        $this->createAdminUsers();
    }

    protected function createAdminUsers()
    {
        $password = Hash::make('admin123'); // Default admin password

        $d = [

            // ===== SUPER ADMIN =====
            [
                'name'           => 'Super Admin',
                'email'          => 'superadmin@school.com',
                'username'       => 'superadmin',
                'password'       => $password,
                'user_type'      => 'super_admin',
                'code'           => strtoupper(Str::random(10)),
                'remember_token' => Str::random(10),
            ],

            // ===== ADMIN =====
            [
                'name'           => 'Admin',
                'email'          => 'admin@school.com',
                'username'       => 'admin',
                'password'       => $password,
                'user_type'      => 'admin',
                'code'           => strtoupper(Str::random(10)),
                'remember_token' => Str::random(10),
            ],

        ];

        DB::table('users')->insert($d);
    }
}
