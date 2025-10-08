<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Réinitialiser cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = [
            'patrouilles',
            'rapports',
            'sites',
            'agents',
            'presences',
            'requetes',
            'planning',
            'rh',
            'communiques',
            'signalements',
            'configurations',
            'utilisateurs',
            'logs'
        ];

        $actions = ['view', 'create', 'edit', 'delete', 'export', 'import'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "$module.$action"]);
            }
        }
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $manager = Role::firstOrCreate(['name' => 'manager']);

        $admin->givePermissionTo(Permission::all());

        // Le manager a accès seulement à certaines fonctionnalités
        $managerPermissions = [
            'patrouilles.view',
            'rapports.view',
            'sites.view',
            'agents.view',
            'presences.view',
            'requetes.view',
            'planning.view',
            'rh.view',
        ];
        $manager->givePermissionTo($managerPermissions);
    }
}
