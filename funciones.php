<?php

function usuarios($conexion){
    $consulta = "SELECT * from usuarios";
  $preparada = $conexion -> prepare($consulta);
  try{
    $preparada -> execute();
  }catch(Exception $e){
    echo "Ha habido un error al crear el usuario: " . $e->getMessage();
  }

  return $preparada->fetchAll();
}

function generarSelect($conexion, $tabla, $columna_id, $columna_nombre, $nombreSelector, $valorSeleccionado = '', $mostrarTodas = true) {
    $html = "<select name='$nombreSelector'>\n";
    if ($mostrarTodas) {
        $html .= " <option value='todas'>$mostrarTodas</option>\n";
    }

    // Ahora pedimos ID y Nombre
    $sql = "SELECT DISTINCT $columna_id, $columna_nombre FROM $tabla ORDER BY $columna_nombre";
    $stmt = $conexion->prepare($sql);
    try {
        $stmt->execute();
    } catch(Exception $e) {
        echo "Error en el filtro: " . $e->getMessage();
    }

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $fila) {
        $id = $fila[$columna_id];
        $nombre = htmlspecialchars($fila[$columna_nombre]);            
        $selected = ($valorSeleccionado == $id) ? " selected" : "";
        $html .= " <option value='$id'$selected>$nombre</option>\n";
    }

    $html .= "</select>\n";
    return $html;
}

function productos($conexion, $id_categoria = "todas", $marca = "todas", $precio = "todas", $busqueda = "") {
    // Usamos JOIN para traer el nombre de la categoría aunque filtremos por ID
    $sql = "SELECT p.*, c.nombre_categoria 
            FROM productos p 
            LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
            WHERE 1=1";
    $params = [];

    if ($id_categoria !== "todas" && !empty($id_categoria)) {
        $sql .= " AND p.id_categoria = :id_cat";
        $params[':id_cat'] = $id_categoria;
    }

    if ($marca !== "todas" && !empty($marca)) {
        $sql .= " AND p.marca = :marca";
        $params[':marca'] = $marca;
    }

    if (!empty($busqueda)) {
        $sql .= " AND (p.nombre_producto LIKE :busqueda OR p.descripcion LIKE :busqueda)";
        $params[':busqueda'] = "%$busqueda%";
    }

    if ($precio === "asc") { $sql .= " ORDER BY p.precio ASC"; } 
    elseif ($precio === "desc") { $sql .= " ORDER BY p.precio DESC"; }

    $stmt = $conexion->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarProducto($conexion, $idProducto){
    $sql = "SELECT p.*, c.nombre_categoria 
            FROM productos p
            LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
            WHERE p.id_producto = :id";
    $stmt = $conexion->prepare($sql);
    try{
        $stmt->execute([
            ':id' => $idProducto
        ]);
    }catch(Exception $e){
        echo("Error al buscar el producto : " . $e->getMessage());
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerResenas($conexion, $idProducto){
    $sql = "SELECT r.puntuacion, r.comentario, r.fecha_resena, u.nombre
            FROM resenas r
            JOIN usuarios u ON r.id_usuario = u.id_usuario
            WHERE r.id_producto = :id
            ORDER BY r.fecha_resena DESC";
    $stmt = $conexion->prepare($sql);
    try {
        $stmt->execute([':id' => $idProducto]);
    } catch(Exception $e){
        echo "Error al obtener reseñas: " . $e->getMessage();
    }
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function crearResena($conexion, $idProducto, $idUsuario, $puntuacion, $comentario) {
    
    if ($puntuacion < 1 || $puntuacion > 5) return false;

    try {
        $sql = "INSERT INTO resenas (id_producto, id_usuario, puntuacion, comentario, fecha_resena)
                VALUES (:id_producto, :id_usuario, :puntuacion, :comentario, NOW())";

        $stmt = $conexion->prepare($sql);

        return $stmt->execute([
            ':id_producto' => $idProducto,
            ':id_usuario' => $idUsuario,
            ':puntuacion' => $puntuacion,
            ':comentario' => $comentario
        ]);

    } catch (PDOException $e) {
        return false;
    }
}

/*Funciones para los pagos*/

function carritoUsuarioActualId(): ?int
{
    return isset($_SESSION['id_usuario']) ? (int) $_SESSION['id_usuario'] : null;
}

function carritoSessionActualId(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    return session_id();
}

function obtenerCarritoActivo(PDO $conexion): ?array
{
    $idUsuario = carritoUsuarioActualId();
    $sessionId = carritoSessionActualId();

    if ($idUsuario) {
        $stmt = $conexion->prepare("
            SELECT *
            FROM carritos
            WHERE estado = 'activo' AND (id_usuario = ? OR session_id = ?)
            ORDER BY id_carrito DESC
            LIMIT 1
        ");
        $stmt->execute([$idUsuario, $sessionId]);
    } else {
        $stmt = $conexion->prepare("
            SELECT *
            FROM carritos
            WHERE estado = 'activo' AND session_id = ?
            ORDER BY id_carrito DESC
            LIMIT 1
        ");
        $stmt->execute([$sessionId]);
    }

    $carrito = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($carrito && $idUsuario && empty($carrito['id_usuario'])) {
        $up = $conexion->prepare("UPDATE carritos SET id_usuario = ? WHERE id_carrito = ?");
        $up->execute([$idUsuario, $carrito['id_carrito']]);
        $carrito['id_usuario'] = $idUsuario;
    }

    return $carrito ?: null;
}

function obtenerOCrearCarritoActivo(PDO $conexion): array
{
    $carrito = obtenerCarritoActivo($conexion);

    if ($carrito) {
        return $carrito;
    }

    $idUsuario = carritoUsuarioActualId();
    $sessionId = carritoSessionActualId();

    $stmt = $conexion->prepare("
        INSERT INTO carritos (id_usuario, session_id, estado, fecha_creacion, fecha_actualizacion)
        VALUES (?, ?, 'activo', NOW(), NOW())
    ");
    $stmt->execute([$idUsuario, $sessionId]);

    return [
        'id_carrito' => (int) $conexion->lastInsertId(),
        'id_usuario' => $idUsuario,
        'session_id' => $sessionId,
        'estado' => 'activo'
    ];
}

function obtenerItemsCarrito(PDO $conexion): array
{
    $carrito = obtenerCarritoActivo($conexion);

    if (!$carrito) {
        return [];
    }

    $stmt = $conexion->prepare("
        SELECT
            dc.id_detalle_carrito,
            dc.id_carrito,
            dc.id_producto,
            dc.cantidad,
            dc.precio_unitario,
            p.nombre_producto,
            p.marca,
            p.descripcion,
            p.imagen,
            p.cantidad_existencias,
            (dc.cantidad * dc.precio_unitario) AS subtotal
        FROM detalle_carrito dc
        INNER JOIN productos p ON p.id_producto = dc.id_producto
        WHERE dc.id_carrito = ?
        ORDER BY dc.id_detalle_carrito DESC
    ");
    $stmt->execute([$carrito['id_carrito']]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerTotalCarrito(PDO $conexion): float
{
    $items = obtenerItemsCarrito($conexion);
    $total = 0.0;

    foreach ($items as $item) {
        $total += (float) $item['subtotal'];
    }

    return $total;
}

function agregarProductoAlCarrito(PDO $conexion, int $idProducto, int $cantidad): void
{
    if ($cantidad < 1) {
        $cantidad = 1;
    }

    $stmt = $conexion->prepare("
        SELECT id_producto, precio, cantidad_existencias
        FROM productos
        WHERE id_producto = ?
        LIMIT 1
    ");
    $stmt->execute([$idProducto]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        throw new RuntimeException('Producto no encontrado');
    }

    if ((int) $producto['cantidad_existencias'] < 1) {
        throw new RuntimeException('Producto sin stock');
    }

    if ($cantidad > (int) $producto['cantidad_existencias']) {
        $cantidad = (int) $producto['cantidad_existencias'];
    }

    $carrito = obtenerOCrearCarritoActivo($conexion);

    $stmt = $conexion->prepare("
        SELECT id_detalle_carrito, cantidad
        FROM detalle_carrito
        WHERE id_carrito = ? AND id_producto = ?
        LIMIT 1
    ");
    $stmt->execute([$carrito['id_carrito'], $idProducto]);
    $detalle = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($detalle) {
        $nuevaCantidad = (int) $detalle['cantidad'] + $cantidad;

        if ($nuevaCantidad > (int) $producto['cantidad_existencias']) {
            $nuevaCantidad = (int) $producto['cantidad_existencias'];
        }

        $up = $conexion->prepare("
            UPDATE detalle_carrito
            SET cantidad = ?
            WHERE id_detalle_carrito = ?
        ");
        $up->execute([$nuevaCantidad, $detalle['id_detalle_carrito']]);
    } else {
        $ins = $conexion->prepare("
            INSERT INTO detalle_carrito (id_carrito, id_producto, cantidad, precio_unitario)
            VALUES (?, ?, ?, ?)
        ");
        $ins->execute([
            $carrito['id_carrito'],
            $idProducto,
            $cantidad,
            $producto['precio']
        ]);
    }

    $upCarrito = $conexion->prepare("UPDATE carritos SET fecha_actualizacion = NOW() WHERE id_carrito = ?");
    $upCarrito->execute([$carrito['id_carrito']]);
}

function obtenerDetalleCarritoPorId(PDO $conexion, int $idDetalle): ?array
{
    $carrito = obtenerCarritoActivo($conexion);

    if (!$carrito) {
        return null;
    }

    $stmt = $conexion->prepare("
        SELECT
            dc.*,
            p.cantidad_existencias
        FROM detalle_carrito dc
        INNER JOIN productos p ON p.id_producto = dc.id_producto
        WHERE dc.id_detalle_carrito = ? AND dc.id_carrito = ?
        LIMIT 1
    ");
    $stmt->execute([$idDetalle, $carrito['id_carrito']]);

    $detalle = $stmt->fetch(PDO::FETCH_ASSOC);

    return $detalle ?: null;
}

function actualizarCantidadDetalleCarrito(PDO $conexion, int $idDetalle, int $cantidad): void
{
    $detalle = obtenerDetalleCarritoPorId($conexion, $idDetalle);

    if (!$detalle) {
        throw new RuntimeException('Línea de carrito no encontrada');
    }

    if ($cantidad <= 0) {
        $del = $conexion->prepare("DELETE FROM detalle_carrito WHERE id_detalle_carrito = ?");
        $del->execute([$idDetalle]);
        return;
    }

    if ($cantidad > (int) $detalle['cantidad_existencias']) {
        $cantidad = (int) $detalle['cantidad_existencias'];
    }

    $up = $conexion->prepare("
        UPDATE detalle_carrito
        SET cantidad = ?
        WHERE id_detalle_carrito = ?
    ");
    $up->execute([$cantidad, $idDetalle]);
}

function eliminarDetalleCarrito(PDO $conexion, int $idDetalle): void
{
    $detalle = obtenerDetalleCarritoPorId($conexion, $idDetalle);

    if (!$detalle) {
        return;
    }

    $del = $conexion->prepare("DELETE FROM detalle_carrito WHERE id_detalle_carrito = ?");
    $del->execute([$idDetalle]);
}

function guardarDatosCheckoutSesion(array $datos): void
{
    $_SESSION['checkout_datos'] = [
        'nombre' => trim($datos['nombre'] ?? ''),
        'correo' => trim($datos['correo'] ?? ''),
        'telefono' => trim($datos['telefono'] ?? ''),
        'direccion' => trim($datos['direccion'] ?? ''),
        'ciudad' => trim($datos['ciudad'] ?? ''),
        'cp' => trim($datos['cp'] ?? '')
    ];
}

function obtenerDatosCheckoutSesion(): array
{
    return $_SESSION['checkout_datos'] ?? [];
}

function limpiarCheckoutSesion(): void
{
    unset($_SESSION['checkout_datos']);
}

function validarDatosCheckout(array $datos): array
{
    $errores = [];

    if (($datos['nombre'] ?? '') === '') {
        $errores[] = 'Nombre obligatorio';
    }

    if (($datos['correo'] ?? '') === '' || !filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Correo no válido';
    }

    if (($datos['telefono'] ?? '') === '') {
        $errores[] = 'Teléfono obligatorio';
    }

    if (($datos['direccion'] ?? '') === '') {
        $errores[] = 'Dirección obligatoria';
    }

    if (($datos['ciudad'] ?? '') === '') {
        $errores[] = 'Ciudad obligatoria';
    }

    if (($datos['cp'] ?? '') === '') {
        $errores[] = 'Código postal obligatorio';
    }

    return $errores;
}

function pedidoExistePorReferencia(PDO $conexion, string $referencia): bool
{
    $stmt = $conexion->prepare("
        SELECT id_pedido
        FROM pedidos
        WHERE referencia_pago = ?
        LIMIT 1
    ");
    $stmt->execute([$referencia]);

    return (bool) $stmt->fetchColumn();
}

function crearPedidoPagadoDesdeCarrito(
    PDO $conexion,
    array $datosCliente,
    string $metodoPago,
    string $referenciaPago,
    string $estadoPago = 'pagado',
    string $estadoPedido = 'pendiente'
): int {
    if (pedidoExistePorReferencia($conexion, $referenciaPago)) {
        $stmt = $conexion->prepare("SELECT id_pedido FROM pedidos WHERE referencia_pago = ? LIMIT 1");
        $stmt->execute([$referenciaPago]);
        return (int) $stmt->fetchColumn();
    }

    $carrito = obtenerCarritoActivo($conexion);

    if (!$carrito) {
        throw new RuntimeException('No hay carrito activo');
    }

    $items = obtenerItemsCarrito($conexion);

    if (!$items) {
        throw new RuntimeException('El carrito está vacío');
    }

    $conexion->beginTransaction();

    try {
        foreach ($items as $item) {
            if ((int) $item['cantidad'] > (int) $item['cantidad_existencias']) {
                throw new RuntimeException('Stock insuficiente para ' . $item['nombre_producto']);
            }
        }

        $stmt = $conexion->prepare("
            INSERT INTO pedidos (
                id_usuario,
                nombre_cliente,
                correo_cliente,
                telefono_cliente,
                direccion_cliente,
                ciudad_cliente,
                cp_cliente,
                fecha_pedido,
                estado,
                metodo_pago,
                estado_pago,
                referencia_pago
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)
        ");
        $stmt->execute([
            carritoUsuarioActualId(),
            $datosCliente['nombre'],
            $datosCliente['correo'],
            $datosCliente['telefono'],
            $datosCliente['direccion'],
            $datosCliente['ciudad'],
            $datosCliente['cp'],
            $estadoPedido,
            $metodoPago,
            $estadoPago,
            $referenciaPago
        ]);

        $idPedido = (int) $conexion->lastInsertId();

        $insDetalle = $conexion->prepare("
            INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario)
            VALUES (?, ?, ?, ?)
        ");

        $upStock = $conexion->prepare("
            UPDATE productos
            SET cantidad_existencias = cantidad_existencias - ?
            WHERE id_producto = ?
        ");

        foreach ($items as $item) {
            $insDetalle->execute([
                $idPedido,
                $item['id_producto'],
                $item['cantidad'],
                $item['precio_unitario']
            ]);

            $upStock->execute([
                $item['cantidad'],
                $item['id_producto']
            ]);
        }

        $del = $conexion->prepare("DELETE FROM detalle_carrito WHERE id_carrito = ?");
        $del->execute([$carrito['id_carrito']]);

        $upCarrito = $conexion->prepare("
            UPDATE carritos
            SET estado = 'convertido', fecha_actualizacion = NOW()
            WHERE id_carrito = ?
        ");
        $upCarrito->execute([$carrito['id_carrito']]);

        $conexion->commit();

        guardarDatosUsuarioCheckout($conexion, $datosCliente);

        return $idPedido;
    } catch (Throwable $e) {
        $conexion->rollBack();
        throw $e;
    }
}

function paypalBaseUrl(): string
{
    return PAYPAL_ENV === 'live'
        ? 'https://api-m.paypal.com'
        : 'https://api-m.sandbox.paypal.com';
}

function paypalAccessToken(): string
{
    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => paypalBaseUrl() . '/v1/oauth2/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_USERPWD => PAYPAL_CLIENT_ID . ':' . PAYPAL_CLIENT_SECRET,
        CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Accept-Language: es_ES'
        ]
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        throw new RuntimeException('Error PayPal OAuth: ' . curl_error($ch));
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode($response, true);

    if ($httpCode < 200 || $httpCode >= 300 || empty($data['access_token'])) {
        throw new RuntimeException('No se pudo obtener token de PayPal');
    }

    return $data['access_token'];
}

function obtenerUsuarioPorId(PDO $conexion, int $idUsuario): ?array
{
    $stmt = $conexion->prepare("
        SELECT id_usuario, nombre, correo_electronico, telefono, direccion, ciudad, cp
        FROM usuarios
        WHERE id_usuario = ?
        LIMIT 1
    ");
    $stmt->execute([$idUsuario]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    return $usuario ?: null;
}

function obtenerDatosCheckoutIniciales(PDO $conexion): array
{
    $datosSesion = obtenerDatosCheckoutSesion();

    $base = [
        'nombre' => '',
        'correo' => '',
        'telefono' => '',
        'direccion' => '',
        'ciudad' => '',
        'cp' => ''
    ];

    $idUsuario = carritoUsuarioActualId();

    if ($idUsuario) {
        $usuario = obtenerUsuarioPorId($conexion, $idUsuario);

        if ($usuario) {
            $base = [
                'nombre' => trim($usuario['nombre'] ?? ''),
                'correo' => trim($usuario['correo_electronico'] ?? ''),
                'telefono' => trim($usuario['telefono'] ?? ''),
                'direccion' => trim($usuario['direccion'] ?? ''),
                'ciudad' => trim($usuario['ciudad'] ?? ''),
                'cp' => trim($usuario['cp'] ?? '')
            ];
        }
    }

    foreach ($base as $clave => $valor) {
        if (!empty($datosSesion[$clave])) {
            $base[$clave] = trim($datosSesion[$clave]);
        }
    }

    return $base;
}

function guardarDatosUsuarioCheckout(PDO $conexion, array $datos): void
{
    $idUsuario = carritoUsuarioActualId();

    if (!$idUsuario) {
        return;
    }

    $stmt = $conexion->prepare("
        UPDATE usuarios
        SET
            nombre = ?,
            correo_electronico = ?,
            telefono = ?,
            direccion = ?,
            ciudad = ?,
            cp = ?
        WHERE id_usuario = ?
    ");

    $stmt->execute([
        trim($datos['nombre'] ?? ''),
        trim($datos['correo'] ?? ''),
        trim($datos['telefono'] ?? ''),
        trim($datos['direccion'] ?? ''),
        trim($datos['ciudad'] ?? ''),
        trim($datos['cp'] ?? ''),
        $idUsuario
    ]);
}
?>

