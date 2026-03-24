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

    <title>Fithouse Sanvi</title>

    <meta name="description" content="Compra suplementos deportivos baratos de calidad: proteína, creatina, pre-entreno y más. Envíos rápidos y precios competitivos.">
    <meta name="keywords" content="suplementos deportivos, proteína whey, creatina, pre entreno, tienda suplementación">
    <meta name="author" content="Fithouse Sanvi">
    <meta name="robots" content="index, follow">

    <link rel="canonical" href="https://fithousesanvi.es/">

    <!-- Open Graph -->
    <meta property="og:title" content="Tienda de Suplementación Deportiva">
    <meta property="og:description" content="Proteína, creatina y suplementos al mejor precio.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://fithousesanvi.es/">
    <meta property="og:image" content="https://fithousesanvi.es/static/img/og-image.jpg">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Tienda de Suplementación Deportiva">
    <meta name="twitter:description" content="Compra suplementos deportivos online.">
    <meta name="twitter:image" content="https://fithousesanvi.es/static/img/og-image.jpg">

    <link rel="icon" href="./static/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="static/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="static/fontawesome/css/solid.min.css">
    <link rel="stylesheet" href="static/fontawesome/css/brands.min.css">
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
        <link rel="stylesheet" href="static/css/registros.css">
    <?php
    }else if($css == "catalogo"){
        ?>
        <link rel="stylesheet" href="static/css/catalogo.css">
    <?php
    }else if($css == "producto"){
        ?>
        <link rel="stylesheet" href="static/css/producto.css">
    <?php
    }else if($css == "carrito"){
        ?>
        <link rel="stylesheet" href="static/css/carrito.css">
    <?php
    }else if($css == "checkout"){
        ?>
        <link rel="stylesheet" href="static/css/checkout.css">
    <?php
    }}
    ?>

</head>

<body>
<input type="checkbox" id="menu" hidden>

<header>
<div class="logo">
            <a href="index.php" id="logo-menu">
                <img src="static/img/logo-indice.webp" width="55" height="40">
            </a>
        </div>
<nav class="navbar">
    <div class="menu-toggle" id="mobile-menu">
        <i class="fa fa-bars"></i>
    </div>

    <ul class="nav-menu" id="nav-menu">
        <?php if(isset($_SESSION["rol"]) && $_SESSION["rol"] === "admin"): ?>
            <li class="admin-link"><a href="admin/administrador.php">Admin</a></li>
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
        <li><a href="carrito.php"><i class="fa-solid fa-cart-shopping"></i></a></li>
        
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
            <li class="user-options"><a href="logout.php">Cerrar Sesión</a></li>
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