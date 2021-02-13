<?php
  include 'headerPantallaPrincipal.php';
?>
      <img src="../img/paisajeRojo.jpg" class="imagenFormUsu">
      <div class="contenedorFormUsuarioIniciarSesion">
        <form action="abm.php" method="post" class="formUsuario">
                <label>Nombre Usuario:</label><input type="text" name="txtNombreUsuario" value="" required placeholder="Nombre Usuario">
                <label>Contraseña:</label><input type="password" name="txtContraseña" value="" required placeholder="Contraseña">
                <div class="botones">
                  <input type="submit" class="btnIniSesion" value="Iniciar Sesion" name="iniciarSecion">
                  <a href="registrarUsuario.php"><input type="button" class="btnRegistrar" value="Registrar"></a>
                </div>
        </form>
      </div>

<?php
  include 'footer.php';
?>
