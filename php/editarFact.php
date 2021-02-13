<?php
  include 'header.php';
    $numFactura=$_GET['numFactModif'];
    $nit=$_GET['nitFactModif'];
    $numAutorizacion=$_GET['numAutModif'];
    $query="SELECT `NUM_FACTURA`,`NIT`, `NUM_AUTORISACION`, `FECHA`, `FECHA_VENCIMIENTO`, `IMPORTE`, `COD_CONTROL` FROM FACTURAS WHERE `NUM_FACTURA`=$numFactura AND `NIT`=$nit AND `NUM_AUTORISACION`=$numAutorizacion";
    $res=$db->query($query);
    $row=mysqli_fetch_array($res);
?>

<div class="contenedorForms">
  <div class="contenedorRegistroFact">
    <form action="abm.php" method="post" class="formaModificar">
      <h2>Editar Factura</h2>
        <label>Número Factura : </label><input required type="number" name="txtNumFact" value="<?php echo $row[0]; ?>" onkeyup="registroFacturaValidacion(this.value)" onchange="registroFacturaValidacion(this.value)" id=NumFact>
        <input type="hidden" name="txtNumFactAnt" value="<?php echo $_GET['numFactModif']; ?>">
        <label>C.I. : </label><input required type="number" name="txtCi" value="<?php echo $_SESSION['usuCI'];?>" readonly class="soloLeer">
        <label>NIT: </label><input required type="number" name="txtNit" value="<?php echo $row[1];?>" onkeyup="registroFacturaValidacion(this.value)" onchange="registroFacturaValidacion(this.value)" id=Nit>
        <input type="hidden" name="txtNitAnt" value="<?php echo $_GET['nitFactModif'];?>">
        <label>Número Autorización : </label><input required type="number" name="txtNumAuto" value="<?php echo $row[2];?>" onkeyup="registroFacturaValidacion(this.value)" onchange="registroFacturaValidacion(this.value)" id=NumAut><span id="mensajeErrorFacturaRegistrada"></span>
        <input type="hidden" name="txtNumAutoAnt" value="<?php echo $_GET['numAutModif'];?>">
        <label>Fecha : </label><input required type="date" name="txtFecha" value="<?php echo $row[3];?>" onkeyup="fecha(this.value)" onchange="fecha(this.value)"><span id="txtFecha"></span>
        <label>Importe : </label><input required type="number" name="txtImporte" value="<?php echo $row[5];?>" onkeyup="importe(this.value)" onchange="importe(this.value)"><span id="txtImporte"></span>
        <label>Código de Control : </label><input type="text" name="txtCodControl" value="<?php echo $row[6];?>">

      <div class="botones">
        <input type="submit" class="btnModificar" name="modificarFactura" value="Guardar Cambios" id="btnRegistrarFactura">
      </div>
    </form>
  </div>
</div>
<?php
  include 'footer.php';
?>
