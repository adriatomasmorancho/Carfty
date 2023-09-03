@extends('layout.app')

@section('title', 'Producto')

@section('content')
<div class="loader active"></div>

<div class="error-tramitar-pedido">
</div>

<div class="cart-num-products"></div>
<div class="cartDisplayer">
    <div class="container-cart">
        <div class="container-products"></div>
        <div class="container-resum fijo"></div>
    </div>
</div>
@endsection