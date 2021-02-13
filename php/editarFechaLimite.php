<?php
  include 'header.php';
    $cod=$_GET["codFechaLimModif"];
    $query="SELECT * FROM FECHA_LIMITE_RH WHERE COD_LIM='$cod'";
    $res=$db->query($query);
    $row=mysqli_fetch_array($res);
?>
<div class="contenedorForms">
  <div class="contenedorNotificacion">
    <form action="abm.php" method="post" class="formaModificar">
      <input type="hidden" name="txtCodFechLim" value="<?php echo $row[0]; ?>">
      <label>Institución:</label><input type="text" name="txtSucursal" value="<?php echo $row[2]; ?>" required>
      <label>Fecha Límite:</label><input type="DATE" name="txtFechaLimite" value="<?php echo $row[3]; ?>" required>
      <div class="botones">
        <input type="submit" class="btnModificar" value="Guardar Cambios" name="FechaLimiteRHEdit">
      </div>
    </form>
  </div>
</div>
<?php
  include 'footer.php';
?>
