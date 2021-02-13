<?php
  include 'header.php';
  if (isset($_GET['email'])) {
    $email=$_GET['email'];
  }
  if (isset($_GET['sucursal'])) {
    $sucursal=$_GET['sucursal'];
  }
  if (isset($_GET['nombre']) && isset($_GET['apellido'])) {
    $nombre=$_GET['nombre'];
    $apellido=$_GET['apellido'];
  }
?>
<div class="contenedorNotificacion">
  <form action="abm.php" method="post" class="formaModificar">
        <h2>Enviar mensaje empleado</h2>
        <label>Nombres Empleado</label>
        <input type="text" name="nombreEmpleado" value="<?php echo $nombre." ".$apellido; ?>" readonly>

        <label>Sucursal</label>
        <input type="text" name="sucursal" value="<?php echo $sucursal; ?>" readonly>

        <label>Correo Electrónico Empleado</label>
        <input type="text" name="correoEmpleado" value="<?php echo $email; ?>" readonly>

        <label>Asunto</label>
        <input type="text" name="asuntoMensajeEmpleado">

        <label>Mensaje</label>
        <textarea name="mensajeEmpleado" required></textarea>
          <div class="botones">
            <input type="submit" class="btnModificar" value="Enviar" name="mensajeAEmpleado">
          </div>
  </form>
</div>
<?php
  include 'footer.php';
?>
