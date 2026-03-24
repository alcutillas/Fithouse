<?php
session_start();
require_once("../configuracion.php");
require_once("../conexion.php");
require_once("../funciones.php");

$idDetalle = isset($_POST['id_detalle_carrito']) ? (int) $_POST['id_detalle_carrito'] : 0;

if ($idDetalle > 0) {
    eliminarDetalleCarrito($conexion, $idDetalle);
}

header("Location: ../carrito.php");
exit;