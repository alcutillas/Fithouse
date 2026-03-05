<?php 
include 'header_admin.php'; 
require_once '../conexion.php';

// Si no llega ID, volvemos al panel
if (!isset($_GET["id"])) {
    header("Location: administrador.php");
    exit;
}

// Botón cancelar
if (isset($_POST["cancelar"])) {
    header("Location: administrador.php");
    exit;
}

// Botón borrar
if (isset($_POST["borrar"])) {

    // 1. Buscar la imagen del producto
    $consulta = "SELECT imagen FROM productos WHERE id_producto = ?";
    $stmt = $conexion->prepare($consulta);

    try {
        $stmt->execute([$_GET["id"]]);
    } catch (Exception $e) {
        echo "Error al buscar la imagen del producto";
    }

    $resultado = $stmt->fetch();
    $nombreImagen = $resultado['imagen'] ?? null;

    // 2. Borrar el producto
    $consulta = "DELETE FROM productos WHERE id_producto = ?";
    $stmt = $conexion->prepare($consulta);

    try {
        $stmt->execute([$_GET["id"]]);
    } catch (Exception $e) {
        echo "Error al borrar el producto";
    }

    // 3. Borrar la imagen si existe
    if ($nombreImagen) {
        $directorio = $_SERVER['DOCUMENT_ROOT'] . "/Fithouse/static/img/productos";
        $rutaCompleta = $directorio . "/" . $nombreImagen;

        if (file_exists($rutaCompleta)) {
            unlink($rutaCompleta);
        }
    }

    // 4. Volver al panel
    header("Location: administrador.php?msg=eliminado");
    exit;
}
?>

<main id="borrar" style="min-height:75vh">
    <h1>Borrar producto (admin)</h1>
    <strong>
    <?php
    echo "ID: " . $_GET["id"];
    echo "<br>Nombre: " . $_GET["nombre_producto"];
    ?>
    </strong>
    <form action="" method="post">
        <label>
            ¿Estás seguro de que deseas borrar el producto?
        </label>

        <input type="submit" class="btn-editar" value="Cancelar" name="cancelar">
        <input type="submit" class="btn-borrar" value="Borrar" name="borrar">
    </form>
</main>

<?php include '../templates/footer.php'; ?>
