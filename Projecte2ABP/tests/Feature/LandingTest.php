<?php

namespace Tests\Feature;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LandingTest extends TestCase
{
    /**
     * A basic feature test example.
     */

     use RefreshDatabase;

    public function test_Producto_En_Lanading_Page(): void
    {

        $this->crearTags();

        $producto = $this->crearProductos();

        $this->crearTagProducto();

        $response = $this->get('/');

        $response->assertSee($producto[0]->nombre_producto);
    }

    public function test_No_Producto_En_Lanading_Page(): void
    {

        $this->crearTags();

        $producto = $this->crearProductos();

        $this->crearTagProductoQueNoEstaEnLaLanding();

        $response = $this->get('/');

        $response->assertDontSee($producto[0]->nombre_producto);
    }

    public static function crearProductos()
    {

        $productosFactory = Producto::factory(1)->create();
        $url = "https://loremflickr.com/640/480/technics";

        foreach ($productosFactory as $producto) {
            $arrayImagen = [];

            $arrayImagen[] = [
                'name' => 'imagePrincipal',
                'contents' => file_get_contents($url),
                'filename' => uniqid('product_', true) . '.png'
            ];
    
            for ($i = 0; $i < 3; $i++) {
                $arrayImagen[] = [
                    'name' => 'image[' . $i . ']',
                    'contents' => file_get_contents($url),
                    'filename' => uniqid('product_', true) . '.png'
                ];
            }

            $arrayImagen[] = [
                'name' => 'producto_id',
                'contents' => $producto->id
            ];

            $urlApi = env('URL_API', 'http://127.0.0.1%27/');
            $response = Http::attach($arrayImagen)->post($urlApi . '/api/addImage');
        }
        
       return $productosFactory;
    }

    public static function crearTags()
    {
        $tag = [
            ['nombre_tag' => 'Verde'],
            ['nombre_tag' => 'Rojo'],
            ['nombre_tag' => 'Amarillo'],
            ['nombre_tag' => 'Rosa'],
            ['nombre_tag' => 'Azul'],
            ['nombre_tag' => 'Morado'],
            ['nombre_tag' => 'Cian'],
            ['nombre_tag' => 'Negro']
        ];

        foreach ($tag as $post) {
            $b = new Tag();
            $b->nombre_tag = $post['nombre_tag'];
            $b->save();
        }
    }

    public static function crearTagProducto()
    {
            $tag_producto = [
                [
                    'tag_id' => 3,
                    'producto_id' => 1
                ]
            ];

            DB::table('tags_productos')->insert($tag_producto);
        }

    public static function crearTagProductoQueNoEstaEnLaLanding()
        {
                $tag_producto = [
                    [
                        'tag_id' => 8,
                        'producto_id' => 1
                    ]
                ];
    
                DB::table('tags_productos')->insert($tag_producto);
            }
}
