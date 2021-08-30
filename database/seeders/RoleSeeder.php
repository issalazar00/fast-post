<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role; 

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create(['guard_name' => 'api','name' => 'Admin']); 
        $atm = Role::create(['guard_name' => 'api','name' => 'Atm']); 

        Permission::create(['guard_name' => 'api', 'name' => 'category.index', 'description' => 'Listar', 'component' => 'Categorías'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'category.store', 'description' => 'Crear', 'component' => 'Categorías'])->syncRoles([$admin, $atm]);
        Permission::create(['guard_name' => 'api', 'name' => 'category.update', 'description' => 'Editar', 'component' => 'Categorías'])->syncRoles([$admin, $atm]);
        Permission::create(['guard_name' => 'api', 'name' => 'category.delete', 'description' => 'Eliminar', 'component' => 'Categorías'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'category.active', 'description' => 'Activar', 'component' => 'Categorías'])->syncRoles([$admin, $atm]);
       

        Permission::create(['guard_name' => 'api', 'name' => 'tax.index', 'description' => 'Listar', 'component' => 'Iva'])->syncRoles([$admin, $atm]);
        Permission::create(['guard_name' => 'api', 'name' => 'tax.store', 'description' => 'Crear', 'component' => 'Iva'])->syncRoles([$admin, $atm]);
        Permission::create(['guard_name' => 'api', 'name' => 'tax.update', 'description' => 'Editar', 'component' => 'Iva'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'tax.delete', 'description' => 'Eliminar', 'component' => 'Iva'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'tax.active', 'description' => 'Activar', 'component' => 'Iva'])->syncRoles([$admin]);


        Permission::create(['guard_name' => 'api', 'name' => 'product.index', 'description' => 'Listar', 'component' => 'Productos'])->syncRoles([$admin, $atm]);
        Permission::create(['guard_name' => 'api', 'name' => 'product.store', 'description' => 'Crear', 'component' => 'Productos'])->syncRoles([$admin, $atm]);
        Permission::create(['guard_name' => 'api', 'name' => 'product.update', 'description' => 'Editar', 'component' => 'Productos'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'product.delete', 'description' => 'Eliminar', 'component' => 'Productos'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'product.active', 'description' => 'Activar', 'component' => 'Productos'])->syncRoles([$admin]);

<<<<<<< HEAD
        Permission::create(['guard_name' => 'api', 'name' => 'supplier.index', 'description' => 'Listar', 'component' => 'Proveedor'])->syncRoles([$admin, $atm]);
        Permission::create(['guard_name' => 'api', 'name' => 'supplier.store', 'description' => 'Crear', 'component' => 'Proveedor'])->syncRoles([$admin, $atm]);
        Permission::create(['guard_name' => 'api', 'name' => 'supplier.update', 'description' => 'Editar', 'component' => 'Proveedor'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'supplier.delete', 'description' => 'Eliminar', 'component' => 'Proveedor'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'supplier.active', 'description' => 'Activar', 'component' => 'Proveedor'])->syncRoles([$admin]);
=======
        Permission::create(['guard_name' => 'api', 'name' => 'brand.index', 'description' => 'Listar', 'component' => 'Marcas'])->syncRoles([$admin, $atm]);
        Permission::create(['guard_name' => 'api', 'name' => 'brand.store', 'description' => 'Crear', 'component' => 'Marcas'])->syncRoles([$admin, $atm]);
        Permission::create(['guard_name' => 'api', 'name' => 'brand.update', 'description' => 'Editar', 'component' => 'Marcas'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'brand.delete', 'description' => 'Eliminar', 'component' => 'Marcas'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'brand.active', 'description' => 'Activar', 'component' => 'Marcas'])->syncRoles([$admin]);
>>>>>>> 5b2dba369900cd1cc1bc2916ce9e43e6821c0bc2


        
    }
}
