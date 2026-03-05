<?php
require_once("header_admin.php");

// Mantenemos la lógica de captura de filtros
$categoria = $_GET['categoria'] ?? "todas";
$marca = $_GET['marca'] ?? "todas";
$precio = $_GET['precio'] ?? "todas";
$busqueda = $_GET['busqueda'] ?? "";

// La función productos() ya devuelve 'nombre_categoria' gracias al JOIN que hicimos antes
$productos = productos($conexion, $categoria, $marca, $precio, $busqueda);
?>

<main id="catalogo">
    <form method="GET" action="" class="filtros-inline">

        <?php 
        // CORRECCIÓN 1: Usar la nueva firma de generarSelect apuntando a la tabla categorias
        echo generarSelect($conexion, "categorias", "id_categoria", "nombre_categoria", "categoria", $categoria, "Categoría"); 
        ?>

        <?php 
        // CORRECCIÓN 2: Para la marca, seguimos usando productos pero con la nueva firma (id y nombre son lo mismo aquí)
        echo generarSelect($conexion, "productos", "marca", "marca", "marca", $marca, "Marca"); 
        ?>

        <select name="precio" id="filtro3">
            <option value="todas">Precio</option>
            <option value="asc">Menor - Mayor</option>
            <option value="desc">Mayor - Menor</option>
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
        foreach($productos as $producto): ?>
            <div class="product-card admin-card">
                <div class="menu-container">
                    <div class="admin-actions dropdown-menu">
                        <a href="producto_editar.php?id=<?= $producto['id_producto']; ?>" class="btn-editar">Editar</a>
                        <a href="producto_borrar.php?id=<?= $producto['id_producto'] . '&nombre_producto=' . urlencode($producto['nombre_producto']); ?>" class="btn-borrar">Borrar</a>
                    </div>
                </div>

                <a href="producto.php?id=<?= $producto['id_producto']; ?>" class="product-link">
                    <div class="img-container">
                        <img src="../static/img/productos/<?= $producto['imagen']; ?>" alt="Imagen">
                    </div>
                    <h3><?= htmlspecialchars($producto['nombre_producto']); ?></h3>

                    <h4>Categoría: <?= htmlspecialchars($producto['nombre_categoria'] ?? 'Sin categoría'); ?></h4>
                    <h4>Marca: <?= htmlspecialchars($producto['marca'] ?? 'Sin marca'); ?></h4>
                    
                    <p class="stock-info">
                        Stock: <span class="<?= ($producto['cantidad_existencias'] < 5) ? 'stock-low' : 'stock-ok' ?>">
                            <?= $producto['cantidad_existencias']; ?> uds.
                        </span>
                    </p>

                    <p>
                        Descripción: <?= $producto['descripcion'];?>
                    </p>

                    <p class="precio">$<?= number_format($producto['precio'], 2); ?></p>
                </a>
            </div>
        <?php endforeach; 
    } ?>
    </div>
</main>

<?php
require_once("../templates/footer.php");
?>