<?php
$css = "cuestionario-plan-nutricional";
require_once("templates/header.php");
?>

<main>
    <h1>Cuestionario Plan Nutricional</h1>
    <div id="informacion-cuestionario-plan-nutricional">
        <p>Este cuestionario se realiza con la intención de conocer un poco tu caso y tus características para poder
            derivarte a un profesional u otro del grupo de trabajo.</p>

        <p>Al rellenar el formulario nos pondremos en contacto contigo para aclarar tus dudas, darte mas información o
            directamente agencian una cita.</p>

        <p>Muchas Gracias por confiar en nosotros!</p>
        <p>Nos pondremos en contacto contigo por WhatsApp para darte mas información.</p>

        <p>Al rellenar el formulario estas dando consentimiento expreso al tratamiento de tus datos conforme al
            reglamento
            general de protección de datos (RGPD) Europeo y la Ley orgánica 3/2018 del 5 de Diciembre, protección de
            datos
            personales y garantía de los derechos digitales.</p>
    </div>
    <form >
        <fieldset>
            <legend>Información personal</legend>
            <label for="correo">Correo electrónico</label><input type="mail" placeholder="Tu respuesta" required><br><br>
            <label for="nombre">Nombre y Apellidos</label><input type="mail" placeholder="Tu respuesta" required><br><br>
            <label for="edad">Edad</label><input type="mail" placeholder="Tu respuesta" required><br>
        </fieldset>
        <fieldset>
            <legend>Objetivo principal</legend>
            <input type="radio" value="perdergrasa" name="objetivo">Perder grasa<br>
            <input type="radio" value="ganarmasamuscular" name="objetivo">Ganar masa muscular<br>
            <input type="radio" value="mejorrendimientodeportivo" name="objetivo">Mejor rendimiento deportivo<br>
            <input type="radio" value="vidasaludable" name="objetivo">Aprender a llevar una vida mas saludable<br>
            <input type="radio" value="otro" name="objetivo">Otro<input type="text" name="objetivo">
        </fieldset>
        <fieldset>
            <legend>¿Practicas deporte ahora mismo?</legend>
            <input type="radio" value="no" name="deportePracticado">No entreno<br>
            <input type="radio" value="gym" name="deportePracticado">Gym<br>
            <input type="radio" value="resistencia" name="deportePracticado">Deporte de resistencia<br>
            <input type="radio" value="equipo" name="deportePracticado">Deporte en equipo<br>
            <input type="radio" value="otro" name="deportePracticado">Otro<input type="text">
        </fieldset>
        <fieldset>
            <legend>¿Has seguido alguna vez un plan nutricional?</legend>
            <input type="radio" value="no" name="seguidoPlan">No<br>
            <input type="radio" value="solo" name="seguidoPlan">Si, por mi cuenta<br>
            <input type="radio" value="profecional" name="seguidoPlan">Si, con un profesional<br>
        </fieldset>
        <fieldset>
            <legend>¿Como te gustaría llevar el seguimiento nutricional?</legend>
            <input type="radio" value="sencillo" name="tipoSeguimiento">Practico y sencillo, pautas claras y adaptadas a mi vida, avanzando poco a poco sin exigencia desmedida.<br>
            <input type="radio" value="estructurado" name="tipoSeguimiento">Seguimiento mas estructurado, con revisiones periodicas (presenciales u online) para ir ajustando el plan y progresar de forma constante<br>
            <input type="radio" value="exigente" name="tipoSeguimiento">Seguimiento mas exigente, con control frecuente, registro de datos y alto compromiso para lograr objetivos lo antes posible<br>
            <input type="radio" value="asesoramiento" name="tipoSeguimiento">No lo tengo claro, necesito asesoramiento<br>
        </fieldset>
        <fieldset>
            <legend>¿Tienes alguna preferencia sobre quien te atienda?</legend>
            <input type="radio" value="daigual" name="preferencia">Me da igual<br>
            <input type="radio" value="mujer" name="preferencia">Prefiero una mujer<br>
            <input type="radio" value="hombre" name="preferencia">Prefiero un hombre<br>
        </fieldset>
        <fieldset>
            <legend>¿Que te gustaría conseguir con nuestra ayuda nutricional?</legend>
        <input type="text" placeholder="Tu respuesta">
        </fieldset>
        <input type="submit" class="btn-enviar" value="Enviar">
    </form>
</main>

<?php
require_once("templates/footer.php")
?>