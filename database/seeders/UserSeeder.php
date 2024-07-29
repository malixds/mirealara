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
        $worker = Role::create([
            'name' => UserRolesEnum::WORKER->value,
        ]);

        foreach (range(1, 10) as $index) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => '130603maxim',
            ]);

            if ($user->id === 1){
                $user->roles()->attach($admin);
            } else if ($user->id % 2 === 0) {
                $user->roles()->attach($worker);
            } else {
                $user->roles()->attach($regular);
            }

        }
        $user = User::create([
            'name'=>'maxim',
            'email'=>'maxim@mail.ru',
            'password'=>'130603maxim',
        ]);
        $user->roles()->attach($regular);
    }
}
