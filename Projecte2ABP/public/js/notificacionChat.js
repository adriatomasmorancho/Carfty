document.addEventListener('DOMContentLoaded', function() {
    var cartMenuNum = document.getElementById('chat');
    var notificaciones = cartMenuNum.dataset.notificaciones;

    cartMenuNum.textContent = notificaciones;
});