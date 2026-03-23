<?php
session_start();
require_once("../configuracion.php");
require_once("../conexion.php");
require_once("../funciones.php");

header('Content-Type: application/json');

$raw = json_decode(file_get_contents('php://input'), true);
$orderId = $raw['orderID'] ?? '';

if ($orderId === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'orderID no recibido']);
    exit;
}

try {
    $token = paypalAccessToken();

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => paypalBaseUrl() . '/v2/checkout/orders/' . urlencode($orderId) . '/capture',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        throw new RuntimeException(curl_error($ch));
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode($response, true);

    if ($httpCode < 200 || $httpCode >= 300 || ($data['status'] ?? '') !== 'COMPLETED') {
        throw new RuntimeException('Pago PayPal no completado');
    }

    $datosCheckout = obtenerDatosCheckoutSesion();

    $idPedido = crearPedidoPagadoDesdeCarrito(
        $conexion,
        $datosCheckout,
        'paypal',
        $orderId,
        'pagado',
        'pendiente'
    );

    guardarDatosUsuarioCheckout($conexion, $datosCheckout);
    limpiarCheckoutSesion();

    echo json_encode([
        'ok' => true,
        'redirect' => '../checkout.php?pagado=1&pedido=' . $idPedido
    ]);
    exit;
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    exit;
}