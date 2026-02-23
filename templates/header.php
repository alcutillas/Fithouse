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
    <link rel="stylesheet" href="static/css/style.css">

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
<input type="checkbox" id="menu" hidden>

<header>
<div class="logo">
            <a href="index.php" id="logo-menu">
                <img src="static/img/logo-indice.png" width="55" height="40">
            </a>
        </div>
<nav class="navbar">
    <div class="menu-toggle" id="mobile-menu">
        <i class="fa fa-bars"></i>
    </div>

    <ul class="nav-menu" id="nav-menu">
        <?php if(isset($_SESSION["rol"]) && $_SESSION["rol"] === "admin"): ?>
            <li class="admin-link"><a href="panel.php">Panel de control</a></li>
        <?php endif; ?>

        <li><a href="acercade.php">Acerca de</a></li>
        <li class="dropdown">
        <input type="checkbox" id="check2">
                <label for="check2">Asesoramiento <i class="fa fa-caret-down"></i></label>
                <ul class="submenu">
                    <li><a href="cuestionario.php">Cuestionario</a></li>
                </ul>
    </li>
        <li><a href="catalogo.php">Catálogo</a></li>
        <li><a href="pedidos.php">Pedidos</a></li>
        <li><a href="contacto.php">Contacto</a></li>
        
        <?php if(empty($_SESSION)): ?>
            <li class="dropdown user-options">
                <input type="checkbox" id="check3">
                <label for="check3"><i class="fa fa-user"></i> Cuenta <i class="fa fa-caret-down"></i></label>
                <ul class="submenu">
                    <li><a href="iniciarsesion.php">Iniciar Sesión</a></li>
                    <li><a href="registrar.php">Registrarse</a></li>
                </ul>
            </li>
        <?php else: ?>
            <li class="user-options"><a href="logout.php">Cerrar Sesión (<?php echo $_SESSION['usuario']; ?>)</a></li>
        <?php endif; ?>
        
    </ul>
</nav>
</header>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const menuToggle = document.getElementById('mobile-menu');
    const navMenu = document.getElementById('nav-menu');

    menuToggle.addEventListener('click', function() {
        navMenu.classList.toggle('active');
        
        // Cambiar icono de hamburguesa a una X (opcional si usas FontAwesome)
        const icon = menuToggle.querySelector('i');
        icon.classList.toggle('fa-bars');
        icon.classList.toggle('fa-times');
    });
});
</script>