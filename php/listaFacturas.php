<?php
  include 'header.php';
?>
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
      <th>Editar</th>
      <th>Eliminar</th></tr>
      <?php
        $fechaLim=$_SESSION["fechaLimite"];

        $ci=$_SESSION["usuCI"];
        $query="SELECT * FROM FACTURAS
        WHERE ENTREGADO=0 AND CI='$ci' and COD_FECH_USU='$codFechaSel'";
        $res=$db->query($query);
        $table='';
        while ($row=mysqli_fetch_array($res)){
          if($row[7]==0){
            $row[7]='NO';
          }
          else{
            $row[7]='SI';
          }
          if($row[8]==0){
            $row[8]='NO';
          }
          else{
            $row[8]='SI';
          }
          if($row[5]<$fechaLim[0] && $row[5]!="")
          {
            $table.="<tr class='facturaExpirada' >";
          }
          else {
            $table.="<tr>";
          }
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
				  $table.="<td><a href =\"editarFact.php?numFactModif=$row[0]&nitFactModif=$row[2]&numAutModif=$row[3]\"><img src='../img/editar.png' class='imgABM'></a></td>";
				  $table.="<td><a href =\"abm.php?numFactElim=$row[0]&nitFactElim=$row[2]&numAutElim=$row[3]\"><img src='../img/x.png' class='imgABM'></a></td>";
          $table.="</tr>";
        }
        echo $table;
        if($_SESSION["entregado"])
        {
          $botonDesactivado="disabled";
        }
        else {
          $botonDesactivado="";
        }
      ?>
    </table>
    <a href="enviar.php"><input type="button" class="btnEnviar" value="Enviar" <?php echo $botonDesactivado; ?>></a>
<?php
  if(!$_SESSION["entregado"])
  {
    echo "<div class='lblInformacion'>
            <div class='divInformacion'><div class='divInformacionTitulos'>Sueldo Neto: </div><div class='divInformacionDatos'>$SueldoUsuario </div></div>
            <div class='divInformacion'><div class='divInformacionTitulos'>Importe a Presentar : </div><div class='divInformacionDatos'>$MontoBase </div></div>
            <div class='divInformacion'><div class='divInformacionTitulos'>Total Facturas Presentadas : </div><div class='divInformacionDatos'>$TotalFacturasPresentadas[0] </div></div>
            <div class='divInformacion'><div class='divInformacionTitulos'>Crédito Anterior : </div><div class='divInformacionDatos'>$CreditoAnterior[0] </div></div>
            <div class='divInformacion'><div class='divInformacionTitulos'>Crédito Del Mes : </div><div class='divInformacionDatos'>$CreditoReunido </div></div>
            <div class='divInformacion'><div class='divInformacionTitulos'>Retención Impuesto : </div><div class='divInformacionDatos'>".$DescuentoImpuesto*0.13."</div></div>
          </div>";
  }
  include 'footer.php';
?>
