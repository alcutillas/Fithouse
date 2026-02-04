<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: index.php.php");
    exit();
}

$error = '';

// Comprobamos si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Para simplificar, usuario/admin con contraseña fija
    // Más adelante se puede hacer con tabla de usuarios en BD
    if ($mail === '' && $password === '') {
        $_SESSION['usuario'] = $mail;
        $_SESSION['rol'] = 'cliente';        
        header("Location: index.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
$css = "registro";
require_once("templates/header.php");
?>

<main>
    <div class="form">
      <form method="post" action="iniciarsesion.php">
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