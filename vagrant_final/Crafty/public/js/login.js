// let alertR = document.getElementById("messageRegister");
// let cancelAlert = document.querySelector(".messaRegister__buttonCancel");

// cancelAlert.addEventListener("click", function (e) {
//     alertR.remove();
// });

let addMsjError = (valor, nameQueryError) => {
    const error = document.querySelector(nameQueryError);
    error.innerHTML = JSON.stringify(valor);
    error.classList.add("style-error");
};

let removeMsjError = (valor, nameQueryError) => {
    const error = document.querySelector(nameQueryError);
    error.innerHTML = valor;
    error.classList.remove("style-error");
};

function mostrarPopup(mensaje) {
    const fondo = document.createElement("div");
    fondo.classList.add("popup-fondo");
    document.body.appendChild(fondo);

    const popup = document.createElement("div");
    popup.innerHTML = `${mensaje} <span class="popup-cerrar">&times;</span>`;
    popup.classList.add("popup");
    document.body.appendChild(popup);

    const cerrar = popup.querySelector(".popup-cerrar");
    cerrar.addEventListener("click", function () {
        popup.remove();
        fondo.remove();
        location.reload();
    });
}

const form = document.querySelector("#login-form");

form.addEventListener("submit", async (e) => {
    e.preventDefault(); // Evita que se recargue la página
    // Obtener el token CSRF del encabezado HTML dentro de la función del evento submit
    const token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    try {
        if (localStorage.getItem("productCarro") != null) {
            carrito = JSON.parse(localStorage.getItem("productCarro"));
        } else {
            localStorage.setItem("productCarro", JSON.stringify([]));
            carrito = JSON.parse(localStorage.getItem("productCarro"));
        }

        const data = {
            email: form.email.value,
            password: form.password.value,
            carrito: JSON.stringify(carrito),
        };

        const response = await fetch("/login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token, // Agregar el token CSRF como encabezado
            },
            body: JSON.stringify(data),
        });

        const valor = await response.json();

        if (response.ok) {
            if (valor.error) {
                addMsjError(valor.error, ".error");
                removeMsjError("", ".error-email");
                removeMsjError("", ".error-password");
            } else if (valor.errorVerify) {
                mostrarPopup(valor.errorVerify);
            } else {
                // Guardar el carrito en el LocalStorage
                localStorage.setItem("productCarro", JSON.stringify(valor));
                // Actualizar la página o redirigir al usuario a otra página
                window.location.href = "/";
            }
        } else {
            if (response.status === 422) {
                removeMsjError("", ".error");

                if (valor.errors) {

                    removeMsjError("", ".error-email");
                    removeMsjError("", ".error-password");

                    if (valor.errors.email) {
                        addMsjError(valor.errors.email[0], ".error-email");
                    }

                    if (valor.errors.password) {
                        addMsjError(
                            valor.errors.password[0],
                            ".error-password"
                        );
                    }
                }
            }
        }
    } catch (error) {
        window.location.href = "/error-generico";
    }
});
