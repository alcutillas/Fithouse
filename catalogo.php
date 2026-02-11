<?php
require_once("templates/header.php");


echo generarSelect($conexion, "productos", "categoria", "categorias","");
echo generarSelect($conexion, "productos", "marca", "marcas","");
?>
<?php
require_once("templates/footer.php");
?>