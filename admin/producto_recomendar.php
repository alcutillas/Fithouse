<?php
$css = "producto";
require_once 'header_admin.php';

$idProducto = (int)($_GET['id'] ?? 0);

$producto = buscarProducto($conexion, $idProducto)[0];
$tieneOferta = ofertaActiva($producto);
$descuento = $tieneOferta ? round((($producto['precio'] - $producto['precio_oferta']) / $producto['precio']) * 100) : 0;

$errorRecomendacion = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_tipo'] ?? '') === 'resena') {
    $texto = trim($_POST['recomendacion'] ?? '');

    if ($texto === '') {
        $errorRecomendacion = "La recomendación no puede estar vacía";
    } elseif (mb_strlen($texto) > 255) {
        $errorRecomendacion = "Máximo 255 caracteres";
    } else {
        $stmt = $conexion->prepare("
            UPDATE productos 
            SET recomendacion = :recomendacion
            WHERE id_producto = :id
        ");

        $stmt->execute([
            ":recomendacion" => $texto,
            ":id" => $idProducto
        ]);

        header("Location: producto_recomendar.php?id=" . $idProducto);
        exit;
    }
}
?>

<main id="producto-detalle">
    <section class="producto producto-detalle-wrap">
        <div class="producto-img">
            <?php if (!empty($producto['imagen'])): ?>
                <img
                    src="../static/img/productos/<?php echo htmlspecialchars($producto['imagen']); ?>"
                    alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                >
            <?php endif; ?>
        </div>

        <div class="producto-info">
            <?php if (!empty($producto['oferta_inicio']) || !empty($producto['oferta_fin'])): ?>
                <p class="oferta-fechas">
                    Oferta
                    <?php if (!empty($producto['oferta_inicio'])): ?>
                        desde <?php echo date('d/m/Y H:i', strtotime($producto['oferta_inicio'])); ?>
                    <?php endif; ?>
                    <?php if (!empty($producto['oferta_fin'])): ?>
                        hasta <?php echo date('d/m/Y H:i', strtotime($producto['oferta_fin'])); ?>
                    <?php endif; ?>
                </p>
            <?php endif; ?>

            <span class="marca"><?php echo htmlspecialchars($producto['marca']); ?></span>
            <h1><?php echo htmlspecialchars($producto['nombre_producto']); ?></h1>

            <?php if (!empty($producto['nombre_categoria'])): ?>
                <p class="categoria"><strong>Categoría:</strong> <?php echo htmlspecialchars($producto['nombre_categoria']); ?></p>
            <?php endif; ?>

            <?php if ($tieneOferta): ?>
                <p class="precio">
                    <span class="precio-original">$<?php echo number_format((float)$producto['precio'], 2); ?></span>
                    <span class="precio-oferta">$<?php echo number_format((float)$producto['precio_oferta'], 2); ?></span>
                    <span class="badge-oferta">-<?php echo $descuento; ?>%</span>
                </p>
            <?php else: ?>
                <p class="precio">
                    <?php echo number_format((float)$producto['precio'], 2); ?>
                </p>
            <?php endif; ?>

            <p class="descripcion"><?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>

            <?php if (isset($producto['cantidad_existencias'])): ?>
                <p class="stock">
                    <strong>Stock disponible:</strong> <?php echo (int) $producto['cantidad_existencias']; ?>
                </p>
            <?php endif; ?>
        </div>
    </section>

    <section class="resenas-wrap">
        <h2>Recomendación del producto</h2>

        <?php if (!empty($errorRecomendacion)): ?>
            <p class="error-resena"><?php echo htmlspecialchars($errorRecomendacion); ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="form_tipo" value="resena">
            <textarea
                name="recomendacion"
                rows="4"
                placeholder="Escribe tu recomendación..."
                required
            ><?= htmlspecialchars($producto['recomendacion'] ?? '') ?></textarea>

            <button type="submit" class="btn-principal">Guardar recomendación</button>
        </form>

        <div class="lista-resenas">
            <?php if (!empty($producto['recomendacion'])): ?>
                <article class="resena">
                    <?= nl2br(htmlspecialchars($producto['recomendacion'])) ?>
                </article>
            <?php else: ?>
                <article class="resena">
                    No hay recomendación aún.
                </article>
            <?php endif; ?>
        </div>
    </section>
</main>