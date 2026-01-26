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
        <script src="static/js/swiper-bundle.min.js"></script>
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
        
        <a href="index.php" id="logo-menu"><img src="static/img/logo-indice.png" width="55px" height="40px"></a>
        <ul>
        
        <li><a href="acercade.php">Acerca de</a></li>
        <li><a>Catálogo</a></li>
        <li><a>Pedidos</a></li>
        <li class="desplegable"><a>Asesoramiento</a>
            <ul class="submenu">
                <li><a href="cuestionario.php">Cuestionario</a></li>
            </ul>
        </li>
        <li><a href="contacto.php">Contacto</a></li>
        <li><a><i class="fa-solid fa-cart-shopping"></i></a></li>
        <li><a><i class="fa-regular fa-user"></i></a></li>
        </ul>

    </header>