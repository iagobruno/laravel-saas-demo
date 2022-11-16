<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Create basic plan role and give permissions to it.
         */
        $basicPlan = Role::create(['name' => 'basic-plan']);
        $basicPlanPermissions = [
            'list-tasks',
            'edit-tasks',
            'create-tasks',
        ];
        foreach ($basicPlanPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        $basicPlan->syncPermissions($basicPlanPermissions);


        /**
         * Create standard plan role and give permissions to it.
         */
        $standardPlan = Role::create(['name' => 'standard-plan']);
        $standardPlanPermissions = [
            'delete-tasks',
        ];
        foreach ($standardPlanPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        $standardPlan->syncPermissions([
            ...$basicPlanPermissions,
            ...$standardPlanPermissions,
        ]);


        /**
         * Create premium plan role and give permissions to it.
         */
        $premiumPlan = Role::create(['name' => 'premium-plan']);
        $premiumPlanPermissions = [
            'send-emails',
            'publish-photos',
        ];
        foreach ($premiumPlanPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        $premiumPlan->syncPermissions([
            ...$basicPlanPermissions,
            ...$standardPlanPermissions,
            ...$premiumPlanPermissions,
        ]);
    }
}
