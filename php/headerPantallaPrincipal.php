<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/pantallaPrincipal.css">
    <link rel="stylesheet" href="../css/forma_estilo.css">
    <link rel="stylesheet" href="../css/modal.css">

    <script>
      function mostrarModal() {
        document.getElementById('modal').style.display = "block";
      }

      function cerrarModal() {
        document.getElementById('modal').style.display = "none";
      }

      window.onclick = function(event) {
          if (event.target == modal) {
              modal.style.display = "none";
          }
      }
    </script>

  </head>
  <body>
    <header>
      <nav>
        <a href ="../index.php" class='botonNavImg'><img src='../img/logo.png' class='imgLogo'></a>
        <a href='iniciarSesion.php' class='botonNav'>Iniciar Sesión</a>
        <a href='registrarUsuario.php' class='botonNav'>Crear Cuenta</a>
      </nav>
    </header>
<?php
  
  if(isset($_GET['tituloMensajeModal']) && isset($_GET['mensajeModal'])){
    $tituloMensajeModal=$_GET['tituloMensajeModal'];
    $mensajeModal=$_GET['mensajeModal'];
    mensajeAdvertenciaModal($tituloMensajeModal,$mensajeModal);
  }
  function mensajeAdvertenciaModal($tituloMensajeModal,$mensajeModal) {
    echo "<div id='modal' class='modal'>
            <div class='modal-contenedor'>
              <div class='modal-cabecera'>
                <span class='boton_cerrar' onclick='cerrarModal()'>&times;</span>
                <h2 class='modal_titulo'>".$tituloMensajeModal."</h2>
              </div>
              <div class='modal-contenido'>
                <p>".$mensajeModal."</p>
              </div>
            </div>
          </div><script>mostrarModal();</script>";
  }
  ?>