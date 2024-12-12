<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Acl\Entities\Permission;
use Modules\Acl\Entities\Role;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = Role::get()->first();
        $models = modelPermission();
        $names =
            [
                ['label' => "view", 'name' => 'view'],
                ['label' => "create", 'name' => 'create'],
                ['label' => "update", 'name' => 'update'],
                ['label' => "delete", 'name' => 'delete'],
            ];
        foreach($models as $model)
        {
            $count = Permission::where("category", $model)->count();
            if($count == 0)
            {
                foreach($names as $name)
                {
                    $permission = Permission::firstOrCreate([
                        'name' => $name['name'] . '_' . $model,
                        'label' => $name['label'] . ' ' . strtolower(trim(str_replace('_', ' ', trim($model)))),
                        'action' => $name['name'],
                        'category' => $model
                    ]);
                    $superAdmin->permissions()->attach($permission);
                }
            }
        }
        $models = ['report'];
        $names =
            [
                ['label' => "product", 'name' => 'product'],
                ['label' => "performance", 'name' => 'performance'],
                ['label' => "validation", 'name' => 'validation'],
                ['label' => "validation performance", 'name' => 'validation_performance'],
                ['label' => "platform performance", 'name' => 'platform_performance'],
                ['label' => "product capaste performance", 'name' => 'product_capaste_performance'],
                ['label' => "logistic", 'name' => 'logistic'],
                ['label' => "canceled performance", 'name' => 'canceled_performance'],
                ['label' => "payment performance", 'name' => 'payment_performance'],
                ['label' => "wms performance", 'name' => 'wms_performance'],
                ['label' => "order sources", 'name' => 'order_sources'],
            ];
        foreach($models as $model)
        {
            foreach($names as $name)
            {
                $count = Permission::where("name", $name['name'] . '_' . $model)->first();
                if($count == null)
                {
                    $permission = Permission::firstOrCreate([
                        'name' => $name['name'] . '_' . $model,
                        'label' => $name['label'] . ' ' . strtolower(trim(str_replace('_', ' ', trim($model)))),
                        'action' => $name['name'],
                        'category' => $model
                    ]);
                    $superAdmin->permissions()->attach($permission);
                }
            }
        }
        $models = ['withdrawal_request', 'order', 'product', 'dropshipper'];
        $names =
            [
                ['label' => "extract", 'name' => 'extract'],
            ];
        foreach($models as $model)
        {
            foreach($names as $name)
            {
                $count = Permission::where("name", $name['name'] . '_' . $model)->first();
                if($count == null)
                {
                    $permission = Permission::firstOrCreate([
                        'name' => $name['name'] . '_' . $model,
                        'label' => $name['label'] . ' ' . strtolower(trim(str_replace('_', ' ', trim($model)))),
                        'action' => $name['name'],
                        'category' => $model
                    ]);
                    $superAdmin->permissions()->attach($permission);
                }
            }
        }
        $models = ['log'];
        $names =
            [
                ['label' => "request", 'name' => 'request'],
                ['label' => "easy_order", 'name' => 'easy_order'],
                ['label' => "validation", 'name' => 'validation'],
                ['label' => "wms", 'name' => 'wms'],
            ];
        foreach($models as $model)
        {
            foreach($names as $name)
            {
                $count = Permission::where("name", $name['name'] . '_' . $model)->first();
                if($count == null)
                {
                    $permission = Permission::firstOrCreate([
                        'name' => $name['name'] . '_' . $model,
                        'label' => $name['label'] . ' ' . strtolower(trim(str_replace('_', ' ', trim($model)))),
                        'action' => $name['name'],
                        'category' => $model
                    ]);
                    $superAdmin->permissions()->attach($permission);
                }
            }
        }
        $models = ['product'];
        $names =
            [
                ['label' => "scan", 'name' => 'scan'],
            ];
        foreach($models as $model)
        {
            foreach($names as $name)
            {
                $count = Permission::where("name", $name['name'] . '_' . $model)->first();
                if($count == null)
                {
                    $permission = Permission::firstOrCreate([
                        'name' => $name['name'] . '_' . $model,
                        'label' => $name['label'] . ' ' . strtolower(trim(str_replace('_', ' ', trim($model)))),
                        'action' => $name['name'],
                        'category' => $model
                    ]);
                    $superAdmin->permissions()->attach($permission);
                }
            }
        }
        $models = ['order'];
        $names =
            [
                ['label' => "view all", 'name' => 'view_all'],
                ['label' => "cod preparing", 'name' => 'cod_preparing'],
            ];
        foreach($models as $model)
        {
            foreach($names as $name)
            {
                $count = Permission::where("name", $name['name'] . '_' . $model)->first();
                if($count == null)
                {
                    $permission = Permission::firstOrCreate([
                        'name' => $name['name'] . '_' . $model,
                        'label' => $name['label'] . ' ' . strtolower(trim(str_replace('_', ' ', trim($model)))),
                        'action' => $name['name'],
                        'category' => $model
                    ]);
                    $superAdmin->permissions()->attach($permission);
                }
            }
        }
        $models = ['users'];
        $names =
            [
                ['label' => "change password", 'name' => 'change_password'],
            ];
        foreach($models as $model)
        {
            $count = Permission::where("name", $names[0]['name'] . '_' . $model)->first();
            if($count == null)
            {
                foreach($names as $name)
                {
                    $permission = Permission::firstOrCreate([
                        'name' => $name['name'] . '_' . $model,
                        'label' => $name['label'] . ' ' . strtolower(trim(str_replace('_', ' ', trim($model)))),
                        'action' => $name['name'],
                        'category' => $model
                    ]);
                    $superAdmin->permissions()->attach($permission);
                }
            }
        }
        $delete = ['create_setting', 'delete_setting', 'create_report', 'update_report', 'delete_report', 'view_request_log',
            'view_fail_order', 'create_order', 'delete_order', 'delete_withdrawal_request', 'attempts_logs_log', 'edit_setting',
            'create_RedeemRequest', 'extract_RedeemRequest', 'delete_RedeemRequest', 'create_withdrawal_request',
        ];
        $permissions = Permission::withTrashed()->whereIn("name", $delete)->get();
        foreach($permissions as $permission)
        {
            $permission->delete();
            $permission->forceDelete();
        }
    }
}
