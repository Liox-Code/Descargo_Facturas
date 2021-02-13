<?php
  include 'header.php';
?>

<h2 onclick='mostrarModal()'>Bottom Modal</h2>

<div id='modal' class='modal'>
  <div class='modal-contenedor'>
    <div class='modal-cabecera'>
      <span class='boton_cerrar' onclick='cerrarModal()'>&times;</span>
      <h2 class='modal_titulo'>Modal cabecera</h2>
    </div>
    <div class='modal-contenido'>
      <p>Some text in the Modal contenido</p>
      <p>Some other text...</p>
    </div>
  </div>
</div>



<?php
  include 'footer.php';
?>
