<?php
  include 'header.php';
    $ci=$_SESSION["usuCI"];
    $query="SELECT `CI`, `NOMBRE`, `APELLIDO` FROM USUARIOS WHERE ci='$ci'";
    $res=$db->query($query);
    $row=mysqli_fetch_array($res);
?>
<script>
  function ciChechModificar(str) {
        if (str.length == 0) {
            document.getElementById("txtCi").innerHTML = "";
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                  if(this.responseText==""){
                    if(document.getElementById("btnModificarCuentaUsuario").disabled != false){
                      document.getElementById("btnModificarCuentaUsuario").disabled = false;
                    }
                  }
                  else {
                    if(document.getElementById("btnModificarCuentaUsuario").disabled != true){
                      document.getElementById("btnModificarCuentaUsuario").disabled = true;
                    }
                  }
                  document.getElementById("txtCi").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET", "validar.php?ci="+str, true);
            xmlhttp.send();
        }
  }
</script>
<div class="contenedorForms">
  <div class="contenedorRegistroFact">
    <form action="abm.php" method="post" class="formaModificar">
            <label>C.I. : </label><input type="text" name="txtCi" value="<?php echo $row[0]; ?>" onchange="ciChechModificar(this.value)" onkeyup="ciChechModificar(this.value)" required><span id="txtCi"></span>
            <label>Nombre : </label><input type="text" name="txtNombre" value="<?php echo $row[1]; ?>" required>
            <label>Apellido : </label><input type="text" name="txtApellido" value="<?php echo $row[2]; ?>" required>
            <div class="botones">
              <input type="submit" class="btnModificar" value="Guardar Cambios" name="modificar" id="btnModificarCuentaUsuario">
            </div>
    </form>
  </div>
</div>
<?php
  include 'footer.php';
?>
