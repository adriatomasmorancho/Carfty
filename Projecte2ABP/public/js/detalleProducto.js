function cambiarMiniatura(miniatura) {
    let imagenPrincipal = document.getElementById('imagen-principal');
    let miniaturaActual = imagenPrincipal.src;
    imagenPrincipal.src = miniatura.src;
    miniatura.src = miniaturaActual;
    imagenPrincipal.addEventListener('error', function() {
        imagenPrincipal.src = miniaturaActual;
        miniatura.src = miniatura.src;
    });
}