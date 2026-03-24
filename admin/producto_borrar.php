<?php 
include 'header_admin.php'; 
require_once '../conexion.php';

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: administrador.php");
    exit;
}

$idProducto = (int) $_GET["id"];

$stmt = $conexion->prepare("SELECT nombre_producto, imagen FROM productos WHERE id_producto = ?");
$stmt->execute([$idProducto]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    header("Location: administrador.php");
    exit;
}

$mensajeError = "";

if (isset($_POST["cancelar"])) {
    header("Location: administrador.php");
    exit;
}

if (isset($_POST["borrar"])) {
    try {
        $stmt = $conexion->prepare("SELECT COUNT(*) FROM detalle_pedido WHERE id_producto = ?");
        $stmt->execute([$idProducto]);
        $tienePedidos = (int) $stmt->fetchColumn() > 0;

        if ($tienePedidos) {
            $mensajeError = "No se puede borrar este producto porque tiene pedidos asociados.";
        } else {
            $conexion->beginTransaction();

            $stmt = $conexion->prepare("DELETE FROM resenas WHERE id_producto = ?");
            $stmt->execute([$idProducto]);

            $stmt = $conexion->prepare("DELETE FROM detalle_carrito WHERE id_producto = ?");
            $stmt->execute([$idProducto]);

            $stmt = $conexion->prepare("DELETE FROM productos WHERE id_producto = ?");
            $stmt->execute([$idProducto]);

            $conexion->commit();

            if (!empty($producto['imagen'])) {
                $rutaImagen = dirname(__DIR__) . "/static/img/productos/" . $producto['imagen'];
                if (file_exists($rutaImagen)) {
                    unlink($rutaImagen);
                }
            }

            header("Location: administrador.php?msg=eliminado");
            exit;
        }
    } catch (Throwable $e) {
        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }
        $mensajeError = "Error al borrar el producto: " . $e->getMessage();
    }
}
?>

<main id="borrar" style="min-height:75vh">
    <h1>Borrar producto (admin)</h1>

    <strong>
        <?= "ID: " . $idProducto; ?>
        <br>
        <?= "Nombre: " . htmlspecialchars($producto["nombre_producto"]); ?>
    </strong>

    <?php if (!empty($mensajeError)): ?>
        <p style="color:#ff6b6b; margin:15px 0; font-weight:700;">
            <?= htmlspecialchars($mensajeError); ?>
        </p>
    <?php endif; ?>

    <form action="" method="post">
        <label>
            ¿Estás seguro de que deseas borrar el producto?
        </label>

        <div class="botones-form">
            <input type="submit" class="btn-editar" value="Cancelar" name="cancelar">
            <input type="submit" class="btn-borrar" value="Borrar" name="borrar">
        </div>
    </form>
</main>

<?php include '../templates/footer.php'; ?>