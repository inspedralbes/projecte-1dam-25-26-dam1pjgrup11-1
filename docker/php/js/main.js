document.addEventListener("DOMContentLoaded", () => {

  const checkbox = document.querySelector('#switch input[type="checkbox"]');
  const img = document.getElementById("imagencambiante");
  //cambiar imatge
  function setTheme(isDark) {
    document.body.classList.toggle("dark-mode", isDark);

    if (img) {
      img.src = isDark ? "../img/oscuridad.png" : "../img/luz.png";
    }

    localStorage.setItem("tema", isDark ? "dark" : "light");
  }
  if (checkbox) {
    checkbox.addEventListener("change", () => {
      setTheme(checkbox.checked);
    });
  }
  // cargar tema guardat
  const savedTheme = localStorage.getItem("tema") === "dark";
  if (checkbox) {
    checkbox.checked = savedTheme;
  }
  setTheme(savedTheme);


  // Validació de formulari incidencia
  const formIncidencia = document.getElementById("guardar_incidencia");

  if (formIncidencia) {
    formIncidencia.addEventListener("submit", function (e) {
      const camps = this.querySelectorAll("input, select, textarea");

      for (let camp of camps) {
        if (camp.value.trim() === "") {
          e.preventDefault();
          alert("Tots els camps son obligatoris.");
          camp.focus();
          return;
        }

        if (
          camp.tagName === "TEXTAREA" &&
          camp.value.trim().length < 20
        ) {
          e.preventDefault();
          alert("La descripció ha de tenir com a mínim 20 caràcters.");
          camp.focus();
          return;
        }
      }
    });
  }

  // Validació de formulari actuacio
  const formActuacio = document.getElementById("formulari_actuacio");

  if (formActuacio) {
    formActuacio.addEventListener("submit", function (e) {
      const camps = this.querySelectorAll("input, select, textarea");

      for (let camp of camps) {
        if (camp.type === "hidden" || camp.type === "checkbox") continue;

        if (camp.value.trim() === "") {
          e.preventDefault();
          alert("Tots els camps son obligatoris.");
          camp.focus();
          return;
        }

        if (camp.type === "date" && camp.value.trim() === "") {
          e.preventDefault();
          alert("La data és obligatòria.");
          camp.focus();
          return;
        }

        if (
          camp.tagName === "TEXTAREA" &&
          camp.value.trim().length < 20
        ) {
          e.preventDefault();
          alert("La descripció ha de tenir com a mínim 20 caràcters.");
          camp.focus();
          return;
        }
      }
    });
  }

});