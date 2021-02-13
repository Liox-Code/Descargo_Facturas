<?php
  include 'header.php';
    $ci=$_SESSION["usuCI"];
    $query="SELECT e.FECHA_LIMITE_RH
    FROM ENTREGADA e
    INNER JOIN FACTURAS f on f.NUM_FACTURA=e.NUM_FACTURA and f.NIT=e.NIT and f.NUM_AUTORISACION = e.NUM_AUTORISACION
    inner join FECHA_LIMITE_USU fus on fus.COD_FECH_USU = f.COD_FECH_USU
    WHERE f.CI='$ci' AND f.COD_FECH_USU='$codFechaSel'
    GROUP BY e.FECHA_LIMITE_RH desc";
    $res=$db->query($query);
    if($db->affected_rows>0){
      $select="<select onchange='seleccionarFechaLimite(this.value)' class='filtro'>
      <option>Seleccionar Fecha Entregado</option>
      <option>Todos</option>";
      while ($row=mysqli_fetch_array($res)){
          $select.="<option value='$row[0]'>";
          $select.=$row[0];
          $select.='</option>';
      }
      $select.='</select>';
      echo $select;
    }
?>

<script>
function seleccionarFechaLimite(str) {
    if (str.length == 0 || str=="Seleccionar Fecha Entregado") {
        document.getElementById("tablaFacturasEntregadas").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("tablaFacturasEntregadas").innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "validar.php?fechaLimite="+str, true);
        xmlhttp.send();
    }
}
</script>

<span id="tablaFacturasEntregadas">
  <table class="ListadoFacturas">
    <tr>
        <th>Num. Factura</th>
        <th>C.I.</th>
        <th>NIT</th>
        <th>Num. Autorización</th>
        <th>Fecha</th>
        <th>Fecha Vencimiento</th>
        <th>Importe</th>
        <th>Entregado</th>
        <th>Válido</th>
        <th>Cod. Control</th>
        <th>Fecha Entregado</th>
        <th>Fecha Límite</th>
        <th>Institución</th>
    </tr>
    <?php
      $query="SELECT f.*,e.FECHA_ENTREGADA,e.FECHA_LIMITE_RH,frh.SUCURSAL
          FROM FACTURAS f
          inner join ENTREGADA e on f.NUM_FACTURA=e.NUM_FACTURA and f.NIT = e.NIT AND f.NUM_AUTORISACION = e.NUM_AUTORISACION
          inner join FECHA_LIMITE_USU fus on fus.COD_FECH_USU = f.COD_FECH_USU
          INNER JOIN FECHA_LIMITE_RH frh on frh.COD_LIM=fus.COD_LIm
          WHERE f.ENTREGADO=1 AND f.CI='$ci' AND f.COD_FECH_USU='$codFechaSel'";
      $res=$db->query($query);
      $table='';
      while ($row=mysqli_fetch_array($res)){
        $table.='<tr>';
        $table.="<td>$row[0]</td>";
        $table.="<td>$row[1]</td>";
        $table.="<td>$row[2]</td>";
        $table.="<td>$row[3]</td>";
        $table.="<td>$row[4]</td>";
        $table.="<td>$row[5]</td>";
        $table.="<td>$row[6]</td>";
        $table.="<td>$row[7]</td>";
        $table.="<td>$row[8]</td>";
        $table.="<td>$row[9]</td>";
        $table.="<td>$row[11]</td>";
        $table.="<td>$row[12]</td>";
        $table.="<td>$row[13]</td>";
        $table.='</tr>';
      }
      echo $table;
    ?>
  </table>
</span>

<?php
  include 'footer.php';
?>
