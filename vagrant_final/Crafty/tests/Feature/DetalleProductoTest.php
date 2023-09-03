<?php

namespace Tests\Feature;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\User;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DetalleProductoTest extends TestCase
{
     /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function testShowDetalleProducto(): void
    {

        $this->createAuthUser();

        $this->crearTags();

        $this->crearCategoria();

        $productos = $this->crearProductos();


        $this->crearTagProducto();

        $this->crearCategoriaProducto();

        $this->crearUsuarioProducto();


        $response = $this->get('/productos/1');



        $response->assertSee($productos[0]->nombre_producto);
    }

    public function createAuthUser()
    {
        $user = User::create([
            'id' => 33,
            'name' => 'joan',
            'email' => 'joan@gmail.com',
            'password' => Hash::make('Copernic123')
        ]);


        $user->save();


        Auth::login($user);

        return $user;
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



    public static function crearCategoria()
    {
        $categoria = [
            ['nombre_categoria' => 'Ceramica'],
            ['nombre_categoria' => 'Joyeria'],
            ['nombre_categoria' => 'Madera'],
            ['nombre_categoria' => 'Juguetes'],
            ['nombre_categoria' => 'Arte'],
            ['nombre_categoria' => 'Ropa'],
            ['nombre_categoria' => 'Piel i Cuero'],
            ['nombre_categoria' => 'Herreria']
        ];

        foreach ($categoria as $post) {
            $b = new Categoria();
            $b->nombre_categoria = $post['nombre_categoria'];
            $b->save();
        }
    }



    public static function crearCategoriaProducto()
    {
        $productos = DB::table('productos')->get();

        foreach ($productos as $product) {

            $categoria = DB::table('categorias')->inRandomOrder()->first();

            $categoria_producto = [
                [
                    'categoria_id' => $categoria->id,
                    'producto_id' => 1
                ]
            ];

            DB::table('categorias_productos')->insert($categoria_producto);
        }
    }

    public static function crearTagProducto()
    {
        $productos = DB::table('productos')->get();

        foreach ($productos as $product) {

            $tag = DB::table('tags')->inRandomOrder()->first();

            $tag_producto = [
                [
                    'tag_id' => $tag->id,
                    'producto_id' => 1
                ]
            ];

            DB::table('tags_productos')->insert($tag_producto);
        }
    }

    public static function crearUsuarioProducto()
    {
        $productos = DB::table('productos')->get();

        foreach ($productos as $product) {

            $shop = [
                [
                    'usuario_id' => 1,
                    'producto_id' => 1
                ]
            ];

            DB::table('shop')->insert($shop);
        }
    }
}
