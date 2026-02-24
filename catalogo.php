<?php
$css = "catalogo";
require_once("templates/header.php");


$categoria = $_GET['categoria'] ?? "todas";
$marca = $_GET['marca'] ?? "todas";
$precio = $_GET['precio'] ?? "todas";
$busqueda = $_GET['busqueda'] ?? "";


$productos = productos($conexion, $categoria, $marca, $precio, $busqueda);

?>
<main id="catalogo">
<!--Filtro-->

<form method="GET" action="" class="filtros-inline">

    <?php echo generarSelect($conexion, "productos", "categoria", "categoria", "", "Categoría"); ?>
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
    <a href="producto.php?id=<?php echo $producto['id_producto']; ?>" class="product-card">
    <div class="img-container">
    <img src="./static/img/productos/<?php echo $producto['imagen']; ?>" alt="Imagen">
    </div>
    <h3><?php echo $producto['nombre_producto']; ?></h3>
    <p><?php echo $producto['marca']; ?></p>
    <p class="precio">$<?php echo number_format($producto['precio'], 2); ?></p>
    <p class="descripcion"><?php echo $producto['descripcion']; ?></p>
</a>


<?php
endforeach; 
}
?>


</div>

</main>

<?php
require_once("templates/footer.php");
?>