<?php

namespace Database\Seeders;

use App\Enums\UserRolesEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Role;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();
        $admin = Role::create([
            'name' => UserRolesEnum::ADMIN->value,
        ]);
        $regular = Role::create([
            'name' => UserRolesEnum::REGULAR->value,
        ]);

        foreach (range(1, 3) as $index) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => '130603maxim',
            ]);

            if ($user->id === 1){
                $user->roles()->attach($admin);
            } else {
                $user->roles()->attach($regular);
            }

        }
    }
}
