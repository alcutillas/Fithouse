<?php
$css = "producto";
require_once("templates/header.php");

//Obtener id del producto desde GET
if (!isset($_GET['id'])) {
    die("No se especificó el producto.");
}

$idProducto = $_GET['id'];
//PROCESAR ENVÍO DE RESEÑA
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_SESSION['id_usuario'])) {
        header('Location:iniciarsesion.php');
    }

    $idUsuario = $_SESSION['id_usuario'];
    $puntuacion = $_POST['puntuacion'];
    $comentario = $_POST['comentario'];

    if (crearResena($conexion, $idProducto, $idUsuario, $puntuacion, $comentario)) {
        // Recargar para evitar reenvío del formulario
        header("Location: producto.php?id=" . $idProducto);
        exit();
    } else {
        echo "Error al guardar la reseña.";
    }
}
//Obtener producto y reseñas
$producto = buscarProducto($conexion, $idProducto);
$producto = $producto[0];
if (!$producto) {
    die("Producto no encontrado.");
}

$resenas = obtenerResenas($conexion, $idProducto);

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
        <p class="descripcion"><strong>Descripción: </strong><?php echo htmlspecialchars($producto['descripcion']); ?></p>
    </div>
</div>


<h3>Reseñas del producto</h3>

<!-- FORMULARIO PARA AÑADIR RESEÑA -->
<div class="form-resena">
    <h4>Deja tu reseña</h4>
    <form method="POST">

        <select name="puntuacion" required>
            <option value="">Puntuación</option>
            <option value="5">⭐⭐⭐⭐⭐ (5)</option>
            <option value="4">⭐⭐⭐⭐ (4)</option>
            <option value="3">⭐⭐⭐ (3)</option>
            <option value="2">⭐⭐ (2)</option>
            <option value="1">⭐ (1)</option>
        </select>

        <textarea name="comentario" rows="4" placeholder="Escribe tu reseña..." required></textarea>

        <button type="submit">Enviar reseña</button>
    </form>
</div>

<?php if (empty($resenas)): ?>
    <p class="sin-resenas">No hay reseñas para este producto.</p>
<?php else: ?>
    <?php foreach ($resenas as $resena): ?>
        <div class="resena">
            <strong><?php echo htmlspecialchars($resena['nombre']); ?></strong> 
            <?php
            $puntuacion = (int)$resena['puntuacion'];

            // Estrellas llenas
            for ($i = 0; $i < $puntuacion; $i++) {
                echo '<span class="estrella llena">★</span>';
            }

            // Estrellas vacías
            for ($i = $puntuacion; $i < 5; $i++) {
                echo '<span class="estrella vacia">☆</span>';
            }
            ?>
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