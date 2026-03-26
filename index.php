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
            <div class="swiper-slide"><img src="static/img/carrusel-marcas-index/big.png"></div>
            <div class="swiper-slide"><img src="static/img/carrusel-marcas-index/barebells.png"></div>
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
        autoplay: { delay: 2000 },
        pagination: { el: '.swiper-pagination' }
      });
    </script>
        </div>
        <section class="home-sliders">

  <div class="slider-section ofertas-section">
    <h2 class="section-title">Ofertas</h2>

    <div class="swiper swiper-productos">
      <div class="swiper-wrapper">
        <?php
      $productosEnOferta = obtenerProductosEnOferta($conexion);

      $mostrar = "";
      foreach ($productosEnOferta as $producto) {
        
        $descuento = round((($producto['precio'] - $producto['precio_oferta']) / $producto['precio']) * 100);
        ?>
          <div class="swiper-slide">
                  <a href="producto.php?id=<?= $producto['id_producto']; ?>" class="product-card">
                  <div class="img-container">
                      <img loading="lazy" src="./static/img/productos/<?= htmlspecialchars($producto['imagen']); ?>" alt="Imagen">

                      <?php if (!empty($producto['oferta_inicio']) || !empty($producto['oferta_fin'])): ?>
                          <?php if (!empty($producto['oferta_fin'])): ?>
                              <p class="oferta-fechas">
                                  Hasta <?= date('d/m/Y H:i', strtotime($producto['oferta_fin'])); ?>
                              </p>
                          <?php endif; ?>
                      <?php endif; ?>
                  </div>

                  <h3><?= htmlspecialchars($producto['nombre_producto']); ?></h3>
                  <span><?= htmlspecialchars($producto['marca']); ?></span>

                      <div class="precio precio-oferta-wrap">
                          <div class="precio-col-izq">
                              <span class="precio-original">$<?= number_format($producto['precio'], 2); ?></span>
                          </div>
                          <div class="precio-col-der">
                              <span class="precio-oferta">$<?= number_format($producto['precio_oferta'], 2); ?></span>
                          </div>
                      </div>

                      <span class="badge-oferta">-<?= $descuento; ?>%</span>
                  
                </a>
          </div>
      <?php
      }

        echo $mostrar;
        
      ?>
      </div>

      <div class="swiper-button-next ofertas-next"></div>
      <div class="swiper-button-prev ofertas-prev"></div>
    </div>
  </div>

  <div class="slider-section recomendaciones-section">
    <h2 class="section-title">Recomendaciones</h2>

    <div class="swiper swiper-recomendaciones">
      <div class="swiper-wrapper">

        <?php
          $productosRecomendados = obtenerProductosRecomendados($conexion);

          $mostrar = "";
          foreach ($productosRecomendados as $producto) {
            
            $descuento = round((($producto['precio'] - $producto['precio_oferta']) / $producto['precio']) * 100);
            ?>
              <div class="swiper-slide">
                      <a href="producto.php?id=<?= $producto['id_producto']; ?>" class="recommendation-card">
                      <div class="img-container">
                        <span class="badge-oferta">-<?= $descuento; ?>%</span>

                          <img loading="lazy" src="./static/img/productos/<?= htmlspecialchars($producto['imagen']); ?>" alt="Imagen">

                          <?php if (!empty($producto['oferta_inicio']) || !empty($producto['oferta_fin'])): ?>
                              <?php if (!empty($producto['oferta_fin'])): ?>
                                  <p class="oferta-fechas">
                                      Hasta <?= date('d/m/Y H:i', strtotime($producto['oferta_fin'])); ?>
                                  </p>
                              <?php endif; ?>
                          <?php endif; ?>
                      </div>
                      <div class="info">
                      <h3><?= htmlspecialchars($producto['nombre_producto']); ?></h3>
                      <span class="marca"><?= htmlspecialchars($producto['marca']); ?></span>
                      <p class="texto-recomendacion">
                        <?= htmlspecialchars($producto['recomendacion']); ?>
                      </p>
                      </div>          
                      
                    </a>
              </div>
          <?php
          }

            echo $mostrar;
            
      ?>

      </div>

      <div class="swiper-button-next rec-next"></div>
      <div class="swiper-button-prev rec-prev"></div>
    </div>
  </div>

</section>

<script>
  const swiperProductos = new Swiper('.swiper-productos', {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    navigation: {
      nextEl: '.ofertas-next',
      prevEl: '.ofertas-prev',
    },
    breakpoints: {
      600: {
        slidesPerView: 2,
      },
      800: {
        slidesPerView: 3,
      },
      1024: {
        slidesPerView: 4,
      },
      1250: {
        slidesPerView: 5,
      },
      1440: {
        slidesPerView: 6,
      }
    }
  });

  const swiperRec = new Swiper('.swiper-recomendaciones', {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    effect: 'slide',
    navigation: {
      nextEl: '.rec-next',
      prevEl: '.rec-prev',
    },
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
  });
</script>
</main>

<?php
require_once("templates/footer.php")
?>