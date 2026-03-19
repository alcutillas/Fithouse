<?php
$css = "producto";
require_once("templates/header.php");

//Obtener id del producto desde GET
if (!isset($_GET['id'])) {
    die("No se especificó el producto.");
}

$idProducto = $_GET['id'];

//Obtener producto y reseñas
$producto = buscarProducto($conexion, $idProducto);
$producto = $producto[0];
if (!$producto) {
    die("Producto no encontrado.");
}

//$resenas = obtenerResenas($conexion, $idProducto);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo($producto['nombre_producto']); ?></title>
</head>
<body>

<div class="producto">
    <?php if (!empty($producto['imagen'])): ?>
        <img src="<?php echo('./static/img/productos/' . $producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">
    <?php endif; ?>
    
    <div class="producto-info">
        <h2><?php echo htmlspecialchars($producto['nombre_producto']); ?></h2>
        <p><strong>Marca:</strong> <?php echo $producto['marca']; ?></p>
        <p><strong>Categoría:</strong> <?php echo $producto['nombre_categoria']; ?></p>
        <p class="precio"><strong>Precio:</strong> $<?php echo $producto['precio']; ?></p>
        <p class="descripcion"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
    </div>
</div>


<h3>Reseñas del producto</h3>
<?php if (empty($resenas)): ?>
    <p>No hay reseñas para este producto.</p>
<?php else: ?>
    <?php foreach ($resenas as $resena): ?>
        <div class="resena">
            <strong><?php echo htmlspecialchars($resena['nombre']); ?></strong> 
            - <?php echo htmlspecialchars($resena['puntuacion']); ?>/5
            <br>
            <em><?php echo date("d/m/Y H:i", strtotime($resena['fecha_resena'])); ?></em>
            <p><?php echo nl2br(htmlspecialchars($resena['comentario'])); ?></p>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>



<?php
require_once("templates/footer.php");
?>