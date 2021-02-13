<?php
  !isset($_POST) ? die('acceso denegado'):'';
  require 'conexion.class.php';
  $db=new Conexion();
  session_start();
  if(isset($_POST['registrar'])){
    $ci=$_POST['txtCi'];
    $nombre=$_POST['txtNombre'];
    $apellido=$_POST['txtApellido'];
    $nombreUsuario=$_POST['txtNombreUsuario'];
    $contraseña=$_POST['txtContraseña'];
    $confirmarContraseña=$_POST['txtConfirmarContraseña'];
    $email=$_POST['txtEmail'];
    $tipo=$_POST['txtTipo'];

    if($contraseña==$confirmarContraseña && $ci!="" && $nombre!="" && $apellido!="" && $nombreUsuario!="" && $contraseña!="" && $email!="" && $tipo!=""){
      $query="INSERT INTO `USUARIOS` (`CI`, `NOMBRE`, `APELLIDO`, `NOMBRE_USUARIO`, `CLAVE`, `EMAIL`, `TIPO`) VALUES ('$ci','$nombre','$apellido','$nombreUsuario','$contraseña','$email','$tipo')";
      $db->query($query);
      if($db->affected_rows<0){
        echo "<script>
                 window.location.href='registrarUsuario.php?tituloMensajeModal=Error registrar&mensajeModal=No se registro Cuenta.';
              </script>";
      }else {
        echo "<script>
                 window.location.href='iniciarSesion.php?tituloMensajeModal=Cuenta Creada Exitosamente&mensajeModal=Su cuenta fue creada exitosamente.';
              </script>";
      }
    }
    else {
      echo "<script>
               window.location.href='registrarUsuario.php?tituloMensajeModal=Error registrar&mensajeModal=Contraseña diferente ingrese de nuevo.';
            </script>";
    }
  }
  if(isset($_POST['iniciarSecion'])){
    $nombreUsuario=$_POST['txtNombreUsuario'];
    $contraseña=$_POST['txtContraseña'];
    $query="SELECT `NOMBRE_USUARIO`,`CLAVE`,`CI`, `TIPO` FROM `USUARIOS` WHERE `NOMBRE_USUARIO`='$nombreUsuario' and `CLAVE`='$contraseña'";
    $res=$db->query($query);
    $datos=mysqli_num_rows($res);
    if($db->affected_rows<=0){
      echo "<script>
               window.location.href='registrarUsuario.php?tituloMensajeModal=Error Inicio Sesión&mensajeModal=Nombre de Usuario o clave erróneos.';
            </script>";
    }
    else{
      $row=mysqli_fetch_array($res);
      $_SESSION['usuCI']=$row[2];
      $_SESSION['tipo']=$row[3];
      $_SESSION['notificado']=0;
      if($row[3]=="Usuario"){
        header("location: listaFacturas.php");
      }
      else {
        header("location: fechaLimite.php");
      }
    }
  }
  if(isset($_POST['modificar'])){
    $ciAnterior=$_SESSION['usuCI'];
    $ci=$_POST['txtCi'];
    $nombre=$_POST['txtNombre'];
    $apellido=$_POST['txtApellido'];
    $query="UPDATE `USUARIOS` SET `CI`='$ci', `NOMBRE`='$nombre', `APELLIDO`='$apellido' WHERE `CI`='$ciAnterior'";
    if($ci!="" && $nombre!="" && $apellido!=""){
      $db->query($query);
      if($db->affected_rows<0){
        echo "<script>
                  window.location.href='editarUsuario.php?tituloMensajeModal=Error Modificación&mensajeModal=Error en la modificación de la cuenta.';
              </script>";
      }
      else{
        $_SESSION['usuCI']=$ci;
        $query="UPDATE `FACTURAS` SET `CI`='$ci' WHERE `CI`='$ciAnterior'";
        $db->query($query);
        $query="UPDATE `FECHA_LIMITE_USU` SET `CI`='$ci' WHERE `CI`='$ciAnterior'";
        $db->query($query);
        echo "<script>
                  window.location.href='notificacion.php?tituloMensajeModal=Modificación Exitosa&mensajeModal=Cuenta modificada exitosamente.';
              </script>";
      }
    }
    else{
      echo "<script>
                window.location.href='editarUsuario.php?tituloMensajeModal=Error Modificación&mensajeModal=No lleno todos los campos.';
            </script>";
    }
  }

  if(isset($_POST['registrarFactura'])){
    $Ci=$_POST['txtCi'];
    $Nit=$_POST['txtNit'];
    $NumFactura=$_POST['txtNumFact'];
    $NumAutorisacion=$_POST['txtNumAuto'];
    $Fecha=$_POST['txtFecha'];
    $FechaInicial=new DateTime($Fecha);;
    $diferenciaDias=$FechaInicial->modify('+120 day');
    $FechaVencimiento=$diferenciaDias->format('Y-m-d');
    $Importe=$_POST['txtImporte'];
    $CodCon=$_POST['txtCodCon'];

    $ci=$_SESSION["usuCI"];
    $codFechaLimite=$_SESSION["codFechaLimite"];
    $query="SELECT f.FECHA_LIMITE_RH
    FROM FECHA_LIMITE_RH f INNER JOIN FECHA_LIMITE_USU fusu
    ON f.COD_LIM = fusu.COD_LIM WHERE CI='$ci'";
    $res=$db->query($query);
    $row=mysqli_fetch_array($res);

    if ($Fecha<$FechaVencimiento && $Fecha<=$_SESSION["fechaLimite"] && $Importe>=0) {
      $query="INSERT INTO `FACTURAS`(`NUM_FACTURA`, `CI`, `NIT`, `NUM_AUTORISACION`, `FECHA`, `FECHA_VENCIMIENTO`, `IMPORTE`, `ENTREGADO`, `VALIDO`,`COD_CONTROL`,`COD_FECH_USU`) VALUES ('$NumFactura','$Ci','$Nit','$NumAutorisacion','$Fecha','$FechaVencimiento',$Importe,0,1,'$CodCon',$codFechaLimite)";
      $db->query($query);
      if($db->affected_rows<0){
        echo "<script>
                 window.location.href='registrarFact.php?tituloMensajeModal=Error Registro&mensajeModal=No se registro.Hubo un error';
              </script>";
      }
      else{
        $_SESSION["numAutorizacion"]=$NumAutorisacion;
        echo "<script>
                 window.location.href='listaFacturas.php?tituloMensajeModal=Registro Exitoso&mensajeModal=Registro Exitoso';
              </script>";
      }
    }
    else {
      echo "<script>
               window.location.href='registrarFact.php?tituloMensajeModal=Error Registro&mensajeModal=Fecha vencimiento mayor que fecha';
            </script>";
    }
  }

  if(isset($_POST['modificarFactura'])){
    $Ci=$_POST['txtCi'];
    $Nit=$_POST['txtNit'];
    $NitAnt=$_POST['txtNitAnt'];
    $NumFactura=$_POST['txtNumFact'];
    $NumFacturaAnt=$_POST['txtNumFactAnt'];
    $NumAutorisacion=$_POST['txtNumAuto'];
    $NumAutorisacionAnt=$_POST['txtNumAutoAnt'];
    $Fecha=$_POST['txtFecha'];
    $FechaInicial=new DateTime($Fecha);;
    $diferenciaDias=$FechaInicial->modify('+120 day');
    $FechaVencimiento=$diferenciaDias->format('Y-m-d');
    $Importe=$_POST['txtImporte'];
    $CodControl=$_POST['txtCodControl'];
    if($CodControl==""){
      $CodControl='-';
    }
    if($Ci!="" && $Nit!="" && $NumFactura!="" && $NumAutorisacion!="" && $Fecha!="" && $FechaVencimiento!="" && $Importe!="" && $Fecha<$FechaVencimiento  && $Fecha<=$_SESSION["fechaLimite"] && $FechaVencimiento>=$_SESSION["fechaLimite"]  && $Importe>=0){
      $query="UPDATE FACTURAS SET `NUM_FACTURA`='$NumFactura',`CI`='$Ci',`NIT`='$Nit',`NUM_AUTORISACION`='$NumAutorisacion',`FECHA`='$Fecha',`FECHA_VENCIMIENTO`='$FechaVencimiento',`IMPORTE`=$Importe ,`COD_CONTROL`='$CodControl' WHERE `NUM_FACTURA`='$NumFacturaAnt' && `NIT`='$NitAnt' && `NUM_AUTORISACION`='$NumAutorisacionAnt'";
      $db->query($query);
      if($db->affected_rows<0){
        header("location: editarFact.php?numFactModif=$NumFacturaAnt&nitFactModif=$NitAnt&numAutModif=$NumAutorisacionAnt&tituloMensajeModal=Error en Modificación&mensajeModal=No existe esta factura");
      }
      else{
        header("location: listaFacturas.php?tituloMensajeModal=Modificación Exitosa&mensajeModal=La factura se modifico exitosamente");
      }
    }
    else{
      header("location: editarFact.php?numFactModif=$NumFacturaAnt&nitFactModif=$NitAnt&numAutModif=$NumAutorisacionAnt&tituloMensajeModal=Error en Modificación&mensajeModal=No lleno todos los campos");
    }
  }

  if(isset($_GET['numFactElim'])){
    $NumFacturaElim=$_GET['numFactElim'];
    $NitElim=$_GET['nitFactElim'];
    $NumAutorisacionElim=$_GET['numAutElim'];
    $query="DELETE FROM FACTURAS WHERE `NUM_FACTURA`='$NumFacturaElim' AND `NIT`='$NitElim' AND `NUM_AUTORISACION`='$NumAutorisacionElim'";
    $db->query($query);
    if($db->affected_rows<0){
      header("location: listaFacturas.php?tituloMensajeModal=Error Eliminada&mensajeModal=La factura no fue eliminada");
    }
    else{
      header("location: listaFacturas.php");
    }
  }

  if(isset($_POST['FechaLimiteRH'])){
    $Ci=$_SESSION["usuCI"];
    $Tipo=$_SESSION["tipo"];
    $Sucursal=$_POST['txtSucursal'];
    $FechaLimite=$_POST['txtFechaLimite'];
    $query="SELECT `NOMBRE_USUARIO`,`CLAVE`,`CI`, `TIPO` FROM `USUARIOS` WHERE `CI`='$Ci' and `TIPO`='$Tipo'";
    $res=$db->query($query);
    $datos=mysqli_num_rows($res);
    if($db->affected_rows<=0){
      header("location: fechaLimite.php?No existe ese ci con ese ci");
    }
    else {
      $query="INSERT INTO `FECHA_LIMITE_RH`(`CIRH`, `SUCURSAL`, `FECHA_LIMITE_RH`) VALUES ('$Ci','$Sucursal','$FechaLimite')";
      $db->query($query);
      if($db->affected_rows<0){
        header("location: fechaLimite.php?tituloMensajeModal=Error Registro Fecha Limite&mensajeModal=Fecha limite no se registro");
      }
      else{
        header("location: fechaLimite.php");
      }
    }
  }

  if(isset($_GET['codFechaLimElim'])){
    $CodFechaLimite=$_GET['codFechaLimElim'];
    $query="DELETE FROM FECHA_LIMITE_RH WHERE `COD_LIM`='$CodFechaLimite'";
    $db->query($query);
    if($db->affected_rows<0){
      header("location: fechaLimite.php?Hubo un error");
    }
    else{
      $query="DELETE FROM FECHA_LIMITE_RH WHERE `COD_LIM`='$CodFechaLimite'";
      $db->query($query);
      if($db->affected_rows<0){
        header("location: fechaLimite.php?Hubo un error 2");
      }
      else{
        $query="DELETE FROM FECHA_LIMITE_USU WHERE `COD_LIM`='$CodFechaLimite'";
        $db->query($query);
        header("location: fechaLimite.php");
      }
    }
  }

  if(isset($_GET['numFactElim'])){
    $NumFacturaElim=$_GET['numFactElim'];
    $NitElim=$_GET['nitFactElim'];
    $NumAutorisacionElim=$_GET['numAutElim'];
    $query="DELETE FROM FACTURAS WHERE `NUM_FACTURA`='$NumFacturaElim' AND `NIT`='$NitElim' AND `NUM_AUTORISACION`='$NumAutorisacionElim'";
    $db->query($query);
    if($db->affected_rows<0){
      header("location: listaFacturas.php?Hubo un error");
    }
    else{
      header("location: listaFacturas.php");
    }
  }

  if(isset($_POST['FechaLimiteUsuario'])){
    $CI=$_SESSION["usuCI"];
    $CI_RH=$_POST["txtCi"];
    $Sucursal="";
    if(isset($_POST['txtSucursal'])){
      $Sucursal=$_POST['txtSucursal'];
    }
    $TIPO_TRABAJO=$_POST['TIPO_TRABAJO'];
    $Sueldo=$_POST['txtSueldo'];
    $codFechaSel=$_SESSION["codFechaLimite"];
    $query="SELECT `NOMBRE_USUARIO` FROM `USUARIOS` WHERE `CI`='$CI_RH' and `TIPO`='RecursosHumano'";
    $res=$db->query($query);
    $datos=mysqli_num_rows($res);
    if($db->affected_rows<=0){
      echo "<script>
               window.location.href='notificacion.php?fechaSeleccionada=$codFechaSel&No existe ese ci rh con ese ci';
            </script>";
    }
    else {
      $query="SELECT `COD_LIM` FROM `FECHA_LIMITE_RH` WHERE `CIRH`='$CI_RH' and `SUCURSAL`='$Sucursal'";
      $res=$db->query($query);
      $row=mysqli_fetch_array($res);
      $COD_LIM=$row[0];
      if($COD_LIM!=""){
        $query="INSERT INTO `FECHA_LIMITE_USU`(`CI`, `COD_LIM`, `ENTREGADO`, `SUELDO`,`CREDITO`,`TIPO_TRABAJO`) VALUES ('$CI','$COD_LIM',0,$Sueldo,0,'$TIPO_TRABAJO')";
        $db->query($query);
        if($db->affected_rows<0){
          echo "<script>
                   window.location.href='notificacion.php?fechaSeleccionada=$codFechaSel&Hubo un error';
                </script>";
        }
        else{
          echo "<script>
                   window.location.href='notificacion.php?fechaSeleccionada=$codFechaSel';
                </script>";
        }
      }
      else {
        echo "<script>
                 window.location.href='notificacion.php?fechaSeleccionada=$codFechaSel&No_existe_fecha_limite';
              </script>";
      }
    }
  }

  if(isset($_POST['FechaLimiteRHEdit'])){
    $CodLim=$_POST['txtCodFechLim'];
    $Sucursal=$_POST['txtSucursal'];
    $FechaLimite=$_POST['txtFechaLimite'];

    if($Sucursal!="" && $FechaLimite!=""){
      $query="UPDATE FECHA_LIMITE_RH SET `SUCURSAL`='$Sucursal',`FECHA_LIMITE_RH`='$FechaLimite' WHERE `COD_LIM`='$CodLim'";
      $db->query($query);
      if($db->affected_rows<0){
        header("location: editarfechaLimite.php?codFechaLimModif=$CodLim");
      }
      else{
        $query="UPDATE `FECHA_LIMITE_USU` SET `ENTREGADO`=0 WHERE `COD_LIM`='$CodLim'";
        $db->query($query);
        header("location: fechaLimite.php");
      }
    }
    else{
      header("location: editarFechaLimite.php?codFechaLimModif=$CodLim");
    }
  }

  if(isset($_POST['EditarFechaLimiteUsuario'])){
    $CI=$_SESSION["usuCI"];
    $CI_RH=$_POST["txtCi"];
    $COD_FECH_LIM_USU=$_POST["CodUsuFechLim"];
    $Sucursal="";
    if(isset($_POST['txtSucursal'])){
      $Sucursal=$_POST['txtSucursal'];
    }
    $Sueldo=$_POST['txtSueldo'];
    $TIPO_TRABAJO=$_POST['TIPO_TRABAJO'];
    $codFechaSel=$_SESSION["codFechaLimite"];
    $query="SELECT `NOMBRE_USUARIO` FROM `USUARIOS` WHERE `CI`='$CI_RH' and `TIPO`='RecursosHumano'";
    $res=$db->query($query);
    if($db->affected_rows<=0){
      echo "<script>
               window.location.href='notificacion.php?fechaSeleccionada=$codFechaSel&No existe ese rh con ese ci';
            </script>";
    }
    else {
      $query="SELECT `COD_LIM` FROM `FECHA_LIMITE_RH` WHERE `CIRH`='$CI_RH' and `SUCURSAL`='$Sucursal'";
      $res=$db->query($query);
      $row=mysqli_fetch_array($res);
      $COD_LIM=$row[0];
      $query="UPDATE `FECHA_LIMITE_USU` SET `COD_LIM`='$COD_LIM',`SUELDO`='$Sueldo',`TIPO_TRABAJO`='$TIPO_TRABAJO' WHERE `COD_FECH_USU`='$COD_FECH_LIM_USU'";
      $db->query($query);
      $_SESSION["TIPO_TRABAJO"]=$TIPO_TRABAJO;
      if($db->affected_rows<0){
        echo "<script>
                 window.location.href='notificacion.php?fechaSeleccionada=$codFechaSel&No existe ese rh con ese ci';
              </script>";
      }
      else{
        echo "<script>
                 window.location.href='notificacion.php?fechaSeleccionada=$codFechaSel';
              </script>";
      }
    }
  }

  if(isset($_GET['CodFechUsuElim'])){
    $COD_FECH_LIM_USU=$_GET["CodFechUsuElim"];
    $codFechaSel=$_SESSION["codFechaLimite"];
    $query="DELETE FROM FECHA_LIMITE_USU WHERE `COD_FECH_USU`='$COD_FECH_LIM_USU'";
    $db->query($query);
    if($db->affected_rows<0){
      echo "<script>
               window.location.href='notificacion.php?fechaSeleccionada=$codFechaSel&Hubo un error';
            </script>";
    }
    else{
      header("location: notificacion.php?fechaSeleccionada=$codFechaSel");
    }
  }

  if(isset($_POST['enviar'])){
    $CreditoAcumulado=0;
    $Deuda=0;
    $Ci=$_POST['txtCI'];
    $Impuesto=$_POST['txtImpuesto'];
    $Importe=$_POST['txtImporteTotal'];
    $Credito=$_POST['txtCredito'];
    $Sueldo=$_POST['txtSueldo'];
    $CI_RH=$_POST['txtCIRH'];
    $FechaEntregado=$_POST['txtFechaEntregado'];
    $FechaLimite=$_SESSION["fechaLimite"];
    $CodFechUsu=$_SESSION["codFechUsu"];
    $codFechaSel=$_SESSION["codFechaLimite"];
    $COD_LIM=$_SESSION["COD_LIM"];
    $TIPO_TRABAJO=$_POST['TIPO_TRABAJO'];

    if($Importe+$Credito-$Impuesto<0){
      $Deuda=($Importe+$Credito-$Impuesto)*-1;
    }
    else {
      $CreditoAcumulado=$CreditoAcumulado+$Importe+$Credito-$Impuesto;
    }

    $query="SELECT * FROM USUARIOS where `CI`='$CI_RH' AND TIPO='RecursosHumano'";
    $db->query($query);

    if($db->affected_rows<=0){
      header("location: enviar.php?No_existe_ese_rh");
    }
    else {
      $query="SELECT * FROM FACTURAS f INNER JOIN FECHA_LIMITE_USU fu on fu.COD_FECH_USU=f.COD_FECH_USU INNER JOIN FECHA_LIMITE_RH fr on fr.COD_LIM=fu.COD_LIM where f.CI='$Ci' AND f.ENTREGADO=0 and fr.COD_LIM='$COD_LIM'";
      $db->query($query);
      if($db->affected_rows<=0 && $Credito<=0){
        echo "<script>
                 window.location.href='enviar.php?tituloMensajeModal=Error Envío&mensajeModal=No existen facturas por entregar';
              </script>";
      }
      else{
          $query="INSERT INTO `ENVIOS`(`CI`, `COD_FECH_USU`, `IMPUESTO`,`IMPORTE` , `CREDITO`, `DEUDA`,`CREDITO_ACUMULADO`, `SUELDO`, `FECHA_ENTREGADO`, `FECHA_LIMITE_RH`) VALUES ('$Ci',$codFechaSel,'$Impuesto','$Importe','$Credito','$Deuda','$CreditoAcumulado','$Sueldo','$FechaEntregado','$FechaLimite')";
          $db->query($query);
          if($db->affected_rows<=0){
            echo "<script>
                     window.location.href='enviar.php?tituloMensajeModal=Error Envío&mensajeModal=No se registro el envío';
                  </script>";
          }
          else{
            $query="SELECT `NUM_FACTURA`,`NIT`, `NUM_AUTORISACION` FROM `FACTURAS` where ENTREGADO=0 AND COD_FECH_USU='$codFechaSel' AND CI='$Ci'";
            $res=$db->query($query);
            if($db->affected_rows>0){
              while ($row=mysqli_fetch_array($res)){
                    $query="INSERT INTO `ENTREGADA`(`CI`, `NUM_FACTURA`,`NIT`,`NUM_AUTORISACION`, `FECHA_ENTREGADA`, `FECHA_LIMITE_RH`) VALUES ('$CI_RH','$row[0]','$row[1]','$row[2]','$FechaEntregado','$FechaLimite')";
                    $db->query($query);

                    $query="UPDATE `FACTURAS` SET `ENTREGADO`=1 WHERE `NUM_FACTURA`='$row[0]' AND `NIT`='$row[1]' AND `NUM_AUTORISACION`='$row[2]'";
                    $db->query($query);
              }
            }
              $query="UPDATE `FECHA_LIMITE_USU` SET `CREDITO`=$CreditoAcumulado , `ENTREGADO`=1 WHERE COD_FECH_USU='$CodFechUsu'";
              $db->query($query);
              echo "<script>
                         window.location.href='listaFacturas.php?tituloMensajeModal=Envío Exitoso&mensajeModal=El envío fue exitoso';
                      </script>";
          }
      }
    }
  }

  if(isset($_POST['mensajeAEmpleado'])){
    $sucursal=$_POST['sucursal'];
    $nombre=$_POST['nombreEmpleado'];
    $destinatario=$_POST['correoEmpleado'];
    $asunto=$_POST['asuntoMensajeEmpleado'];
    $mensaje=$nombre."\n".$_POST['mensajeEmpleado']."\nDe: ".$sucursal;
    $headers="From: pruebasistemas745@gmail.com";
    if(mail($destinatario, $asunto, $mensaje, $headers)){
    echo "<script>
               alert('Envío Exitoso');
               window.location.href='notificarEmpleados.php?tituloMensajeModal=Envío Exitoso&mensajeModal=El email fue enviado exitosamente';
            </script>";
    }
    else{
    echo "<script>
               alert('Error Envío');
               window.location.href='notificarEmpleados.php?tituloMensajeModal=Error Envío&mensajeModal=El email no fue enviado';
            </script>";
    }
  }

  if(isset($_GET['salir'])){
    session_unset();
    session_destroy();
    echo "<script>
               window.location.href='iniciarSesion.php';
            </script>";
  }
  
?>
