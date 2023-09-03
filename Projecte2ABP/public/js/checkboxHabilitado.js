let habilitado = document.getElementById("habilitado");

if (habilitado != null) {
    habilitado.addEventListener("change", function () {
        if (this.checked) {
            this.value = 1;
        } else {
            this.value = 0;
        }
    });
}

let destacado = document.getElementById("destacado");

if (destacado != null) {
    destacado.addEventListener("change", function () {
        if (this.checked) {
            this.value = 1;
        } else {
            this.value = 0;
        }
    });
}
