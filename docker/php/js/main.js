// Validació del formulari d'incidència, evita camps buits i mostra un missatge d'alerta
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

// Amaga el formulari d'incidència al carregar la pàgina
document.getElementById("formularioIncidencia").style.display = "none";

document.getElementById("guardar_actuacio").addEventListener("submit", function(e) {
  const data_actuacio = document.getElementById('data_actuacio');
  const descripcio_actuacio = document.getElementById('descripcio_actuacio');
  const temps = document.getElementById('temps');

  if (descripcio_actuacio.value.trim() === "" || data_actuacio.value.trim() === "" || temps.value.trim() === "") {
    e.preventDefault();
    alert("Tots els camps son obligatoris.");
  }
  if (descripcio_actuacio.value.length < 20) {
        alert("La descripció ha de tenir almenys 20 caràcters.");
        descripcio_actuacio.focus();
  }
});