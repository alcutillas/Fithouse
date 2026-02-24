<?php 
include 'header_admin.php'; 

$id = $_GET["id"];

// Obtener datos del producto
$consulta = "SELECT * FROM productos WHERE id_producto = ?";
$stmt = $conexion->prepare($consulta);
$stmt->execute([$id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    header("Location: administrador.php");
    exit;
}

// Si se cancela
if (isset($_POST["cancelar"])) {
    header("Location: administrador.php");
    exit;
}

// Si se guarda
if (isset($_POST["guardar"])) {

    $extensiones = ["image/jpg", "image/jpeg", "image/png"];

    // Obtener imagen actual
    $consulta = "SELECT imagen FROM productos WHERE id_producto = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->execute([$id]);
    $res = $stmt->fetch();
    $imagenActual = $res['imagen'];

    $directorio = $_SERVER['DOCUMENT_ROOT'] . "/Fithouse/static/img/productos";

    // Si se sube una nueva imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

        // Borrar imagen anterior si existe
        if ($imagenActual && file_exists($directorio . "/" . $imagenActual)) {
            unlink($directorio . "/" . $imagenActual);
        }

        // Subir nueva imagen
        $nombreOriginal = $_FILES['imagen']['name'];
        $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
        $nombreSeguro = uniqid() . "." . $extension;

        if (in_array($_FILES['imagen']['type'], $extensiones)) {
            move_uploaded_file($_FILES['imagen']['tmp_name'], $directorio . "/" . $nombreSeguro);
        }

    } else {
        // Si no se sube imagen nueva, mantener la actual
        $nombreSeguro = $imagenActual;
    }

    // Datos del formulario
    $nombre = $_POST["nombre_producto"];
    $marca = $_POST["marca"];
    $precio = $_POST["precio"];
    $descripcion = $_POST["descripcion"];

    // Actualizar producto
    $update = "UPDATE productos 
               SET nombre_producto = ?, marca = ?, precio = ?, descripcion = ?, imagen = ?
               WHERE id_producto = ?";

    $stmt = $conexion->prepare($update);
    $stmt->execute([$nombre, $marca, $precio, $descripcion, $nombreSeguro, $id]);

    header("Location: administrador.php?msg=editado");
    exit;
}
?>

<main id="editar">
    <h1>Editar producto (admin)</h1>

    <form action="" method="post" enctype="multipart/form-data">

        <label>Nombre del producto</label>
        <input type="text" name="nombre_producto" value="<?= $producto["nombre_producto"] ?>" required>

        <label>Marca</label>
        <input type="text" name="marca" value="<?= $producto["marca"] ?>" required>

        <label>Precio</label>
        <input type="number" step="0.01" name="precio" value="<?= $producto["precio"] ?>" required>

        <label>Descripción</label>
        <textarea name="descripcion" required><?= $producto["descripcion"] ?></textarea>

        <label>Imagen actual</label>
        <?php if ($producto["imagen"]): ?>
            <img src="../static/img/<?= $producto["imagen"] ?>" width="150">
        <?php else: ?>
            <p>No hay imagen</p>
        <?php endif; ?>

        <label>Subir nueva imagen (opcional)</label>
        <input type="file" name="imagen">

        <br><br>

        <input type="submit" class="btn-edit" value="Cancelar" name="cancelar">
        <input type="submit" class="btn-save" value="Guardar cambios" name="guardar">

    </form>
</main>

<?php include '../templates/footer.php'; ?>
