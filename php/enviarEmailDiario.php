<?php
  require 'conexion.class.php';
  $db=new Conexion();
  session_start();
  $headers = "From: pruebasistemas745@gmail.com";
  $asunto = "Advertencia No entrego Facturas";
  $entregadosCant =0;
  $emailNoEnviados ="";
  $query="SELECT u.NOMBRE,u.APELLIDO,u.EMAIL,fusu.SUELDO,fusu.TIPO_TRABAJO,frh.FECHA_LIMITE_RH,DATEDIFF(frh.FECHA_LIMITE_RH,now()),frh.SUCURSAL
          FROM USUARIOS U
          INNER JOIN FECHA_LIMITE_USU fusu on u.CI = fusu.CI
          INNER JOIN FECHA_LIMITE_RH frh on fusu.COD_LIM = frh.COD_LIM
          WHERE fusu.ENTREGADO=0 AND DATEDIFF(frh.FECHA_LIMITE_RH,now()) <= 5 AND DATEDIFF(frh.FECHA_LIMITE_RH,now()) >= 0";

          $res=$db->query($query);
          while ($row=mysqli_fetch_array($res)){
              $destinatario=$row[2];
              if ($row[4]=='Principal') {
                $descuento=$row[3]-8240;
              }
              else if ($row[4]=='Secundario'){
                $descuento=$row[3];
              }
              if($descuento<0){
                $descuento=0;
              }
              $mensaje=$row[0]." ".$row[1]."\nAún no envió facturas, le quedan ".$row[6]." días para entregar las mismas.\nCaso contrario se le descontará ".$descuento."\nDe: ".$row[7];
              if(mail($destinatario, $asunto, $mensaje, $headers)){
                $entregadosCant++;
              }
              else{
                $emailNoEnviados.=$destinatario."\n";
              }
          }
          if(mysqli_num_rows($res)==$entregadosCant){
            echo "Todos los Envios Fueron Exitosos";
          }
          else if(mysqli_num_rows($res)>$entregadosCant){
            echo "No se enviaron Todos las advertencias";
          }
          else if($entregadosCant==0){
            echo "Hubo un error No se envio ningun email";
          }

          /*$datetime1 = new DateTime($Fecha);
          $datetime2 = new DateTime($FechaVencimiento);
          $interval = $datetime1->diff($datetime2);
          $diferencia = $interval->format('%a');
          echo $diferencia;
          $datetime1->modify('+120 day');
          echo $datetime1->format('Y-m-d') . "\n";*/
?>
