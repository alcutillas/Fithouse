<?php
$css = "contacto";
require_once("templates/header.php");

$mensajeEstado = "";
$mensajeTexto = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nombre"], $_POST["email"], $_POST["mensaje"])) {
    $nombre  = trim($_POST['nombre']);
    $email   = trim($_POST['email']);
    $mensaje = trim($_POST['mensaje']);

    $destino = "fit.housesanvi@gmail.com";
    $asunto  = "Nuevo mensaje desde el formulario de contacto";

    $contenido = "Nombre: $nombre\n";
    $contenido .= "Email: $email\n\n";
    $contenido .= "Mensaje:\n$mensaje";

    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    if (mail($destino, $asunto, $contenido, $headers)) {
        $mensajeEstado = "ok";
        $mensajeTexto = "Mensaje enviado correctamente";
    } else {
        $mensajeEstado = "error";
        $mensajeTexto = "Error al enviar el mensaje";
    }
}
?>

<main class="contacto-page">
    <section class="contacto-hero">
        <div class="contacto-hero__overlay"></div>
        <div class="contacto-hero__content">
            <span class="contacto-tag">FITHOUSE · CONTACTO</span>
            <h1>Hablemos de tus objetivos</h1>
            <p>
                Escríbenos para resolver dudas sobre suplementación, entrenamiento o cualquier consulta sobre nuestros productos.
            </p>
        </div>
    </section>

    <section class="contacto-wrapper">
        <?php if ($mensajeTexto !== ""): ?>
            <div class="contacto-alerta <?= $mensajeEstado === 'ok' ? 'contacto-alerta--ok' : 'contacto-alerta--error' ?>">
                <i class="fa-solid <?= $mensajeEstado === 'ok' ? 'fa-circle-check' : 'fa-circle-xmark' ?>"></i>
                <span><?= htmlspecialchars($mensajeTexto) ?></span>
            </div>
        <?php endif; ?>

        <div class="contacto-grid">
            <aside class="contacto-info">
                <div class="contacto-card">
                    <div class="contacto-card__icon">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div>
                        <h3>Teléfono</h3>
                        <p>658 554 385</p>
                        <p>695 716 622</p>
                    </div>
                </div>

                <div class="contacto-card">
                    <div class="contacto-card__icon">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div>
                        <h3>Email</h3>
                        <p>fit.housesanvi@gmail.com</p>
                    </div>
                </div>

                <div class="contacto-card">
                    <div class="contacto-card__icon">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <h3>Ubicación</h3>
                        <p>Calle Alicante 13</p>
                        <p>San Vicente del Raspeig 03690</p>
                    </div>
                </div>

                <div class="contacto-social-box">
                    <h3>Síguenos</h3>
                    <div class="contacto-social">
                        <a href="https://www.instagram.com/fithouse.sanvi" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="https://www.tiktok.com/@fithouse.sanvi" target="_blank" rel="noopener noreferrer" aria-label="TikTok">
                            <i class="fa-brands fa-tiktok"></i>
                        </a>
                    </div>
                </div>
            </aside>

            <div class="contacto-form-box">
                <div class="contacto-form-box__header">
                    <span class="contacto-mini-tag">Formulario</span>
                    <h2>Envíanos tu mensaje</h2>
                    <p>Te responderemos lo antes posible.</p>
                </div>

                <form method="post" action="contacto.php" class="contacto-form">
                    <div class="contacto-form__row">
                        <div class="contacto-field">
                            <label for="nombre">Nombre</label>
                            <input
                                type="text"
                                id="nombre"
                                name="nombre"
                                placeholder="Tu nombre completo"
                                value="<?= isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '' ?>"
                                required
                            >
                        </div>

                        <div class="contacto-field">
                            <label for="email">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="tu@email.com"
                                value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                                required
                            >
                        </div>
                    </div>

                    <div class="contacto-field">
                        <label for="mensaje">Mensaje</label>
                        <textarea
                            id="mensaje"
                            name="mensaje"
                            placeholder="Cuéntanos en qué podemos ayudarte..."
                            required
                        ><?= isset($_POST['mensaje']) ? htmlspecialchars($_POST['mensaje']) : '' ?></textarea>
                    </div>

                    <button type="submit" class="contacto-btn">
                        <i class="fa-solid fa-paper-plane"></i>
                        Enviar mensaje
                    </button>
                </form>
            </div>
        </div>
    </section>
</main>

<?php
require_once("templates/footer.php");
?>