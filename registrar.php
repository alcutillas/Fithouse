<?php
$css = "registro";
require_once("templates/header.php");
?>
<main>
    <div class="form">
      <form method="post" action="registro.php">
        <input type="email" placeholder="Nombre completo" name="nombre" required>
        <input type="email" placeholder="Email" name="email" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <input type="email" placeholder="Teléfono" name="telefono" required>
        <input type="email" placeholder="Código postal" name="codigopostal" required>
        <button type="submit">Registrarse</button>
      </form>
    </div>

  </div>
</main>
<?php
require_once("templates/footer.php");
?>