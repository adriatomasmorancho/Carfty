@extends('layout.app')


@section('content')
    <div class="login__container">
        <form class="form-create" method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data">
            @csrf
            <h2 class="title-h2">Subir un producto</h2>
            <div class="register__email">
                <h3 class="login__element__h3">
                    Nombre</h3>
                <input class="login__element__input" name="name" type="text" value="{{ old('name') }}"></input>
                @error('name')
                    <div class="style-error">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <h3 class="login__element__h3">
                    Descripción</h3>
                <input class="login__element__input" name="description" type="textArea"
                    value="{{ old('description') }}"></input>
                @error('description')
                    <div class="style-error">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <h3 class="login__element__h3">
                    Precio</h3>
                <input class="login__element__input" name="precio" type="text" value="{{ old('precio') }}"></input>
                @error('precio')
                    <div class="style-error">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <h3 class="login__element__h3">
                    Imagen principal</h3>
                <input class="login__element__input" type="file" name="imagePrincipal" accept="image/*"
                    value="{{ old('imagePrincipal') }}">
                {{-- <img id="imagen-preview"> --}}
            </div>
            @error('image')
                <div class="style-error">{{ $message }}</div>
            @enderror

            <div>
                <h3 class="login__element__h3">
                    Imagenes secundarias</h3>
                <input class="login__element__input" type="file" name="image[]" accept="image/*">
                {{-- <img class="image-show" id="imagen-preview"> --}}
            </div>
            @error('image')
                <div class="style-error">{{ $message }}</div>
            @enderror

            <div>
                <input class="login__element__input" type="file" name="image[]" accept="image/*">
                {{-- <img class="image-show" id="imagen-preview"> --}}
            </div>

            <div>
                <input class="login__element__input" type="file" name="image[]" accept="image/*">
                {{-- <img class="image-show" id="imagen-preview"> --}}
            </div>

            <div>
                <h3 class="login__element__h3">
                    Selecciona una categoria</h3>
                <select name="categorySelect">
                    <option value="0">Categoria</option>
                    @foreach ($categorias as $key => $categoria)
                        <option value="{{ $categoria['id'] }}" class="lCat"
                            @if ($categoria['id'] == old('categorySelect')) selected @endif>
                            {{ $categoria['nombre_categoria'] }}</option>
                    @endforeach
                </select>
                @error('categorySelect')
                    <div class="style-error">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <h3 class="login__element__h3">Selección de tags</h3>
                <input type="hidden" name="seleccionados" id="seleccionados">
                @foreach ($tags as $key => $tag)
                    <button type="button" class="boton-seleccionable"
                        value="{{ $tag['id'] }}">{{ $tag['nombre_tag'] }}</button>
                @endforeach
                @error('seleccionados')
                    <div class="style-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="create__button">
                <a class="login__element__button__atras btn-create"
                    href="{{ route('shop.index', ['nombre' => auth()->user()->shop_url]) }}">Volver</a>
                <button class="login__element__button btn-create" id="enviar" type="submit">Crear</button>
            </div>

        </form>
    </div>
@endsection
