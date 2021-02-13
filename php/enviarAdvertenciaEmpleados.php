<?php
        session_start();
        require 'conexion.class.php';
        $db=new Conexion();
        if(isset($_POST['EnviarEmailTodos'])){
          $sucursal= $_POST['sucursalSeleccionada'];
          $estado= $_POST['estadoSeleccionado'];
          $mensaje=$_POST['mensajeTodosEmpleado'];
          $ci=$_SESSION["usuCI"];
          $query="SELECT U.CI,U.NOMBRE,FRH.SUCURSAL,FRH.FECHA_LIMITE_RH,FUSU.ENTREGADO,U2.CI,U2.NOMBRE,U2.EMAIL
          FROM USUARIOS U
          INNER JOIN FECHA_LIMITE_RH FRH ON FRH.CIRH = U.CI
          INNER JOIN FECHA_LIMITE_USU FUSU ON FUSU.COD_LIM = FRH.COD_LIM
          INNER JOIN USUARIOS U2 ON U2.CI=FUSU.CI
          WHERE U.CI='$ci'";

          if($sucursal!="Todos" && $sucursal!="Seleccionar Sucursal"){
            $query=$query." AND FRH.SUCURSAL='$sucursal'";
          }
          if($estado!="Todos" && $estado!="Seleccionar Estado Entrega"){
            $query=$query." AND FUSU.ENTREGADO=$estado";  
          }

          $entregadosCant =0;
          $emailNoEnviados ="";
          $res=$db->query($query);
          $headers = "From: pruebasistemas745@gmail.com";
          $asunto = "Advertencia No entrego Facturas";
          while ($row=mysqli_fetch_array($res)){ 
            $destinatario = $row[7];
            $carta = "De: $row[2] \n";
            $carta .= $mensaje;
            if(mail($destinatario, $asunto, $carta, $headers)){
              $entregadosCant++;
            }
            else{
              $emailNoEnviados.=$destinatario."\n";
            }
          }
          if(mysqli_num_rows($res)==$entregadosCant){
            echo "<script>
                     window.location.href='notificarEmpleados.php?tituloMensajeModal=Envió exitoso&mensajeModal=Todos los Envíos Fueron Exitosos.';
                  </script>";
          }
          else if(mysqli_num_rows($res)>$entregadosCant){
            echo "<script>
                     window.location.href='notificarEmpleados.php?tituloMensajeModal=Error Envió&mensajeModal= No se Enviaron Todos las Advertencias.';
                  </script>";
          }
          else if($entregadosCant==0){
            echo "<script>
                     window.location.href='notificarEmpleados.php?tituloMensajeModal=Error Envió&mensajeModal= Hubo un error No se envió ningún email.';
                  </script>";
          }
        }
?>
