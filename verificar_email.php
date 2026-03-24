<?php
require_once("conexion.php");
session_start();

$token = $_GET['token'] ?? '';

if ($token === '') {
    header("Location: iniciarsesion.php?verificacion=error");
    exit;
}

$stmt = $conexion->prepare("
    SELECT id_usuario, token_expira
    FROM usuarios
    WHERE token_verificacion = ?
    LIMIT 1
");
$stmt->execute([$token]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: iniciarsesion.php?verificacion=error");
    exit;
}

if (empty($usuario['token_expira']) || strtotime($usuario['token_expira']) < time()) {
    header("Location: iniciarsesion.php?verificacion=expirada");
    exit;
}

$up = $conexion->prepare("
    UPDATE usuarios
    SET email_verificado = 1,
        token_verificacion = NULL,
        token_expira = NULL
    WHERE id_usuario = ?
");
$up->execute([$usuario['id_usuario']]);

header("Location: iniciarsesion.php?verificacion=ok");
exit;