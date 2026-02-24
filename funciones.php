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

    function generarSelect($conexion, $tabla, $columna, $nombreSelector, $valorSeleccionado = '', $mostrarTodas = true) {
            $html = "<select name='$nombreSelector'>\n";
            if ($mostrarTodas) {
                $html .= " <option value='todas'>$mostrarTodas</option>\n";
            }

            // Consulta usando PDO
            $sql = "SELECT DISTINCT $columna FROM $tabla ORDER BY $columna";
            $stmt = $conexion->prepare($sql);
            try{
            $stmt->execute();
            }catch(Exception $e){
              echo "Error en el filtro: " . $e->getMessage();
            }

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $fila) {
                $opcion = htmlspecialchars($fila[$columna]);            
                $selected = (trim($valorSeleccionado) === trim($fila[$columna])) ? " selected" : "";
                $html .= " <option value='$opcion'$selected>$opcion</option>\n";
            }

            $html .= "</select>\n";
            return $html;
    }


    function productos($conexion, $categoria = "todas", $marca = "todas", $precio = "todas", $busqueda = "")
{
    $sql = "SELECT * FROM productos WHERE 1=1";
    $params = [];

    // Filtro por categoría
    if ($categoria !== "todas" && !empty($categoria)) {
        $sql .= " AND categoria = :categoria";
        $params[':categoria'] = $categoria;
    }

    // Filtro por marca
    if ($marca !== "todas" && !empty($marca)) {
        $sql .= " AND marca = :marca";
        $params[':marca'] = $marca;
    }

    // Filtro por búsqueda (nombre o descripción)
    if (!empty($busqueda)) {
        $sql .= " AND (nombre_producto LIKE :busqueda OR descripcion LIKE :busqueda)";
        $params[':busqueda'] = "%$busqueda%";
    }

    // Orden por precio
    if ($precio === "asc") {
        $sql .= " ORDER BY precio ASC";
    } elseif ($precio === "desc") {
        $sql .= " ORDER BY precio DESC";
    }

    $stmt = $conexion->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>