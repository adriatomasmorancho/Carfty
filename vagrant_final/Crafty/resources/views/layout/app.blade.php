<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href={{ asset('css/style.css') }}>
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu" />
    <title>Crafty</title>
</head>

<body>

    <nav class="navbar">
        <!--<span class="material-symbols-outlined" id="menu-icon"> menu </span>-->
        <div class="menu-logo">
            <a href="{{ route('landing.index') }}">
                <img class="logo" src="{{ asset('Imagenes/imagenesCategoriaProductos/Logo/logo_CrafTy.png') }}"
                    alt="logo" id="logoImg" />
            </a>
        </div>
        <div class="barraBusqueda">
            <form class="formulari-busqueda" action="{{ route('productos.search') }}" method="get">
                @csrf
                <input class="search" type="search" placeholder="Buscador..." name="search" value="<?php if (isset($filtro)) {
                    echo $filtro[0];
                } ?>">
                <button class="btn-search" type="submit">Search</button>
            </form>
        </div>

        <div class="carrito-cuenta">

            <div class="carrito">
                <a href="{{ route('cart.index') }}" id="carro-url">
                    <div class="img-carrito">
                        <div class="imagencarro cart">
                            <span class="cart_menu_num" id="increment" data-action="cart-can">0</span>
                        </div>
                    </div>
                </a>
            </div>

            @if (auth()->check())
            <a href="{{ route('compras.index') }}">
                <img class="shop" src="{{ asset('Imagenes/Iconos/compras.png') }}" alt="account-icon" id="accountIcon" />
            </a>                                                                                           
            @if (auth()->user()->shop == 1)
            <a href="{{ route('ventas.index') }}">
                <img class="shop" src="{{ asset('Imagenes/Iconos/ventas.png') }}" alt="account-icon" id="accountIcon" />
            </a>
            <a href="{{ route('shop.index', ['nombre' => auth()->user()->shop_url] ) }}">
                <img class="shop" src="{{ asset('Imagenes/Iconos/tienda.png') }}" alt="account-icon" id="accountIcon" />
            </a>
            @endif
            <a href="{{ route('chat.index') }}">
                <div class="img-carrito">
                        <div class="imagenchat cart">
                        <span class="cart_menu_num" id="chat" data-notificaciones="{{ app('App\Http\Controllers\ChatController')->numeroNotificaciones() }}">0</span>
                        </div>
                </div>
                {{-- <img class="shop" src="{{ asset('Imagenes/Iconos/chat.png') }}" alt="account-icon" id="accountIcon" /> --}}
            </a> 
                @if (auth()->user()->image)
                    <div class="cuenta">
                        <img class="image-perfil"  src="{{ asset(auth()->user()->image) }}" alt="account-icon"
                            id="accountIcon">
                        <div class="cuenta-menu">
                            <div class="menu">
                                <div class="menu-options">
                                    <div class="menu_option"><a href="{{ route('user.editar') }}">Editar Perfil</a>
                                    </div>
                                    <div class="menu_option"><a id="btnLogout"
                                            href="{{ route('login.logout') }}">Logout</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="cuenta cuenta-color">
                        <div class="cuenta-letra">{{ substr(auth()->user()->name, 0, 1) }}</div>

                        <div class="cuenta-menu">
                            <div class="menu">
                                <div class="menu-options">
                                    <div class="menu_option"><a href="{{ route('user.editar') }}">Editar Perfil</a>
                                    </div>
                                    <div class="menu_option"><a id="btnLogout"
                                            href="{{ route('login.logout') }}">Logout</a></div>
                                </div>
                            </div>

                        </div>
                    </div>
                @endif
            @else
                <a href="{{ route('login.index') }}">
                    <img class="cuenta" src="{{ asset('Imagenes/Iconos/user-circle-solid-108.png') }}"
                        alt="account-icon" id="accountIcon" />
                </a>
            @endif

        </div>
    </nav>

    <div class="content">
        @yield('content')
    </div>

    <footer>
        <div class="footer-container">
          <div class="social-icons">
            <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
            <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
            <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
          </div>
          <div class="footer-info">
            <p>Miembros: Alexander García, Adrià Tomas, Joan Canals</p>
            <p>Año de creación: 2023</p>
            <p>Año actual: 2023</p>
          </div>
        </div>
      </footer>
      
    @if (auth()->check())
        <script type="module" src={{ asset('js/carritoLogin.js') }}></script>
        <script src={{ asset('js/seleccionTags.js') }}></script>
        <script src={{ asset('js/camposTienda.js') }}></script>
        <script src={{ asset('js/checkboxHabilitado.js') }}></script>
        <script src={{ asset('js/chat.js') }}></script>
        <script src={{ asset('js/notificacionChat.js') }}></script>
    @else
        <script type="module" src={{ asset('js/carritoAnonim.js') }}></script>
    @endif
    <script src={{ asset('js/menu.js') }}></script>
    <script src={{ asset('js/image.js') }}></script>

</body>

</html>
