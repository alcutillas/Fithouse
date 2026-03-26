<?php 
$css = "formularios-admin";
include 'header_admin.php'; 

$error = false;
$nombreSeguro = null;

$stmt_cat = $conexion->query("SELECT * FROM categorias ORDER BY nombre_categoria ASC");
$categorias = $stmt_cat->fetchAll();

if (isset($_POST['guardar'])) {

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK && is_uploaded_file($_FILES['imagen']['tmp_name'])) {

        $directorio = "../static/img/productos";

        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $info = getimagesize($_FILES['imagen']['tmp_name']);

        if ($info === false) {
            echo "<script>alert('El archivo no es una imagen válida');</script>";
            $error = true;
        } else {
            $mime = $info['mime'];

            switch ($mime) {
                case 'image/jpeg':
                    $imagenOriginal = @imagecreatefromjpeg($_FILES['imagen']['tmp_name']);
                    break;
                case 'image/png':
                    $imagenOriginal = @imagecreatefrompng($_FILES['imagen']['tmp_name']);
                    break;
                case 'image/webp':
                    $imagenOriginal = @imagecreatefromwebp($_FILES['imagen']['tmp_name']);
                    break;
                default:
                    $imagenOriginal = false;
                    echo "<script>alert('Formato no soportado');</script>";
                    $error = true;
                    break;
            }

            if (!$error && !$imagenOriginal) {
                echo "<script>alert('Error al procesar la imagen');</script>";
                $error = true;
            }

            if (!$error) {
                $anchoOriginal = imagesx($imagenOriginal);
                $altoOriginal = imagesy($imagenOriginal);

                $nuevoAncho = 500;
                $nuevoAlto = ($altoOriginal / $anchoOriginal) * $nuevoAncho;

                $imagenNueva = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

                imagecopyresampled(
                    $imagenNueva,
                    $imagenOriginal,
                    0, 0, 0, 0,
                    $nuevoAncho,
                    $nuevoAlto,
                    $anchoOriginal,
                    $altoOriginal
                );

                if (function_exists('imagewebp')) {
                    $nombreSeguro = uniqid() . ".webp";
                    imagewebp($imagenNueva, $directorio . "/" . $nombreSeguro, 80);
                } else {
                    $nombreSeguro = uniqid() . ".jpg";
                    imagejpeg($imagenNueva, $directorio . "/" . $nombreSeguro, 75);
                }

                imagedestroy($imagenOriginal);
                imagedestroy($imagenNueva);
            }
        }
    } else {
        echo "<script>alert('Debes subir una imagen válida');</script>";
        $error = true;
    }

    if (!$error) {
        $id_categoria_final = $_POST['id_categoria'];

        if ($id_categoria_final === 'nueva') {
            $nombre_nueva_cat = trim($_POST['nueva_categoria']);

            $stmt_check = $conexion->prepare("SELECT id_categoria FROM categorias WHERE nombre_categoria = ?");
            $stmt_check->execute([$nombre_nueva_cat]);
            $existe = $stmt_check->fetchColumn();

            if ($existe) {
                $id_categoria_final = $existe;
            } else {
                $ins_cat = $conexion->prepare("INSERT INTO categorias (nombre_categoria) VALUES (?)");
                $ins_cat->execute([$nombre_nueva_cat]);
                $id_categoria_final = $conexion->lastInsertId();
            }
        }

        $nombre = trim($_POST["nombre_producto"]);
        $marca = trim($_POST["marca"]);
        $precio = (float) $_POST["precio"];
        $descripcion = trim($_POST["descripcion"]);
        $existencias = (int) $_POST["cantidad_existencias"];
        $precio_oferta = $_POST["precio_oferta"] !== '' ? (float) $_POST["precio_oferta"] : null;
        $oferta_inicio = !empty($_POST["oferta_inicio"]) ? $_POST["oferta_inicio"] : null;
        $oferta_fin = !empty($_POST["oferta_fin"]) ? $_POST["oferta_fin"] : null;
        $imagen = $nombreSeguro;

        if ($precio_oferta !== null && $precio_oferta >= $precio) {
            echo "<script>alert('El precio de oferta debe ser menor que el precio normal');</script>";
            $error = true;
        }

        if (!$error && $oferta_inicio && $oferta_fin && strtotime($oferta_fin) < strtotime($oferta_inicio)) {
            echo "<script>alert('La fecha de fin de oferta no puede ser menor que la de inicio');</script>";
            $error = true;
        }

        if (!$error) {
            $insert = $conexion->prepare("
                INSERT INTO productos (
                    nombre_producto, 
                    marca, 
                    precio, 
                    precio_oferta, 
                    oferta_inicio, 
                    oferta_fin, 
                    descripcion, 
                    imagen, 
                    id_categoria, 
                    cantidad_existencias
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $insert->execute([
                $nombre,
                $marca,
                $precio,
                $precio_oferta,
                $oferta_inicio,
                $oferta_fin,
                $descripcion,
                $imagen,
                $id_categoria_final,
                $existencias
            ]);

            header("Location: administrador.php?msg=creado");
            exit;
        }
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

        <label>Precio oferta</label>
        <input type="number" step="0.01" name="precio_oferta">

        <label>Inicio oferta</label>
        <input type="datetime-local" name="oferta_inicio">

        <label>Fin oferta</label>
        <input type="datetime-local" name="oferta_fin">

        <label>Descripción</label>
        <textarea name="descripcion" required></textarea>

        <label>Imagen</label>
        <input type="file" name="imagen" accept="image/*" required>

        <label>Existencias iniciales</label>
        <input type="number" name="cantidad_existencias" min="0" value="0" required>

        <label>Categoría</label>
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <select name="id_categoria" id="select_categoria" onchange="checkNuevaCat(this)" required>
                <option value="">Selecciona una categoría</option>
                <?php foreach($categorias as $cat): ?>
                    <option value="<?= $cat['id_categoria'] ?>"><?= htmlspecialchars($cat['nombre_categoria']) ?></option>
                <?php endforeach; ?>
                <option value="nueva" style="background-color: #f5c400; font-weight: bold;">+ Crear nueva categoría</option>
            </select>
            
            <input type="text" id="nueva_cat_input" name="nueva_categoria" placeholder="Escribe el nombre de la nueva categoría" style="display:none;">
        </div>

        <br>
        <div class="botones-form">
            <a href="administrador.php" class="btn-edit" style="text-decoration:none; background:#ccc; padding:10px; border-radius:5px; color:black;">Cancelar</a>
            <input type="submit" class="btn-save" value="Crear producto" name="guardar">
        </div>

    </form>
</main>

<script>
function checkNuevaCat(select) {
    const input = document.getElementById('nueva_cat_input');
    if (select.value === 'nueva') {
        input.style.display = 'block';
        input.required = true;
        input.focus();
    } else {
        input.style.display = 'none';
        input.required = false;
    }
}
</script>

<?php include '../templates/footer.php'; ?>