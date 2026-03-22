<?php 
include 'header_admin.php'; 

$extensiones = ["image/jpg", "image/jpeg", "image/png", "image/webp"];
$error = false;

// Obtener todas las categorías para el select
$stmt_cat = $conexion->query("SELECT * FROM categorias ORDER BY nombre_categoria ASC");
$categorias = $stmt_cat->fetchAll();

if (isset($_POST['guardar'])) {

    // 1. GESTIÓN DE LA IMAGEN
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        if (is_uploaded_file($_FILES['imagen']['tmp_name'])) {
            $nombreOriginal = $_FILES['imagen']['name'];
            $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
            $nombreSeguro = uniqid() . "." . $extension;

            if (in_array($_FILES['imagen']['type'], $extensiones)) {
                $directorio = "../static/img/productos";
                // Asegúrate de que la carpeta existe
                if (!is_dir($directorio)) {
                    mkdir($directorio, 0777, true);
                }
                $directorio = "../static/img/productos";

                $extensiones = ["image/jpeg", "image/png", "image/jpg", "image/webp"];
                $directorio = "../static/img/productos";
                
                if (!is_dir($directorio)) {
                    mkdir($directorio, 0777, true);
                }
                
                $nombreSeguro = null;
                
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                
                    // 🔍 Detectar tipo REAL del archivo
                    $info = getimagesize($_FILES['imagen']['tmp_name']);
                
                    if ($info === false) {
                        echo "<script>alert('El archivo no es una imagen válida');</script>";
                        $error = true;
                    } else {
                
                        $mime = $info['mime'];
                
                        // 🔥 Crear imagen según tipo REAL
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
                                echo "<script>alert('Formato no soportado');</script>";
                                $error = true;
                                break;
                        }
                
                        // 🛑 VALIDACIÓN CLAVE
                        if (empty($error) && !$imagenOriginal) {
                            echo "<script>alert('Error al procesar la imagen');</script>";
                            $error = true;
                        }
                
                        if (empty($error)) {
                
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
                
                            // 🔥 Guardado seguro (con fallback)
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
                }
            } else {
                echo "<script>alert('Error: Extensión de imagen no permitida');</script>";
                $error = true;
            }
        } else {
            $error = true;
        }
    } else {
        $error = true;
    }

if (!$error) {
    // 3. GESTIÓN DE LA CATEGORÍA (Evitando duplicados)
$id_categoria_final = $_POST['id_categoria'];

if ($id_categoria_final === 'nueva') {
    $nombre_nueva_cat = trim($_POST['nueva_categoria']);
    
    // Verificamos si ya existe una categoría con ese nombre exacto
    $stmt_check = $conexion->prepare("SELECT id_categoria FROM categorias WHERE nombre_categoria = ?");
    $stmt_check->execute([$nombre_nueva_cat]);
    $existe = $stmt_check->fetchColumn();

    if ($existe) {
        // Si existe, simplemente usamos el ID de la que ya estaba creada
        $id_categoria_final = $existe;
    } else {
        // Si no existe, la creamos de cero
        $ins_cat = $conexion->prepare("INSERT INTO categorias (nombre_categoria) VALUES (?)");
        $ins_cat->execute([$nombre_nueva_cat]);
        $id_categoria_final = $conexion->lastInsertId();
    }
}

    $nombre = $_POST["nombre_producto"];
    $marca = $_POST["marca"];
    $precio = $_POST["precio"];
    $descripcion = $_POST["descripcion"];
    $existencias = $_POST["cantidad_existencias"];
    $imagen = $nombreSeguro;

    // Cambiamos 'categoria' (texto) por 'id_categoria' (número)
    $insert = $conexion->prepare("
        INSERT INTO productos (nombre_producto, marca, precio, descripcion, imagen, id_categoria, cantidad_existencias)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $insert->execute([$nombre, $marca, $precio, $descripcion, $imagen, $id_categoria_final, $existencias]);

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

        <label>Existencias iniciales</label>
        <input type="number" name="cantidad_existencias" min="0" value="0" required>

        <label>Categoría</label>
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <select name="id_categoria" id="select_categoria" onchange="checkNuevaCat(this)" required>
                <option value="">Selecciona una categoría</option>
                <?php foreach($categorias as $cat): ?>
                    <option value="<?= $cat['id_categoria'] ?>"><?= $cat['nombre_categoria'] ?></option>
                <?php endforeach; ?>
                <option value="nueva" style="background-color: #e0f7fa; font-weight: bold;">+ Crear nueva categoría</option>
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
// Función para mostrar/ocultar el campo de nueva categoría
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