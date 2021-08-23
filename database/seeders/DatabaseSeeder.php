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
       // $this->call(RoleSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(UserSeeder::class);
        // $this->call(ProductTableSeeder::class);
        Product::factory()->create();
    }
}
