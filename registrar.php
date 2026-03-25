<?php
$css = "registro";
require_once("templates/header.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST["nombre"] ?? '');
    $email    = trim(strtolower($_POST["email"] ?? ''));
    $password = $_POST["password"] ?? '';
    $telefono = trim($_POST["telefono"] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p class='error'>Correo electrónico no válido</p>";
    } else {
        $stmt = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE LOWER(correo_electronico) = :email LIMIT 1");
        $stmt->execute([":email" => $email]);
        $usuarioExistente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuarioExistente) {
            echo "<p class='error'>Este correo ya está registrado, inicia sesión</p>";
        } else {
            $password_encriptada = password_hash($password, PASSWORD_DEFAULT);

            $consulta = "INSERT INTO usuarios (nombre, correo_electronico, telefono, password, rol) 
                         VALUES (:nombre, :email, :telefono, :password, :rol)";

            $preparada = $conexion->prepare($consulta);

            try {
                $preparada->execute([
                    ":nombre"   => $nombre,
                    ":email"    => $email,
                    ":telefono" => $telefono,
                    ":password" => $password_encriptada,
                    ":rol"      => "cliente"
                ]);

                $_SESSION["rol"] = "cliente";
                $_SESSION["id_usuario"] = $conexion->lastInsertId();
                $_SESSION["nombre"] = $nombre;

                header("Location: index.php");
                exit();
            } catch (Exception $e) {
                echo "<p class='error'>Error al registrar el usuario</p>";
            }
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
            <input type="text" placeholder="Nombre completo" name="nombre" required>
            <input type="email" placeholder="Email" name="email" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="text" placeholder="Teléfono" name="telefono" required>
            <button type="submit">Registrarse</button>
        </form>
    </div>
</main>

<?php
require_once("templates/footer.php");
?>