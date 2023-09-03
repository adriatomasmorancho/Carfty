const form = document.getElementById("register-form");
let carrito = [];

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
    });
}

form.addEventListener("submit", async (event) => {
    event.preventDefault(); // Evita que se recargue la página
    // Obtener el token CSRF del encabezado HTML dentro de la función del evento submit
    const token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    try {
        if (localStorage.getItem("productCarro") != null) {
            carrito = JSON.parse(localStorage.getItem("productCarro"));
        }

        const data = {
            name: form.name.value,
            email: form.email.value,
            password: form.password.value,
            password_confirmation: form.password_confirmation.value,
            carrito: JSON.stringify(carrito),
        };

        const response = await fetch("/validar-register", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token, // Agregar el token CSRF como encabezado
            },
            body: JSON.stringify(data),
        });

        const valor = await response.json(); // Leer la respuesta del servidor como un objeto JSON
        // Si el registro fue exitoso, se redirige a la página de inicio de sesión

        if (response.ok) {
            if (valor.error) {
                addMsjError(valor.error, ".error");
                removeMsjError("", ".error-email");
                removeMsjError("", ".error-password");
                removeMsjError("", ".error-user");
            } else {
                mostrarPopup(valor.message);
                // Vaciar los campos de entrada
                form.name.value = "";
                form.email.value = "";
                form.password.value = "";
                form.password_confirmation.value = "";
            }
        } else {
            if (response.status === 422) {
                removeMsjError("", ".error");

                if (valor.errors) {
                    removeMsjError("", ".error-email");
                    removeMsjError("", ".error-password");
                    removeMsjError("", ".error-user");

                    if (valor.errors.name) {
                        addMsjError(valor.errors.name[0], ".error-user");
                    }

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
