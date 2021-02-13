<?php
  include 'header.php';
    $cod=$_GET["CodFechUsu"];
    $query="SELECT fusu.COD_FECH_USU,frh.CIRH,frh.SUCURSAL,fusu.SUELDO
    FROM FECHA_LIMITE_USU fusu
    INNER JOIN FECHA_LIMITE_RH frh
    on frh.COD_LIM=fusu.COD_LIM
    WHERE fusu.COD_FECH_USU='$cod'";
    $res=$db->query($query);
    $row=mysqli_fetch_array($res);

?>
<div class="contenedorForms">
  <div class="contenedorNotificacion">
    <form action="abm.php" method="post" class="formaModificar">
            <input type="hidden" name="CodUsuFechLim" value="<?php echo $row[0]; ?>">
            <label>Número C.I. Recursos Humanos:</label><input type="text" id="txtcirh" required name="txtCi" value="<?php echo $row[1]; ?>" onkeyup="cirh(this.value,'<?php echo $row[2]; ?>' )" onchange="cirh(this.value,'<?php echo $row[2]; ?>' )">
            <script type="text/javascript">
              var x =cirh(document.getElementById('txtcirh').value,'<?php echo $row[2]; ?>');
            </script>
            <span id='SucursalesRH'></span>
            <label>Sueldo:</label><input type="number" required name="txtSueldo" value="<?php echo $row[3]; ?>" min="0">
            <label>Tipo Trabajo:</label>
            <select name="TIPO_TRABAJO">
              <option value="Principal">Principal</option>
              <option value="Secundario">Secundario</option>
            </select>
            <div class="botones">
              <input type="submit" class="btnModificar" value="Guardar Cambios" name="EditarFechaLimiteUsuario" id="btnNotificacionRegistrar">
            </div>
    </form>
  </div>
</div>
<?php
  include 'footer.php';
?>
