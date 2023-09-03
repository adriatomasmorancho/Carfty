let botones = document.getElementsByClassName("boton-seleccionable");
let inputSeleccionados = document.getElementById("seleccionados");
let boton_formulario = document.getElementById("enviar");
let valoresSeleccionados = [];



for (let i = 0; i < botones.length; i++) {
  botones[i].addEventListener("click", function() {
    if (this.classList.contains("boton-seleccionado")) {
      this.classList.remove("boton-seleccionado");
    } else {
      this.classList.add("boton-seleccionado");
    } 
  });

 boton_formulario.addEventListener("click", function() {

    valoresSeleccionados = [];

    añadiendovaloresSeleccionados();

  });

  let añadiendovaloresSeleccionados = () => {
    for (let j = 0; j < botones.length; j++) {
      if (botones[j].classList.contains("boton-seleccionado")) {
        valoresSeleccionados.push(botones[j].value);
      }
      inputSeleccionados.value = valoresSeleccionados.join(",");
    }
    }

 





}