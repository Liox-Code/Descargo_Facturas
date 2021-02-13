<?php
  session_start();
  if( !isset($_SESSION["usuCI"]) ){
      header("location:iniciarSesion.php");
      exit();
  }
  require 'conexion.class.php';
  $db=new Conexion();
  $fechaSel="";
  $codFechaSel="";
  if (isset($_GET['fechaSeleccionada'])) {
    $fechaSel=$_GET['fechaSeleccionada'];
    $_SESSION["codFechaLimite"]=$fechaSel;
  }
  if (isset($_GET['sucursalSeleccionada'])) {
    $_SESSION["SUCURSAL"]=$_GET['sucursalSeleccionada'];
  }
  if(isset($_SESSION["codFechaLimite"]) ){
    $codFechaSel=$_SESSION["codFechaLimite"];
  }
  if(isset($_SESSION["usuCI"]) ){
    $ci=$_SESSION["usuCI"];
  }
  if(isset($_GET["codigoLim"]) ){
    $_SESSION["COD_LIM"]=$_GET["codigoLim"];
  }
  if(isset($_SESSION["COD_LIM"]) ){
    $CodLim=$_SESSION["COD_LIM"];
  }

  $query="SELECT f.FECHA_LIMITE_RH,fusu.ENTREGADO,fusu.COD_FECH_USU
  FROM FECHA_LIMITE_RH f INNER JOIN FECHA_LIMITE_USU fusu
  ON f.COD_LIM = fusu.COD_LIM WHERE fusu.CI='$ci' and fusu.COD_FECH_USU='$codFechaSel'";
  $res=$db->query($query);
  $row=mysqli_fetch_array($res);
  $_SESSION["fechaLimite"]=$row[0];
  $_SESSION["entregado"]=$row[1];
  $_SESSION["codFechUsu"]=$row[2];

  $CodFechLimUsu=$_SESSION["codFechUsu"];
  $FechaLimite=date("Y-m-d", strtotime('+1 month -1 day'));
  $partes_ruta = pathinfo($_SERVER['REQUEST_URI']);
  if(date("Y-m-d")>$_SESSION["fechaLimite"] && isset($_SESSION["COD_LIM"])){
    $query="UPDATE FECHA_LIMITE_RH SET `FECHA_LIMITE_RH`='$FechaLimite' WHERE `COD_LIM`='$CodLim'";
    $db->query($query);
    $query="UPDATE FECHA_LIMITE_USU SET `ENTREGADO`= 0 WHERE `COD_FECH_USU`='$CodFechLimUsu'";
    $db->query($query);
  }
  if($partes_ruta['filename'] != 'notificacion' && $partes_ruta['filename'] != 'editarNotificacion' && $partes_ruta['filename'] != 'editarUsuario' && $_SESSION["tipo"]!='RecursosHumano' && $row[0]==""){
    header("location:notificacion.php?fechaSeleccionada=$codFechaSel");
  }

?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/tablas.css">
    <link rel="stylesheet" href="../css/iniciarSesion.css">
    <link rel="stylesheet" href="../css/validacion.css">
    <link rel="stylesheet" href="../css/modal.css">
    <script src="../js/ventanaEmergente.js"></script>
    <script src="../js/validar.js"></script>

    <script>

    var validarRegistroFacturaValidacion =true;
    var validarFecha =true;
    var validarImporte =true;

    function registroFacturaValidacion(str) {
      var Nit =document.getElementById('Nit').value;
      var NumFact =document.getElementById('NumFact').value;
      var NumAut =document.getElementById('NumAut').value;
        if (str.length == 0) {
            document.getElementById("mensajeErrorFacturaRegistrada").innerHTML = "";
            return;
        }
        else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    if(this.responseText==""){
                      validarRegistroFacturaValidacion =true;
                    }
                    else {
                      validarRegistroFacturaValidacion =false;
                    }
                    desactivarCrearFactura();
                  document.getElementById("mensajeErrorFacturaRegistrada").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET", "validar.php?Nit="+Nit+"&&NumFact="+NumFact+"&&NumAut="+NumAut, true);
            xmlhttp.send();
        }
    }
    function fecha(str) {
        if (str.length == 0) {
            document.getElementById("txtFecha").innerHTML = "";
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    if(this.responseText==""){
                      validarFecha =true;
                    }
                    else {
                      validarFecha =false;
                    }
                    desactivarCrearFactura();
                    document.getElementById("txtFecha").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET", "validar.php?fecha="+str, true);
            xmlhttp.send();
        }
    }

    function importe(str) {
        if (str.length == 0) {
            document.getElementById("txtImporte").innerHTML = "";
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    if(this.responseText==""){
                      validarImporte =true;
                    }
                    else {
                      validarImporte =false;
                    }
                    desactivarCrearFactura();
                    document.getElementById("txtImporte").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET", "validar.php?importe="+str, true);
            xmlhttp.send();
        }
    }

    function desactivarCrearFactura(){
      if(validarRegistroFacturaValidacion==true && validarFecha==true && validarImporte==true){
        document.getElementById("btnRegistrarFactura").disabled  = false;
      }
      else {
        document.getElementById("btnRegistrarFactura").disabled  = true;
      }
    }

    function cirh(str,sucursalSel) {
        if (str.length == 0) {
            document.getElementById("SucursalesRH").innerHTML = "";
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                  if(this.responseText=="<span class='validacionIncorrecta'>No existe ese usuario de recursos Humanos</span>"){
                    if(document.getElementById("btnNotificacionRegistrar").disabled != true){
                      document.getElementById("btnNotificacionRegistrar").disabled  = true;
                    }
                  }
                  else{
                    if(document.getElementById("btnNotificacionRegistrar").disabled != false){
                    document.getElementById("btnNotificacionRegistrar").disabled  = false;
                    }
                  }
                  document.getElementById("SucursalesRH").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET", "validar.php?SucursalesRH="+str+"&SucursalSel="+sucursalSel, true);
            xmlhttp.send();
        }
    }

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
    <?php
        if($_SESSION['notificado']==0 && $_SESSION["tipo"]=='Usuario'){
          $_SESSION['notificado']=1;
          $ventanaConfirmar="onload='ventanaConfirmar()'";
        }
        else {
          $ventanaConfirmar="";
        }
    ?>
  </head>
  <body <?php echo $ventanaConfirmar; ?> class="cuerpoHeader">
    <input type="hidden" id="fechaActual" value="<?php echo date("Y-m-d"); ?>">
    <input type="hidden" id="fechaLimite" value="<?php echo $_SESSION["fechaLimite"]; ?>">
      <?php

      if($_SESSION["tipo"]=='Usuario'){
        echo "<div class='informacion'>";
        if ($_SESSION["fechaLimite"]!=null) {
          $path = parse_url($partes_ruta['filename'], PHP_URL_PATH);
          echo basename($path);
          if(basename($path)=='listaFacturas.php' || basename($path)=='listaFacturas'){
            $CreditoReunido=0;
            $DescuentoImpuesto=0;
            $CreditoAnterior=0;
            $SueldoUsuario=$_SESSION["sueldo"];
            $tipoTrabajoUsuario=$_SESSION["TIPO_TRABAJO"];
            $query="SELECT SUM(`IMPORTE`) FROM FACTURAS WHERE ENTREGADO=0 AND CI='$ci' and COD_FECH_USU='$CodFechLimUsu'";
            $res=$db->query($query);
            $TotalFacturasPresentadas=mysqli_fetch_array($res);
            $query="SELECT `CREDITO` FROM `FECHA_LIMITE_USU` WHERE COD_FECH_USU='$CodFechLimUsu'";
            $res=$db->query($query);
            $CreditoAnterior=mysqli_fetch_array($res);

            if($tipoTrabajoUsuario=="Principal"){
              $MontoBase=$SueldoUsuario-8240;
            }
            else if($tipoTrabajoUsuario=="Secundario"){
              $MontoBase=$SueldoUsuario;
            }

            if($TotalFacturasPresentadas[0]==""){
              $TotalFacturasPresentadas[0]=0;
            }

            if($MontoBase<0){
              $MontoBase=0;
            }

            if($TotalFacturasPresentadas[0]+$CreditoAnterior[0]-$MontoBase<0){
              $DescuentoImpuesto=($TotalFacturasPresentadas[0]+$CreditoAnterior[0]-$MontoBase)*-1;
            }
            else {
              $CreditoReunido=$CreditoReunido+$TotalFacturasPresentadas[0]+$CreditoAnterior[0]-$MontoBase;
            }
          }
          if($_SESSION["entregado"])
          {
            echo "<label class='envioCerrado'>Ya se envió cerrado</label>";
          }
          else{
            echo "<label class='envioAbierto'>Abierto</label>";
          }

          if(isset($_SESSION['SUCURSAL'])){
            echo "<label class='lblSucursal'>".$_SESSION['SUCURSAL']."</label>";
          }
          else{
            echo "<label class='lblSucursal'>No se selecciono sucursal</label>";
          }

          if(date("Y-m-d")<$_SESSION["fechaLimite"]){
            echo "<label class='lblFechaLimite'>Fecha Limite : ".$_SESSION["fechaLimite"]."</label>";
          }
          else if(date("Y-m-d")==$_SESSION["fechaLimite"]){
            echo "<label class='lblFechaLimite'>Fecha Limite : ".$_SESSION["fechaLimite"]." Ultimo dia"."</label>";
          }
        }
        else if($_SESSION["fechaLimite"]==null){
          echo "<label class='lblFechaLimite'>Seleccione una sucursal</label>";
        }
        echo "</div>";
      }
       ?>
    <header>
      <nav>
        <?php
          if($_SESSION["tipo"]=='Usuario'){
            echo "<a href='registrarFact.php' class='botonNav'>Registrar</a>";
            echo "<a href='listaFacturasEnviadas.php' class='botonNav'>Facturas Enviadas</a>";
            echo "<a href='listaFacturas.php' class='botonNav'>Lista Facturas</a>";
          }
          if($_SESSION["tipo"]=='RecursosHumano'){
            echo "<a href='fechaLimite.php' class='botonNav'>Fecha Límite</a>";
            echo "<a href='notificarEmpleados.php' class='botonNav'>Notificar empleados</a>";
            echo "<a href='abm.php?salir=si' class='botonNav'>Cerrar</a>";
          }
         ?>
        <?php
          if($_SESSION["tipo"]=='Usuario'){
            echo "<div class='dropdown'>";
            echo "<button class='dropbtn'>Opciones</button>";
            echo "<div class='dropdown-content'>";
            echo "<a href='editarUsuario.php' class='dropdownBtn'>Editar Usuario</a>";
            echo "<a href=\"notificacion.php?fechaSeleccionada=$codFechaSel\" class='dropdownBtn'>Registrar Institución</a>";
            echo "<a href='abm.php?salir=si' class='dropdownBtn'>Cerrar</a>";
            echo "</div></div>";
          }
        ?>
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
  <div class="cuerpoWeb">
    <div class="cuerpoWebElementos">
