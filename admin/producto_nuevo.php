<?php 
include 'header_admin.php'; 

$extensiones = ["image/jpg", "image/jpeg", "image/png"];
$error = false;

if (isset($_POST['guardar'])) {

    // -------------------------
    // SUBIR IMAGEN
    // -------------------------
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

        if (is_uploaded_file($_FILES['imagen']['tmp_name'])) {

            $nombreOriginal = $_FILES['imagen']['name'];
            $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
            $nombreSeguro = uniqid() . "." . $extension;

            if (in_array($_FILES['imagen']['type'], $extensiones)) {

                $directorio = $_SERVER['DOCUMENT_ROOT'] . "/static/img/productos";
                move_uploaded_file($_FILES['imagen']['tmp_name'], $directorio . "/" . $nombreSeguro);

            } else {
                echo "No se permite esa extensión";
                $error = true;
            }

        } else {
            $error = true;
        }

    } else {
        $error = true;
    }

    if (!$error) {

        // -------------------------
        // INSERTAR PRODUCTO
        // -------------------------
        $nombre = $_POST["nombre_producto"];
        $marca = $_POST["marca"];
        $precio = $_POST["precio"];
        $descripcion = $_POST["descripcion"];
        $imagen = $nombreSeguro;

        $insert = $conexion->prepare("
            INSERT INTO productos (nombre_producto, marca, precio, descripcion, imagen)
            VALUES (?, ?, ?, ?, ?)
        ");

        $insert->execute([$nombre, $marca, $precio, $descripcion, $imagen]);

        header("Location: administrador.php?msg=creado");
        exit;
    }
}
?>

<main id="editar">
    <h1>Nuevo producto (admin)</h1>

    <form action="" method="post" enctype="multipart/form-data">

        <label>Nombre del producto</label>
        <input type="text" name="nombre_producto" required>

        <label>Marca</label>
        <input type="text" name="marca" required>

        <label>Precio</label>
        <input type="number" step="0.01" name="precio" required>

        <label>Descripción</label>
        <textarea name="descripcion" required></textarea>

        <label>Imagen</label>
        <input type="file" name="imagen" required>

        <br><br>

        <a href="admin_productos.php" class="btn-edit" style="text-decoration:none">Cancelar</a>
        <input type="submit" class="btn-save" value="Crear producto" name="guardar">

    </form>
</main>

<?php include '../templates/footer.php'; ?>
