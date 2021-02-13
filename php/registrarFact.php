<?php
  include 'header.php';
  $NumAutorizacion="";
  if(isset($_SESSION["numAutorizacion"]) ){
    $NumAutorizacion=$_SESSION["numAutorizacion"];
  }
?>
<div class="contenedorForms">
  <div class="contenedorRegistroFact">
    <form action="abm.php" method="post" class="formaModificar">
        <H2>Registrar Factura</H2>
        <label>C.I. : </label><input type="text" name="txtCi" value="<?php echo $_SESSION['usuCI']; ?>" readonly>
        <label>NIT : </label><input required type="number" name="txtNit" placeholder="Ingresar NIT" value="" onkeyup="registroFacturaValidacion(this.value)" onchange="registroFacturaValidacion(this.value)" id=Nit>
        <label>Número Factura : </label><input required type="number" name="txtNumFact" placeholder="Ingresar Numero Factura" value="" onkeyup="registroFacturaValidacion(this.value)" onchange="registroFacturaValidacion(this.value)" id=NumFact>
        <label>Número Autorización : </label><input required type="number" name="txtNumAuto" placeholder="Ingresar Numero Autorización" value="<?php echo $NumAutorizacion; ?>" onkeyup="registroFacturaValidacion(this.value)" onchange="registroFacturaValidacion(this.value)" id=NumAut><span id="mensajeErrorFacturaRegistrada"></span>
        <label>Fecha : </label><input required type="date" name="txtFecha" value="" onkeyup="fecha(this.value)" onchange="fecha(this.value)"><span id="txtFecha"></span>
        <label>Importe : </label><input required type="number" name="txtImporte" placeholder="Ingresar Importe" value=""   onkeyup="importe(this.value)" onchange="importe(this.value)"><span id="txtImporte"></span>
        <label>Código de Control : </label><input type="text" name="txtCodCon" placeholder="Ingresar Código de Control" value="">
      <div class="botones">
        <input type="submit" class="btnModificar" name="registrarFactura" id="btnRegistrarFactura" value="Registrar">
      </div>
    </form>
  </div>
</div>
<?php
  include 'footer.php';
?>
