<?php 
$css = "registro";
require_once("templates/header.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim(strtolower($_POST['email'] ?? ''));
    $pass  = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p class='error'>Correo electrónico no válido</p>";
    } else {
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE LOWER(correo_electronico) = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            if (password_verify($pass, $usuario['password'])) {
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['rol'] = $usuario['rol'];
                $_SESSION['id_usuario'] = $usuario['id_usuario'];

                if ($usuario['rol'] === 'admin') {
                    header("Location: admin/administrador.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                echo "<p class='error'>Contraseña incorrecta</p>";
            }
        } else {
            echo "<p class='error'>El correo no está registrado</p>";
        }
    }
}
?>

<main>
    <div class="particulas-neon"></div>
<div class="logo-neon"></div>
<div class="trazo-neon-1"></div>
<div class="trazo-neon-2"></div>
<div class="trazo-neon-3"></div>
    <div class="form">
        <form method="post" action="">
            <input type="email" placeholder="Email" name="email" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Iniciar Sesión</button>
        </form>
    </div>
</main>

<?php
require_once("templates/footer.php");
?>