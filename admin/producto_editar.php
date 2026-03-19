<?php 
include 'header_admin.php'; 

$id = $_GET["id"];

// 1. OBTENER DATOS DEL PRODUCTO ACTUAL
$consulta = "SELECT * FROM productos WHERE id_producto = ?";
$stmt = $conexion->prepare($consulta);
$stmt->execute([$id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    header("Location: administrador.php");
    exit;
}

// 2. OBTENER TODAS LAS CATEGORÍAS PARA EL SELECT
$stmt_cat = $conexion->query("SELECT * FROM categorias ORDER BY nombre_categoria ASC");
$categorias = $stmt_cat->fetchAll();

// Si se cancela
if (isset($_POST["cancelar"])) {
    header("Location: administrador.php");
    exit;
}

// Si se guarda
if (isset($_POST["guardar"])) {

    $extensiones = ["image/jpg", "image/jpeg", "image/png"];
    $directorio = "../static/img/productos";
    $nombreSeguro = $producto['imagen']; // Por defecto mantenemos la actual

    // GESTIÓN DE IMAGEN NUEVA
    $extensiones = ["image/jpeg", "image/png", "image/jpg"];
    $directorio = "../static/img/productos";

    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }
    
    // Mantener imagen actual por defecto
    $nombreSeguro = $producto['imagen'];
    
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    
        $info = getimagesize($_FILES['imagen']['tmp_name']);
    
        if ($info !== false) {
    
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
                    break;
            }
    
            if ($imagenOriginal) {
    
                // 🔥 borrar antigua solo si la nueva es válida
                if ($producto['imagen'] && file_exists($directorio . "/" . $producto['imagen'])) {
                    unlink($directorio . "/" . $producto['imagen']);
                }
    
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
    
                // 🔥 Guardar optimizada
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
    $stock = $_POST["cantidad_existencias"];

    // UPDATE usando el ID de categoría
    $update = "UPDATE productos 
               SET nombre_producto = ?, marca = ?, precio = ?, descripcion = ?, imagen = ?, id_categoria = ?, cantidad_existencias = ?
               WHERE id_producto = ?";

    $stmt = $conexion->prepare($update);
    $stmt->execute([$nombre, $marca, $precio, $descripcion, $nombreSeguro, $id_categoria_final, $stock, $id]);

    header("Location: administrador.php?msg=editado");
    exit;
}
?>

<main id="editar">
    <h1>Editar producto (admin)</h1>

    <form action="" method="post" enctype="multipart/form-data">

        <label>Nombre del producto</label>
        <input type="text" name="nombre_producto" value="<?= htmlspecialchars($producto["nombre_producto"]) ?>" required>

        <label>Marca</label>
        <input type="text" name="marca" value="<?= htmlspecialchars($producto["marca"]) ?>" required>

        <label>Precio</label>
        <input type="number" step="0.01" name="precio" value="<?= $producto["precio"] ?>" required>

        <label>Descripción</label>
        <textarea name="descripcion" required><?= htmlspecialchars($producto["descripcion"]) ?></textarea>

        <label>Imagen actual</label>
        <?php if ($producto["imagen"]): ?>
            <div style="margin-bottom: 10px;">
                <img src="../static/img/productos/<?= $producto["imagen"] ?>" width="150" style="border-radius: 8px;">
            </div>
        <?php else: ?>
            <p>No hay imagen</p>
        <?php endif; ?>

        <label>Subir nueva imagen (opcional)</label>
        <input type="file" name="imagen" accept="image/*">

        <label>Existencias actuales</label>
        <input type="number" name="cantidad_existencias" value="<?= $producto["cantidad_existencias"] ?>" min="0" required>

        <label>Categoría</label>
        <div style="display: flex; flex-direction: column; gap: 10px;">
        <select name="id_categoria" id="select_categoria" onchange="checkNuevaCat(this)" required>
    <option value="">Selecciona una categoría</option>
    <?php foreach($categorias as $cat): ?>
        <?php $selected = ($cat['id_categoria'] == $producto['id_categoria']) ? 'selected' : ''; ?>
        <option value="<?= $cat['id_categoria'] ?>" <?= $selected ?>>
            <?= htmlspecialchars($cat['nombre_categoria']) ?>
        </option>
    <?php endforeach; ?>
    <option value="nueva">+ Crear nueva categoría</option>
</select>
            
            <input type="text" id="nueva_cat_input" name="nueva_categoria" placeholder="Nombre de la nueva categoría" style="display:none;">
        </div>

        <br><br>
        <div class="botones-form">
            <input type="submit" class="btn-edit" value="Cancelar" name="cancelar" style="background:#ccc; color:black; cursor:pointer;">
            <input type="submit" class="btn-save" value="Guardar cambios" name="guardar">
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