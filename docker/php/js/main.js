// Validacio del formulari d'incidencia, evita camps buits i mostra un missatge d'alerta
document.getElementById("guardar_incidencia").addEventListener("submit", function(e) {
  const inputs = this.querySelectorAll("input");

  for (let input of inputs) {
    if (input.value.trim() === "") {
      e.preventDefault();
      alert("Tots els camps son obligatoris.");
      input.focus();
      return;
    }
  }
});

// Amaga el formulari d'incidència al carregar la pagina
document.getElementById("formularioIncidencia").style.display = "none";

//Boton cambiar a modo oscuro


const colorSwitch = document.querySelector('#switch input[type="checkbox"]');
            function cambiaTema(ev){
                if(ev.target.checked){
                    document.documentElement.setAttribute('tema', 'light');
                } else {
                    document.documentElement.setAttribute('tema', 'dark');
                }
            }
            colorSwitch.addEventListener('change', cambiaTema);


