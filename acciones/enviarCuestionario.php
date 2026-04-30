<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

function limpiar($dato) {
    return htmlspecialchars(trim($dato), ENT_QUOTES, 'UTF-8');
}

$correo = limpiar($_POST["correo"] ?? '');
$nombre = limpiar($_POST["nombre"] ?? '');
$edad = limpiar($_POST["edad"] ?? '');
$objetivo = limpiar($_POST["objetivo"] ?? '');
$deporte = limpiar($_POST["deportePracticado"] ?? '');
$plan = limpiar($_POST["seguidoPlan"] ?? '');
$seguimiento = limpiar($_POST["tipoSeguimiento"] ?? '');
$preferencia = limpiar($_POST["preferencia"] ?? '');
$ayuda = limpiar($_POST["ayuda_nutricional"] ?? '');

if ($objetivo === "otro") {
    $objetivo = limpiar($_POST["objetivo_otro"] ?? '');
}

if ($deporte === "otro") {
    $deporte = limpiar($_POST["deporte_otro"] ?? '');
}

if (
    empty($correo) ||
    empty($nombre) ||
    empty($edad) ||
    empty($objetivo) ||
    empty($deporte) ||
    empty($plan) ||
    empty($seguimiento) ||
    empty($preferencia) ||
    empty($ayuda)
) {
    die("Faltan campos obligatorios.");
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    die("Correo inválido.");
}

$destinatario = "fit.housesanvi@gmail.com";
$asunto = "Nuevo cuestionario nutricional - $nombre";

$mensaje = "
Nuevo cuestionario nutricional recibido:

Nombre: $nombre
Correo: $correo
Edad: $edad

Objetivo principal: $objetivo
Deporte practicado: $deporte
Ha seguido plan nutricional: $plan
Tipo de seguimiento: $seguimiento
Preferencia profesional: $preferencia

Qué quiere conseguir:
$ayuda
";

$headers = "From: Fithouse <no-reply@fithouse.com>\r\n";
$headers .= "Reply-To: $correo\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

if (mail($destinatario, $asunto, $mensaje, $headers)) {
    header("Location: ../gracias.php");
    exit;
} else {
    die("Error al enviar el formulario.");
}