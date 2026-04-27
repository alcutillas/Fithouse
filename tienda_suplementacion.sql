-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-03-2026 a las 07:57:25
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda_suplementacion`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carritos`
--

CREATE TABLE `carritos` (
  `id_carrito` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `session_id` varchar(100) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` enum('activo','convertido','abandonado') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carritos`
--

INSERT INTO `carritos` (`id_carrito`, `id_usuario`, `session_id`, `fecha_creacion`, `fecha_actualizacion`, `estado`) VALUES
(1, 14, 't3ti80r9a83rhk8vkq3ufap8eu', '2026-03-23 11:35:42', '2026-03-25 09:58:25', 'activo'),
(2, 16, '4o3jhgspj90tlqjaj06b8ukjlm', '2026-03-23 11:55:38', '2026-03-25 10:46:56', 'activo'),
(3, NULL, 'fumdfc98f5jd1j0ihp77nlk8qb', '2026-03-25 13:23:29', '2026-03-25 13:23:29', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre_categoria`) VALUES
(4, 'aa'),
(3, 'Nike'),
(5, 'Proteína'),
(2, 'prueba 2'),
(1, 'Prueba de cat');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_carrito`
--

CREATE TABLE `detalle_carrito` (
  `id_detalle_carrito` int(11) NOT NULL,
  `id_carrito` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_carrito`
--

INSERT INTO `detalle_carrito` (`id_detalle_carrito`, `id_carrito`, `id_producto`, `cantidad`, `precio_unitario`) VALUES
(8, 1, 51, 1, 13.24),
(10, 1, 50, 11, 34.67),
(13, 2, 51, 1, 13.24),
(14, 3, 51, 2, 13.24);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id_detalle` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `nombre_cliente` varchar(120) NOT NULL,
  `correo_cliente` varchar(150) NOT NULL,
  `telefono_cliente` varchar(30) NOT NULL,
  `direccion_cliente` varchar(200) NOT NULL,
  `ciudad_cliente` varchar(120) NOT NULL,
  `cp_cliente` varchar(20) NOT NULL,
  `fecha_pedido` datetime DEFAULT current_timestamp(),
  `estado` enum('pendiente','recogido','cancelado') NOT NULL DEFAULT 'pendiente',
  `metodo_pago` enum('stripe','paypal') DEFAULT NULL,
  `estado_pago` enum('pendiente','pagado','fallido','reembolsado') NOT NULL DEFAULT 'pendiente',
  `referencia_pago` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `nombre_producto` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `precio_oferta` decimal(10,2) DEFAULT NULL,
  `oferta_inicio` datetime DEFAULT NULL,
  `oferta_fin` datetime DEFAULT NULL,
  `cantidad_existencias` int(11) NOT NULL DEFAULT 0,
  `imagen` varchar(100) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `recomendacion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `marca`, `nombre_producto`, `descripcion`, `precio`, `precio_oferta`, `oferta_inicio`, `oferta_fin`, `cantidad_existencias`, `imagen`, `id_categoria`, `recomendacion`) VALUES
(33, 'Adidas', 'prote', 'ss', 11.00, 1.00, NULL, NULL, 2, '69c24aa2dcf04.webp', 3, NULL),
(34, 's', 's', 'ssss', 12.00, 1.00, '2026-03-24 10:05:00', '2026-03-29 10:05:00', 0, '69c253ebee062.webp', 1, NULL),
(35, 'Weigh', 'Proteina', 'ssssssssssssssssssss ssssssssssssssssss ss s  ssssssssssssssssss sss', 22.11, 11.22, '2026-03-24 10:06:00', '2026-03-29 10:06:00', 0, '69c2542a810ff.webp', 1, NULL),
(36, 'aa', 'aa', 'dadcwec ca wecawc asc wc aw s', 11.00, NULL, NULL, NULL, 22, '69c25580b9c11.webp', 3, NULL),
(37, '1', '1', '1', 1.00, NULL, NULL, NULL, 0, '69c257d122cd4.webp', 3, NULL),
(38, '2', '2', '2', 2.00, NULL, NULL, NULL, 0, '69c257d9381d3.webp', 2, NULL),
(39, '3', '3', '3', 3.00, NULL, NULL, NULL, 0, '69c257e29b116.webp', 2, NULL),
(40, '4', '4', '4', 4.00, NULL, NULL, NULL, 0, '69c257ee6c848.webp', 3, NULL),
(41, '5', '5', '5aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 5.00, NULL, NULL, NULL, 5, '69c257f8f254b.webp', 4, NULL),
(42, '6', '6', '6', 6.00, NULL, NULL, NULL, 0, '69c2580565bd2.webp', 4, NULL),
(43, '7', '7', '7', 7.00, 5.00, NULL, NULL, 0, '69c2580ebfbb0.webp', 3, NULL),
(44, '8', '8', '8', 8.00, 7.00, NULL, NULL, 0, '69c258268365c.webp', 3, NULL),
(45, '9', '9', '9', 9.00, NULL, NULL, NULL, 3, '69c2582e68f4c.webp', 2, NULL),
(46, '0', '0', '0', 0.00, NULL, NULL, NULL, 4, '69c2583a551c6.webp', 1, NULL),
(47, '22', '22', '22aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa bbbbbbbbbbbbbb bbbbbbbbbbbbbbb b b bb b  b b b b bssssssssssssssssss ssssssssaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 22.00, NULL, NULL, NULL, 11, '69c2584af3831.webp', 2, NULL),
(48, 'w', 'w', 'waaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 1.00, NULL, NULL, NULL, 0, '69c2586224261.webp', 2, NULL),
(49, 'aa', 'aa', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 11.00, 1.00, '2026-03-24 10:54:00', '2026-03-28 10:54:00', 0, '69c25f6dc6dc6.webp', 4, NULL),
(50, 'MyProtein', 'Proteina weigh', 'Este producto de utiliza para que la recuperacion del musculo sea mas efectiva, ya que el musculo se regenera gracias a la proteina', 34.67, NULL, NULL, NULL, 12, '69c387e724f34.webp', 5, 'aaaaaaaaaaaaaammmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm'),
(51, 'Adidas', 'Proteina Seca', 'Esta es una oferta especial muy limitada', 13.24, 11.22, '2026-03-25 08:00:00', '2026-03-29 08:03:00', 2, '69c4df2084fbb.webp', 5, 'Este producto es bueno bonito y barato y si no que me digan lo contrario'),
(52, 'pefas', 'Proteina hori', 'esto es una prueba para la foto horizonrtal', 14.56, NULL, NULL, NULL, 1, '69c389d0ed28f.webp', 5, 'ffffffffffffffffffffffffffffffffffffffff ffffffffffffffffffffffffffffffffffffffffffff ffffffffffffffffffffffffffffffffffffffffffffffff fffffffffffffffffffffffffffffffffffffffff');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resenas`
--

CREATE TABLE `resenas` (
  `id_resena` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `puntuacion` tinyint(4) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `fecha_resena` datetime DEFAULT current_timestamp(),
  `id_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `resenas`
--

INSERT INTO `resenas` (`id_resena`, `id_usuario`, `puntuacion`, `comentario`, `fecha_resena`, `id_producto`) VALUES
(4, 14, 5, 'ss', '2026-03-24 10:48:48', 45),
(6, 14, 5, 'Esta es una buena suplem,enmntaidieoc ac ejc ka cacaec.\r\ndnedc jka ce jcas caewcA cewakc ac ecj kdsce vlev', '2026-03-25 09:21:30', 49),
(7, 14, 5, 'ff', '2026-03-26 08:46:26', 47),
(10, 21, 3, 'Esta bn', '2026-03-26 08:47:10', 47);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo_electronico` varchar(150) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `ciudad` varchar(120) DEFAULT NULL,
  `cp` varchar(20) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `rol` varchar(50) NOT NULL DEFAULT 'cliente',
  `password` varchar(255) NOT NULL,
  `email_verificado` tinyint(1) NOT NULL DEFAULT 0,
  `token_verificacion` varchar(255) DEFAULT NULL,
  `token_expira` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo_electronico`, `telefono`, `direccion`, `ciudad`, `cp`, `fecha_registro`, `rol`, `password`, `email_verificado`, `token_verificacion`, `token_expira`) VALUES
(14, 'Fithouse', 'fit.housesanvi@gmail.com', '658554385', 'aa', 'aa', '11111', '2026-03-05 11:42:14', 'admin', '$2y$10$mqUq2AVuulYafbJvEjhH8.S5NIcq/2envg2tc4vkgl4nDcBgr4e3m', 0, NULL, NULL),
(16, 'a', 'a@gmail.com', '1', 'a', 'b', '02222', '2026-03-23 09:37:13', 'cliente', '$2y$10$lPVBzxJdLFeCrRmSygj0meuuEC778fRYR6GqIw.NSEClJsBlTiNDS', 0, 'd84cdc31824c987f787b548bff5fc921bdc15d95bb97744a5d8079734992facf', '2026-03-25 11:56:38'),
(21, 'prueba', 'prueba@gmail.com', '612345678', NULL, NULL, NULL, '2026-03-26 08:19:10', 'cliente', '$2y$10$cCp1pH7.y7BJFX5fP09Khu/aOcOYey7UfNNzS/UorIo18NepMYz6S', 0, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carritos`
--
ALTER TABLE `carritos`
  ADD PRIMARY KEY (`id_carrito`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `nombre_categoria` (`nombre_categoria`);

--
-- Indices de la tabla `detalle_carrito`
--
ALTER TABLE `detalle_carrito`
  ADD PRIMARY KEY (`id_detalle_carrito`),
  ADD KEY `id_carrito` (`id_carrito`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD UNIQUE KEY `uk_pedidos_referencia_pago` (`referencia_pago`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `fk_producto_categoria` (`id_categoria`);

--
-- Indices de la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD PRIMARY KEY (`id_resena`),
  ADD UNIQUE KEY `unique_usuario_producto` (`id_usuario`,`id_producto`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo_electronico` (`correo_electronico`),
  ADD UNIQUE KEY `correo_electronico_2` (`correo_electronico`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carritos`
--
ALTER TABLE `carritos`
  MODIFY `id_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalle_carrito`
--
ALTER TABLE `detalle_carrito`
  MODIFY `id_detalle_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de la tabla `resenas`
--
ALTER TABLE `resenas`
  MODIFY `id_resena` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carritos`
--
ALTER TABLE `carritos`
  ADD CONSTRAINT `carritos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `detalle_carrito`
--
ALTER TABLE `detalle_carrito`
  ADD CONSTRAINT `detalle_carrito_ibfk_1` FOREIGN KEY (`id_carrito`) REFERENCES `carritos` (`id_carrito`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_carrito_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_producto_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD CONSTRAINT `resenas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE,
  ADD CONSTRAINT `resenas_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
