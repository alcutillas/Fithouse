<?php
$css = "registro";
require_once("templates/header.php");
?>
<main>
    <div class="form">
      <h2>Registro</h2>

      <form method="post" action="registro.php">
        <input type="email" placeholder="Email" name="email" required>
        <input type="password" name="password" placeholder="Contraseña">
        <button type="submit" onclick="enviar">Iniciar Sesión</button>
      </form>
    </div>

  </div>
</main>
<?php
require_once("templates/footer.php");
?>