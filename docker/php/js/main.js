document.addEventListener("DOMContentLoaded", () => {

  const colorSwitch = document.querySelector('#switch input[type="checkbox"]');

  function setTheme(theme) {
    if (theme === 'dark') {
      document.body.classList.add('dark-mode');
    } else {
      document.body.classList.remove('dark-mode');
    }
    localStorage.setItem('tema', theme);
  }
  function cambiaTema(ev) {
    setTheme(ev.target.checked ? 'dark' : 'light');
  }
  if (colorSwitch) {
    colorSwitch.addEventListener('change', cambiaTema);
  }
  // cargar tema guardado
  const savedTheme = localStorage.getItem('tema') || 'light';
  setTheme(savedTheme);

  if (colorSwitch) {
    colorSwitch.checked = savedTheme === 'dark';
  }

  // validación formulario incidencias
  const form = document.getElementById("guardar_incidencia");
  if (form) {
      form.addEventListener("submit", function(e) {
          const camps = this.querySelectorAll("input, select, textarea");
          for (let camp of camps) {
              if (camp.value.trim() === "") {
                  e.preventDefault();
                  alert("Tots els camps son obligatoris.");
                  camp.focus();
                  return;
              }
              if (camp.tagName === "TEXTAREA" && camp.value.trim().length < 20) {
                  e.preventDefault();
                  alert("La descripció ha de tenir com a mínim 20 caràcters.");
                  camp.focus();
                  return;
              }
          }
      });
  }
  // ocultar formulario incidencia
  const formInc = document.getElementById("formularioIncidencia");

  if (formInc) {
    formInc.style.display = "none";
  }
});