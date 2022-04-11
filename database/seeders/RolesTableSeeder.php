<?php

namespace Database\Seeders;

use App\Models\Role;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'id' => '1',
            'name' => 'Admin',
            'access_level' => 'Admin'
        ]);

        Role::create([
            'id' => '9',
            'name' => 'Investor',
            'access_level' => 'Investor'
        ]);

        Role::create([
            'id' => '18',
            'name' => 'Super Admin',
            'access_level' => 'Super Admin'
        ]);

    }
}
