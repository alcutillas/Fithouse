<?php
$css = "registro";
require_once("templates/header.php");

if (isset($_POST['email'])) {

    if($_POST['email'] == 'fit.housesanvi@gmail.com'){
      $_SESSION['rol'] = 'admin';
      header("Location: administrador.php");
      exit();
    }

    $usuarios = usuarios($conexion);
  foreach($usuarios as $u){
    if($u["correo_electronico"] == $_POST["email"]){
      $_SESSION["rol"] = "cliente";
      header("Location: index.php");
      exit();
    }
  }
  header("Location:registrar.php");
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