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

document.getElementById("formularioIncidencia").style.display = "none";