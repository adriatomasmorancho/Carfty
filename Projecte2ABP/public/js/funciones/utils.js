export const containerProduct = document.querySelector(".products__click");
export const containerProductCart = document.querySelector(
    ".container-products"
);
export const increment = document.getElementById("increment");
export const cartTotalProducts = document.querySelector(".cart-num-products");
export const cartTotalPrecio = document.querySelector(".container-resum");
export const cartDisplayer = document.querySelector(".cartDisplayer");

export let totalPrecio = "";
export let cartPorducts = "";
export let jsonProductes = "";
export let numproductos = 0;
export let arrayProductos = [];
export let locationHome = "home/0";

export let addMsjError = (valor, nameQueryError) => {
    const error = document.querySelector(nameQueryError);
    error.innerHTML = JSON.stringify(valor);
    error.classList.add("style-error");
};

export let addMsjCorrect = (valor, nameQueryError) => {
    const error = document.querySelector(nameQueryError);
    error.innerHTML = JSON.stringify(valor);
    error.classList.add("style-correct");
};

export let saveArrayLocalStorage = (idProducto, arrayProductos) => {
    numproductos++;
    arrayProductos.push(idProducto);
    arrayProductos.sort();
    localStorage.setItem("productCarro", JSON.stringify(arrayProductos));
};

export let toogleIcon = (imgCarrito) => {
    imgCarrito.toggle("carrito-remove");
    imgCarrito.toggle("carrito-add");
};

export let removeArrayLocalStorage = (idProducto, arrayProductos) => {
    for (let i = 0; i < arrayProductos.length; i++) {
        if (idProducto === arrayProductos[i]) {
            arrayProductos.splice(i, 1);
        }
    }

    localStorage.setItem("productCarro", JSON.stringify(arrayProductos));
    numproductos--;
};

export let saveLocalStorageArray = (arrayLocalStorage) => {
    arrayLocalStorage.forEach((product) => {
        if (product != "") {
            numproductos++;
            arrayProductos.push(product);
        }
    });
};

/*
Se ejecuta cuando se recarga la pantalla o entras de nuevo.

Obtienes los datos del localStorage y muestras el numero de 
productos en el icono del carrito del nav y se cambia el icono 
si esta en el carrito.
*/

export let visualizarDatosLocalStorage = () => {
    jsonProductes = JSON.parse(localStorage.getItem("productCarro"));

    if (jsonProductes != null) {
        //insertamos los productos del localStorage al array
        if (jsonProductes != null && jsonProductes != "") {
            saveLocalStorageArray(jsonProductes);
        }

        //cambiar el icono si esta en el carrito ("localStorage")
        const allProducts = document.querySelectorAll(".carro");
        allProducts.forEach((elemento) => {
            for (let i = 0; i < jsonProductes.length; i++) {
                let productCarro = jsonProductes[i];
                if (parseInt(elemento.id) === productCarro) {
                    let productoActual = document.getElementById(elemento.id);
                    toogleIcon(productoActual.classList);
                }
            }
        });

        //mostramos el num de productos que hay en el carrito
        increment.innerText = numproductos;
        //cartTotalProducts.innerText = `Hay ${numproductos} productos en el carrito`;
    }
};

export const productoHtml = (
    id,
    nombre,
    precio,
    descripcion,
    vendido,
    url,
    imagen
) => {
    console.log(vendido);
    if (vendido != 1) {
        const productoContainer = `
    <div class="cart-product" id="${id}">
        <div class="cart-img"><img src="${url + imagen}"></div>
        <div class="cart-info">
            <div class="cart-titulo-precio">
                <div class="cart-titulo">
                    <p class="nombre-producto">${nombre}</p>
                </div>
                <div class="cart-precio">
                    <p class="precio-producto">${precio}</p>
                    <p>€</p>
                </div>
            </div>
            <div class="cart-descripcion">
                <p class="precio-producto">${descripcion}</p>
            </div>

            <div class="cart-eliminar">
                <button class="icon eliminar" data-precio="${precio}"
                    id="${id}"> <img class="delete eliminar"
                        src="Imagenes/iconos/delete2.png" />
                    <strong class="eliminar">Eliminar</strong>
                </button>
            </div>
        </div>
    </div>
    `;

        containerProductCart.insertAdjacentHTML("beforeend", productoContainer);
    } else {
        const productoContainer = `
        <div class="cart-product vendido" id="${id}">
            <div class="cart-img"><img src="${url + imagen}"></div>
            <div class="cart-info">
                <div class="cart-titulo-precio">
                    <div class="cart-titulo">
                        <p class="nombre-producto">${nombre}</p>
                    </div>
                    <div class="cart-precio">
                        <p class="precio-producto">${precio}</p>
                        <p>€</p>
                    </div>
                </div>
                <div class="cart-descripcion">
                    <p class="precio-producto">${descripcion}</p>
                </div>
    
                <div class="cart-eliminar">
                    <button class="icon eliminar" data-precio="${precio}"
                        id="${id}"> <img class="delete eliminar"
                            src="Imagenes/iconos/delete2.png" />
                        <strong class="eliminar">Eliminar</strong>
                    </button>
                </div>
            </div>
        </div>
        `;

        containerProductCart.insertAdjacentHTML("beforeend", productoContainer);
    }
};

export const totalCartHtml = (precioTotal) => {
    const totalCart = `
    <div class="cart-resum" id="resum-cart">
        <div class="resum-titulo">Resumen</div>
        <hr>
        <div class="resum-total">
            <p>Total</p>
            <div class="precio-carrito"> 
                <p id="cart-total">${precioTotal}</p>
                <p>€</p>
            </div>
        </div>
        <div class="resum-btns style-none-btn">
            <div class="btn"><a class="btnstyle-1 tramitar_pedido">Tramitar pedido</a></div>
            <div class="btn"><a class="btnstyle-2" href="${locationHome}">Continuar comprando</a></div>
        </div>
    </div>
    `;

    cartTotalPrecio.insertAdjacentHTML("beforeend", totalCart);
    totalPrecio = document.querySelector("#cart-total");
};

export const totalProductsHtml = (numProducts) => {
    const totalProducts = `
    <h2>Carrito</h2>
    <p class="cart-total-products">Hay ${numProducts} productos en el carrito</p>
    `;

    cartTotalProducts.insertAdjacentHTML("beforeend", totalProducts);
    cartPorducts = document.querySelector(".cart-total-products");
};

export const noHayProductos = () => {
    const noProducts = `
    <div class="container-no-products">
        <div class="no-products">
            <img class="carrito-vacio" src="Imagenes/iconos/carritoVacio.png">
            <h2 class="no-products-titulo">Carrito</h2>
            <p class="no-products-subtitulo">No hay produtos en tu carrito</p>
            <div class="resum-btns style-none-btn">
                <div class="btn"><a class="btnstyle-1" href="${locationHome}">Seguir comprando</a></div>
            </div>
        </div>
    </div>
    `;
    cartDisplayer.insertAdjacentHTML("beforebegin", noProducts);
};

export const generarContent = (productos) => {
    let precioTotal = 0;
    let productsTotal = 0;
    arrayProductos = [];
    numproductos = 0;

    if (productos.productos.length != 0) {
        productos.productos.forEach((element) => {
            productos.imagenes.forEach((imagen) => {
                if (element.id === imagen.product_id) {
                    productoHtml(
                        element.id,
                        element.nombre_producto,
                        element.precio,
                        element.descripcion,
                        element.vendido,
                        productos.url_api,
                        imagen.url
                    );

                    saveArrayLocalStorage(element.id, arrayProductos);

                    precioTotal = precioTotal + element.precio;
                    productsTotal++;
                }
            });
        });
        totalProductsHtml(productsTotal);
        totalCartHtml(precioTotal);
    } else {
        noHayProductos();
    }
};

export async function obtenerProductosDesdeLocalStorage() {
    try {
        // Obtener el token CSRF del encabezado HTML
        const token = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");

        // Obtener los datos del local storage
        const datos = JSON.parse(localStorage.getItem("productCarro"));

        // Enviar una solicitud al servidor usando fetch
        const respuesta = await fetch("/cart", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token, // Agregar el token CSRF como encabezado
            },
            body: JSON.stringify(datos),
        });

        // Obtener la respuesta del servidor como JSON
        const productos = await respuesta.json();
        localStorage.removeItem("productCarro");
        generarContent(productos);
        //Añadir error tramitar si existe.
        if (localStorage.getItem("errorTramitar") != null) {
            addMsjError(
                localStorage.getItem("errorTramitar"),
                ".error-tramitar-pedido"
            );
        }
        if (localStorage.getItem("compraExitosa") != null) {
            addMsjCorrect(
                localStorage.getItem("compraExitosa"),
                ".error-tramitar-pedido"
            );
            localStorage.removeItem("compraExitosa");
        }
    } catch (e) {
        console.error(e);
    }
}

export const getProductsWithAsyncAwait = async () => {
    const loader = document.querySelector(".loader");
    if (loader != null) {
        loader.classList.remove("active");

        await obtenerProductosDesdeLocalStorage();

        loader.classList.add("active");
    }
};

export async function guardarEliminarProduct() {
    try {
        const token = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");

        const carrito = JSON.parse(localStorage.getItem("productCarro"));

        const data = {
            carrito: JSON.stringify(carrito),
        };

        const response = await fetch("/cart", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token,
            },
            body: JSON.stringify(data),
        });
    } catch (error) {
        console.error(`Error en la respuesta del servidor: ${error}`);
    }
}

export async function tramitarPedidoLogueado() {
    try {
        const token = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");

        const carrito = JSON.parse(localStorage.getItem("productCarro"));

        const data = {
            carrito: JSON.stringify(carrito),
        };

        const response = await fetch("/tramitar_pedido", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token,
            },
            body: JSON.stringify(data),
        });

        const valor = await response.json();

        if (response.ok) {
            if (valor.errorTramitar) {
                localStorage.setItem("errorTramitar", valor.errorTramitar);
                window.location.href = "/cart";
            } else {
                localStorage.removeItem("errorTramitar");
                localStorage.removeItem("productCarro");
                localStorage.setItem("compraExitosa", valor.compraExitosa);
                window.location.href = "/cart";
            }
        }
    } catch (error) {
        console.error(`Error en la respuesta del servidor: ${error}`);
    }
}
