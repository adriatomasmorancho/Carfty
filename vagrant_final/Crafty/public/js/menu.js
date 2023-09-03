const cuenta = document.querySelector('.cuenta');
const cuenta2 = document.querySelector('.cuenta2');
const menu = document.querySelector('.menu');

if(cuenta!=null){
cuenta.addEventListener('click', function() {
    menu.classList.toggle('show');
});
}

if(cuenta2!=null){
cuenta2.addEventListener('click', function() {
    menu.classList.toggle('show');
  });
}