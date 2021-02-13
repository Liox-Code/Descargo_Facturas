
function ventanaConfirmar(){
  var fechaActual = document.getElementById("fechaActual").value;
  var fechaLimite = document.getElementById("fechaLimite").value;
  var diasDiferencia=(new Date(fechaLimite).getTime()-new Date(fechaActual).getTime())/(1000*60*60*24);
  confirm("La fecha limite es "+fechaLimite+" Te Quedan "+diasDiferencia+" para Entregar las facturas");
}
