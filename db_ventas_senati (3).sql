-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 04-06-2025 a las 22:39:00
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
-- Base de datos: `db_ventas_senati`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE `articulos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `articulos`
--

INSERT INTO `articulos` (`id`, `nombre`, `descripcion`, `precio`, `stock`, `fecha_creacion`, `estado`) VALUES
(4, 'Samsung A15', 'Moderno y potente', 899.00, 20, '2025-05-21 03:49:09', 1),
(5, 'Mouse Logitech G203', 'Baratao y bueno', 175.00, 5, '2025-05-21 03:49:34', 1),
(6, 'Laptop', 'Potente y con buen diseño', 2300.00, 12, '2025-05-25 00:18:35', 1),
(7, 'Teclado Kumara k552', 'Teclado mecanico, bueno para el gaming', 200.00, 20, '2025-05-25 02:24:56', 1),
(8, 'Sporte para laptop ', 'Con ventiladores para ayudar con el enfriamineto ', 45.00, 45, '2025-05-25 02:25:45', 1),
(9, 'Audifonos Lenovo Think Plus', 'Con buen sonido para las prohibidas ;v', 60.00, 31, '2025-05-25 02:26:51', 1),
(10, 'Iphone 16 promax', 'Buena camara con exelente procesador', 4500.00, 22, '2025-05-25 02:27:39', 1),
(11, 'Monitor de 144hz', 'Calidad y fluidez garantizada', 560.00, 18, '2025-05-25 02:28:51', 1),
(12, 'PadMouse', 'De buen tamaño y calidad', 30.00, 33, '2025-05-25 02:29:26', 1),
(13, 'Silla gamer ergonomica', 'buen material, garantizado el reposo de la espalda', 999.00, 9, '2025-05-25 02:30:25', 1),
(14, 'Ventilador', 'Perfecto para los dias calurosos', 270.00, 15, '2025-05-25 02:30:49', 1),
(15, 'Escritorio ', 'Perfecto para poner una pc o laptop ', 490.00, 13, '2025-05-25 02:31:27', 1),
(16, 'procesador ryzen 5 5600g', 'Muy buen procesador, uno de los mejores del mercado', 896.00, 17, '2025-05-25 02:32:40', 1),
(17, 'GeForce RTX 3080', 'El mejor del mercado de las tarjetas graficas', 5600.00, 21, '2025-05-25 02:34:07', 1),
(18, 'Pasta termica', 'perfecto para mejorar la transferencia de calor entre dos superficies', 120.00, 22, '2025-05-25 17:12:04', 0),
(19, ' Laptop Gamer', 'Potente equipo para juegos y tareas exigentes, con procesador de última generación.', 3200.00, 15, '2025-06-05 03:30:03', 1),
(20, 'Monitor LED 24\" Full HD', 'Pantalla de alta definición ideal para trabajo y entretenimiento, con colores vibrantes.', 180.00, 30, '2025-06-05 03:30:28', 1),
(21, 'Teclado Mecánico RGB', 'Teclado de alto rendimiento con interruptores mecánicos y retroiluminación RGB personalizable.', 175.00, 23, '2025-06-05 03:30:55', 1),
(22, 'Mouse Inalámbrico Ergonómico', 'atón cómodo y preciso para uso prolongado, con conexión inalámbrica estable.', 25.00, 50, '2025-06-05 03:31:19', 1),
(23, ' Auriculares con Micrófono Gaming', 'Sonido envolvente y micrófono con cancelación de ruido, perfectos para sesiones de juego.', 60.00, 20, '2025-06-05 03:31:40', 1),
(24, 'Disco Duro Externo 1TB', 'Almacenamiento portátil de gran capacidad para guardar tus archivos importantes.', 450.00, 9, '2025-06-05 03:31:59', 1),
(25, 'Impresora Multifuncional Inkjet', 'Imprime, escanea y copia con facilidad. Ideal para el hogar o pequeña oficina.', 450.00, 10, '2025-06-05 03:32:29', 1),
(26, 'Consola Nintendo Switch OLED', 'La última versión de la consola híbrida de Nintendo, con una vibrante pantalla OLED de 7 pulgadas, base con puerto LAN y almacenamiento mejorado.', 1700.00, 8, '2025-06-05 03:33:26', 1),
(27, ' PlayStation 5 (PS5) - Standard Edition', ' Consola de última generación de Sony con unidad de disco Blu-ray. Ofrece gráficos 4K, ray tracing, carga ultrarrápida con SSD y retroalimentación háptica en su control DualSense. Ideal para juegos exclusivos y multiplataforma de alto rendimiento.', 2800.00, 7, '2025-06-05 03:34:15', 1),
(28, 'Xbox Series X', 'La consola más potente de Microsoft, diseñada para ofrecer juegos en 4K nativo a 120 FPS. Cuenta con un SSD personalizado para cargas rápidas y es compatible con miles de juegos de Xbox de todas las generaciones a través de la retrocompatibilidad.', 2700.00, 6, '2025-06-05 03:34:35', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boletas`
--

CREATE TABLE `boletas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_emision` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `estado` varchar(50) DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `boletas`
--

INSERT INTO `boletas` (`id`, `id_usuario`, `fecha_emision`, `total`, `estado`) VALUES
(1, 6, '2025-05-25 02:17:13', 899.00, 'Pendiente'),
(2, 7, '2025-05-25 17:22:19', 16034.00, 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_boleta`
--

CREATE TABLE `detalles_boleta` (
  `id` int(11) NOT NULL,
  `id_boleta` int(11) NOT NULL,
  `id_articulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `detalles_boleta`
--

INSERT INTO `detalles_boleta` (`id`, `id_boleta`, `id_articulo`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 1, 4, 1, 899.00, 899.00),
(2, 2, 4, 1, 899.00, 899.00),
(3, 2, 9, 1, 60.00, 60.00),
(4, 2, 10, 3, 4500.00, 13500.00),
(5, 2, 7, 7, 200.00, 1400.00),
(6, 2, 5, 1, 175.00, 175.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_factura`
--

CREATE TABLE `detalles_factura` (
  `id` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `id_articulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `detalles_factura`
--

INSERT INTO `detalles_factura` (`id`, `id_factura`, `id_articulo`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(2, 2, 7, 1, 200.00, 200.00),
(3, 2, 5, 1, 175.00, 175.00),
(4, 2, 11, 1, 560.00, 560.00),
(5, 2, 16, 1, 896.00, 896.00),
(6, 2, 4, 1, 899.00, 899.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_emision` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `estado` varchar(50) DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id`, `id_usuario`, `fecha_emision`, `total`, `estado`) VALUES
(1, 6, '2025-05-25 00:46:00', 5.00, 'Pendiente'),
(2, 7, '2025-05-25 17:19:14', 2730.00, 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `usuario`, `password`, `fecha_registro`) VALUES
(1, 'fran cachai', 'pepe@gmail.com', 'FazePepinio', '$2y$10$7B9lPVmi35mbC12ATr7LiOdYOdoUyNTvMqMz.oCETjm4GPJB5kx6i', '2025-05-21 00:11:18'),
(2, 'pepe', 'pepegrillo@gmail.com', 'pepe', '$2y$10$730Bsy5pWifxdMEVNBhjOeCQgm6zK8/ojGM/vnZsdZPwXeZ2x9nTm', '2025-05-21 00:23:35'),
(3, 'angek', 'qsdqsdgqhs@gmail.com', '8782', '$2y$10$1qdeBUHEriRTz3UqiiDfGOM.NfE7Hnd2FYMkvu0prndNbHZ5RiR8y', '2025-05-21 00:27:25'),
(4, 'tito', 'vovo@gmail.com', 'elpepe', '$2y$10$v49wOQSdlyS9HDM61iVsE.9OV7xko80YEK/bYp5Mz8C8wnE8uePgy', '2025-05-21 02:01:17'),
(5, 'yomar', 'arnoz1234@gmail.com', 'YomarST', '$2y$10$tgds9i7LkNKfeIlPGeUonujtZHaijCTezcwL8GycbnFEPjLh40/0K', '2025-05-21 02:03:29'),
(6, 'tito lara', 'pepe@senati.pe', 'TitoL', '$2y$10$CoXcwRuipUdxfL7sHZrUb.4RJZ7bANWDTWfXVMcCIZlYHu7ys7KXe', '2025-05-24 23:40:30'),
(7, 'Yomar Ortiz Diaz', '1596777@senati.pe', 'YomarOD', '$2y$10$k2B1D69u5D0psRHlA3jajexLoVpVSMU4HRdfW8XsS.fZr88D7ZgUG', '2025-05-25 17:08:05'),
(8, 'Yomar Ortiz', 'Yomar@gmail.com', 'YomarST3', '$2y$10$PXwsSnDH79omFjqVO0LBzurzxrVsI0GIQAeKYliOdYqJK05EaRSey', '2025-06-03 03:37:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_venta` datetime DEFAULT current_timestamp(),
  `total_venta` decimal(10,2) NOT NULL,
  `tipo_documento` enum('Boleta','Factura') NOT NULL,
  `estado` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `boletas`
--
ALTER TABLE `boletas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `detalles_boleta`
--
ALTER TABLE `detalles_boleta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_boleta` (`id_boleta`),
  ADD KEY `id_articulo` (`id_articulo`);

--
-- Indices de la tabla `detalles_factura`
--
ALTER TABLE `detalles_factura`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_factura` (`id_factura`),
  ADD KEY `id_articulo` (`id_articulo`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulos`
--
ALTER TABLE `articulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `boletas`
--
ALTER TABLE `boletas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalles_boleta`
--
ALTER TABLE `detalles_boleta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `detalles_factura`
--
ALTER TABLE `detalles_factura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `boletas`
--
ALTER TABLE `boletas`
  ADD CONSTRAINT `boletas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalles_boleta`
--
ALTER TABLE `detalles_boleta`
  ADD CONSTRAINT `detalles_boleta_ibfk_1` FOREIGN KEY (`id_boleta`) REFERENCES `boletas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalles_boleta_ibfk_2` FOREIGN KEY (`id_articulo`) REFERENCES `articulos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalles_factura`
--
ALTER TABLE `detalles_factura`
  ADD CONSTRAINT `detalles_factura_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `facturas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalles_factura_ibfk_2` FOREIGN KEY (`id_articulo`) REFERENCES `articulos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
