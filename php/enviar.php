<?php
  include 'header.php';

  if($_SESSION["entregado"])
  {
    echo "<script>
             window.location.href='listaFacturasEnviadas.php';
          </script>";
  }

    $CreditoAcumulado=0;
    $Deuda=0;
    $ci=$_SESSION["usuCI"];
    $CodFechUsu=$_SESSION["codFechUsu"];
    $Sueldo=$_SESSION["sueldo"];
    $TIPO_TRABAJO=$_SESSION["TIPO_TRABAJO"];
    $query="SELECT SUM(`IMPORTE`) FROM FACTURAS WHERE ENTREGADO=0 AND CI='$ci' and COD_FECH_USU='$codFechaSel'";
    $res=$db->query($query);
    $Importe=mysqli_fetch_array($res);
    $query="SELECT `CREDITO` FROM `FECHA_LIMITE_USU` WHERE COD_FECH_USU='$CodFechUsu'";
    $res=$db->query($query);
    $Credito=mysqli_fetch_array($res);

    if($TIPO_TRABAJO=="Principal"){
      $Impuesto=$Sueldo-8240;
    }
    else if($TIPO_TRABAJO=="Secundario"){
      $Impuesto=$Sueldo;
    }

    if($Importe[0]==""){
      $Importe[0]=0;
    }

    if($Impuesto<0){
      $Impuesto=0;
    }

    if($Importe[0]+$Credito[0]-$Impuesto<0){
      $Deuda=($Importe[0]+$Credito[0]-$Impuesto)*-1;
    }
    else {
      $CreditoAcumulado=$CreditoAcumulado+$Importe[0]+$Credito[0]-$Impuesto;
    }

?>
  <div class="contenedorIniciarSesion">
    <form action="abm.php" method="post" class="formaModificar">
      <label>CI:</label>
      <input type="text" name="txtCI" value="<?php echo $_SESSION['usuCI']; ?>" readonly>
      <label>Sueldo:</label>
      <input type="text" name="txtSueldo" value="<?php echo $_SESSION['sueldo']; ?>" id="idSueldo" readonly>
      <label>Monto Base:</label>
      <input type="text" name="txtImpuesto" value="<?php echo $Impuesto; ?>" id="idImpuesto" readonly>
      <label>Total Facturas Presentadas:</label>
      <input type="text" name="txtImporteTotal" id="idImporteTotal" value="<?php echo $Importe[0]; ?>" readonly>
      <label>Credito Anterior:</label>
      <input type="text" name="txtCredito" id="idCredito" value="<?php echo $Credito[0]; ?>" readonly>
      <label>Credito Acumulado:</label>
      <input type="text" value="<?php echo $CreditoAcumulado; ?>" readonly>
      <label>Descuento Impuesto:</label>
      <input type="text" value="<?php echo $Deuda; ?>" readonly>
      <label>CI_Recursos_Humanos:</label>
      <input type="text" name="txtCIRH" value="<?php echo $_SESSION['CI_RH']; ?>" readonly>
      <label>Sucursal:</label>
      <input type="text" value="<?php echo $_SESSION['SUCURSAL']; ?>" readonly>
      <label>Tipo Trabajo:</label>
      <input type="text" value="<?php echo $TIPO_TRABAJO; ?>" name="TIPO_TRABAJO" readonly>
      <label>Fecha Entrega:</label>
      <input type="text" name="txtFechaEntregado" value="<?php echo date("Y-m-d"); ?>" readonly>
      <div class="botones">
        <input type="submit" class="btnModificar" name="enviar" value="Enviar">
      </div>
    </form>

      <!--<input type="submit" class="btnModificar" name="enviar" value="Enviar" onclick="lol()">
      <script type="text/javascript">
        alert("lol");
      </script>-->
  </div>
<?php
  include 'footer.php';
?>
