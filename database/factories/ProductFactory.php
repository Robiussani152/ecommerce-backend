<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class ProductFactory extends Factory
{

    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(4, true),
            'description' => $this->faker->sentences(3, true),
            'price' => mt_rand(100, 500),
            'quantity' => mt_rand(50, 100),
            'image' => 'images/test-product.jpg'
        ];
    }
}
