<?php
  include 'headerPantallaPrincipal.php';
?>
    <script>

    var validarCiCheck = true;
    var validarNombreUsuario = true;
    var validarEmailCheck = true;
    var validarContrasenaIguales = true;

    function ciChech(str) {
        if (str.length == 0) {
            document.getElementById("txtCi").innerHTML = "";
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                  if(this.responseText==""){
                    validarCiCheck = true;
                  }
                  else {
                    validarCiCheck = false;
                  }
                  desactivarRegistrarUsuario();
                  document.getElementById("txtCi").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET", "validar.php?ci="+str, true);
            xmlhttp.send();
        }
    }
    function nombreUsuario(str) {
        if (str.length == 0) {
            document.getElementById("txtNombreUsuario").innerHTML = "";
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                  if(this.responseText==""){
                    validarNombreUsuario = true;
                  }
                  else {
                    validarNombreUsuario = false;
                  }
                  desactivarRegistrarUsuario();
                  document.getElementById("txtNombreUsuario").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET", "validar.php?nomUsu="+str, true);
            xmlhttp.send();
        }
    }
    function emailCheck(str) {
        if (str.length == 0) {
            document.getElementById("txtEmail").innerHTML = "";
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                  if(this.responseText==""){
                    validarEmailCheck = true;
                  }
                  else {
                    validarEmailCheck = false;
                  }
                  desactivarRegistrarUsuario();
                 document.getElementById("txtEmail").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET", "validar.php?email="+str, true);
            xmlhttp.send();
        }
    }

    function contrasenaIguales(){
      var contrasena = document.getElementById('contrasena').value;
      var confirmarContrasena = document.getElementById('confirmarContrasena').value;
      if(contrasena==confirmarContrasena){
        validarContrasenaIguales=true;
        document.getElementById("txtContrasenaDiferente").innerHTML = "";
      }
      else{
        validarContrasenaIguales=false;
        document.getElementById("txtContrasenaDiferente").innerHTML = "<span class='validacionIncorrecta'>Contraseñas Diferentes</span>";
      }
      desactivarRegistrarUsuario();
    }

    function desactivarRegistrarUsuario(){
      if(validarCiCheck==true && validarNombreUsuario==true && validarEmailCheck==true && validarContrasenaIguales==true){
        if(document.getElementById("btnRegistrar").disabled != false){
          document.getElementById("btnRegistrar").disabled = false;
        }
      }
      else {
        if(document.getElementById("btnRegistrar").disabled != true){
          document.getElementById("btnRegistrar").disabled  = true;
        }
      }
    }

    </script>


        <img src="../img/paisajeRojo2.jpg" class="imagenFormUsu">
        <div class="contenedorFormUsuarioRegistrar">
            <form action="abm.php" method="post" class="formUsuario" >
                    <label>CI : </label><input type="number" name="txtCi" value="" onkeyup="ciChech(this.value)" onchange="ciChech(this.value)" required placeholder="CI"><span class="validacionIncorrecta" id="txtCi"></span>
                    <label>Nombre : </label><input type="text" name="txtNombre" value="" required placeholder="Nombre">
                    <label>Apellido : </label><input type="text" name="txtApellido" value="" required placeholder="Apellido">
                    <label>Nombre Usuario : </label><input type="text" name="txtNombreUsuario" value="" onkeyup="nombreUsuario(this.value)" onchange="nombreUsuario(this.value)" required placeholder="Nombre Usuario"><span class="validacionIncorrecta" id="txtNombreUsuario"></span>
                    <label>Contraseña : </label><input type="password" name="txtContraseña" value="" required placeholder="Contraseña" onkeyup="contrasenaIguales()" onchange="contrasenaIguales()" id="contrasena">
                    <label>Confirmar Contraseña : </label><input type="password" name="txtConfirmarContraseña" value="" required placeholder="Confirmar Contraseña" onkeyup="contrasenaIguales()" onchange="contrasenaIguales()" id="confirmarContrasena"><span class="validacionIncorrecta" id="txtContrasenaDiferente"></span>
                    <label>Email : </label><input type="email" name="txtEmail" value="" onkeyup="emailCheck(this.value)" onchange="emailCheck(this.value)" required placeholder="Email"><span class="validacionIncorrecta" id="txtEmail"></span>
                    <label>Tipo : </label><select name="txtTipo"><option value="Usuario">Usuario</option><option value="RecursosHumano" required>Recursos Humanos</option></select>
                    <div class="botones">
                      <input type="submit" class="btnIniSesion" value="Registrar" name="registrar" id="btnRegistrar" readonly="true">
                      <a href="iniciarSesion.php"><input type="button" class="btnRegistrar" value="Iniciar Sesión"></a>
                    </div>
            </form>
        </div>
    <?php
      include 'footer.php';
    ?>
