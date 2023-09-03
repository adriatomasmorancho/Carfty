window.addEventListener("DOMContentLoaded", function () {
    let chatContainer = document.getElementById("chat-messages");

    // Hacer scroll al final del div de chat
    if (chatContainer != null) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
});
