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
                $html .= " <option value='TODAS'>TODAS</option>\n";
            }

            // Consulta usando PDO
            $sql = "SELECT DISTINCT $columna FROM $tabla ORDER BY $columna";
            $stmt = $conexion->prepare($sql);
            try{
            $stmt->execute();
            }catch(Exception $e){
              echo "Error en el filtro: " . $e->getMessage();
            }
            $stmt->execute();

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $fila) {
                $opcion = htmlspecialchars($fila[$columna]);            
                $selected = (trim($valorSeleccionado) === trim($fila[$columna])) ? " selected" : "";
                $html .= " <option value='$opcion'$selected>$opcion</option>\n";
            }

            $html .= "</select>\n";
            return $html;
    }
?>