<?php
session_start();
require_once("../configuracion.php");
require_once("../conexion.php");
require_once("../funciones.php");

$items = obtenerItemsCarrito($conexion);
$datosCheckout = obtenerDatosCheckoutSesion();
$carrito = obtenerCarritoActivo($conexion);

if (!$carrito || !$items || !$datosCheckout) {
    header("Location: ../checkout.php");
    exit;
}

$params = [
    'mode' => 'payment',
    'success_url' => APP_URL . '/acciones/stripe_success.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => APP_URL . '/checkout.php?cancel=1',
    'client_reference_id' => (string) $carrito['id_carrito'],
    'customer_email' => $datosCheckout['correo'],
    'metadata[carrito_id]' => (string) $carrito['id_carrito'],
    'metadata[correo]' => $datosCheckout['correo']
];

foreach ($items as $i => $item) {
    $params["line_items[$i][price_data][currency]"] = strtolower(CURRENCY);
    $params["line_items[$i][price_data][product_data][name]"] = $item['nombre_producto'];
    $params["line_items[$i][price_data][unit_amount]"] = (int) round(((float) $item['precio_unitario']) * 100);
    $params["line_items[$i][quantity]"] = (int) $item['cantidad'];
}

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => 'https://api.stripe.com/v1/checkout/sessions',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($params),
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . STRIPE_SECRET_KEY,
        'Content-Type: application/x-www-form-urlencoded'
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

if ($httpCode < 200 || $httpCode >= 300 || empty($data['url'])) {
    header("Location: ../checkout.php?error=stripe");
    exit;
}

header("Location: " . $data['url']);
exit;