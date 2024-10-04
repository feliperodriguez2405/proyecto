-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-10-2024 a las 21:50:20
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `jenny`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(1, 'Alimentos'),
(2, 'Bebidas'),
(3, 'Limpieza'),
(4, 'Higiene');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `impuestos` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id`, `nombre`, `email`, `direccion`, `subtotal`, `impuestos`, `total`, `fecha`) VALUES
(1, 'william', 'w@gmail.com', 'calle 39 a 24-43', 2800.00, 280.00, 3080.00, '2024-10-01 12:38:34'),
(2, 'felipe', 'felipe@gmail.com', 'calle 39 a 24-43', 9300.00, 930.00, 10230.00, '2024-10-01 12:41:32'),
(3, 'felipeb', 'felipe@gmail.com', 'calle 39 a 24-43', 1600.00, 160.00, 1760.00, '2024-10-01 12:59:35'),
(4, 'felipeb', 'felipe@gmail.com', 'calle 39 a 24-43', 1600.00, 160.00, 1760.00, '2024-10-01 13:11:35'),
(5, 'felipeb', 'felipe@gmail.com', 'calle 39 a 24-43', 1600.00, 160.00, 1760.00, '2024-10-01 13:12:17'),
(6, 'felipeb', 'felipe@gmail.com', 'calle 39 a 24-43', 1600.00, 160.00, 1760.00, '2024-10-01 13:12:37'),
(7, 'felipeb', 'felipe@gmail.com', 'calle 39 a 24-43', 1600.00, 160.00, 1760.00, '2024-10-01 13:17:21'),
(8, 'camila', 'c@gmail.com', 'calle 39 a 24-43', 1600.00, 160.00, 1760.00, '2024-10-01 13:19:29'),
(9, 'felipe', 'felipe@gmail.com', 'calle 39 a 24-43', 1600.00, 160.00, 1760.00, '2024-10-01 13:26:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `estado` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos_detalles`
--

CREATE TABLE `pedidos_detalles` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `imagen`, `categoria_id`, `cantidad`) VALUES
(1, 'Arroz Blanco', 'Libra de arroz blanco', 2800.00, 'https://example.com/arroz-blanco.jpg', 1, 30),
(2, 'Agua Mineral', 'Botella de agua mineral 500ml', 1500.00, 'https://example.com/agua-mineral.jpg', 2, 0),
(3, 'Jabón Líquido Antibacterial', 'Jabón líquido antibacterial 250ml', 3200.00, 'https://example.com/jabon-liquido.jpg', 4, 0),
(4, 'Azúcar Morena', 'Azúcar blanca refinada', 1600.00, 'https://example.com/azucar-morena.jpg', 4, 20),
(5, 'Arroz Extra Largo', 'Arroz blanco de grano largo', 2900.00, 'https://example.com/arroz-extra-largo.webp', 1, 0),
(6, 'Leche Entera', 'Leche fresca entera', 2600.00, 'https://example.com/leche.png', 2, 0),
(7, 'Avena en Polvo Kuaker', 'Avena instantánea en polvo', 3800.00, 'https://example.com/avena-kuaker.jpg', 1, 40),
(8, 'Café Molido', 'Café molido 100% arábica', 4500.00, 'https://example.com/cafe-molido.jpg', 3, 15),
(9, 'Aceite de Oliva', 'Aceite de oliva extra virgen', 6000.00, 'https://example.com/aceite-oliva.jpg', 2, 25),
(10, 'Galletas de Chocolate', 'Galletas rellenas de chocolate', 2200.00, 'https://example.com/galletas-chocolate.jpg', 5, 50),
(11, 'Papel Higiénico', 'Paquete de 4 rollos de papel higiénico', 3500.00, 'https://example.com/papel-higienico.jpg', 4, 10),
(12, 'Champú para Cabello Seco', 'Champú hidratante para cabello seco', 4200.00, 'https://example.com/shampoo.jpg', 4, 0),
(13, 'Pasta de Tomate', 'Pasta de tomate natural', 2800.00, 'https://example.com/pasta-tomate.jpg', 1, 20),
(14, 'Cerveza Artesanal', 'Cerveza rubia artesanal', 5500.00, 'https://example.com/cerveza.jpg', 3, 30),
(15, 'Detergente en Polvo', 'Detergente para ropa en polvo', 4800.00, 'https://example.com/detergente.jpg', 4, 5);------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`) VALUES
(1, 'Juan Perez', 'juan@example.com', 'hashed_password', 'cliente'),
(2, 'Maria Lopez', 'maria@example.com', 'hashed_password', 'bibliotecario'),
(3, 'luis', 'luisfelipebermudez02@gmail.com', '1234', 'cliente'),
(4, 'luis', 'luisfelipebermudez02@gmail.com', '1234', 'cliente'),
(5, 'luis', 'luisfelipebermudez02@gmail.com', '1234', 'cliente'),
(6, 'andres', 'a@gmail.com', '1234', ''),
(7, 'andres', 'a@gmail.com', '1234', ''),
(8, 'andres', 'a@gmail.com', '1234', 'cliente'),
(9, 'camila', 'cami@gmail.com', '1234', 'cliente'),
(10, 'dana', 'dana@gmail.com', '1234', 'cliente');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `pedidos_detalles`
--
ALTER TABLE `pedidos_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
