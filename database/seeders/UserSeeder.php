<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $developer1 = User::create([
            'name' => 'Steven',
            'email' => 'steven@plus94.co.za',
                'password' => bcrypt('steven123!@'),
        ]);
        $developer2 = User::create([
            'name' => 'dp',
            'email' => 'dp@plus94.co.za',
            'password' => bcrypt('dp123!@'),
        ]);

        $adminRole = Role::where('name', 'admin')->first();

        if ($adminRole) {
            $developer1->roles()->attach($adminRole);
            $developer2->roles()->attach($adminRole);
        }
//        $user = User::create([
//            'name' => 'Ancgp',
//            'email' => 'Ancgp@anc.co.za',
//            'password' => bcrypt('password'),
//        ]);
//
//        $userRole = Role::where('name', 'user')->first();
//
//        if ($userRole) {
//            $user->roles()->attach($userRole);
//        }
    }
}
