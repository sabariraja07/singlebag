<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
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
                'group_name' => 'Category',
                'permissions' => [
                    'category.create',
                    'category.edit',
                    'category.show',
                    'category.update',
                    'category.destroy',
                    'category.index',

                ]
            ],
            [
                'group_name' => 'Coupon',
                'permissions' => [
                    'coupon.create',
                    'coupon.edit',
                    'coupon.show',
                    'coupon.update',
                    'coupons.destroy',
                    'coupon.index',

                ]
            ],
            [
                'group_name' => 'Marketing',
                'permissions' => [
                    'marketing.show',
                    'marketing.store',

                ]
            ],
            [
                'group_name' => 'Gateway',
                'permissions' => [
                    'gateway.show',
                    'gateway.store',
                    'gateway.install',
                    'gateway.update',

                ]
            ],
            [
                'group_name' => 'Transaction',
                'permissions' => [
                    'transaction.index',
                    'transaction.store',

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
