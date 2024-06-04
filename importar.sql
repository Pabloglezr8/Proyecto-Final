-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-06-2024 a las 18:12:15
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
-- Base de datos: `proyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_pedidos`
--

CREATE TABLE `estado_pedidos` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_pedidos`
--

INSERT INTO `estado_pedidos` (`id`, `pedido_id`, `estado`, `fecha_actualizacion`) VALUES
(8, 105, 'En espera', '2024-06-03 16:06:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `date` datetime NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `shipment_method` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_usuario`, `total_price`, `date`, `payment_method`, `shipment_method`) VALUES
(105, 112, 15.25, '2024-06-03 18:06:02', 'transferencia', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos_productos`
--

CREATE TABLE `pedidos_productos` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos_productos`
--

INSERT INTO `pedidos_productos` (`id`, `pedido_id`, `product_id`, `quantity`, `price`) VALUES
(152, 105, 11, 1, 15.25);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(1500) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `img` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `name`, `description`, `price`, `img`) VALUES
(1, 'Martillo', '<p class=\"parragraf\"><p><strong>Lorem Ipsum</strong> es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno est&aacute;ndar de las industrias desde el a&ntilde;o 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido us&oacute; una galer&iacute;a de textos y los mezcl&oacute; de tal manera que logr&oacute; hacer un libro de textos especimen. No s&oacute;lo sobrevivi&oacute; 500 a&ntilde;os, sino que tambien ingres&oacute; como texto de relleno en documentos electr&oacute;nicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creaci&oacute;n de las hojas \"Letraset\", las cuales contenian pasajes de Lorem Ipsum, y m&aacute;s recientemente con software de autoedici&oacute;n, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.</p>\r\n<p>&nbsp;</p></p>', 15.99, 'martillo.jpg'),
(2, 'Destornillador plano', '<p class=\"parragraf\"><p><strong>Lorem Ipsum</strong> es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno est&aacute;ndar de las industrias desde el a&ntilde;o 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido us&oacute; una galer&iacute;a de textos y los mezcl&oacute; de tal manera que logr&oacute; hacer un libro de textos especimen. No s&oacute;lo sobrevivi&oacute; 500 a&ntilde;os, sino que tambien ingres&oacute; como texto de relleno en documentos electr&oacute;nicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creaci&oacute;n de las hojas \"Letraset\", las cuales contenian pasajes de Lorem Ipsum, y m&aacute;s recientemente con software de autoedici&oacute;n, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.</p></p>', 7.50, 'destornillador_plano.jpg'),
(3, 'Olla de 5 litros', '<p class=\"parragraf\"><p><strong>Lorem Ipsum</strong> es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno est&aacute;ndar de las industrias desde el a&ntilde;o 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido us&oacute; una galer&iacute;a de textos y los mezcl&oacute; de tal manera que logr&oacute; hacer un libro de textos especimen. No s&oacute;lo sobrevivi&oacute; 500 a&ntilde;os, sino que tambien ingres&oacute; como texto de relleno en documentos electr&oacute;nicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creaci&oacute;n de las hojas \"Letraset\", las cuales contenian pasajes de Lorem Ipsum, y m&aacute;s recientemente con software de autoedici&oacute;n, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.</p></p>', 28.99, 'olla_5l.jpg'),
(4, 'Cuchillos de cocina', '<p class=\"parragraf\"><p class=\"parragraf\"><strong>Lorem Ipsum</strong> es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno est&aacute;ndar de las industrias desde el a&ntilde;o 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido us&oacute; una galer&iacute;a de textos y los mezcl&oacute; de tal manera que logr&oacute; hacer un libro de textos especimen. No s&oacute;lo sobrevivi&oacute; 500 a&ntilde;os, sino que tambien ingres&oacute; como texto de relleno en documentos electr&oacute;nicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creaci&oacute;n de las hojas \"Letraset\", las cuales contenian pasajes de Lorem Ipsum, y m&aacute;s recientemente con software de autoedici&oacute;n, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.</p></p>', 19.75, 'juego_cuchillos_cocina.jpg'),
(5, 'Cerradura puerta', 'Cerradura de alta seguridad con llave de seguridad para proteger tu puerta principal contra intrusos.', 45.50, 'cerradura_puerta.jpg'),
(6, 'Sierra de mano', 'Sierra compacta y plegable, perfecta para cortar madera y otros materiales en espacios reducidos.', 22.99, 'sierra_mano.jpg'),
(7, 'Juego de llaves allen', 'Juego de llaves allen de diferentes tamaños, útiles para apretar o aflojar tornillos hexagonales.', 14.99, 'juego_llaves_allen.jpg'),
(8, 'Sartén 10 pulgadas', 'Sartén de cocina antiadherente de alta calidad, ideal para cocinar sin que los alimentos se peguen.', 12.50, 'sarten_10\'.jpg'),
(9, 'Candado combinación', 'Candado de seguridad con combinación de 4 dígitos, perfecto para asegurar casilleros, maletas, entre otros.', 9.99, 'candado_combinacion.jpg'),
(10, 'Destornilladores precisión', 'Juego de destornilladores pequeños de precisión, ideales para reparaciones delicadas en dispositivos electrónicos.', 8.75, 'destornilladores_precision.jpg'),
(11, 'Tetera de vidrio', 'Tetera de vidrio transparente con capacidad para 1 litro, resistente al calor y apta para hervir agua.', 15.25, 'tetera_vidrio.jpg'),
(13, 'Sartenes cerámicas', 'Juego de sartenes con revestimiento de cerámica antiadherente, perfectas para cocinar de manera saludable.', 29.99, 'sartenes_ceramicas.jpg'),
(14, 'Caja de herramientas', 'Caja de herramientas resistente con múltiples compartimentos para organizar y transportar herramientas de manera eficiente.', 24.50, 'caja_herramientas.jpg'),
(15, 'Candado bicicleta', 'Candado resistente para bicicletas con cable de acero, proporciona una protección adicional contra robos.', 18.75, 'candado_bicicleta.jpg'),
(16, 'Llave de tubo', 'Llave de tubo ajustable de alta resistencia con capacidad para adaptarse a diferentes tamaños de tuercas y tornillos.', 10.99, 'llave_tubo.jpg'),
(17, 'Tabla de cortar', 'Tabla de cortar duradera fabricada en bambú natural, ideal para cortar y preparar alimentos en la cocina.', 14.25, 'tabla_cortar.jpg'),
(18, 'Extensor de grifo', 'Grifo de cocina de alta calidad con rociador extraíble, facilita la limpieza de fregaderos y recipientes grandes.', 42.99, 'extensor_grifo.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `phone` bigint(9) UNSIGNED DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `shipment_method` varchar(50) DEFAULT NULL,
  `role` tinyint(1) NOT NULL DEFAULT 1 CHECK (`role` in (0,1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `name`, `surname`, `email`, `password`, `address`, `postal_code`, `location`, `country`, `phone`, `payment_method`, `shipment_method`, `role`) VALUES
(94, 'Pablo', 'González Ruiz', 'admin@admin.es', '$2y$10$BTvtpOEOLV.1Kay2K.StZOWx3u9SuYCVYMY7H1dyxrWeki9u5VWim', 'mi calle', '33202', 'Gijón', 'España', 123456789, 'contrareembolso', 'Envío Normal', 0),
(99, 'user', 'user', 'user@example.com', '$2y$10$Q30ad4poRD5opTRlXt.MfuLhUpy2eHZR5Je1r8g6oaTiyFcxPj/uu', 'calle manolo', '33202', 'Gijón', 'España', 123456789, 'transferencia', 'Envío Normal', 1),
(100, 'sara', 'pidal martinez', 'sara@gmail.es', '$2y$10$PSBxRimojlqyAmOmVLSAo.cvqdBashNjMwsiqZ1jjxlocy0KsQIPG', 'avenida constitucion, 42, 1 izquierda', '33203', 'Gijón', 'España', 658921036, NULL, '', 1),
(112, 'Prueba', 'Prueba', 'prueba@prueba.com', '$2y$10$dYz/oeI.41foddAiBPVZweKHJpN3LLRVUZZcQtHVq7/7M5xpWFij.', 'Calle Prueba', '33202', 'Gijón', 'España', 123456789, 'transferencia', 'Envío Normal', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estado_pedidos`
--
ALTER TABLE `estado_pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `pedidos_productos`
--
ALTER TABLE `pedidos_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `product_id` (`product_id`);

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
-- AUTO_INCREMENT de la tabla `estado_pedidos`
--
ALTER TABLE `estado_pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT de la tabla `pedidos_productos`
--
ALTER TABLE `pedidos_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `estado_pedidos`
--
ALTER TABLE `estado_pedidos`
  ADD CONSTRAINT `estado_pedidos_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pedidos_productos`
--
ALTER TABLE `pedidos_productos`
  ADD CONSTRAINT `pedidos_productos_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `pedidos_productos_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `productos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
