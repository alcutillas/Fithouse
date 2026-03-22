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
  // Encriptamos la contraseña antes de guardarla
  $password_encriptada = password_hash($_POST["password"], PASSWORD_DEFAULT);
    
  $_SESSION["rol"] = "cliente";
  
  $consulta = "INSERT INTO usuarios (nombre, correo_electronico, telefono, password, rol) 
               VALUES (:nombre, :email, :telefono, :password, :rol)";
  
  $preparada = $conexion->prepare($consulta);
  try {
    $preparada->execute([
      ":nombre"   => $_POST["nombre"],
      ":email"    => $_POST["email"],
      ":telefono" => $_POST["telefono"],
      ":password" => $password_encriptada,
      ":rol"      => "cliente"
  ]);
  
  // Obtener el ID generado
  $id_usuario = $conexion->lastInsertId();
  
  // Guardarlo en sesión
  $_SESSION["id_usuario"] = $id_usuario;
  $_SESSION["nombre"] = $_POST["nombre"];
  
  header("Location: index.php");
  } catch(Exception $e) {
      echo "Error: " . $e->getMessage();
  }
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