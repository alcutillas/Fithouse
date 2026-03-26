<?php
$css = "producto";
require_once("templates/header.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: catalogo.php");
    exit;
}

$idProducto = (int) $_GET['id'];

$producto = buscarProducto($conexion, $idProducto);

if (!$producto || !isset($producto[0])) {
    header("Location: catalogo.php");
    exit;
}

$producto = $producto[0];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form_tipo"]) && $_POST["form_tipo"] === "resena") {
    if (!isset($_SESSION['id_usuario'])) {
        header("Location: iniciarsesion.php");
        exit;
    }

    $idUsuario = (int) $_SESSION['id_usuario'];
    $puntuacion = isset($_POST['puntuacion']) ? (int) $_POST['puntuacion'] : 0;
    $comentario = trim($_POST['comentario'] ?? "");

    if ($puntuacion >= 1 && $puntuacion <= 5 && $comentario !== "") {
        if (crearResena($conexion, $idProducto, $idUsuario, $puntuacion, $comentario)) {
            header("Location: producto.php?id=" . $idProducto);
            exit;
        }

        $errorResena = "Error al guardar la reseña.";
    } else {
        $errorResena = "Completa correctamente la puntuación y el comentario.";
    }
}

$resenas = obtenerResenas($conexion, $idProducto);
$tieneOferta = ofertaActiva($producto);
$descuento = $tieneOferta ? round((($producto['precio'] - $producto['precio_oferta']) / $producto['precio']) * 100) : 0;
?>

<main id="producto-detalle">
    <section class="producto producto-detalle-wrap">
        <div class="producto-img">
            <?php if (!empty($producto['imagen'])): ?>
                <img
                    src="./static/img/productos/<?php echo htmlspecialchars($producto['imagen']); ?>"
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

            <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
                <p class="error-resena">No se pudo añadir el producto al carrito.</p>
            <?php endif; ?>

            <?php if (isset($producto['cantidad_existencias']) && (int)$producto['cantidad_existencias'] > 0): ?>
                <form action="acciones/agregar_carrito.php" method="POST" class="form-add-cart">
                    <input type="hidden" name="id_producto" value="<?php echo (int) $producto['id_producto']; ?>">

                    <label for="cantidad">Cantidad</label>
                    <input
                        type="number"
                        name="cantidad"
                        id="cantidad"
                        min="1"
                        max="<?php echo (int) $producto['cantidad_existencias']; ?>"
                        value="1"
                        required
                    >

                    <button type="submit" class="btn-principal">Añadir al carrito</button>
                </form>
            <?php else: ?>
                <p class="sin-stock">Producto sin stock</p>
            <?php endif; ?>
        </div>
    </section>
    
    <section class="resenas-wrap">
        <h2>Reseñas del producto</h2>


            <?php if (!empty($errorResena)): ?>
                <p class="error-resena"><?php echo htmlspecialchars($errorResena); ?></p>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="form_tipo" value="resena">

                <select name="puntuacion" required>
                    <option value="">Puntuación</option>
                    <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                    <option value="4">⭐⭐⭐⭐ (4)</option>
                    <option value="3">⭐⭐⭐ (3)</option>
                    <option value="2">⭐⭐ (2)</option>
                    <option value="1">⭐ (1)</option>
                </select>

                <textarea
                    name="comentario"
                    rows="4"
                    placeholder="Escribe tu reseña..."
                    required
                ></textarea>

                <button type="submit" class="btn-principal">Enviar reseña</button>
            </form>

        <div class="lista-resenas">
            <?php if (empty($resenas)): ?>
                <p class="sin-resenas">No hay reseñas para este producto.</p>
            <?php else: ?>
                <?php foreach ($resenas as $resena): ?>
                    <article class="resena">
                        <div class="resena-top">
                            <strong><?php echo htmlspecialchars($resena['nombre']); ?></strong>

                            <div class="resena-estrellas">
                                <?php
                                $puntuacion = (int) $resena['puntuacion'];

                                for ($i = 0; $i < $puntuacion; $i++) {
                                    echo '<span class="estrella llena">★</span>';
                                }

                                for ($i = $puntuacion; $i < 5; $i++) {
                                    echo '<span class="estrella vacia">☆</span>';
                                }
                                ?>
                            </div>
                        </div>

                        <em><?php echo date("d/m/Y H:i", strtotime($resena['fecha_resena'])); ?></em>
                        <p><?php echo nl2br(htmlspecialchars($resena['comentario'])); ?></p>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
require_once("templates/footer.php");
?>