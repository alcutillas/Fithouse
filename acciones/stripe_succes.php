<?php
session_start();
require_once("../configuracion.php");
require_once("../conexion.php");
require_once("../funciones.php");

$sessionId = $_GET['session_id'] ?? '';

if ($sessionId === '') {
    header("Location: ../checkout.php?error=stripe");
    exit;
}

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => 'https://api.stripe.com/v1/checkout/sessions/' . urlencode($sessionId),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . STRIPE_SECRET_KEY
    ]
]);

$response = curl_exec($ch);

if ($response === false) {
    curl_close($ch);
    header("Location: ../checkout.php?error=stripe");
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$data = json_decode($response, true);

if ($httpCode < 200 || $httpCode >= 300 || empty($data['id'])) {
    header("Location: ../checkout.php?error=stripe");
    exit;
}

if (($data['payment_status'] ?? '') !== 'paid') {
    header("Location: ../checkout.php?error=stripe");
    exit;
}

try {
    $datosCheckout = obtenerDatosCheckoutSesion();
    $idPedido = crearPedidoPagadoDesdeCarrito(
        $conexion,
        $datosCheckout,
        'stripe',
        $data['id'],
        'pagado',
        'pendiente'
    );

    guardarDatosUsuarioCheckout($conexion, $datosCheckout);
    limpiarCheckoutSesion();

    header("Location: ../checkout.php?pagado=1&pedido=" . $idPedido);
    exit;
} catch (Throwable $e) {
    header("Location: ../checkout.php?error=stripe");
    exit;
}