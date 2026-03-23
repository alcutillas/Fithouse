<?php
// carrito.php
$css = "carrito";
require_once("templates/header.php");

$items = obtenerItemsCarrito($conexion);
$total = obtenerTotalCarrito($conexion);
?>

<main id="carrito">
    <section class="carrito-wrap">
        <div class="carrito-head">
            <h1>Mi carrito</h1>
            <a href="catalogo.php" class="btn-secundario">Seguir comprando</a>
        </div>

        <?php if (!$items): ?>
            <div class="carrito-vacio">
                <h2>Tu carrito está vacío</h2>
                <p>Añade productos desde el catálogo.</p>
                <a href="catalogo.php" class="btn-principal">Ir al catálogo</a>
            </div>
        <?php else: ?>
            <div class="carrito-grid">
                <div class="carrito-lista">
                    <?php foreach ($items as $item): ?>
                        <article class="carrito-item">
                            <div class="carrito-item-img">
                                <img src="./static/img/productos/<?php echo htmlspecialchars($item['imagen']); ?>" alt="<?php echo htmlspecialchars($item['nombre_producto']); ?>">
                            </div>

                            <div class="carrito-item-info">
                                <span class="marca"><?php echo htmlspecialchars($item['marca']); ?></span>
                                <h3><?php echo htmlspecialchars($item['nombre_producto']); ?></h3>
                                <p class="descripcion"><?php echo htmlspecialchars($item['descripcion']); ?></p>
                                <p class="precio-unitario"><?php echo number_format((float) $item['precio_unitario'], 2); ?> € / unidad</p>
                            </div>

                            <div class="carrito-item-acciones">
                                <form action="acciones/actualizar_carrito.php" method="post" class="cantidad-form">
                                    <input type="hidden" name="id_detalle_carrito" value="<?php echo (int) $item['id_detalle_carrito']; ?>">

                                    

                                    <input
                                        type="number"
                                        name="cantidad"
                                        value="<?php echo (int) $item['cantidad']; ?>"
                                        min="1"
                                        max="<?php echo (int) $item['cantidad_existencias']; ?>"
                                    >

                                    
                                    <button type="submit" name="accion" value="actualizar" class="btn-principal">Actualizar</button>
                                </form>

                                <div class="subtotal">
                                    <span>Subtotal</span>
                                    <strong><?php echo number_format((float) $item['subtotal'], 2); ?> €</strong>
                                </div>

                                <form action="acciones/eliminar_carrito.php" method="post">
                                    <input type="hidden" name="id_detalle_carrito" value="<?php echo (int) $item['id_detalle_carrito']; ?>">
                                    <button type="submit" class="btn-eliminar">Eliminar</button>
                                </form>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <aside class="carrito-resumen">
                <h2>Resumen del pedido</h2>

                <div class="resumen-lista">
                    <?php foreach ($items as $item): ?>
                        <div class="resumen-item">
                            <div class="resumen-item-top">
                                <span class="resumen-nombre"><?php echo htmlspecialchars($item['nombre_producto']); ?></span>
                                <strong class="resumen-subtotal"><?php echo number_format((float) $item['subtotal'], 2); ?> €</strong>
                            </div>

                            <div class="resumen-item-detalles">
                                <span>Cantidad: <?php echo (int) $item['cantidad']; ?></span>
                                <span>Precio: <?php echo number_format((float) $item['precio_unitario'], 2); ?> € / unidad</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="resumen-totales">
                    <div class="resumen-linea">
                        <span>Productos distintos</span>
                        <strong><?php echo count($items); ?></strong>
                    </div>

                    <div class="resumen-linea">
                        <span>Unidades totales</span>
                        <strong>
                            <?php
                            $unidadesTotales = 0;
                            foreach ($items as $item) {
                                $unidadesTotales += (int) $item['cantidad'];
                            }
                            echo $unidadesTotales;
                            ?>
                        </strong>
                    </div>

                    <div class="resumen-linea total">
                        <span>Total</span>
                        <strong><?php echo number_format($total, 2); ?> €</strong>
                    </div>
                </div>

                <a href="checkout.php" class="btn-principal btn-full">Ir al pago</a>
            </aside>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php require_once("templates/footer.php"); ?>