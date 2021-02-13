<?php
  include 'header.php';
?>
  <script>
  function seleccionarSucursal() {
    var sucursalSeleccionada=document.getElementById('seleccionarSucursal').value;
    var estadoSeleccionado=document.getElementById('seleccionarEstado').value;
      if (sucursalSeleccionada=='Seleccionar Sucursal' && estadoSeleccionado == 'Seleccionar Estado Entrega') {
          document.getElementById("tablaEmpleados").innerHTML = "";
          return;
      } else {
          var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                  document.getElementById("tablaEmpleados").innerHTML = this.responseText;
              }
          }
          xmlhttp.open("GET", "validar.php?sucursal="+sucursalSeleccionada+"&estado="+estadoSeleccionado, true);
          xmlhttp.send();
      }
  }
  </script>
  <?php
    $query="SELECT FRH.SUCURSAL
    FROM USUARIOS U
    INNER JOIN FECHA_LIMITE_RH FRH ON FRH.CIRH = U.CI
    WHERE U.CI='$ci'";
    $res=$db->query($query);
    if($db->affected_rows>0){
      $select="<select id='seleccionarSucursal' onchange='seleccionarSucursal()' class='filtro'><option>Seleccionar Sucursal</option><option>Todos</option>";
      while ($row=mysqli_fetch_array($res)){
          $select.="<option value='$row[0]'>";
          $select.=$row[0];
          $select.='</option>';
      }
      $select.='</select>';
      echo $select;
    }
  ?>
  <Select id='seleccionarEstado' onchange='seleccionarSucursal()' class='filtro'>
    <option>Seleccionar Estado Entrega</option>
    <option>Todos</option>
    <option>Si Entregó</option>
    <option>No Entregó</option>
  </Select>
  <span id="tablaEmpleados">
    <?php
        $ci=$_SESSION["usuCI"];
        $query="SELECT U.CI,U.NOMBRE,FRH.SUCURSAL,FRH.FECHA_LIMITE_RH,FUSU.ENTREGADO,U2.CI,U2.NOMBRE,U2.EMAIL,U2.APELLIDO
          FROM USUARIOS U
          INNER JOIN FECHA_LIMITE_RH FRH ON FRH.CIRH = U.CI
          INNER JOIN FECHA_LIMITE_USU FUSU ON FUSU.COD_LIM = FRH.COD_LIM
          INNER JOIN USUARIOS U2 ON U2.CI=FUSU.CI
          WHERE U.CI='$ci'";
        $res=$db->query($query);
        $tabla = "<table class='ListadoFacturas'>
                    <tr>
                        <th>Enviar</th>
                        <th>CI EMPLEADO</th>
                        <th>NOMBRE EMPLEADO</th>
                        <th>EMAIL</th>
                        <th>SUCURSAL</th>
                        <th>FECHA LIMITE</th>
                        <th>ENTREGO</th>
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
          <input type='hidden' name='sucursalSeleccionada' value='Todos'>
          <input type='hidden' name='estadoSeleccionado' value='Todos'> 
          <textarea name='mensajeTodosEmpleado' class='txttEmailTodosEmpleados'>
          </textarea>
          <input type='submit' class='btnEnviar' value='Enviar Email a Todos' name='EnviarEmailTodos'>
          </form>";
          echo $tabla;
    ?>
  </span>
<?php
  include 'footer.php';
?>
