<?php
$css = "registro";
require_once("templates/header.php");

if (isset($_POST['email']) && isset($_POST['password'])) {
  $email = $_POST['email'];
  $pass  = $_POST['password'];

  // 1. Buscamos al usuario por email
  $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE correo_electronico = :email");
  $stmt->execute([':email' => $email]);
  $usuario = $stmt->fetch();

  if ($usuario) {
      // 2. Verificamos si la contraseña coincide con el hash guardado
      if (password_verify($pass, $usuario['password'])) {
          
          // Login correcto
          $_SESSION['rol'] = $usuario['rol']; // Usamos el rol de la BD
          $_SESSION['id_usuario'] = $usuario['id_usuario'];

          if ($email == 'fit.housesanvi@gmail.com') {
              header("Location: admin/administrador.php");
          } else {
              header("Location: index.php");
          }
          exit();
      } else {
          echo "<p class='error'>Contraseña incorrecta</p>";
      }
  } else {
      // Si no existe el email, mandamos a registrar
      header("Location: registrar.php");
      exit();
  }
}


?>

<main>
    <div class="form">
      <form method="post" action="">
        <input type="email" placeholder="Email" name="email" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Iniciar Sesión</button>
      </form>
    </div>

  </div>
</main>
<?php
require_once("templates/footer.php");
?>