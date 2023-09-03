import * as utils from "./funciones/utils.js";

utils.visualizarDatosLocalStorage();
utils.getProductsWithAsyncAwait();

/*
Evento al darle click al icono del carrito del producto
cambia de color, se suma o se resta el valor del carrito 
del nav y se añade un array en el storage con los productos
seleccionados.
*/
if (utils.containerProduct != null) {
    utils.containerProduct.addEventListener("click", function (e) {
        let imgCarrito = e.target.classList;

        if (e.target.classList.contains("carro")) {
            e.preventDefault();

            let idProducto = parseInt(e.target.id);

            if (e.target.classList.contains("carrito-remove")) {
                utils.removeArrayLocalStorage(idProducto, utils.arrayProductos);
            }

            if (e.target.classList.contains("carrito-add")) {
                utils.saveArrayLocalStorage(idProducto, utils.arrayProductos);
            }

            utils.toogleIcon(imgCarrito);
            utils.increment.innerText = utils.numproductos;
        }
    });
}

/*
Eliminar producto del carrito
*/

if (utils.containerProductCart != null) {
    utils.containerProductCart.addEventListener("click", function (e) {
        if (e.target.classList.contains("eliminar")) {
            let idProducto = parseInt(e.target.closest(".eliminar").id);
            let productPreu = e.target.closest(".cart-product");
            let valor =
                productPreu.lastElementChild.firstElementChild.lastElementChild
                    .firstElementChild.innerText;
            utils.removeArrayLocalStorage(idProducto, utils.arrayProductos);
            document.getElementById(idProducto).remove();

            utils.increment.innerText = utils.numproductos;
            utils.cartPorducts.innerText = `Hay ${utils.numproductos} productos en el carrito`;
            utils.totalPrecio.innerText = utils.totalPrecio.innerText - valor;

            if (utils.numproductos == 0) {
                window.location.reload();
            }
        } else {
            window.location.href =
                "/productos/" + e.target.closest(".cart-product").id;
        }
    });
}

if (document.querySelector(".cartDisplayer") != null) {
    document
        .querySelector(".cartDisplayer")
        .addEventListener("click", function (e) {
            console.log(e.target.classList);
            if (e.target.classList.contains("tramitar_pedido")) {
                window.location.href = "/login";
            }
        });
}
