<?php
$css = "registro";
require_once("templates/header.php");

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST["nombre"] ?? '');
    $email    = trim(strtolower($_POST["email"] ?? ''));
    $password = $_POST["password"] ?? '';
    $telefono = trim($_POST["telefono"] ?? '');

    if ($nombre === '' || $email === '' || $password === '' || $telefono === '') {
        $error = "Todos los campos son obligatorios";
    } elseif (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{2,60}$/u', $nombre)) {
        $error = "Nombre no válido";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Correo electrónico no válido";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $password)) {
        $error = "La contraseña debe tener al menos 8 caracteres y una mayúscula";
    } elseif (!preg_match('/^(\+34)?[6789][0-9]{8}$/', $telefono)) {
        $error = "Teléfono no válido";
    } else {
        $stmt = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE LOWER(correo_electronico) = :email LIMIT 1");
        $stmt->execute([":email" => $email]);
        $usuarioExistente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuarioExistente) {
            $error = "Este correo ya está registrado, inicia sesión";
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
                $error = "Error al registrar el usuario";
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
    <div class="trazo-neon-4"></div>
    <div class="trazo-neon-5"></div>

    <div class="form">
        <form method="post" action="">
            <input type="text" placeholder="Nombre completo" name="nombre" required>
            <input type="email" placeholder="Email" name="email" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="text" placeholder="Teléfono" name="telefono" required>

            <?php if ($error !== ""): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>

            <button type="submit">Registrarse</button>
        </form>
    </div>
</main>

<?php
require_once("templates/footer.php");
?>