<?php
$css = "catalogo";
require_once("templates/header.php");

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

?>
<main id="catalogo">

<form method="GET" action="" class="filtros-inline">

<?php
echo generarSelect($conexion, "categorias", "id_categoria", "nombre_categoria", "categoria", $categoria, "Categoría");
echo generarSelect($conexion, "productos", "marca", "marca", "marca", $marca, "Marca");
?>
    <select name="precio" id="filtro3">
        <option value="todas" <?= $precio === 'todas' ? 'selected' : ''; ?>>Precio</option>
        <option value="asc" <?= $precio === 'asc' ? 'selected' : ''; ?>>Menor - Mayor</option>
        <option value="desc" <?= $precio === 'desc' ? 'selected' : ''; ?>>Mayor - Menor</option>
    </select>

    <input type="text" name="busqueda" placeholder="Buscar producto..." 
           value="<?= htmlspecialchars($busqueda); ?>">

    <button class="btn-filtrar">Filtrar</button>

    <a href="catalogo.php" class="btn-resetear">Borrar filtros</a>
</form>

<div class="productos-container">

<?php
if(count($productos) == 0){
    echo "<h1>No disponemos de productos con esos requisitos</h1>";
}else{
foreach($productos as $producto):
    $tieneOferta = ofertaActiva($producto);
    $precioFinal = $tieneOferta ? $producto['precio_oferta'] : $producto['precio'];
    $descuento = $tieneOferta ? round((($producto['precio'] - $producto['precio_oferta']) / $producto['precio']) * 100) : 0;
?>
    <a href="producto.php?id=<?= $producto['id_producto']; ?>" class="product-card">
        <div class="img-container">
            <img loading="lazy" src="./static/img/productos/<?= htmlspecialchars($producto['imagen']); ?>" alt="Imagen">

            <?php if (!empty($producto['oferta_inicio']) || !empty($producto['oferta_fin'])): ?>
                <?php if (!empty($producto['oferta_fin'])): ?>
                    <p class="oferta-fechas">
                        Hasta <?= date('d/m/Y H:i', strtotime($producto['oferta_fin'])); ?>
                    </p>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <h3><?= htmlspecialchars($producto['nombre_producto']); ?></h3>
        <span><?= htmlspecialchars($producto['marca']); ?></span>

        <?php if ($tieneOferta): ?>
            <div class="precio precio-oferta-wrap">
                <div class="precio-col-izq">
                    <span class="precio-original">$<?= number_format($producto['precio'], 2); ?></span>
                </div>
                <div class="precio-col-der">
                    <span class="precio-oferta">$<?= number_format($producto['precio_oferta'], 2); ?></span>
                </div>
            </div>

            <span class="badge-oferta">-<?= $descuento; ?>%</span>
        <?php else: ?>
            <p class="precio">
                <span class="precio-normal">$<?= number_format($precioFinal, 2); ?></span>
            </p>
        <?php endif; ?>
    </a>

<?php
endforeach; 
}
?>

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
require_once("templates/footer.php");
?>