<?php
require_once("header_admin.php");

$categoria = $_GET['categoria'] ?? "todas";
$marca = $_GET['marca'] ?? "todas";
$precio = $_GET['precio'] ?? "todas";
$busqueda = $_GET['busqueda'] ?? "";


$productos = productos($conexion, $categoria, $marca, $precio, $busqueda);

?>
<main id="catalogo" style="padding:15px">
<!--Filtro-->
<a href="producto_nuevo.php"class="btn-nuevo">Nuevo Producto</a>

<form method="GET" action="" class="filtros-inline">

    <?php echo generarSelect($conexion, "productos", "categoria", "categoria", "", "Categoria"); ?>
    <?php echo generarSelect($conexion, "productos", "marca", "marca", "", "Marca"); ?>

    <select name="precio" id="filtro3">
        <option value="todas">Precio</option>
        <option value="asc">Menor - Mayor</option>
        <option value="desc">Mayor - Menor</option>
    </select>

    <input type="text" name="busqueda" placeholder="Buscar producto..." 
           value="<?php echo $_GET['busqueda'] ?? ''; ?>">

           <button class="btn-filtrar">Filtrar</button>

<a href="catalogo.php" class="btn-resetear">Borrar filtros</a>

</form>


<div class="productos-container">

<?php
if(count($productos) == 0){
    echo "<h1>No disponemos de productos con esos requisitos</h1>";
}else{
    foreach($productos as $producto): ?>
    
    <div class="product-card admin-card">

        <div class="admin-actions">
            <a href="producto_editar.php?id=<?php echo $producto['id_producto']; ?>&nombre=<?php echo $producto['nombre_producto']; ?>" class="btn-editar">Editar</a>

            <a href="producto_borrar.php?id=<?php echo $producto['id_producto']; ?>&nombre=<?php echo $producto['nombre_producto']; ?>"
            class="btn-borrar">
               Borrar
            </a>
        </div>

        <a href="producto.php?id=<?php echo $producto['id_producto']; ?>" class="product-link">
        <div class="img-container">
    <img src="../static/img/productos/<?php echo $producto['imagen']; ?>" alt="Imagen">
    </div>
            <h3><?php echo $producto['nombre_producto']; ?></h3>
            <p><?php echo $producto['marca']; ?></p>
            <p class="precio">$<?php echo number_format($producto['precio'], 2); ?></p>
            <p class="descripcion"><?php echo $producto['descripcion']; ?></p>
        </a>

    </div>

<?php
    endforeach; 
}
?>

</div>


</main>

<?php
require_once("../templates/footer.php");
?>