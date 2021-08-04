<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->faker->numberBetween(0, 100000),
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'mobile' => $this->faker->phoneNumber(),
            'fax' => $this->faker->text(),
            'fisrtname_contact' => $this->faker->firstName(),
            'lastname_contact' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'giro_neg' => $this->faker->text(),
            'type_person' => $this->faker->numberBetween(1,9),
            'departament' => $this->faker->numberBetween(1,32),
            'city' => $this->faker->numberBetween(1,1105),
            'zipcode' => $this->faker->text(50),
            'type_document' => $this->faker->numberBetween(1,3),
            'document' => $this->faker->randomNumber(),
            'business_registration' => $this->faker->text(),
            'state' => $this->faker->numberBetween(0,1),
            'tax' => $this->faker->numberBetween(1,9),

        ];
    }
}
