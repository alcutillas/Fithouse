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
?>