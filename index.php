<?php
$css = "index";
require_once("templates/header.php");
?>

<main>
    <div id="fondo-informacion">
        <div>
            <p>En FitHouse creemos que cada persona puede superar sus límites con la combinación adecuada de entrenamiento, nutrición y motivación.
                Por eso, trabajamos con marcas de confianza y te asesoramos personalmente para que encuentres exactamente lo que necesitas para alcanzar tus objetivos.
            </p>
            <p>
                FitHouse – Donde la pasión por el deporte se convierte en estilo de vida.</p>
        </div>
    </div>
    <section id="cuerpo-presentacion">
        <div>
            <h2>Marcas con las que trabajamos</h2>
            <div class="swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide"><img src="static/img/carrusel-marcas-index/amix.png"></div>
                    <div class="swiper-slide"><img src="static/img/carrusel-marcas-index/barebells.png"></div>
                    <div class="swiper-slide"><img src="static/img/carrusel-marcas-index/big.png"></div>
                    <div class="swiper-slide"><img src="static/img/carrusel-marcas-index/biotech.png"></div>
                    <div class="swiper-slide"><img src="static/img/carrusel-marcas-index/elevenfit.png"></div>
                    <div class="swiper-slide"><img src="static/img/carrusel-marcas-index/maxprotein.png"></div>
                    <div class="swiper-slide"><img src="static/img/carrusel-marcas-index/protella.png"></div>
                    <div class="swiper-slide"><img src="static/img/carrusel-marcas-index/quamtrax.png"></div>
                    <div class="swiper-slide"><img src="static/img/carrusel-marcas-index/scitec.png"></div>
                    <div class="swiper-slide"><img src="static/img/carrusel-marcas-index/torafood.png"></div>
                    <div class="swiper-slide"><img src="static/img/carrusel-marcas-index/trecnutrition.png"></div>
                    <div class="swiper-slide"><img src="static/img/carrusel-marcas-index/vitobest.png"></div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
            <script>
                    const swiper = new Swiper('.swiper', {
                        loop: true,
                        autoplay: {
                            delay: 1500
                        },
                        pagination: {
                            el: '.swiper-pagination'
                        }
                    });
                </script>
        </div>
        <div>
            <h2>Ofertas</h2>
        </div>
        <div>
            <h2>Recomendaciones</h2>
        </div>
    </section>
</main>

<?php
require_once("templates/footer.php")
?>