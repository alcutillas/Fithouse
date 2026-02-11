<?php
$css = "registro";
require_once("templates/header.php");

if(isset($_POST["email"])){
  $usuarios = usuarios($conexion);
  $registrado = false;
  foreach($usuarios as $u){
    if($u["correo_electronico"] == $_POST["email"]){
      echo "<p class=error>Este correo ya esta registrado, inicia sesión</p>";

      $registrado = true;
    }
  }

if($registrado == false){
  $_SESSION["rol"] = "cliente";
  $consulta = "INSERT into usuarios (nombre,correo_electronico,telefono) VALUES (:nombre,:email,:telefono)";
  $preparada = $conexion -> prepare($consulta);
  try{
    $preparada -> execute([
      ":nombre" => $_POST["nombre"],
      ":email" => $_POST["email"],
      ":telefono" => $_POST["telefono"]
    ]);
  }catch(Exception $e){
    echo "Ha habido un error al crear el usuario: " . $e->getMessage();
  }
  header("Location:index.php");
}
  
  

}


?>
<main>
    <div class="form">
      <form method="post" action="">
        <input type="text" placeholder="Nombre completo" name="nombre" required>
        <input type="email" placeholder="Email" name="email" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <input type="text" placeholder="Teléfono" name="telefono" required>
        <button type="submit">Registrarse</button>
      </form>
    </div>

  </div>
</main>
<?php
require_once("templates/footer.php");
?>