<?php
$css = "checkout";
require_once("configuracion.php");
require_once("templates/header.php");

$items = obtenerItemsCarrito($conexion);
$total = obtenerTotalCarrito($conexion);
$errores = [];
$datosCheckout = obtenerDatosCheckoutIniciales($conexion);

if (!$items && !isset($_GET['pagado'])) {
    header("Location: carrito.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_checkout'])) {
    $datosCheckout = [
        'nombre' => trim($_POST['nombre'] ?? ''),
        'correo' => trim($_POST['correo'] ?? ''),
        'telefono' => trim($_POST['telefono'] ?? ''),
        'direccion' => trim($_POST['direccion'] ?? ''),
        'ciudad' => trim($_POST['ciudad'] ?? ''),
        'cp' => trim($_POST['cp'] ?? '')
    ];

    $errores = validarDatosCheckout($datosCheckout);

    if (!$errores) {
        guardarDatosCheckoutSesion($datosCheckout);
        guardarDatosUsuarioCheckout($conexion, $datosCheckout);
        $datosCheckout = obtenerDatosCheckoutIniciales($conexion);
    }
}
?>

<main id="checkout">
    <section class="checkout-wrap">
        <?php if (isset($_GET['pagado']) && isset($_GET['pedido'])): ?>
            <div class="checkout-ok">
                <h1>Pago completado</h1>
                <p>Tu pedido #<?php echo (int) $_GET['pedido']; ?> se ha registrado correctamente.</p>
                <a href="catalogo.php" class="btn-principal">Seguir comprando</a>
            </div>
        <?php else: ?>
            <div class="checkout-grid">
                <div class="checkout-form-box">
                    <h1>Datos de envío</h1>

                    <?php if ($errores): ?>
                        <div class="checkout-errores">
                            <?php foreach ($errores as $error): ?>
                                <p><?php echo htmlspecialchars($error); ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" class="checkout-formulario" autocomplete="on">
                        <input type="hidden" name="guardar_checkout" value="1">

                        <div class="campo">
                            <label for="nombre">Nombre completo</label>
                            <input
                                type="text"
                                id="nombre"
                                name="nombre"
                                value="<?php echo htmlspecialchars($datosCheckout['nombre'] ?? ''); ?>"
                                autocomplete="name"
                                required
                            >
                        </div>

                        <div class="campo">
                            <label for="correo">Correo</label>
                            <input
                                type="email"
                                id="correo"
                                name="correo"
                                value="<?php echo htmlspecialchars($datosCheckout['correo'] ?? ''); ?>"
                                autocomplete="email"
                                required
                            >
                        </div>

                        <div class="campo">
                            <label for="telefono">Teléfono</label>
                            <input
                                type="text"
                                id="telefono"
                                name="telefono"
                                value="<?php echo htmlspecialchars($datosCheckout['telefono'] ?? ''); ?>"
                                autocomplete="tel"
                                required
                            >
                        </div>

                        <div class="campo">
                            <label for="direccion">Dirección</label>
                            <input
                                type="text"
                                id="direccion"
                                name="direccion"
                                value="<?php echo htmlspecialchars($datosCheckout['direccion'] ?? ''); ?>"
                                autocomplete="street-address"
                                required
                            >
                        </div>

                        <div class="campo">
                            <label for="ciudad">Ciudad</label>
                            <input
                                type="text"
                                id="ciudad"
                                name="ciudad"
                                value="<?php echo htmlspecialchars($datosCheckout['ciudad'] ?? ''); ?>"
                                autocomplete="address-level2"
                                required
                            >
                        </div>

                        <div class="campo">
                            <label for="cp">Código postal</label>
                            <input
                                type="text"
                                id="cp"
                                name="cp"
                                value="<?php echo htmlspecialchars($datosCheckout['cp'] ?? ''); ?>"
                                autocomplete="postal-code"
                                required
                            >
                        </div>

                        <button type="submit" class="btn-principal btn-full">Guardar y continuar</button>
                    </form>

                    <?php if ($datosCheckout && !$errores): ?>
                        <div class="checkout-pagos">
                            <h2>Pago</h2>

                            <?php if (defined('STRIPE_PUBLIC_KEY') && STRIPE_PUBLIC_KEY): ?>
                            <form action="acciones/stripe_checkout.php" method="post">
                                <button type="submit" class="btn-stripe btn-full">Pagar con Stripe</button>
                            </form>
                            <?php endif; ?>

                            <?php if (defined('PAYPAL_CLIENT_ID') && PAYPAL_CLIENT_ID): ?>
                            <div id="paypal-button-container"></div>

                            <script src="https://www.paypal.com/sdk/js?client-id=<?php echo urlencode(PAYPAL_CLIENT_ID); ?>&currency=<?php echo urlencode(CURRENCY); ?>&intent=capture"></script>
                            <script>
                            paypal.Buttons({
                                style: { layout: 'vertical', color: 'gold', shape: 'rect', label: 'paypal' },
                                createOrder: async function () {
                                    const response = await fetch('acciones/paypal_create_order.php', {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json' }
                                    });
                                    const data = await response.json();
                                    return data.id;
                                },
                                onApprove: async function (data) {
                                    const response = await fetch('acciones/paypal_capture_order.php', {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json' },
                                        body: JSON.stringify({ orderID: data.orderID })
                                    });
                                    const result = await response.json();
                                    window.location.href = result.redirect;
                                }
                            }).render('#paypal-button-container');
                            </script>
                        <?php endif; ?>
                        </div>

                        <script src="https://www.paypal.com/sdk/js?client-id=<?php echo urlencode(PAYPAL_CLIENT_ID); ?>&currency=<?php echo urlencode(CURRENCY); ?>&intent=capture"></script>
                        <script>
                        paypal.Buttons({
                            style: {
                                layout: 'vertical',
                                color: 'gold',
                                shape: 'rect',
                                label: 'paypal'
                            },

                            createOrder: async function () {
                                const response = await fetch('acciones/paypal_create_order.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    }
                                });

                                const data = await response.json();

                                if (!response.ok || !data.id) {
                                    throw new Error(data.error || 'No se pudo crear la orden de PayPal');
                                }

                                return data.id;
                            },

                            onApprove: async function (data) {
                                const response = await fetch('acciones/paypal_capture_order.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        orderID: data.orderID
                                    })
                                });

                                const result = await response.json();

                                if (!response.ok || !result.ok) {
                                    throw new Error(result.error || 'No se pudo capturar el pago');
                                }

                                window.location.href = result.redirect;
                            },

                            onError: function () {
                                alert('Error en PayPal');
                            }
                        }).render('#paypal-button-container');
                        </script>
                    <?php endif; ?>
                </div>

                <aside class="checkout-resumen">
                    <h2>Resumen del pedido</h2>

                    <?php foreach ($items as $item): ?>
                        <div class="checkout-item">
                            <span><?php echo htmlspecialchars($item['nombre_producto']); ?> x<?php echo (int) $item['cantidad']; ?></span>
                            <strong><?php echo number_format((float) $item['subtotal'], 2); ?> €</strong>
                        </div>
                    <?php endforeach; ?>

                    <div class="checkout-total">
                        <span>Total</span>
                        <strong><?php echo number_format($total, 2); ?> €</strong>
                    </div>
                </aside>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php require_once("templates/footer.php"); ?>