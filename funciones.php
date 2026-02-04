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
?>