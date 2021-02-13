<?php
require 'conexion.class.php';
$db=new Conexion();
session_start();

if(isset($_GET['Nit']) || isset($_GET['NumFact']) || isset($_GET['NumAut'])){
  $Nit = $_REQUEST["Nit"];
  $NumFact = $_REQUEST["NumFact"];
  $NumAut = $_REQUEST["NumAut"];
  $hint = "";
  $query="SELECT * FROM FACTURAS WHERE NIT='$Nit' AND NUM_FACTURA='$NumFact' AND NUM_AUTORISACION='$NumAut'";
  $res=$db->query($query);
  $row=mysqli_fetch_array($res);
  if($db->affected_rows>0){
    $hint="<p class='validacionIncorrecta'>Ya se registro esta factura</p>";
  }
  echo $hint === "" ? "" : $hint;
}

if(isset($_GET['fecha'])){
  $Fecha = $_REQUEST["fecha"];
  $FechaInicial=new DateTime($Fecha);
  $diferenciaDias=$FechaInicial->modify('+120 day');
  $FechaVencimiento=$diferenciaDias->format('Y-m-d');
  if($_SESSION["entregado"])
  {
    $FechaL=$_SESSION["fechaLimite"];
    $FechaLimite=new DateTime($FechaL);
    $diferenciaDias=$FechaLimite->modify('+1 month');
    $FechaLimite=$diferenciaDias->format('Y-m-d');
  }
  else {
    $FechaLimite=$_SESSION["fechaLimite"];
  }
  $hint = "";
  if($Fecha>$FechaLimite){
    $hint="<span class='validacionIncorrecta'>Fecha ingresada mayor que fecha límite</span>";
  }
  else if($FechaVencimiento<$FechaLimite){
    $hint="<span class='validacionIncorrecta'>Expirara antes de fecha límite</span>";
  }
  echo $hint === "" ? "" : $hint;
}

if(isset($_GET['importe'])){
  $importe = $_REQUEST["importe"];
  $hint = "";
  if($importe<0){
    $hint="<span class='validacionIncorrecta'>No puede ser un numero negativo</span>";
  }
  echo $hint === "" ? "" : $hint;
}

if(isset($_GET['ci'])){
  $ci = $_REQUEST["ci"];
  $hint = "";
  $query="SELECT CI FROM USUARIOS WHERE CI='$ci'";
  $res=$db->query($query);
  $row=mysqli_fetch_array($res);
  if($db->affected_rows>0 ){
    $hint="<span class='validacionIncorrecta'>Ya existe usuario con ese ci</span>";
  }
  echo $hint === "" ? "" : $hint;
}

if(isset($_GET['nomUsu'])){
  $nombreUsuario = $_REQUEST["nomUsu"];
  $hint = "";
  $query="SELECT NOMBRE_USUARIO FROM USUARIOS WHERE NOMBRE_USUARIO='$nombreUsuario'";
  $res=$db->query($query);
  $row=mysqli_fetch_array($res);
  if($db->affected_rows>0){
    $hint="<span class='validacionIncorrecta'>Ya existe usuario con ese nombre usuario</span>";
  }
  echo $hint === "" ? "" : $hint;
}

if(isset($_GET['email'])){
  $email = $_REQUEST["email"];
  $hint = "";
  $query="SELECT EMAIL FROM USUARIOS WHERE EMAIL='$email'";
  $res=$db->query($query);
  $row=mysqli_fetch_array($res);
  if($db->affected_rows>0){
    $hint="<span class='validacionIncorrecta'>Ya existe usuario con ese email</span>";
  }
  echo $hint === "" ? "" : $hint;
}

if(isset($_GET['SucursalesRH'])){
  $sucursales = $_REQUEST["SucursalesRH"];
  $select = "";
  $sucursalSel=$_GET['SucursalSel'];
  $query="SELECT SUCURSAL FROM FECHA_LIMITE_RH WHERE CIRH='$sucursales'";
  $res=$db->query($query);
  if($db->affected_rows>0){
    $select="<label>Sucursales:</label><select name='txtSucursal'>";
    while ($row=mysqli_fetch_array($res)){
      if($row[0]==$sucursalSel){
        $select.="<option value='$row[0]' selected>";
        $select.=$row[0];
        $select.='</option>';
      }
      else {
        $select.="<option value='$row[0]'>";
        $select.=$row[0];
        $select.='</option>';
      }
    }
    $select.='</select>';
  }
  echo $select === "" ? "<span class='validacionIncorrecta'>No existe ese usuario de recursos Humanos</span>" : $select;
}

if(isset($_GET['sucursal']) && isset($_GET['estado'])){
  $ci=$_SESSION["usuCI"];
  $sucursal = $_REQUEST['sucursal'];
  $estado = $_REQUEST['estado'];
  $query="SELECT U.CI,U.NOMBRE,FRH.SUCURSAL,FRH.FECHA_LIMITE_RH,FUSU.ENTREGADO,U2.CI,U2.NOMBRE,U2.EMAIL,U2.APELLIDO
          FROM USUARIOS U
          INNER JOIN FECHA_LIMITE_RH FRH ON FRH.CIRH = U.CI
          INNER JOIN FECHA_LIMITE_USU FUSU ON FUSU.COD_LIM = FRH.COD_LIM
          INNER JOIN USUARIOS U2 ON U2.CI=FUSU.CI
          WHERE U.CI='$ci'";
  if($sucursal!="Todos" && $sucursal!="Seleccionar Sucursal"){
    $query=$query." AND FRH.SUCURSAL='$sucursal'";
  }
  if($estado!="Todos" && $estado!="Seleccionar Estado Entrega"){
    if($estado=='Si Entregó'){
      $estado=1;
    }
    else{
      $estado=0;
    }
    $query=$query." AND FUSU.ENTREGADO=$estado";  
  }
  $res=$db->query($query);
  $tabla = "<table class='ListadoFacturas'>
              <tr>
              <th>Enviar</th>
              <th>C.I. Empleado</th>
              <th>Nombre Empleado</th>
              <th>Email</th>
              <th>Institución</th>
              <th>Fecha Límite</th>
              <th>Entrego</th>
              </tr>";
    while ($row=mysqli_fetch_array($res)){
      if($row[4]==0){
        $row[4]='NO';
      }
      else{
        $row[4]='SI';
      }
      $tabla.='<tr>';
      $tabla.="<td><a href =\"enviarEmailEmpleadp.php?email=$row[7]&&nombre=$row[6]&&apellido=$row[8]&&sucursal=$row[2]\"><img src='../img/mail.png' class='imgABM'></a></td>";
      $tabla.="<td>$row[5]</td>";
      $tabla.="<td>$row[6] $row[8]</td>";
      $tabla.="<td>$row[7]</td>";
      $tabla.="<td>$row[2]</td>";
      $tabla.="<td>$row[3]</td>";
      $tabla.="<td>$row[4]</td>";
      $tabla.='</tr>';
    }
    $tabla.="</table>
    <form action='enviarAdvertenciaEmpleados.php' method='post'>
    <input type='hidden' name='sucursalSeleccionada' value='$sucursal'>
    <input type='hidden' name='estadoSeleccionado' value='$estado'> 
    <textarea name='mensajeTodosEmpleado' class='txttEmailTodosEmpleados'>
    </textarea>
    <input type='submit' class='btnEnviar' value='Enviar Email a Todos' name='EnviarEmailTodos'>
    </form>";
    echo $tabla === "" ? "" : $tabla;
}



if(isset($_GET['fechaLimite'])){
  $fechaLimite= $_REQUEST['fechaLimite'];
  $ci=$_SESSION["usuCI"];
  $codFechaSel=$_SESSION["codFechaLimite"];
  if($fechaLimite=="Todos"){
    $query="SELECT f.*,e.FECHA_ENTREGADA,e.FECHA_LIMITE_RH,frh.SUCURSAL
        FROM FACTURAS f
        inner join ENTREGADA e on f.NUM_FACTURA=e.NUM_FACTURA and f.NIT = e.NIT AND f.NUM_AUTORISACION = e.NUM_AUTORISACION
        inner join FECHA_LIMITE_USU fus on fus.COD_FECH_USU = f.COD_FECH_USU
        INNER JOIN FECHA_LIMITE_RH frh on frh.COD_LIM=fus.COD_LIm
        WHERE f.ENTREGADO=1 AND f.CI='$ci' and f.COD_FECH_USU='$codFechaSel'";
  }else {
    $query="SELECT en.SUELDO,en.IMPUESTO,en.IMPORTE,en.CREDITO,en.CREDITO_ACUMULADO,en.DEUDA FROM ENVIOS en
    INNER JOIN FECHA_LIMITE_USU fusu on fusu.COD_FECH_USU = en.COD_FECH_USU
    WHERE fusu.CI='$ci' and en.FECHA_LIMITE_RH='$fechaLimite' and en.COD_FECH_USU='$codFechaSel'";
    $res=$db->query($query);
    $row=mysqli_fetch_array($res);
    $SueldoUsuario=$row[0];
    $MontoBase=$row[1];
    $TotalFacturasPresentadas=$row[2];
    $CreditoAnterior=$row[3];
    $CreditoReunido=$row[4];
    $DescuentoImpuesto=$row[5];
    echo "<div class='lblInformacion'>
            <div class='divInformacion'><div class='divInformacionTitulos'>Sueldo : </div><div class='divInformacionDatos'>$SueldoUsuario </div></div>
            <div class='divInformacion'><div class='divInformacionTitulos'>Monto Base : </div><div class='divInformacionDatos'>$MontoBase </div></div>
            <div class='divInformacion'><div class='divInformacionTitulos'>Total Facturas Presentadas : </div><div class='divInformacionDatos'>$TotalFacturasPresentadas</div></div>
            <div class='divInformacion'><div class='divInformacionTitulos'>Crédito Anterior : </div><div class='divInformacionDatos'>$CreditoAnterior</div></div>
            <div class='divInformacion'><div class='divInformacionTitulos'>Crédito Acumulado : </div><div class='divInformacionDatos'>$CreditoReunido </div></div>
            <div class='divInformacion'><div class='divInformacionTitulos'>Descuento Impuesto : </div><div class='divInformacionDatos'>".$DescuentoImpuesto*0.13."</div></div>
            </div>";
      $query="SELECT f.*,e.FECHA_ENTREGADA,e.FECHA_LIMITE_RH,frh.SUCURSAL
      FROM FACTURAS f
      inner join ENTREGADA e on f.NUM_FACTURA=e.NUM_FACTURA and f.NIT = e.NIT AND f.NUM_AUTORISACION = e.NUM_AUTORISACION
      inner join FECHA_LIMITE_USU fus on fus.COD_FECH_USU = f.COD_FECH_USU
      INNER JOIN FECHA_LIMITE_RH frh on frh.COD_LIM=fus.COD_LIm
      WHERE f.ENTREGADO=1 AND f.CI='$ci' and f.COD_FECH_USU='$codFechaSel' and e.FECHA_LIMITE_RH='$fechaLimite'";
  }
    $res=$db->query($query);
    $tabla = "<table class='ListadoFacturas'>
          <tr>
              <th>Num. Factura</th>
              <th>C.I.</th>
              <th>NIT</th>
              <th>Num. Autorización</th>
              <th>Fecha</th>
              <th>Fecha Vencimiento</th>
              <th>Importe</th>
              <th>Entregado</th>
              <th>Valido</th>
              <th>Cod. Control</th>
              <th>Fecha Entregado</th>
              <th>Fecha Límite</th>
              <th>Institución</th>
          </tr>";
    while ($row=mysqli_fetch_array($res)){
    $tabla.='<tr>';
    $tabla.="<td>$row[0]</td>";
    $tabla.="<td>$row[1]</td>";
    $tabla.="<td>$row[2]</td>";
    $tabla.="<td>$row[3]</td>";
    $tabla.="<td>$row[4]</td>";
    $tabla.="<td>$row[5]</td>";
    $tabla.="<td>$row[6]</td>";
    $tabla.="<td>$row[7]</td>";
    $tabla.="<td>$row[8]</td>";
    $tabla.="<td>$row[9]</td>";
    $tabla.="<td>$row[11]</td>";
    $tabla.="<td>$row[12]</td>";
    $tabla.="<td>$row[13]</td>";
    $tabla.='</tr>';
    }
    $tabla.="</table>";
    echo $tabla === "" ? "" : $tabla;
}



/*$datetime1 = new DateTime($Fecha);
$datetime2 = new DateTime($FechaVencimiento);
$interval = $datetime1->diff($datetime2);
$diferencia = $interval->format('%a');
echo $diferencia;
$datetime1->modify('+120 day');
echo $datetime1->format('Y-m-d') . "\n";*/




?>
