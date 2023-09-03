<?php

namespace Database\Factories;

use Termwind\Components\Hr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    // public function generarImageAleatoria(){
    //     $response =  Http::get('"https://picsum.photos/200/300?random=2"');
    //     $data = $response->json();
    //     echo $data
    // }
     
    public function definition(): array
    {
        return [
            'nombre_producto' => fake()->userName(),
            'descripcion' => fake()->realText(100), 
            'precio' => fake()->randomFloat(2, 2, 5)
        ];
    }
}
