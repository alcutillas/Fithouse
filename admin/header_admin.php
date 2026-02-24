<?php
session_start();

if($_SESSION["rol"] != "admin")
    header("Location:../iniciarsesion.php");

    require_once($_SERVER['DOCUMENT_ROOT'] . "/Fithouse/funciones.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . "/Fithouse/conexion.php");

    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto Entorno Servidor</title>
    
    <link rel="stylesheet" href="../static/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../static/css/stylu.css">
    <link rel="stylesheet" href="../static/css/catalogo.css">
</head>

<body>
    
<header>
<div class="logo">
            <a href="../index.php" id="logo-menu">
                <img src="../static/img/logo-indice.png" width="55" height="40">
            </a>
        </div>
        <div class="header-admin">
        <li class="admin-link"><a href="../index.php">Volver a la página</a></li>
        <li class="user-options"><a href="../logout.php">Cerrar Sesión</a></li>
        </div>
</header>