<?php

namespace Database\Seeders;

use App\Models\{Role, Permission};
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permission::pluck('id');

        $role = Role::firstOrCreate([
            'title' => 'مدیر کل',
            'description' => 'دسترسی به همه قسمت های وبسایت',
        ]);

        $role->permissions()->sync($permissions);
    }
}
