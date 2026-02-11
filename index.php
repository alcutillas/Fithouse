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
        <div>
            <h2>Ofertas</h2>
    <div class="swiper swiper-productos">
    <div class="swiper-wrapper">
      <?php
        //Aqui habra que hacer algo para que lo edite el admin
      ?>
      <div class="swiper-slide">
        <div class="product-card">
          <img src="./static/img/fondo-index.jpg" alt="Producto 1">
          <h3>Nombre del Producto</h3>
          <p class="precio">$99.00</p>
          <button class="btn-buy">Añadir al carrito</button>
        </div>
      </div>
        <div class="swiper-slide">
        <div class="product-card">
          <img src="./static/img/fondo-index.jpg" alt="Producto 1">
          <h3>Nombre del Producto</h3>
          <p class="precio">$99.00</p>
          <button class="btn-buy">Añadir al carrito</button>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="product-card">
          <img src="./static/img/fondo-index.jpg" alt="Producto 1">
          <h3>Nombre del Producto</h3>
          <p class="precio">$99.00</p>
          <button class="btn-buy">Añadir al carrito</button>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="product-card">
          <img src="./static/img/fondo-index.jpg" alt="Producto 1">
          <h3>Nombre del Producto</h3>
          <p class="precio">$99.00</p>
          <button class="btn-buy">Añadir al carrito</button>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="product-card">
          <img src="./static/img/fondo-index.jpg" alt="Producto 1">
          <h3>Nombre del Producto</h3>
          <p class="precio">$99.00</p>
          <button class="btn-buy">Añadir al carrito</button>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="product-card">
          <img src="./static/img/fondo-index.jpg" alt="Producto 1">
          <h3>Nombre del Producto</h3>
          <p class="precio">$99.00</p>
          <button class="btn-buy">Añadir al carrito</button>
        </div>
      </div>
      
    </div>

    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    
  </div>

  <script>
    const swiperProductos = new Swiper('.swiper-productos', {
  slidesPerView: 1,
  spaceBetween: 20,
  loop: true,

  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },

  breakpoints: {
    640: {
      slidesPerView: 2,
    },
    1024: {
      slidesPerView: 4, 
    },
    1440: {
      slidesPerView: 5, 
    }
  }
});
  </script>

        <div>
            <h2>Recomendaciones</h2>
  
  <div class="swiper swiper-recomendaciones">
    <div class="swiper-wrapper">
      
      <div class="swiper-slide">
        <div class="recommendation-card">
          <div class="card-image">
            <img src="./static/img/fondo-index.jpg" alt="Producto">
          </div>
          <div class="card-content">
            <h4>Nombre del producto</h4>
            <p class="quote">Aquí se escribe la recomendación de por qué se utiliza</p>
          </div>
        </div>
      
      
      
      <div class="swiper-slide">
        <div class="recommendation-card">
          <div class="card-image">
            <img src="./static/img/fondo-index.jpg" alt="Producto">
          </div>
          <div class="card-content">
            <h4>Nombre del producto</h4>
            <p class="quote">Aquí se escribe la recomendación de por qué se utiliza</p>
          </div>
        </div>
      
        
      
      <div class="swiper-slide">
        <div class="recommendation-card">
          <div class="card-image">
            <img src="./static/img/fondo-index.jpg" alt="Producto">
          </div>
          <div class="card-content">
            <h4>Nombre del producto</h4>
            <p class="quote">Aquí se escribe la recomendación de por qué se utiliza</p>
          </div>
        </div>
      </div>
      </div>

    <div class="swiper-button-next rec-next"></div>
    <div class="swiper-button-prev rec-prev"></div>
  </div>
<script>
    const swiperRec = new Swiper('.swiper-recomendaciones', {
  slidesPerView: 1,
  spaceBetween: 30,
  loop: true,
  // Efecto de transición (opcional, 'fade' queda muy bien para 1 solo slide)
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
  
        </div>
    </section>
</main>

<?php
require_once("templates/footer.php")
?>