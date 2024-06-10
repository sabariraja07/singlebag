<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class BrandPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create permission
        $permissions = [
            [
                'group_name' => 'Brand',
                'permissions' => [
                    'brand.create',
                    'brand.edit',
                    'brand.show',
                    'brand.update',
                    'brands.destroy',
                    'brand.index',

                ]
            ]
        ];

        //assign permission

        foreach ($permissions as $key => $row) {


            foreach ($row['permissions'] as $per) {
                $permission = Permission::create(['name' => $per, 'group_name' => $row['group_name']]);
            }
        }

    }
}
