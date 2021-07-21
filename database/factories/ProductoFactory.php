<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Producto::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'codigo_barras' =>  $this->faker->unique()->ean13(),
            'producto' =>  $this->faker->name(),
            'tipo_producto' =>  $this->faker->numberBetween(1,3),
            'precio_costo' =>  $this->faker->randomFloat(2,1, 10000),
            'valor_ganancia' =>  $this->faker->randomFloat(2,1, 10000),
            'precio_venta' =>  $this->faker->randomFloat(2,1, 10000),
            'precio_mayoreo' =>  $this->faker->randomFloat(2,1, 10000),
            'inventario' =>  $this->faker->numberBetween(0,1),
            'cantidad_actual' =>  $this->faker->numberBetween(0,100),
            'cantidad_minima' =>  $this->faker->numberBetween(0,100),
            'cantidad_maxima' =>  $this->faker->numberBetween(0,100),
            'estado' => '1',
            'id_categoria' =>  $this->faker->numberBetween(1,10),
            'id_impuesto' =>  $this->faker->numberBetween(1,10),

        ];
    }
}
