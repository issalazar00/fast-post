<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CategorySeeder::class);
        $this->call(TaxSeeder::class);
<<<<<<< HEAD
       // $this->call(RoleSeeder::class);
=======
        $this->call(RoleSeeder::class);
        $this->call(ProductTableSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(ClientSeeder::class);
>>>>>>> main
        $this->call(UserSeeder::class);
        Product::factory()->create();
    }
}
