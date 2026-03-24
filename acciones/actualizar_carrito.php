<?php
session_start();
require_once("../configuracion.php");
require_once("../conexion.php");
require_once("../funciones.php");

$idDetalle = isset($_POST['id_detalle_carrito']) ? (int) $_POST['id_detalle_carrito'] : 0;
$accion = $_POST['accion'] ?? 'actualizar';
$cantidadManual = isset($_POST['cantidad']) ? (int) $_POST['cantidad'] : 1;

try {
    $detalle = obtenerDetalleCarritoPorId($conexion, $idDetalle);

    if (!$detalle) {
        throw new RuntimeException('Detalle no encontrado');
    }

    $cantidad = (int) $detalle['cantidad'];

    if ($accion === 'sumar') {
        $cantidad++;
    } elseif ($accion === 'restar') {
        $cantidad--;
    } else {
        $cantidad = $cantidadManual;
    }

    actualizarCantidadDetalleCarrito($conexion, $idDetalle, $cantidad);

    header("Location: ../carrito.php");
    exit;
} catch (Throwable $e) {
    header("Location: ../carrito.php?error=1");
    exit;
}