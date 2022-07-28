<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $actions = [
            'read',
            'write',
            'remove'
        ];

        $resources = [
            // General
            'roles' => $actions,
            'users' => $actions,
            'api_keys' => $actions,

            // Trading
            'currencies' => $actions,
            'symbols' => $actions,
            'exchanges' => $actions,
            'exchange_accounts' => $actions,
            'exchange_datasets' => $actions,
            'indicators' => $actions,
            'conditional_trades' => $actions,
            'bots' => $actions,
            'bot_sessions' => $actions,
            'orders' => $actions,

            // News
            'sources' => $actions,
            'feeds' => $actions,
            'authors' => $actions,
            'categories' => $actions,
            'articles' => $actions,

            // Marketplace
            'payment_processors' => $actions,
            'seller_account' => $actions,
            'withdraw_methods' => $actions,
            'withdraws' => $actions,
            'product_categories' => $actions,
            'products' => $actions,
            'product_orders' => $actions,
            'product_order_payments' => $actions,
            'product_reviews' => $actions,
            'payment_methods' => $actions,
            'discount_codes' => $actions
        ];

        foreach($resources as $resourceName => $resourceActions) {
            foreach($resourceActions as $actionName) {
                $permissionName = ucfirst($actionName) . ' ' . str_replace('_', ' ', $resourceName);

                $permission = Permission::whereName($permissionName)->first();
                if(!$permission) {
                    $permission = new Permission();
                    $permission->name = $permissionName;
                    $permission->resource = $resourceName;
                    $permission->action = $actionName;
                    $permission->description = "Ability to be able to {$actionName} " . str_replace('_', ' ', $resourceName);
                    $permission->save();
                }
            }
        }
    }
}