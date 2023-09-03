let nombre_tienda = document.getElementById("nombre_tienda");
let logo_tienda = document.getElementById("logo_tienda");
let banner_tienda = document.getElementById("banner_tienda");
let shop = document.getElementById("shop");
let boton_actualizar = document.getElementById("actualizar");
let hidden_shop = document.getElementById("hidden-shop");

if (shop != null) {
    if (shop.checked) {
        nombre_tienda.hidden = false;
        logo_tienda.hidden = false;
        banner_tienda.hidden = false;
    }

    shop.addEventListener("change", function () {
        if (this.checked) {
            this.value = 1;
            nombre_tienda.hidden = false;
            logo_tienda.hidden = false;
            banner_tienda.hidden = false;
        } else {
            this.value = 0;
            nombre_tienda.hidden = true;
            logo_tienda.hidden = true;
            banner_tienda.hidden = true;
        }
    });
}
