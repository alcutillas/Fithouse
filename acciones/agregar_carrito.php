<?php
session_start();
require_once("../configuracion.php");
require_once("../conexion.php");
require_once("../funciones.php");

try {
    $idProducto = isset($_POST['id_producto']) ? (int) $_POST['id_producto'] : 0;
    $cantidad = isset($_POST['cantidad']) ? (int) $_POST['cantidad'] : 1;

    if ($idProducto <= 0) {
        throw new RuntimeException('Producto no válido');
    }

    agregarProductoAlCarrito($conexion, $idProducto, $cantidad);

    header("Location: ../carrito.php");
    exit;
} catch (Throwable $e) {
    header("Location: ../producto.php?id=" . (int) ($_POST['id_producto'] ?? 0) . "&error=1");
    exit;
}