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
