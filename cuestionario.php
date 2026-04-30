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
    <form id="cuestionario-nutricion" method="POST" action="acciones/enviarCuestionario.php" novalidate>
    <fieldset>
        <legend>Información personal</legend>

        <label for="correo">Correo electrónico</label>
        <input type="email" id="correo" name="correo" placeholder="Tu respuesta" required>

        <label for="nombre">Nombre y apellidos</label>
        <input type="text" id="nombre" name="nombre" placeholder="Tu respuesta" minlength="3" maxlength="100" required>

        <label for="edad">Edad</label>
        <input type="number" id="edad" name="edad" placeholder="Tu respuesta" min="10" max="100" required>
    </fieldset>

    <fieldset>
        <legend>Objetivo principal</legend>

        <label class="opcion-radio">
            <input type="radio" value="perdergrasa" name="objetivo" required>
            <span>Perder grasa</span>
        </label>

        <label class="opcion-radio">
            <input type="radio" value="ganarmasamuscular" name="objetivo">
            <span>Ganar masa muscular</span>
        </label>

        <label class="opcion-radio">
            <input type="radio" value="mejorrendimientodeportivo" name="objetivo">
            <span>Mejor rendimiento deportivo</span>
        </label>

        <label class="opcion-radio">
            <input type="radio" value="vidasaludable" name="objetivo">
            <span>Aprender a llevar una vida más saludable</span>
        </label>

        <div class="opcion-otro">
            <label class="opcion-radio">
                <input type="radio" value="otro" name="objetivo" id="objetivo_otro_radio">
                <span>Otro</span>
            </label>
            <input type="text" name="objetivo_otro" id="objetivo_otro_texto" placeholder="Especifica cuál">
        </div>
    </fieldset>

    <fieldset>
        <legend>¿Practicas deporte ahora mismo?</legend>

        <label class="opcion-radio">
            <input type="radio" value="no" name="deportePracticado" required>
            <span>No entreno</span>
        </label>

        <label class="opcion-radio">
            <input type="radio" value="gym" name="deportePracticado">
            <span>Gym</span>
        </label>

        <label class="opcion-radio">
            <input type="radio" value="resistencia" name="deportePracticado">
            <span>Deporte de resistencia</span>
        </label>

        <label class="opcion-radio">
            <input type="radio" value="equipo" name="deportePracticado">
            <span>Deporte en equipo</span>
        </label>

        <div class="opcion-otro">
            <label class="opcion-radio">
                <input type="radio" value="otro" name="deportePracticado" id="deporte_otro_radio">
                <span>Otro</span>
            </label>
            <input type="text" name="deporte_otro" id="deporte_otro_texto" placeholder="Especifica cuál">
        </div>
    </fieldset>

    <fieldset>
        <legend>¿Has seguido alguna vez un plan nutricional?</legend>

        <label class="opcion-radio">
            <input type="radio" value="no" name="seguidoPlan" required>
            <span>No</span>
        </label>

        <label class="opcion-radio">
            <input type="radio" value="solo" name="seguidoPlan">
            <span>Sí, por mi cuenta</span>
        </label>

        <label class="opcion-radio">
            <input type="radio" value="profesional" name="seguidoPlan">
            <span>Sí, con un profesional</span>
        </label>
    </fieldset>

    <fieldset>
        <legend>¿Cómo te gustaría llevar el seguimiento nutricional?</legend>

        <label class="opcion-radio">
            <input type="radio" value="sencillo" name="tipoSeguimiento" required>
            <span>Práctico y sencillo, pautas claras y adaptadas a mi vida, avanzando poco a poco sin exigencia desmedida.</span>
        </label>

        <label class="opcion-radio">
            <input type="radio" value="estructurado" name="tipoSeguimiento">
            <span>Seguimiento más estructurado, con revisiones periódicas (presenciales u online) para ir ajustando el plan y progresar de forma constante.</span>
        </label>

        <label class="opcion-radio">
            <input type="radio" value="exigente" name="tipoSeguimiento">
            <span>Seguimiento más exigente, con control frecuente, registro de datos y alto compromiso para lograr objetivos lo antes posible.</span>
        </label>

        <label class="opcion-radio">
            <input type="radio" value="asesoramiento" name="tipoSeguimiento">
            <span>No lo tengo claro, necesito asesoramiento.</span>
        </label>
    </fieldset>

    <fieldset>
        <legend>¿Tienes alguna preferencia sobre quien te atienda?</legend>

        <label class="opcion-radio">
            <input type="radio" value="daigual" name="preferencia" required>
            <span>Me da igual</span>
        </label>

        <label class="opcion-radio">
            <input type="radio" value="mujer" name="preferencia">
            <span>Prefiero una mujer</span>
        </label>

        <label class="opcion-radio">
            <input type="radio" value="hombre" name="preferencia">
            <span>Prefiero un hombre</span>
        </label>
    </fieldset>

    <fieldset>
        <legend>¿Qué te gustaría conseguir con nuestra ayuda nutricional?</legend>
        <input type="text" name="ayuda_nutricional" placeholder="Tu respuesta" minlength="5" maxlength="300" required>
    </fieldset>

    <input type="submit" class="btn-enviar" value="Enviar">
</form>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("cuestionario-nutricion");

    function configurarOtro(radioId, inputId) {
        const radio = document.getElementById(radioId);
        const input = document.getElementById(inputId);
        const grupo = document.querySelectorAll(`input[name="${radio.name}"]`);

        function actualizarEstado() {
            if (radio.checked) {
                input.disabled = false;
                input.required = true;
            } else {
                input.disabled = true;
                input.required = false;
                input.value = "";
                input.classList.remove("input-error");
            }
        }

        input.addEventListener("input", () => {
            if (input.value.trim() !== "") {
                radio.checked = true;
            }
            actualizarEstado();
        });

        input.addEventListener("focus", () => {
            radio.checked = true;
            actualizarEstado();
        });

        grupo.forEach(opcion => {
            opcion.addEventListener("change", actualizarEstado);
        });

        actualizarEstado();
    }

    configurarOtro("objetivo_otro_radio", "objetivo_otro_texto");
    configurarOtro("deporte_otro_radio", "deporte_otro_texto");

    const campos = form.querySelectorAll("input[required]");

    function marcarError(campo) {
        campo.classList.add("input-error");
        campo.setAttribute("aria-invalid", "true");
    }

    function quitarError(campo) {
        campo.classList.remove("input-error");
        campo.removeAttribute("aria-invalid");
    }

    campos.forEach(campo => {
        campo.addEventListener("input", () => quitarError(campo));
        campo.addEventListener("change", () => quitarError(campo));
        campo.addEventListener("invalid", (e) => {
            e.preventDefault();
            marcarError(campo);
        });
    });

    form.addEventListener("submit", (e) => {
        let primerInvalido = null;

        const radiosRequeridos = ["objetivo", "deportePracticado", "seguidoPlan", "tipoSeguimiento", "preferencia"];

        radiosRequeridos.forEach(nombre => {
            const opciones = form.querySelectorAll(`input[name="${nombre}"]`);
            const marcada = form.querySelector(`input[name="${nombre}"]:checked`);
            const fieldset = opciones[0].closest("fieldset");

            fieldset.classList.remove("fieldset-error");

            if (!marcada) {
                e.preventDefault();
                fieldset.classList.add("fieldset-error");
                if (!primerInvalido) primerInvalido = opciones[0];
            }
        });

        if (!form.checkValidity()) {
            e.preventDefault();

            const invalidos = form.querySelectorAll(":invalid");
            invalidos.forEach(campo => marcarError(campo));

            if (!primerInvalido && invalidos.length > 0) {
                primerInvalido = invalidos[0];
            }
        }

        if (primerInvalido) {
            primerInvalido.focus();
            primerInvalido.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
        }
    });
});
</script>
</main>

<?php
require_once("templates/footer.php")
?>