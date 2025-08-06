-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-08-2025 a las 21:10:42
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
-- Base de datos: `bd_usuarios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_usuario`
--

CREATE TABLE `estado_usuario` (
  `id_estado` int(11) NOT NULL,
  `nombre_estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_usuario`
--

INSERT INTO `estado_usuario` (`id_estado`, `nombre_estado`) VALUES
(1, 'Habilitado'),
(2, 'Eliminado'),
(3, 'Suspendido'),
(4, 'Pendiente de verificación');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id_log` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `accion` varchar(255) NOT NULL,
  `fecha_hora` datetime DEFAULT current_timestamp(),
  `ip_origen` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `logs`
--

INSERT INTO `logs` (`id_log`, `id_usuario`, `accion`, `fecha_hora`, `ip_origen`) VALUES
(1, 5, 'Visualización de página de logs', '2025-08-06 14:46:31', '::1'),
(2, NULL, 'Error de inicio de sesión para: adm_fernando', '2025-08-06 14:52:25', '::1'),
(3, 5, 'Inicio de sesión exitoso', '2025-08-06 14:52:49', '::1'),
(4, 5, 'Inicio de sesión exitoso', '2025-08-06 15:06:01', '::1'),
(5, 5, 'Edición de perfil', '2025-08-06 15:50:25', '::1'),
(6, 5, 'Edición de perfil', '2025-08-06 15:50:32', '::1'),
(7, 5, 'Cierre de sesión', '2025-08-06 15:54:22', '::1'),
(8, NULL, 'Registro de nuevo usuario', '2025-08-06 15:55:20', '::1'),
(9, 6, 'Verificación de cuenta exitosa', '2025-08-06 15:56:49', '::1'),
(10, 6, 'Inicio de sesión exitoso', '2025-08-06 15:57:01', '::1'),
(11, 6, 'Edición de perfil', '2025-08-06 15:57:22', '::1'),
(12, 6, 'Cierre de sesión', '2025-08-06 15:57:36', '::1'),
(13, 5, 'Inicio de sesión exitoso', '2025-08-06 15:57:46', '::1'),
(14, 5, 'Cierre de sesión', '2025-08-06 16:07:25', '::1'),
(15, NULL, 'Error de inicio de sesión para: blancodd', '2025-08-06 16:08:21', '::1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`) VALUES
(1, 'Administrador'),
(2, 'Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `verificado` tinyint(1) DEFAULT 0,
  `token_verificacion` varchar(255) DEFAULT NULL,
  `fecha_verificacion` datetime DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_modificacion` datetime DEFAULT NULL,
  `id_estado` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `apellido`, `email`, `password`, `verificado`, `token_verificacion`, `fecha_verificacion`, `fecha_registro`, `fecha_modificacion`, `id_estado`, `id_rol`) VALUES
(5, 'adm_fernando', 'Carrilloo', 'fjc07062001@gmail.com', '$2y$10$bt94u3a8wpIkz7ZrWSYxOeYvsXLNrrKyoZRI7DO67YyO9nsczZS7.', 1, NULL, '2025-08-06 07:41:51', '2025-08-06 07:41:21', NULL, 1, 1),
(6, 'celestee', 'Carrillo', 'florrolito@gmail.com', '$2y$10$i1sQRMyqZJeEKG7sW1JPxegKyx217l8kgo13UcwTQVeaoTGIpJiHG', 1, NULL, '2025-08-06 15:56:49', '2025-08-06 15:55:13', NULL, 1, 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estado_usuario`
--
ALTER TABLE `estado_usuario`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `fk_logs_usuario` (`id_usuario`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `fk_usuario_estado` (`id_estado`),
  ADD KEY `fk_usuario_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `fk_logs_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuario_estado` FOREIGN KEY (`id_estado`) REFERENCES `estado_usuario` (`id_estado`),
  ADD CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
