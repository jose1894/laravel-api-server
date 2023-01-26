<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission; 
use Spatie\Permission\Models\Role;

class TipoPolizaPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $managePermission = Permission::create(['guard_name' => 'api','name' => 'tipo-poliza.manage']);
        $indexPermission = Permission::create(['guard_name' => 'api','name' => 'tipo-poliza.index']);
        $createPermission = Permission::create(['guard_name' => 'api','name' => 'tipo-poliza.create']);
        $readPermission = Permission::create(['guard_name' => 'api','name' => 'tipo-poliza.read']);
        $updatePermission = Permission::create(['guard_name' => 'api','name' => 'tipo-poliza.update']);
        $deletePermission = Permission::create(['guard_name' => 'api','name' => 'tipo-poliza.delete']);
        
        $adminRole = Role::findByName('Admin');
        $adminRole->syncPermissions(compact('managePermission','indexPermission', 'createPermission', 'readPermission', 'updatePermission', 'deletePermission'));
    }
}
