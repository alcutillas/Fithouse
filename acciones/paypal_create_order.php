<?php
session_start();
require_once("../configuracion.php");
require_once("../conexion.php");
require_once("../funciones.php");

header('Content-Type: application/json');

try {
    $items = obtenerItemsCarrito($conexion);
    $datosCheckout = obtenerDatosCheckoutSesion();

    if (!$items || !$datosCheckout) {
        throw new RuntimeException('Checkout incompleto');
    }

    $total = number_format(obtenerTotalCarrito($conexion), 2, '.', '');
    $token = paypalAccessToken();

    $payload = [
        'intent' => 'CAPTURE',
        'purchase_units' => [[
            'reference_id' => (string) (obtenerCarritoActivo($conexion)['id_carrito'] ?? 'carrito'),
            'amount' => [
                'currency_code' => CURRENCY,
                'value' => $total
            ]
        ]],
        'application_context' => [
            'brand_name' => 'FITHOUSE',
            'shipping_preference' => 'NO_SHIPPING',
            'user_action' => 'PAY_NOW'
        ]
    ];

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => paypalBaseUrl() . '/v2/checkout/orders',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
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

    if ($httpCode < 200 || $httpCode >= 300 || empty($data['id'])) {
        throw new RuntimeException('No se pudo crear la orden PayPal');
    }

    echo json_encode(['id' => $data['id']]);
    exit;
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}