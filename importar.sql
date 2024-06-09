-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-06-2024 a las 11:50:29
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
  `shipment_method` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(75, 'Martillo', 'Martillo de alta calidad con mango ergonómico de goma para mayor comodidad y precisión en cada golpe. Ideal para trabajos de carpintería, reparación y bricolaje en general.', 15.99, 'martillo.jpg'),
(76, 'Destornillador plano', 'Destornillador plano con mango antideslizante y punta de acero endurecido. Perfecto para trabajos de precisión y aplicaciones generales de bricolaje.', 7.50, 'destornillador_plano.jpg'),
(77, 'Taladro', 'Taladro eléctrico de alta potencia con múltiples velocidades y funciones de perforación y atornillado. Incluye un juego de brocas y puntas.', 55.99, 'taladro.jpg'),
(78, 'Llave Inglesa', 'Llave inglesa ajustable fabricada en acero al carbono de alta resistencia. Ideal para una variedad de trabajos de ajuste y reparación.', 12.75, 'llaveInglesa.jpg'),
(79, 'Cinta Métrica', 'Cinta métrica de 5 metros con carcasa resistente y bloqueo automático. Incluye clip para el cinturón y función de rebobinado automático.', 6.50, 'cintaMetrica.jpg'),
(80, 'Nivel de Burbuja', 'Nivel de burbuja de 60 cm con cuerpo de aluminio resistente y burbujas de alta precisión para medir nivelación horizontal y vertical.', 9.99, 'nivelBurbuja.jpg'),
(81, 'Alicate Universal', 'Alicate universal multiusos con mordazas endurecidas y mango antideslizante para trabajos de corte, pelado y sujeción.', 14.99, 'alicate.jpg'),
(82, 'Olla de 5 litros', 'Olla de 5 litros de acero inoxidable con tapa de vidrio templado y asas resistentes al calor. Ideal para cocinar sopas, guisos y más.', 28.99, 'olla_5l.jpg'),
(83, 'Cuchillos de cocina', 'Juego de cuchillos de cocina de alta calidad con hojas de acero inoxidable y mangos ergonómicos. Incluye cuchillo de chef, santoku, pelador y más.', 19.75, 'juego_cuchillos_cocina.jpg'),
(84, 'Juego de Tazas', 'Juego de 6 tazas de cerámica decoradas con patrones elegantes. Aptas para microondas y lavavajillas.', 25.50, 'tazas.jpg'),
(85, 'Batidora de Mano', 'Batidora de mano eléctrica de 500W con múltiples velocidades y accesorios intercambiables para mezclar, batir y picar.', 33.99, 'batidora.jpg'),
(86, 'Platos de Porcelana', 'Juego de 12 platos de porcelana blanca con diseño clásico y bordes reforzados. Perfectos para cualquier ocasión.', 45.75, 'platos.jpg'),
(87, 'Vaso Medidor', 'Vaso medidor de vidrio con escala en mililitros y onzas. Resistente al calor y apto para lavavajillas.', 5.99, 'vasoMedidor.jpg'),
(88, 'Set de Utensilios de Cocina', 'Set de 5 utensilios de cocina de silicona y madera, incluye espátula, cucharón, batidor y más. Resistente al calor y fácil de limpiar.', 19.99, 'utensiliosCocina.jpg'),
(89, 'Sartén 10 pulgadas', 'Sartén de cocina antiadherente de alta calidad, ideal para freír, saltear y cocinar con menos aceite. Compatible con todas las estufas, incluida la inducción.', 12.50, 'sarten_10\'.jpg'),
(90, 'Sartenes cerámicas', 'Juego de sartenes con revestimiento de cerámica antiadherente, libres de PFOA y PTFE. Incluye sartenes de 20 cm y 28 cm.', 29.99, 'sartenes_ceramicas.jpg'),
(91, 'Tetera de vidrio', 'Tetera de vidrio transparente con capacidad para 1 litro. Incluye infusor de acero inoxidable para preparar té de hojas sueltas.', 15.25, 'tetera_vidrio.jpg'),
(92, 'Tabla de cortar', 'Tabla de cortar duradera fabricada en bambú natural, con ranuras para recoger líquidos y superficie resistente a cortes.', 14.25, 'tabla_cortar.jpg'),
(93, 'Extensor de grifo', 'Grifo de cocina de alta calidad con rociador extraíble y manguera extensible. Facilita la limpieza y el llenado de recipientes grandes.', 42.99, 'extensor_grifo.jpg'),
(94, 'Cerradura puerta', 'Cerradura de alta seguridad con llave de seguridad. Incluye todos los componentes necesarios para una instalación sencilla y rápida.', 45.50, 'cerradura_puerta.jpg'),
(95, 'Cerradura de Seguridad', 'Cerradura de seguridad con sistema anti-bumping y cilindro reforzado. Aumenta la protección contra robos y entradas forzadas.', 52.99, 'cerraduraSeguridad.jpg'),
(96, 'Kit de Instalación de Cerraduras', 'Kit completo para la instalación de cerraduras, incluye brocas, destornilladores y guías de instalación.', 37.50, 'instalacionCerraduras.jpg'),
(97, 'Candado combinación', 'Candado de seguridad con combinación de 4 dígitos, ideal para asegurar maletas, taquillas y más.', 9.99, 'candado_combinacion.jpg'),
(98, 'Candado de Alta Seguridad', 'Candado de alta seguridad con tecnología anti-corte y cuerpo de acero endurecido. Ideal para asegurar puertas y almacenes.', 22.99, 'candadoSeguridad.jpg'),
(99, 'Candado bicicleta', 'Candado resistente para bicicletas con cable de acero reforzado y cerradura de combinación de 5 dígitos.', 18.75, 'candado_bicicleta.jpg'),
(100, 'Llave Maestra', 'Llave maestra para cerraduras de alta seguridad, fabricada en acero inoxidable de alta resistencia.', 12.50, 'llaveMaestra.jpg'),
(101, 'Pomos para Puertas', 'Juego de pomos para puertas con diseño moderno y elegante, incluye todos los accesorios necesarios para la instalación.', 18.75, 'pomoPuerta.jpg'),
(102, 'Sierra de mano', 'Sierra compacta y plegable, perfecta para cortar madera, plástico y metal en trabajos de bricolaje y jardinería.', 22.99, 'sierra_mano.jpg'),
(103, 'Juego de llaves allen', 'Juego de llaves allen de diferentes tamaños, útiles para ajustes y reparaciones en bicicletas, muebles y electrodomésticos.', 14.99, 'juego_llaves_allen.jpg'),
(104, 'Destornilladores precisión', 'Juego de destornilladores pequeños de precisión, ideales para reparar dispositivos electrónicos, gafas y relojes.', 8.75, 'destornilladores_precision.jpg'),
(105, 'Caja de herramientas', 'Caja de herramientas resistente con múltiples compartimentos y bandejas extraíbles, perfecta para almacenar y organizar tus herramientas.', 24.50, 'caja_herramientas.jpg'),
(106, 'Llave de tubo', 'Llave de tubo ajustable de alta resistencia con capacidad para diversos tamaños de tuercas y pernos.', 10.99, 'llave_tubo.jpg'),
(107, 'Surtido de Tornillos', 'Set surtido de tornillos y tuercas de alta calidad en diferentes tamaños y tipos. Perfecto para proyectos de construcción, reparación y bricolaje en el hogar, la oficina o el taller. Este set incluye una variedad de tornillos y tuercas en diferentes longitudes y diámetros, lo que te permite encontrar la pieza perfecta para cada aplicación. Fabricados con materiales resistentes y duraderos, estos tornillos y tuercas ofrecen un rendimiento confiable y una sujeción segura. Con su amplia gama de opciones, este set es una solución conveniente para tus necesidades de fijación.', 19.99, 'surtidoTornillos.jpg'),
(108, 'Alargador Eléctrico', 'Alargador eléctrico de 5 metros con enchufes múltiples y protección contra sobretensiones. Perfecto para conectar y alimentar dispositivos electrónicos y electrodomésticos en diferentes áreas de tu hogar u oficina. Este alargador eléctrico cuenta con un cable grueso y resistente que garantiza una transmisión de energía segura y estable. Los enchufes múltiples permiten conectar varios dispositivos al mismo tiempo, mientras que la protección contra sobretensiones protege tus equipos contra daños eléctricos. Con su diseño práctico y su seguridad integrada, este alargador eléctrico ofrece una solución conveniente para tus necesidades de energía.', 24.99, 'alargadorElectrico.jpg'),
(109, 'Maza de Goma', 'Maza de goma con mango ergonómico y cabeza de goma resistente. Perfecta para trabajos de montaje, ajuste y demolición en los que se requiera una fuerza de impacto controlada. Esta maza está diseñada para golpear superficies delicadas o materiales que podrían dañarse con herramientas de metal. El mango ergonómico proporciona un agarre cómodo y antideslizante, lo que permite un uso prolongado sin fatiga. Con su construcción duradera y su capacidad para absorber impactos, esta maza de goma es una herramienta esencial para cualquier caja de herramientas.', 14.99, 'mazaGoma.jpg');

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
(94, 'Pablo', 'González Ruiz', 'pabligoru99@gmail.com', '$2y$10$BTvtpOEOLV.1Kay2K.StZOWx3u9SuYCVYMY7H1dyxrWeki9u5VWim', 'mi calle', '33202', 'Gijón', 'España', 123456789, 'contrareembolso', 'Envío24h', 0),
(99, 'user', 'user', 'user@example.com', '$2y$10$Q30ad4poRD5opTRlXt.MfuLhUpy2eHZR5Je1r8g6oaTiyFcxPj/uu', 'calle manolo', '33202', 'Gijón', 'España', 123456789, 'transferencia', 'Envío Normal', 1),
(100, 'sara', 'pidal martinez', 'sara@gmail.es', '$2y$10$PSBxRimojlqyAmOmVLSAo.cvqdBashNjMwsiqZ1jjxlocy0KsQIPG', 'avenida constitucion, 42, 1 izquierda', '33203', 'Gijón', 'España', 658921036, NULL, '', 1),
(112, 'Pedro', 'a', 'prueba@prueba.com', '$2y$10$dYz/oeI.41foddAiBPVZweKHJpN3LLRVUZZcQtHVq7/7M5xpWFij.', 'w123123123', '33202', 'wqeqwe', 'qweqeqw', 123456789, 'contrareembolso', 'Envío24h', 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT de la tabla `pedidos_productos`
--
ALTER TABLE `pedidos_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

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
