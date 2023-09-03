@extends('layout.app')


@section('content')
    <div class="login__container">
        <form class="form-create" method="POST" action="{{ route('productos.guardar') }}" enctype="multipart/form-data">
            @csrf
            <h2 class="title-h2">Editar un producto</h2>
            <input type="hidden" name="id" value="{{ $producto['id'] }}">
            <div class="register__email">
                <h3 class="login__element__h3">
                    Nombre</h3>
                <input class="login__element__input" name="name" value="{{ $producto['nombre_producto'] }}"
                    type="text"></input>
                @error('name')
                    <div class="style-error">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <h3 class="login__element__h3">
                    Descripción</h3>
                <input class="login__element__input" value="{{ $producto['descripcion'] }}" name="description"
                    type="text"></input>
                @error('description')
                    <div class="style-error">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <h3 class="login__element__h3">
                    Precio</h3>
                <input class="login__element__input" value="{{ $producto['precio'] }}" name="precio"
                    type="text"></input>
                @error('precio')
                    <div class="style-error">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <h3 class="login__element__h3">
                    Imagen principal</h3>
                <input class="login__element__input" type="file" name="imagePrincipal" accept="image/*">
                {{-- <img id="imagen-preview"> --}}
            </div>
            @error('image')
                <div class="style-error">{{ $message }}</div>
            @enderror

            <h3 class="login__element__h3">
                Imagenes secundarias</h3>

            <div>

                <h4 class="login__element__h3">
                    Imagen 1</h4>
                <input class="login__element__input" type="file" name="image[]" accept="image/*">
                {{-- <img class="image-show" id="imagen-preview"> --}}
            </div>
            @error('image')
                <div class="style-error">{{ $message }}</div>
            @enderror

            <div>
                <h4 class="login__element__h3">
                    Imagen 2</h4>
                <input class="login__element__input" type="file" name="image[]" accept="image/*">
                {{-- <img class="image-show" id="imagen-preview"> --}}
            </div>

            <div>
                <h4 class="login__element__h3">
                    Imagen 3 </h4>
                <input class="login__element__input" type="file" name="image[]" accept="image/*">
                {{-- <img class="image-show" id="imagen-preview"> --}}
            </div>

            <div class="imageProducto">
                @foreach ($imagenes as $key => $imagen)
                    @if ($imagen['imagePrincipal'] == 1)
                        <h3 class="login__element__h3">
                            Imagen principal</h3>
                        <img src="{{ env('URL_API') . $imagen['url'] }}">
                        <h3 class="login__element__h3">
                            Imagenes secundarias</h3>
                        <div class="imagenes-secundarias">
                    @else
                        <div>
                            <h4 class="login__element__h3">Imagen {{$key}}</h4>
                            <img src="{{ env('URL_API') . $imagen['url'] }}">
                        </div>
                    @endif
                @endforeach
                </div>
            </div>

            <div>
                <h3 class="login__element__h3">
                    Selecciona una categoria</h3>
                <select name="categorySelect">
                    @foreach ($categorias as $key => $categoria)
                        @if ($categoria['id'] == $categoria_producto)
                            <option value="{{ $categoria['id'] }}" class="lCat" selected>
                                {{ $categoria['nombre_categoria'] }}</option>
                        @else
                            <option value="{{ $categoria['id'] }}" class="lCat">
                                {{ $categoria['nombre_categoria'] }}</option>
                        @endif
                    @endforeach
                </select>
            </div>


            <div>
                <h3 class="login__element__h3">Selección de tags</h3>
                <input type="hidden" name="seleccionados" id="seleccionados">
                @foreach ($tags as $key => $tag)
                    @php $selected_tags = []; @endphp
                    @foreach ($tags_productos as $key => $tag_producto)
                        @if ($tag_producto->producto_id == $producto->id)
                            @php $selected_tags[] = $tag_producto->tag_id; @endphp
                        @endif
                    @endforeach
                    @if (in_array($tag['id'], $selected_tags))
                        <button type="button" class="boton-seleccionado boton-seleccionable"
                            value="{{ $tag['id'] }}">{{ $tag['nombre_tag'] }}</button>
                    @else
                        <button type="button" class="boton-seleccionable"
                            value="{{ $tag['id'] }}">{{ $tag['nombre_tag'] }}</button>
                    @endif
                @endforeach
                @error('seleccionados')
                    <div class="style-error">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label>Habilitar vista producto:</label>
                <input type="checkbox" value="{{ $producto['habilitado'] }}" name="habilitado" id="habilitado"
                    @if ($producto['habilitado'] == 1) checked @endif></input>
            </div>

            <div>
                <label>Destacado:</label>
                <input type="checkbox" value="{{ $producto['destacado'] }}" name="destacado" id="destacado"
                    @if ($producto['destacado'] == 1) checked @endif></input>
            </div>



            <div class="create__button">
                <a class="login__element__button__atras btn-create"
                    href="{{ route('shop.index', ['nombre' => auth()->user()->shop_url]) }}">Volver</a>
                <button class="login__element__button btn-create" id="enviar" type="submit">Actualizar Producto</button>
            </div>

        </form>
    </div>
@endsection
