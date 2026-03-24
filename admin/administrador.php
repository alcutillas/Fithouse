<?php
require_once("header_admin.php");

$categoria = $_GET['categoria'] ?? "todas";
$marca = $_GET['marca'] ?? "todas";
$precio = $_GET['precio'] ?? "todas";
$busqueda = $_GET['busqueda'] ?? "";

$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;

$porPagina = 12;
$offset = ($pagina - 1) * $porPagina;

$totalProductos = contarProductos($conexion, $categoria, $marca, $busqueda);
$totalPaginas = (int) ceil($totalProductos / $porPagina);

$productos = productos($conexion, $categoria, $marca, $precio, $busqueda, $porPagina, $offset);

function ofertaActiva($producto) {
    if (empty($producto['precio_oferta'])) {
        return false;
    }

    $ahora = time();

    $inicioValido = empty($producto['oferta_inicio']) || strtotime($producto['oferta_inicio']) <= $ahora;
    $finValido = empty($producto['oferta_fin']) || strtotime($producto['oferta_fin']) >= $ahora;

    return $inicioValido && $finValido && $producto['precio_oferta'] < $producto['precio'];
}
?>

<main id="catalogo">
    <form method="GET" action="" class="filtros-inline">

        <?php 
        echo generarSelect($conexion, "categorias", "id_categoria", "nombre_categoria", "categoria", $categoria, "Categoría"); 
        ?>

        <?php 
        echo generarSelect($conexion, "productos", "marca", "marca", "marca", $marca, "Marca"); 
        ?>

        <select name="precio" id="filtro3">
            <option value="todas" <?= $precio === 'todas' ? 'selected' : ''; ?>>Precio</option>
            <option value="asc" <?= $precio === 'asc' ? 'selected' : ''; ?>>Menor - Mayor</option>
            <option value="desc" <?= $precio === 'desc' ? 'selected' : ''; ?>>Mayor - Menor</option>
        </select>

        <input type="text" name="busqueda" placeholder="Buscar producto..." 
               value="<?php echo htmlspecialchars($busqueda); ?>">

        <button class="btn-filtrar">Filtrar</button>

        <a href="administrador.php" class="btn-resetear">Borrar filtros</a>
        <a href="producto_nuevo.php" class="btn-nuevo">Nuevo Producto</a>
    </form>

    <div class="productos-container">
    <?php
    if(count($productos) == 0){
        echo "<h1>No disponemos de productos con esos requisitos</h1>";
    } else {
        foreach($productos as $producto):
            $tieneOferta = ofertaActiva($producto);
            $precioFinal = $tieneOferta ? $producto['precio_oferta'] : $producto['precio'];
            $descuento = $tieneOferta ? round((($producto['precio'] - $producto['precio_oferta']) / $producto['precio']) * 100) : 0;
        ?>


<div class="product-card admin-card">
                <div class="menu-container">
                    <div class="admin-actions dropdown-menu">
                        <a href="producto_editar.php?id=<?= $producto['id_producto']; ?>" class="btn-editar">Editar</a>
                        <a href="producto_borrar.php?id=<?= $producto['id_producto'] . '&nombre_producto=' . urlencode($producto['nombre_producto']); ?>" class="btn-borrar">Borrar</a>
                    </div>
                </div>

<a href="../producto.php?id=<?= $producto['id_producto']; ?>" style="text-decoration: none; color: inherit;">
        <div class="img-container">
            <img loading="lazy" src="../static/img/productos/<?= htmlspecialchars($producto['imagen']); ?>" alt="Imagen">
        </div>

        <h3><?= htmlspecialchars($producto['nombre_producto']); ?></h3>
        <span><?= htmlspecialchars($producto['marca']); ?></span><br>

        <?php if ($tieneOferta): ?>
            <p class="precio">
                <span class="precio-original">$<?= number_format($producto['precio'], 2); ?></span>
                <span class="precio-oferta">$<?= number_format($producto['precio_oferta'], 2); ?></span>
            </p>

            <span class="badge-oferta">-<?= $descuento; ?>%</span>

            <?php if (!empty($producto['oferta_inicio']) || !empty($producto['oferta_fin'])): ?>
                <p class="oferta-fechas">
                    <?php if (!empty($producto['oferta_fin'])): ?>
                        Hasta <?= date('d/m/Y H:i', strtotime($producto['oferta_fin'])); ?>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        <?php else: ?>
            <p class="precio">
                <span class="precio-normal">$<?= number_format($precioFinal, 2); ?></span>
            </p>
        <?php endif; ?>

        <p class="descripcion"><?= htmlspecialchars($producto['descripcion']); ?></p>
    </a>
</div>
        <?php endforeach; 
    } ?>
    </div>

<?php if ($totalPaginas > 1): ?>
    <div class="paginacion">
        <?php if ($pagina > 1): ?>
            <a href="?categoria=<?= urlencode($categoria) ?>&marca=<?= urlencode($marca) ?>&precio=<?= urlencode($precio) ?>&busqueda=<?= urlencode($busqueda) ?>&pagina=<?= $pagina - 1 ?>">← Anterior</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <a href="?categoria=<?= urlencode($categoria) ?>&marca=<?= urlencode($marca) ?>&precio=<?= urlencode($precio) ?>&busqueda=<?= urlencode($busqueda) ?>&pagina=<?= $i ?>"
               class="<?= $i === $pagina ? 'activa' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($pagina < $totalPaginas): ?>
            <a href="?categoria=<?= urlencode($categoria) ?>&marca=<?= urlencode($marca) ?>&precio=<?= urlencode($precio) ?>&busqueda=<?= urlencode($busqueda) ?>&pagina=<?= $pagina + 1 ?>">Siguiente →</a>
        <?php endif; ?>
    </div>
<?php endif; ?>

</main>

<?php
require_once("../templates/footer.php");
?>