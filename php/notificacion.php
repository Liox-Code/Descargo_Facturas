<?php
  include 'header.php';
?>
<div class="contenedorForms">
<div class="contenedorNotificacion">
    <form action="abm.php" method="post" class="formaModificar">
            <label>Número C.I. Recursos Humanos : </label>
            <input type="text" name="txtCi" required value="" onkeyup="cirh(this.value,'')" onchange="cirh(this.value,'')">
            <span id='SucursalesRH'></span>
            <label>Sueldo : </label>
            <input type="number" name="txtSueldo" required value="0" min="0">
            <label>Tipo Trabajo : </label>
            <select name="TIPO_TRABAJO">
              <option value="Principal">Principal</option>
              <option value="Secundario">Secundario</option>
            </select>
            <div class="botones">
              <input type="submit" class="btnModificar" value="Registrar Institución" name="FechaLimiteUsuario" id="btnNotificacionRegistrar">
            </div>
    </form>
  </div>
</div>
  <table class="ListadoFacturas">
      <tr>
        <th>C.I.</th>
        <th>Institución</th>
        <th>Fecha Límite</th>
        <th>Sueldo</th>
        <th>Tipo</th>
        <th>Editar</th>
        <th>Eliminar</th></tr>
      <?php
        $ci=$_SESSION["usuCI"];
        $query="SELECT fusu.CI,f.CIRH,f.SUCURSAL,f.FECHA_LIMITE_RH,fusu.COD_FECH_USU,fusu.SUELDO,fusu.COD_LIM,f.SUCURSAL,fusu.TIPO_TRABAJO
        FROM FECHA_LIMITE_RH f INNER JOIN FECHA_LIMITE_USU fusu
        ON f.COD_LIM = fusu.COD_LIM WHERE CI='$ci'";
        $res=$db->query($query);
        $table='';
        while ($row=mysqli_fetch_array($res)){
          $table.='<tr>';
          $table.="<td>$row[0]</td>";
          $table.="<td>$row[2]</td>";
          $table.="<td>$row[3]</td>";
          $table.="<td>$row[5]</td>";
          $table.="<td>$row[8]</td>";
          $table.="<td><a href =\"editarNotificacion.php?CodFechUsu=$row[4]&txtCodLimAnt=$row[4]\"><img src='../img/editar.png' class='imgABM'></a></td>";
          $table.="<td><a href =\"abm.php?CodFechUsuElim=$row[4]\"><img src='../img/x.png' class='imgABM'></a></td>";
          if($fechaSel==$row[4] && $fechaSel!=""){
            $_SESSION['sueldo']=$row[5];
            $_SESSION['CI_RH']=$row[1];
            $_SESSION["COD_LIM"]=$row[6];
            $_SESSION["SUCURSAL"]=$row[7];
            $_SESSION["TIPO_TRABAJO"]=$row[8];
            $table.="<td><a href =\"notificacion.php?fechaSeleccionada=$row[4]\"><img src='../img/checked.png' class='imgABM'></a></td>";
          }
          else {
            $table.="<td><a href =\"notificacion.php?fechaSeleccionada=$row[4]&codigoLim=$row[6]&sucursalSeleccionada=$row[7]\"><img src='../img/empty.png' class='imgABM'></a></td>";
          }
          $table.='</tr>';
        }
        echo $table;
      ?>
    </table>
   
<?php
  include 'footer.php';
?>
