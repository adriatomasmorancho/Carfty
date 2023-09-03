<?php

namespace Database\Seeders;

use DateTime;
use App\Models\Tag;
use App\Models\User;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        //$productCount = $this->command->ask('Cuantos productos quieres insertar? ');

        self::generarUsuarios();
        $this->command->info('Taula usuarios inicialitzada amb èxit');
        self::generarCategorias();
        $this->command->info('Taula categorias inicialitzada amb èxit');
        self::generarTags();
        $this->command->info('Taula tags inicialitzada amb èxit');
        self::generarProductos(env('NUM_PRODUCT'));
        $this->command->info('Taula productos inicialitzada amb èxit');
        self::generarCategoriasProductos();
        $this->command->info('Taula categorias_productos inicialitzada amb èxit');
        self::generarTagsProductos();
        $this->command->info('Taula tags_productos inicialitzada amb èxit');
        self::generarTienda();
        $this->command->info('Taula shop inicialitzada amb èxit');
    }

    private static function generarUsuarios()
    {
        DB::table('users')->delete();
        $usuario = [
            [
                'name' => 'Adria',
                'email' =>  'adria@gmail.com',
                'password' => Hash::make('123456'),
                'shop' => 1,
                'shop_name' => 'Adria SL',
                'shop_url' => 'Adria-SL'
            ],
            [
                'name' => 'Joan',
                'email' =>  'joan@gmail.com',
                'password' => Hash::make('123456'),
                'shop' => 0,
                'shop_name' => null,
                'shop_url' => null
            ]
        ];

        foreach ($usuario as $post) {
            $b = new User();
            $b->name = $post['name'];
            $b->email = $post['email'];
            $b->password = $post['password'];
            $b->shop = $post['shop'];
            $b->shop_name = $post['shop_name'];
            $b->shop_url = $post['shop_url'];
            $b->save();
        }


        DB::table('users')->where('id', 1)->update(['verify_send' => 1]);
        DB::table('users')->where('id', 2)->update(['verify_send' => 1]);
    }

    private static function generarCategorias()
    {
        DB::table('categorias')->delete();
        $categoria = [
            ['nombre_categoria' => 'Ceramica'],
            ['nombre_categoria' => 'Joyeria'],
            ['nombre_categoria' => 'Juguetes'],
            ['nombre_categoria' => 'Arte'],
            ['nombre_categoria' => 'Ropa'],
            ['nombre_categoria' => 'Madera'],
            ['nombre_categoria' => 'Piel y Cuero'],
            ['nombre_categoria' => 'Herreria']
        ];

        foreach ($categoria as $post) {
            $b = new Categoria();
            $b->nombre_categoria = $post['nombre_categoria'];
            $b->save();
        }
    }

    private static function generarTags()
    {
        DB::table('tags')->delete();
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

    private static function generarProductos(int $numProducts)
    {
        DB::table('productos')->delete();
        $producto = [
            [
                'nombre_producto' => 'Ferrari',
                'descripcion' => 'Coche poderoso en carreteras de asfalto',
                'precio' => 200000
            ],
            [
                'nombre_producto' => 'Férrari',
                'descripcion' => 'Coche poderoso en carreteras de asfalto',
                'precio' => 100000
            ]
        ];


        
        //Producto::factory(10)->create();
        $productosFactory = Producto::factory($numProducts)->create();
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
            $response = Http::withoutVerifying()->attach($arrayImagen)->post($urlApi . '/api/addImage');
        }
        //Producto::factory(50)->create();

        // foreach ($producto as $post) {
        //     $b = new Producto();
        //     $b->nombre_producto = $post['nombre_producto'];
        //     $b->descripcion = $post['descripcion'];
        //     $b->precio = $post['precio'];
        //     $b->save();
        // }
    }

    private static function generarCategoriasProductos()
    {

        DB::table('categorias_productos')->delete();

        $productos = DB::table('productos')->get();

        foreach ($productos as $product) {

            $categoria = DB::table('categorias')->inRandomOrder()->first();

            $categoria_producto = [
                [
                    'categoria_id' => $categoria->id,
                    'producto_id' => $product->id
                ]
            ];

            DB::table('categorias_productos')->insert($categoria_producto);
        }
    }

    private static function generarTagsProductos()
    {

        DB::table('tags_productos')->delete();

        $productos = DB::table('productos')->get();

        foreach ($productos as $product) {

            $tag = DB::table('tags')->inRandomOrder()->first();

            $tag_producto = [
                [
                    'tag_id' => $tag->id,
                    'producto_id' => $product->id
                ]
            ];

            DB::table('tags_productos')->insert($tag_producto);
        }
    }

    private static function generarTienda()
    {

        DB::table('shop')->delete();

        $productos = DB::table('productos')->get();

        foreach ($productos as $product) {

            $shop = [
                [
                    'usuario_id' => 1,
                    'producto_id' => $product->id
                ]
            ];

            DB::table('shop')->insert($shop);
        }
    }
}
