<?php
require_once("conexion.php");
require_once("funciones.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cabecera</title>
      <link rel="stylesheet" href="static/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="static/css/stylo.css">

    <?php
    if (isset($css)) {
       if($css == "cuestionario-plan-nutricional"){
    ?>
        <link rel="stylesheet" href="static/css/cuestionario-plan-nutricional.css">
    <?php
    }else if($css == "acercade"){
        ?>
        <link rel="stylesheet" href="static/css/acercade.css">
    <?php
    }else if($css == "contacto"){
        ?>
        <link rel="stylesheet" href="static/css/contacto.css">
    <?php
    }else if($css == "index"){
        ?>
        <link rel="stylesheet" href="static/css/swiper-bundle.min.css">
        <script src="./static/js/swiper-bundle.min.js"></script>
    <?php
    }else if($css == "registro"){
        ?>
        <link rel="stylesheet" href="static/css/registro.css">
    <?php
    }}
    ?>

</head>

<body>

<header>
    <nav class="navbar">
        
        <div class="logo">
            <a href="index.php" id="logo-menu">
                <img src="static/img/logo-indice.png" width="55" height="40">
            </a>
        </div>

        <!-- Botón hamburguesa -->
            <input type="checkbox" name="check" id="check">
            <label for="check"><i class="fa-solid fa-bars"></i></label>
        

        <ul class="nav-menu" id="nav-menu">

            <?php if(isset($_SESSION["rol"]) && $_SESSION["rol"] === "admin"): ?>
                <li><a href="panel.php">Panel de control</a></li>
            <?php endif; ?>

            <li><a href="acerca.php">Acerca de</a></li>

            <li><a href="catalogo.php">Catálogo</a></li>

            <li><a href="pedidos.php">Pedidos</a></li>

            <li class="dropdown">
                <input type="checkbox" name="check2" id="check2">
                <label for="check2"><a href="#">Asesoramiento</a></label>
                <ul class="submenu">
                    <li><a href="cuestionario.php">Cuestionario</a></li>
                </ul>
            </li>

            <li><a href="contacto.php">Contacto</a></li>

            <?php if(empty($_SESSION)): ?>
                <li class="dropdown">
                    <input type="checkbox" name="check3" id="check3">
                    <label for="check3"></label><a href="#"><i class="fa fa-user"></i></a>
                    <ul class="submenu">
                        <li><a href="login.php">Iniciar Sesión</a></li>
                        <li><a href="registro.php">Registrarse</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            <?php endif; ?>

        </ul>
    </nav>
</header>


