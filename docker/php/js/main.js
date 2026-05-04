document.getElementById('form_actuacio').addEventListener('submit', function(e) {
  var camp = document.getElementById('descripcio_actuacio');
  if (camp.value.trim() === '') {
    e.preventDefault();
    alert('Es obligatori posar una descripció');
  }else if (campo.value.trim().length < 20) {
    e.preventDefault();
    alert('El text ha de tenir almenys 20 caràcters.');
  }
});