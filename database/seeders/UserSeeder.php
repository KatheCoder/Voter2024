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
        $developer = User::create([
            'name' => 'Developer',
            'email' => 'katlego.majatladi@gmail.com',
                'password' => bcrypt('DeveloperPassword'),
        ]);

        $adminRole = Role::where('name', 'admin')->first();

        if ($adminRole) {
            $developer->roles()->attach($adminRole);
        }
        $user = User::create([
            'name' => 'Ancgp',
            'email' => 'Ancgp@anc.co.za',
            'password' => bcrypt('password'),
        ]);

        $userRole = Role::where('name', 'user')->first();

        if ($userRole) {
            $user->roles()->attach($userRole);
        }
    }
}
