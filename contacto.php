<?php
$css = "contacto";
require_once("templates/header.php");
?>
    


    <main class="contacto-container">
        <?php

if (isset($_POST["nombre"]) && isset($_POST["email"]) && isset($_POST["mensaje"])) {?>
<h3 id="mensaje" class="mensaje-enviado"><i class="fa-solid fa-check" ></i> Mensaje enviado correctamente</h3>
<?php

  $nombre  = $_POST['nombre'];
  $email   = $_POST['email'];
  $mensaje = $_POST['mensaje'];

  $destino = "fit.housesanvi@gmail.com";
  $asunto  = "Nuevo mensaje desde el formulario de contacto";

  $contenido = "
  Nombre: $nombre
  Email: $email
  Mensaje:
  $mensaje
  ";

  $headers = "From: $email";

  if (mail($destino, $asunto, $contenido, $headers)) {
    echo "Mensaje enviado correctamente";
  } else {
    echo "Error al enviar el mensaje";
  }
}


?>
        
  <div class="container">

    <!-- Columna izquierda -->
    <div class="info">
      <h3>Teléfono</h3>
      <p>
        658 554 385<br>
        695 716 622
      </p>

      <h3>Síguenos</h3>
      <div class="social">
        <a href="https://www.instagram.com/fithouse.sanvi"><i class=" redes fa-brands fa-instagram"></i></a>
        <a href="https://www.tiktok.com/@fithouse.sanvi"><i class=" redes fa-brands fa-tiktok"></i></a>
      </div>
    </div>

    <!-- Columna derecha -->
    <div class="form">
      <h2>Formulario de contacto</h2>

      <form method="post" action="contacto.php">
        <input type="text" placeholder="Nombre" name="nombre" required>
        <input type="email" placeholder="Email" name="email" required>
        <textarea placeholder="Mensaje" name="mensaje" required></textarea>
        <button type="submit" onclick="enviar">Enviar</button>
      </form>
    </div>

  </div>
    </main>

    
<?php
require_once("templates/footer.php")
?>