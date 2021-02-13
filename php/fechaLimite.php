<?php
  include 'header.php';
?>
  <div class="contenedorNotificacion">
    <form action="abm.php" method="post" class="formaModificar">
            <label>Sucursales:</label><input type="text" name="txtSucursal" value="" required>
            <label>Fecha Limite:</label><input type="DATE" name="txtFechaLimite" value="" required>
            <div class="botones">
              <input type="submit" class="btnModificar" value="Guardas" name="FechaLimiteRH">
            </div>
    </form>
  </div>
  <table class="ListadoFacturas">
    <tr><th>Ci</th><th>Sucursal</th><th>Fecha Limite</th><th>Editar</th><th>Eliminar</th></tr>
    <?php
      $ci=$_SESSION["usuCI"];
      $query="SELECT * FROM FECHA_LIMITE_RH WHERE CIRH='$ci' ";
      $res=$db->query($query);
      $table='';
      while ($row=mysqli_fetch_array($res)){
        $table.='<tr>';
        $table.="<td>$row[1]</td>";
        $table.="<td>$row[2]</td>";
        $table.="<td>$row[3]</td>";
        $table.="<td><a href =\"editarFechaLimite.php?codFechaLimModif=$row[0]\"><img src='../img/editar.png' class='imgABM'></a></td>";
        $table.="<td><a href =\"abm.php?codFechaLimElim=$row[0]\"><img src='../img/x.png' class='imgABM'></a></td>";
        $table.='</tr>';
      }
      echo $table;
    ?>
  </table>
<?php
  include 'footer.php';
?>
