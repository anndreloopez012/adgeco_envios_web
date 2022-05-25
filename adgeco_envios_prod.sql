-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 25-05-2022 a las 10:39:16
-- Versión del servidor: 5.7.36
-- Versión de PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `adgeco_envios_prod`
--

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `sp_mi_cuenta_persona_update`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_mi_cuenta_persona_update` (IN `intPersona` INT(10) UNSIGNED, IN `strNombre` VARCHAR(255), IN `strEmail` VARCHAR(255), IN `intUser` INT(10) UNSIGNED)  NO SQL
BEGIN

    UPDATE  persona
       SET  nombre_usual = strNombre,
            email = strEmail,
            mod_user = intUser,
            mod_fecha = NOW()
     WHERE  persona = intPersona;

END$$

DROP PROCEDURE IF EXISTS `sp_mi_cuenta_usuario_password_update`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_mi_cuenta_usuario_password_update` (IN `intPersona` INT(10) UNSIGNED, IN `strPassword` VARCHAR(255), IN `intUser` INT(10) UNSIGNED)  NO SQL
BEGIN

    UPDATE  usuario
       SET password = MD5(strPassword),
            mod_user = intUser,
            mod_fecha = NOW()
     WHERE  persona = intPersona;

END$$

DROP PROCEDURE IF EXISTS `sp_perfil_acceso_delete`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_perfil_acceso_delete` (IN `intPerfil` INT UNSIGNED, IN `intModulo` INT UNSIGNED)  NO SQL
BEGIN

    IF intModulo > 0 THEN
        DELETE FROM perfil_acceso
        WHERE  perfil = intPerfil
        AND acceso IN( SELECT acceso FROM acceso WHERE modulo = intModulo);
    ELSE
        DELETE FROM perfil_acceso
        WHERE  perfil = intPerfil;
    END IF;

END$$

DROP PROCEDURE IF EXISTS `sp_perfil_acceso_insert`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_perfil_acceso_insert` (IN `intPerfil` INT(5) UNSIGNED, IN `intAcceso` INT(10), IN `intTipoAcceso` INT(3))  NO SQL
BEGIN

     INSERT INTO perfil_acceso ( perfil, acceso, tipo_acceso)
        VALUES ( intPerfil, intAcceso, intTipoAcceso);

END$$

DROP PROCEDURE IF EXISTS `sp_perfil_cuenta_delete`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_perfil_cuenta_delete` (IN `intPersona` INT, IN `intPerfil` INT)  NO SQL
BEGIN

    DELETE FROM persona_perfil WHERE perfil = intPerfil AND persona = intPersona;

END$$

DROP PROCEDURE IF EXISTS `sp_perfil_cuenta_insert`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_perfil_cuenta_insert` (IN `intPersona` INT(10), IN `intPerfil` INT(5) UNSIGNED)  NO SQL
BEGIN

     INSERT INTO persona_perfil ( persona, perfil)
        VALUES ( intPersona, intPerfil);

END$$

DROP PROCEDURE IF EXISTS `sp_perfil_cuenta_update`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_perfil_cuenta_update` (IN `intPersona` INT(10), IN `intPerfil` INT(5) UNSIGNED, IN `intPersonaOriginal` INT(10))  NO SQL
BEGIN
        UPDATE  persona_perfil
          SET   persona = intPersona,
                perfil = intPerfil
        WHERE   persona = intPersonaOriginal;


END$$

DROP PROCEDURE IF EXISTS `sp_perfil_delete`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_perfil_delete` (IN `intPerfil` SMALLINT UNSIGNED)  NO SQL
BEGIN

    DELETE FROM perfil WHERE perfil = intPerfil;

END$$

DROP PROCEDURE IF EXISTS `sp_perfil_insert`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_perfil_insert` (IN `strNombre` VARCHAR(75), IN `strDescripcion` VARCHAR(300), IN `strActivo` VARCHAR(1), IN `intUser` INT(10) UNSIGNED, OUT `intID` INT(12) UNSIGNED)  NO SQL
BEGIN

     INSERT INTO perfil ( nombre, descripcion, activo, add_user, add_fecha)
        VALUES ( strNombre, strDescripcion, strActivo, intUser, now());
        SET intID = LAST_INSERT_ID();

END$$

DROP PROCEDURE IF EXISTS `sp_perfil_update`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_perfil_update` (IN `intPerfil` INT(5) UNSIGNED, IN `strNombre` VARCHAR(75), IN `strDescripcion` VARCHAR(300), IN `strActivo` VARCHAR(1), IN `intUser` INT(10) UNSIGNED)  NO SQL
BEGIN
        UPDATE  perfil
          SET   nombre = strNombre,
                descripcion = strDescripcion,
                activo = strActivo,
                mod_user = intUser,
                mod_fecha = now()
        WHERE   perfil = intPerfil;

END$$

DROP PROCEDURE IF EXISTS `sp_persona_delete`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_persona_delete` (IN `intPersona` INT(10) UNSIGNED)  NO SQL
BEGIN

    UPDATE  persona
       SET  eliminado = 'Y',
            activo = 'N'
     WHERE  persona = intPersona;

    UPDATE  usuario
       SET  bloqueado = 'Y'
     WHERE  persona = intPersona;

    DELETE FROM persona_perfil WHERE persona = intPersona;

END$$

DROP PROCEDURE IF EXISTS `sp_persona_insert`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_persona_insert` (IN `strNombreUsual` VARCHAR(255), IN `strSexo` VARCHAR(10), IN `intPais` SMALLINT UNSIGNED, IN `strEmail` VARCHAR(255), IN `strFoto` VARCHAR(255), IN `intUser` INT(10) UNSIGNED, OUT `intID` INT(10) UNSIGNED)  NO SQL
BEGIN

    INSERT INTO persona ( nombre_usual, sexo, pais, email, foto, add_user, add_fecha )
    VALUES ( strNombreUsual, strSexo, intPais, strEmail, strFoto, intUser, now());
    SET intID = LAST_INSERT_ID();

END$$

DROP PROCEDURE IF EXISTS `sp_persona_perfil_delete`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_persona_perfil_delete` (IN `intPersona` INT(10) UNSIGNED)  NO SQL
BEGIN

    DELETE FROM persona_perfil WHERE persona = intPersona;

END$$

DROP PROCEDURE IF EXISTS `sp_persona_perfil_insert`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_persona_perfil_insert` (IN `intPersona` INT(10) UNSIGNED, IN `intPerfil` INT(5) UNSIGNED)  NO SQL
BEGIN

    INSERT INTO persona_perfil ( persona, perfil )
    VALUES ( intPersona, intPerfil );

END$$

DROP PROCEDURE IF EXISTS `sp_persona_update`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_persona_update` (IN `intPersona` INT(10) UNSIGNED, IN `strNombreUsual` VARCHAR(255), IN `strSexo` VARCHAR(10), IN `intPais` SMALLINT UNSIGNED, IN `strEmail` VARCHAR(255), IN `strFoto` VARCHAR(255), IN `intUser` INT(10) UNSIGNED)  NO SQL
BEGIN

    UPDATE  persona
       SET  nombre_usual = strNombreUsual,
            sexo = strSexo,
            pais = intPais,
            email = strEmail,
            foto = strFoto,
            mod_user = intUser,
            mod_fecha = NOW()
     WHERE  persona = intPersona;

END$$

DROP PROCEDURE IF EXISTS `sp_usuario_insert`$$
CREATE DEFINER=`idctest`@`localhost` PROCEDURE `sp_usuario_insert` (IN `intPersona` INT(10) UNSIGNED, IN `strUsuario` VARCHAR(75), IN `strPassword` VARCHAR(40), IN `intIdioma` INT(3) UNSIGNED, IN `strTipo` VARCHAR(20), IN `strBloqueado` VARCHAR(1), IN `intUser` INT(10) UNSIGNED)  NO SQL
BEGIN

    INSERT INTO usuario ( persona, usuario, password, idioma, tipo, bloqueado, add_user, add_fecha)
    VALUES ( intPersona, strUsuario, MD5(strPassword), intIdioma, strTipo, strBloqueado, intUser, now());

END$$

DROP PROCEDURE IF EXISTS `sp_usuario_password_aleatorio_update`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_usuario_password_aleatorio_update` (IN `intPersona` SMALLINT UNSIGNED, IN `strIsAleatoria` CHAR(1))  NO SQL
BEGIN
    UPDATE usuario
    SET isAleatorio = strIsAleatoria
    WHERE persona = intPersona;
END$$

DROP PROCEDURE IF EXISTS `sp_usuario_update`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_usuario_update` (IN `intPersona` INT(10) UNSIGNED, IN `strPassword` VARCHAR(40), IN `intIdioma` INT(3) UNSIGNED, IN `strTipo` VARCHAR(20), IN `strBloqueado` VARCHAR(1), IN `intUser` INT(10) UNSIGNED)  NO SQL
BEGIN

    IF strPassword != '' THEN
        UPDATE  usuario
           SET password = MD5(strPassword),
                idioma = intIdioma,
                tipo = strTipo,
                bloqueado = strBloqueado,
                mod_user = intUser,
                mod_fecha = now()
        WHERE   persona = intPersona;
    ELSE
        UPDATE  usuario
           SET  idioma = intIdioma,
                tipo = strTipo,
                bloqueado = strBloqueado,
                mod_user = intUser,
                mod_fecha = now()
        WHERE   persona = intPersona;
    END IF;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acceso`
--

DROP TABLE IF EXISTS `acceso`;
CREATE TABLE IF NOT EXISTS `acceso` (
  `acceso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `modulo` int(5) UNSIGNED NOT NULL COMMENT 'MODULO AL QUE PERTENECE EL ACCESO',
  `codigo` varchar(100) NOT NULL COMMENT 'CODIGO PARA IDENTIFICAR EN PROGRAMACION A QUIEN PERTENECE',
  `orden` int(5) UNSIGNED NOT NULL COMMENT 'INDICA EN QU? ORDEN SE QUIERE MOSTRAR EN EL MEN?',
  `acceso_pertenece` int(10) UNSIGNED DEFAULT NULL COMMENT 'ACCESO PADRE AL QUE PERTENECE EL ACCESO',
  `path` varchar(150) DEFAULT NULL COMMENT 'PATH DE A DONDE SE DIRIGE EN EL MENU',
  `publico` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'INDICA SI ES PUBLICO O NO EL ACCESO',
  `privado` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'INDICA SI SALE EN EL MEN? YA QUE EST? LOGINEADO',
  `activo` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'DETERMINA SI EST? ACTIVO O NO',
  `icono` varchar(100) DEFAULT NULL COMMENT 'ALMACENA LA CLASE EN CSS QUE TIENE LA IMAGEN QUE REPRESENTA AL ACCESO',
  `menu` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'INDICA SI SE DESPLIEGA EN EL MENU',
  `add_user` int(10) UNSIGNED NOT NULL COMMENT 'USUARIO QUE HIZO EL REGISTR? ',
  `add_fecha` datetime NOT NULL COMMENT 'FECHA QUE SE HIZO EL REGISTRO',
  `mod_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'USUARIO QUE MODIFICO EL REGISTRO',
  `mod_fecha` datetime DEFAULT NULL COMMENT 'FECHA QUE SE HIZO LA MODIFICACI?N',
  `acceso_extra` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'INDICA QUE EL ACCESO ES PARA UN TAB DE UN ACCESO',
  PRIMARY KEY (`acceso`),
  UNIQUE KEY `acceso_codigo` (`modulo`,`codigo`),
  KEY `acceso_mod_i` (`modulo`),
  KEY `acceso_acc_per_i` (`acceso_pertenece`),
  KEY `acceso_pub_i` (`publico`),
  KEY `acceso_pri_i` (`privado`),
  KEY `acceso_act_i` (`activo`),
  KEY `acceso_add_use_i` (`add_user`),
  KEY `acceso_mod_use_i` (`mod_user`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1 COMMENT='TABLA DE ACCESOS DE TODAS LAS PANTALLAS DEL SITIO';

--
-- Volcado de datos para la tabla `acceso`
--

INSERT INTO `acceso` (`acceso`, `modulo`, `codigo`, `orden`, `acceso_pertenece`, `path`, `publico`, `privado`, `activo`, `icono`, `menu`, `add_user`, `add_fecha`, `mod_user`, `mod_fecha`, `acceso_extra`) VALUES
(1, 1, 'config', 1, NULL, NULL, 'N', 'Y', 'Y', NULL, 'Y', 1, '2016-06-13 11:17:59', NULL, NULL, 'N'),
(2, 1, 'cpanel', 1, 1, 'cpanel.php', 'N', 'Y', 'Y', NULL, 'Y', 1, '2016-06-13 11:17:59', NULL, NULL, 'N'),
(4, 2, 'usuarios_perfiles_acceso', 2, NULL, 'usuarios_perfiles_acceso.php', 'N', 'Y', 'Y', NULL, 'Y', 1, '2016-06-13 11:18:05', NULL, NULL, 'N'),
(5, 2, 'usuarios_usuarios', 3, NULL, 'usuarios_usuarios.php', 'N', 'Y', 'Y', NULL, 'Y', 1, '2016-06-13 11:18:05', NULL, NULL, 'N'),
(26, 5, 'configuracion_empresa', 1, NULL, 'configuracion_empresa.php', 'N', 'Y', 'Y', NULL, 'Y', 1, '2016-08-16 15:43:30', NULL, NULL, 'N'),
(27, 5, 'configuracion_agencia', 2, NULL, 'configuracion_agencia.php', 'N', 'Y', 'Y', NULL, 'Y', 1, '2016-08-16 15:43:30', NULL, NULL, 'N'),
(30, 5, 'configuracion_pais', 8, NULL, 'configuracion_pais.php', 'N', 'Y', 'Y', NULL, 'Y', 1, '2016-08-16 15:43:31', 1, '2022-05-25 04:26:47', 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acceso_idioma`
--

DROP TABLE IF EXISTS `acceso_idioma`;
CREATE TABLE IF NOT EXISTS `acceso_idioma` (
  `acceso` int(10) UNSIGNED NOT NULL COMMENT 'PK',
  `idioma` int(10) UNSIGNED NOT NULL COMMENT 'PK',
  `nombre_menu` varchar(75) NOT NULL COMMENT 'NOMBRE QUE SE DESEA QUE APAREZCA EN EL MENU',
  `nombre_pantalla` varchar(75) NOT NULL COMMENT 'NOMBRE QUE SE DESEA QUE APAREZCA EN PANTALLA',
  PRIMARY KEY (`acceso`,`idioma`),
  KEY `acceso_idioma_acc` (`acceso`),
  KEY `acceso_idioma_idi` (`idioma`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLA QUE ADMINISTRA LOS NOMBRES EN CADA IDIOMA DE LAS PANTA';

--
-- Volcado de datos para la tabla `acceso_idioma`
--

INSERT INTO `acceso_idioma` (`acceso`, `idioma`, `nombre_menu`, `nombre_pantalla`) VALUES
(1, 1, 'Configuracion', 'Configuracion'),
(2, 1, 'Panel de control', 'Panel de control'),
(4, 1, 'Perfiles de acceso', 'Perfiles de acceso'),
(5, 1, 'Usuarios', 'Usuarios'),
(26, 1, 'Empresas', 'Empresas'),
(27, 1, 'Agencias', 'Agencias'),
(30, 1, 'Paises', 'Paises');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acceso_tipo_permitido`
--

DROP TABLE IF EXISTS `acceso_tipo_permitido`;
CREATE TABLE IF NOT EXISTS `acceso_tipo_permitido` (
  `acceso` int(10) UNSIGNED NOT NULL COMMENT 'PK FK',
  `tipo_acceso` int(3) UNSIGNED NOT NULL COMMENT 'PK FK',
  PRIMARY KEY (`acceso`,`tipo_acceso`),
  KEY `acceso_tipo_permitido_acc` (`acceso`),
  KEY `acceso_tipo_permitido_tip` (`tipo_acceso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='INDICA QU? TIPOS PERMITIDOS SON UTILIZADOS POR UN ACCESO EN ';

--
-- Volcado de datos para la tabla `acceso_tipo_permitido`
--

INSERT INTO `acceso_tipo_permitido` (`acceso`, `tipo_acceso`) VALUES
(2, 1),
(4, 1),
(4, 2),
(4, 3),
(4, 4),
(5, 1),
(5, 2),
(5, 3),
(5, 4),
(6, 1),
(26, 1),
(26, 2),
(26, 3),
(26, 4),
(27, 1),
(27, 2),
(27, 3),
(27, 4),
(28, 1),
(28, 2),
(28, 3),
(28, 4),
(30, 1),
(30, 2),
(30, 3),
(30, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agencia`
--

DROP TABLE IF EXISTS `agencia`;
CREATE TABLE IF NOT EXISTS `agencia` (
  `agencia` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK ID QUE IDENTIFICA EL REGISTRO EN LA TABLA',
  `empresa` int(10) UNSIGNED NOT NULL COMMENT 'FK ID DE LA EMPRESA',
  `nombre` varchar(255) NOT NULL COMMENT 'NOMBRE DE LA AGENCIA',
  `codigo` varchar(75) NOT NULL COMMENT 'CODIGO DE LA AGENCIA',
  `direccion` text NOT NULL COMMENT 'INDICA LA DIRECCION DONDE ESTA UBICADA LA AGENCIA',
  `correo_electronico` varchar(320) NOT NULL COMMENT 'INDICA EL CORREO ELECTRONICO DE NOTIFICACIONES',
  `activo` enum('Y','N') NOT NULL COMMENT 'CAMPO QUE INDICA SI LA AGENCIA ESTA ACTIVA O NO',
  `add_user` int(10) UNSIGNED NOT NULL COMMENT 'FK ID DEL USUARIO QUE INSERTO LA TUPLA',
  `add_fecha` datetime NOT NULL COMMENT 'FECHA EN LA QUE SE INSERTO LA TUPLA',
  `mod_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK ID DEL USUARIO QUE MODIFICO LA TUPLA',
  `mod_fecha` datetime DEFAULT NULL COMMENT 'FECHA EN LA QUE SE MODIFICO LA TUPLA',
  PRIMARY KEY (`agencia`),
  KEY `age_emp_f` (`empresa`),
  KEY `age_add_use_f` (`add_user`),
  KEY `age_mod_use_f` (`mod_user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `agencia`
--

INSERT INTO `agencia` (`agencia`, `empresa`, `nombre`, `codigo`, `direccion`, `correo_electronico`, `activo`, `add_user`, `add_fecha`, `mod_user`, `mod_fecha`) VALUES
(1, 1, 'ADGECO', 'CENTRAL', 'PENDIENTE', 'ANDRELOPEZ012@GMAIL.COM', 'Y', 1, '2016-02-10 10:54:20', 1, '2022-05-25 04:34:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

DROP TABLE IF EXISTS `configuracion`;
CREATE TABLE IF NOT EXISTS `configuracion` (
  `modulo` int(5) UNSIGNED NOT NULL COMMENT 'PK FK',
  `codigo` varchar(20) NOT NULL COMMENT 'IDENTIFICADOR DE LA CONFIGURACION',
  `tipo_dato` enum('texto','fecha','descripcion','lista','checkbox') NOT NULL DEFAULT 'texto' COMMENT 'TIPO DE DATO QUE SE INGRESA EN LA CONFIGURACION',
  `valores` varchar(300) DEFAULT NULL COMMENT 'INDICA LOS POSIBLES VALORES DEL TIPO DE DATO LISTA',
  `valor` text NOT NULL COMMENT 'VALOR DEL DATO QUE SE INGRESA EN LA CONFIGURACION',
  `add_user` int(10) UNSIGNED NOT NULL COMMENT 'PERSONA QUE HIZO EL REGISTRO',
  `add_fecha` datetime NOT NULL COMMENT 'FECHA EN QUE SE HIZO EL REGISTRO',
  `mod_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'USUARIO QUE MODIFICO EL REGISTRO',
  `mod_fecha` datetime DEFAULT NULL COMMENT 'FECHA EN QUE SE MODIFICO EL REGISTRO',
  PRIMARY KEY (`modulo`,`codigo`),
  UNIQUE KEY `configuracion_modulo_codigo` (`modulo`,`codigo`),
  KEY `configuracion_mod_i` (`modulo`),
  KEY `configuracion_add_use_i` (`add_user`),
  KEY `configuracion_mod_use_i` (`mod_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='ADMINISTRA LAS CONFIGURACIONES DE CADA MODULO';

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`modulo`, `codigo`, `tipo_dato`, `valores`, `valor`, `add_user`, `add_fecha`, `mod_user`, `mod_fecha`) VALUES
(1, 'session_timeout', 'texto', NULL, '20', 1, '2016-06-13 11:17:59', 12, '2017-11-03 09:02:57'),
(1, 'template', 'texto', NULL, 'idc', 1, '2016-06-13 11:17:59', NULL, NULL),
(1, 'type', 'lista', 'Publico,Privado', 'Privado', 1, '2016-06-13 11:17:59', 12, '2017-11-03 11:53:21'),
(1, 'url', 'texto', NULL, 'http://www.idc.com.gt/homeland/', 1, '2016-06-13 11:17:59', 12, '2017-11-03 09:03:35'),
(2, 'usuarios_correo_envi', 'texto', NULL, 'andrelopez02@gmail.com', 1, '2016-06-13 11:18:08', 1, '2022-05-25 04:25:38'),
(3, 'globalCronJob', 'checkbox', 'true,false', 'true', 1, '2016-06-23 09:38:14', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_idioma`
--

DROP TABLE IF EXISTS `configuracion_idioma`;
CREATE TABLE IF NOT EXISTS `configuracion_idioma` (
  `modulo` int(5) UNSIGNED NOT NULL COMMENT 'PK FK',
  `codigo` varchar(20) NOT NULL COMMENT 'PK FK',
  `idioma` int(3) UNSIGNED NOT NULL COMMENT 'PK FK',
  `nombre` varchar(75) NOT NULL COMMENT 'NOMBRE DE LA CONFIGURACION EN EL IDIOMA SELECCIONADO',
  `descripcion` varchar(350) NOT NULL COMMENT 'DESCRIPCION DE LA CONFIGURACION EN EL IDIOMA SELECCIONADO',
  PRIMARY KEY (`modulo`,`codigo`,`idioma`),
  KEY `configuracion_idioma_mod_i` (`modulo`),
  KEY `configuracion_idioma_idi_i` (`idioma`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='NOMBRE DE LA CONFIGURACION EN EL IDIOMA DETERMINADO';

--
-- Volcado de datos para la tabla `configuracion_idioma`
--

INSERT INTO `configuracion_idioma` (`modulo`, `codigo`, `idioma`, `nombre`, `descripcion`) VALUES
(1, 'session_timeout', 1, 'Tiempo expirado de sesion', 'Tiempo en minutos de expirar la sesion'),
(1, 'template', 1, 'Plantilla', 'Plantilla'),
(1, 'type', 1, 'Tipo de sitio', 'Indica si el sitio tiene una parte publica o solo una parte privada'),
(1, 'url', 1, 'Sitio', 'Sitio donde esta alojado el sistema'),
(2, 'usuarios_correo_envi', 1, 'Correo electronico para enviar la contraseï¿½a', 'Correo electronico desde el que se enviara la contraseï¿½a'),
(3, 'globalCronJob', 1, 'Indicador de cronjob', 'Esta variable indica que hay cronjob en este modulo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

DROP TABLE IF EXISTS `departamento`;
CREATE TABLE IF NOT EXISTS `departamento` (
  `departamento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK ID QUE IDENTIFICA EL REGISTRO EN LA TABLA',
  `pais` smallint(5) UNSIGNED NOT NULL COMMENT 'FK ID DEL PAIS',
  `nombre` varchar(75) COLLATE utf8_unicode_ci NOT NULL COMMENT 'NOMBRE DEL DEPARTAMENTO',
  `activo` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL COMMENT 'CAMPO QUE INDICA SI EL DEPARTAMENTO ESTA ACTIVO O NO',
  `codigo_departamento` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `add_user` int(10) UNSIGNED NOT NULL COMMENT 'FK ID DEL USUARIO QUE INSERTO LA TUPLA',
  `add_fecha` datetime NOT NULL COMMENT 'FECHA EN LA QUE SE INSERTO LA TUPLA',
  `mod_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK ID DEL USUARIO QUE MODIFICO LA TUPLA',
  `mod_fecha` datetime DEFAULT NULL COMMENT 'FECHA EN LA QUE SE MODIFICO LA TUPLA',
  PRIMARY KEY (`departamento`),
  KEY `dep_pai_f` (`pais`),
  KEY `dep_add_use_f` (`add_user`),
  KEY `dep_mod_use_f` (`mod_user`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`departamento`, `pais`, `nombre`, `activo`, `codigo_departamento`, `add_user`, `add_fecha`, `mod_user`, `mod_fecha`) VALUES
(1, 1, 'Alta Verapaz', 'Y', '16', 1, '2016-03-11 16:11:37', NULL, NULL),
(2, 1, 'Baja Verapaz', 'Y', '15', 1, '2016-03-11 16:11:37', NULL, NULL),
(3, 1, 'Chimaltenango', 'Y', '03', 1, '2016-03-11 16:11:37', NULL, NULL),
(4, 1, 'Chiquimula', 'Y', '20', 1, '2016-03-11 16:11:37', NULL, NULL),
(5, 1, 'PetÃ©n', 'Y', '17', 1, '2016-03-11 16:11:37', NULL, NULL),
(6, 1, 'El Progreso', 'Y', '04', 1, '2016-03-11 16:11:37', NULL, NULL),
(7, 1, 'QuichÃ©', 'Y', '14', 1, '2016-03-11 16:11:37', NULL, NULL),
(8, 1, 'Escuintla', 'Y', '05', 1, '2016-03-11 16:11:37', NULL, NULL),
(9, 1, 'Guatemala', 'Y', '01', 1, '2016-03-11 16:11:37', NULL, NULL),
(10, 1, 'Huehuetenango', 'Y', '13', 1, '2016-03-11 16:11:37', NULL, NULL),
(11, 1, 'Izabal', 'Y', '18', 1, '2016-03-11 16:11:37', NULL, NULL),
(12, 1, 'Jalapa', 'Y', '21', 1, '2016-03-11 16:11:37', NULL, NULL),
(13, 1, 'Jutiapa', 'Y', '22', 1, '2016-03-11 16:11:37', NULL, NULL),
(14, 1, 'Quetzaltenango', 'Y', '09', 1, '2016-03-11 16:11:37', NULL, NULL),
(15, 1, 'Retalhuleu', 'Y', '11', 1, '2016-03-11 16:11:37', NULL, NULL),
(16, 1, 'SacatepÃ©quez', 'Y', '02', 1, '2016-03-11 16:11:37', NULL, NULL),
(17, 1, 'San Marcos', 'Y', '12', 1, '2016-03-11 16:11:37', NULL, NULL),
(18, 1, 'Santa Rosa', 'Y', '06', 1, '2016-03-11 16:11:37', NULL, NULL),
(19, 1, 'SololÃ¡', 'Y', '07', 1, '2016-03-11 16:11:37', NULL, NULL),
(20, 1, 'SuchitepÃ©quez', 'Y', '10', 1, '2016-03-11 16:11:37', NULL, NULL),
(21, 1, 'TotonicapÃ¡n', 'Y', '08', 1, '2016-03-11 16:11:37', NULL, NULL),
(22, 1, 'Zacapa', 'Y', '19', 1, '2016-03-11 16:11:37', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

DROP TABLE IF EXISTS `empresa`;
CREATE TABLE IF NOT EXISTS `empresa` (
  `empresa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK ID QUE IDENTIFICA EL REGISTRO EN LA TABLA',
  `nombre` varchar(75) COLLATE utf8_unicode_ci NOT NULL COMMENT 'NOMBRE DE LA EMPRESA',
  `giin` varchar(75) COLLATE utf8_unicode_ci NOT NULL COMMENT 'C?DIGO DE LA PERSONA OBLIGADA GIIN',
  `por_defecto` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL COMMENT 'INDICA SI ES POR DEFECTO',
  `activo` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL COMMENT 'CAMPO QUE INDICA SI LA EMPRESA ESTA ACTIVA O NO',
  `add_user` int(10) UNSIGNED NOT NULL COMMENT 'FK ID DEL USUARIO QUE INSERTO LA TUPLA',
  `add_fecha` datetime NOT NULL COMMENT 'FECHA EN LA QUE SE INSERTO LA TUPLA',
  `mod_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK ID DEL USUARIO QUE MODIFICO LA TUPLA',
  `mod_fecha` datetime DEFAULT NULL COMMENT 'FECHA EN LA QUE SE MODIFICO LA TUPLA',
  PRIMARY KEY (`empresa`),
  KEY `emp_add_use_f` (`add_user`),
  KEY `emp_mod_use_f` (`mod_user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`empresa`, `nombre`, `giin`, `por_defecto`, `activo`, `add_user`, `add_fecha`, `mod_user`, `mod_fecha`) VALUES
(1, 'ADGECO ENVIOS', 'PENDIENTE', 'Y', 'Y', 1, '2016-02-10 10:53:09', 1, '2022-05-25 04:33:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `idioma`
--

DROP TABLE IF EXISTS `idioma`;
CREATE TABLE IF NOT EXISTS `idioma` (
  `idioma` int(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `codigo` varchar(5) NOT NULL COMMENT 'CODIGO PARA IDENTIFICAR AL IDIOMA',
  `nombre` varchar(15) NOT NULL COMMENT 'NOMBRE PARA IDENTIFICAR EL IDIOMA',
  `add_user` int(10) UNSIGNED NOT NULL COMMENT 'IDENTIFICA A LA PERSONA QUE CREO EL REGISTRO',
  `add_fecha` datetime NOT NULL COMMENT 'FECHA EN QUE SE CREO EL REGISTRO',
  `mod_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'USUARIO QUE MODIFICO POR ULTIMA VEZ EL REGISTRO',
  `mod_fecha` datetime DEFAULT NULL COMMENT 'FECHA EN QUE SE MODIFICO POR ULTIMA VEZ EL REGISTRO',
  PRIMARY KEY (`idioma`),
  UNIQUE KEY `idioma_codigo` (`codigo`),
  KEY `idioma_add_use_i` (`add_user`),
  KEY `idioma_mod_use_i` (`mod_user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='TABLA QUE ADMINISTRA LOS DIFERENTES IDIOMAS QUE MANEJAR? EL ';

--
-- Volcado de datos para la tabla `idioma`
--

INSERT INTO `idioma` (`idioma`, `codigo`, `nombre`, `add_user`, `add_fecha`, `mod_user`, `mod_fecha`) VALUES
(1, 'esp', 'Espanol', 1, '2016-06-13 11:17:59', 1, '2022-05-25 04:24:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lang`
--

DROP TABLE IF EXISTS `lang`;
CREATE TABLE IF NOT EXISTS `lang` (
  `lang` varchar(50) NOT NULL COMMENT 'PK',
  `modulo` int(5) UNSIGNED NOT NULL COMMENT 'PK FK',
  `add_user` int(10) UNSIGNED NOT NULL COMMENT 'PERSONA QUE HIZO EL REGISTRO ',
  `add_fecha` datetime NOT NULL COMMENT 'FECHA QUE SE HIZO EL REGISTRO',
  `mod_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'PERSONA QUE MODIFICO POR ULTIMA VEZ',
  `mod_fecha` datetime DEFAULT NULL COMMENT 'FECHA QUE SE MODIFICO EL REGISTRO',
  PRIMARY KEY (`lang`,`modulo`),
  KEY `lang_mod_i` (`modulo`),
  KEY `lang_add_use_i` (`add_user`),
  KEY `lang_mod_use_i` (`mod_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='ADMINISTRA LOS LANGS QUE SE UTILIZARAN EN EL SITIO';

--
-- Volcado de datos para la tabla `lang`
--

INSERT INTO `lang` (`lang`, `modulo`, `add_user`, `add_fecha`, `mod_user`, `mod_fecha`) VALUES
('access_denied', 1, 1, '2016-06-13 11:18:00', NULL, NULL),
('account', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('account_cancel', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('account_error_contrasena_content', 1, 1, '2016-06-13 11:18:02', NULL, NULL),
('account_error_contrasena_title', 1, 1, '2016-06-13 11:18:02', NULL, NULL),
('account_nueva_contrasena_content', 1, 1, '2016-06-13 11:18:02', NULL, NULL),
('account_nueva_contrasena_title', 1, 1, '2016-06-13 11:18:02', NULL, NULL),
('account_save', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('campo_requerido_content', 1, 1, '2016-06-13 11:18:02', NULL, NULL),
('campo_requerido_title', 1, 1, '2016-06-13 11:18:02', NULL, NULL),
('config_elija_opcion', 1, 1, '2016-06-13 11:18:02', NULL, NULL),
('config_inicio', 1, 1, '2016-06-13 11:18:04', NULL, NULL),
('config_modulo', 1, 1, '2016-06-13 11:18:00', NULL, NULL),
('config_modulo_activo', 1, 1, '2016-06-13 11:18:00', NULL, NULL),
('config_modulo_busqueda', 1, 1, '2016-06-13 11:18:03', NULL, NULL),
('config_modulo_cancel', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('config_modulo_codigo', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('config_modulo_descripcion', 1, 1, '2016-06-13 11:18:02', NULL, NULL),
('config_modulo_enlace', 1, 1, '2016-06-13 11:18:02', NULL, NULL),
('config_modulo_new', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('config_modulo_nombre', 1, 1, '2016-06-13 11:18:00', NULL, NULL),
('config_modulo_orden', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('config_modulo_permisos', 1, 1, '2016-06-13 11:18:03', NULL, NULL),
('config_modulo_privado', 1, 1, '2016-06-13 11:18:00', NULL, NULL),
('config_modulo_publico', 1, 1, '2016-06-13 11:18:00', NULL, NULL),
('config_modulo_regresar', 1, 1, '2016-06-13 11:18:03', NULL, NULL),
('config_modulo_save', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('config_modulo_save_new', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('config_modulo_tipo_campo', 1, 1, '2016-06-13 11:18:02', NULL, NULL),
('config_modulo_usuarios', 1, 1, '2016-06-13 11:18:03', NULL, NULL),
('config_modulo_valor', 1, 1, '2016-06-13 11:18:02', NULL, NULL),
('config_nombre_menu', 1, 1, '2016-06-13 11:18:03', NULL, NULL),
('config_nombre_perfil', 1, 1, '2016-06-13 11:18:03', NULL, NULL),
('config_nomb_pantalla', 1, 1, '2016-06-13 11:18:03', NULL, NULL),
('core_editar', 1, 1, '2016-06-13 11:18:03', NULL, NULL),
('core_marque_aqui_para_desconectar', 1, 1, '2016-06-13 11:18:04', NULL, NULL),
('core_mes_1', 1, 1, '2016-08-16 15:44:16', NULL, NULL),
('core_mes_10', 1, 1, '2016-08-16 15:44:17', NULL, NULL),
('core_mes_11', 1, 1, '2016-08-16 15:44:17', NULL, NULL),
('core_mes_12', 1, 1, '2016-08-16 15:44:17', NULL, NULL),
('core_mes_2', 1, 1, '2016-08-16 15:44:17', NULL, NULL),
('core_mes_3', 1, 1, '2016-08-16 15:44:17', NULL, NULL),
('core_mes_4', 1, 1, '2016-08-16 15:44:17', NULL, NULL),
('core_mes_5', 1, 1, '2016-08-16 15:44:17', NULL, NULL),
('core_mes_6', 1, 1, '2016-08-16 15:44:17', NULL, NULL),
('core_mes_7', 1, 1, '2016-08-16 15:44:17', NULL, NULL),
('core_mes_8', 1, 1, '2016-08-16 15:44:17', NULL, NULL),
('core_mes_9', 1, 1, '2016-08-16 15:44:17', NULL, NULL),
('core_modulo_dependencia', 1, 1, '2016-06-13 11:18:03', NULL, NULL),
('core_tipo_admin', 1, 1, '2016-06-13 11:18:03', NULL, NULL),
('core_tipo_normal', 1, 1, '2016-06-13 11:18:03', NULL, NULL),
('invalid_password', 1, 1, '2016-06-13 11:18:00', NULL, NULL),
('language', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('login', 1, 1, '2016-06-13 11:18:00', NULL, NULL),
('login_ya_conectado', 1, 1, '2016-06-13 11:18:04', NULL, NULL),
('logout', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('mensaje_sesion_expirada', 1, 1, '2016-06-13 11:18:04', NULL, NULL),
('module_disabled', 1, 1, '2016-06-13 11:18:00', NULL, NULL),
('no', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('password', 1, 1, '2016-06-13 11:18:00', NULL, NULL),
('persona_activo', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('persona_apellido1', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('persona_apellido2', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('persona_apellido_casada', 1, 1, '2016-06-13 11:18:03', NULL, NULL),
('persona_aplica_usuario', 1, 1, '2016-06-13 11:18:03', NULL, NULL),
('persona_bloqueado', 1, 1, '2016-06-13 11:18:03', NULL, NULL),
('persona_cambio_contrasena', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('persona_contrasena_actual', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('persona_contrasena_nueva', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('persona_correo', 1, 1, '2016-06-13 11:18:03', NULL, NULL),
('persona_femenino', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('persona_generar_nueva_contrasena', 1, 1, '2016-06-13 11:18:03', NULL, NULL),
('persona_idioma', 1, 1, '2016-06-13 11:18:02', NULL, NULL),
('persona_masculino', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('persona_nombre1', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('persona_nombre2', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('persona_nombre_usual', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('persona_repetir_contrasena', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('persona_sexo', 1, 1, '2016-06-13 11:18:01', NULL, NULL),
('persona_tipo_usuario', 1, 1, '2016-06-13 11:18:03', NULL, NULL),
('remember', 1, 1, '2016-06-13 11:18:00', NULL, NULL),
('remember_no', 1, 1, '2016-06-13 11:18:00', NULL, NULL),
('remember_yes', 1, 1, '2016-06-13 11:18:00', NULL, NULL),
('session_expired', 1, 1, '2016-06-13 11:18:00', NULL, NULL),
('sidebar_access', 1, 1, '2016-06-13 11:18:00', NULL, NULL),
('sidebar_configuration', 1, 1, '2016-06-13 11:18:02', NULL, NULL),
('sidebar_langs', 1, 1, '2016-06-13 11:18:02', NULL, NULL),
('sidebar_module', 1, 1, '2016-06-13 11:18:00', NULL, NULL),
('sidebar_structure', 1, 1, '2016-06-13 11:18:02', NULL, NULL),
('sidebar_tipo_acceso', 1, 1, '2016-06-13 11:18:02', NULL, NULL),
('sidebar_variables_configuracion', 1, 1, '2016-06-13 11:18:02', NULL, NULL),
('site_name', 1, 1, '2016-06-13 11:18:00', NULL, NULL),
('title', 1, 1, '2016-06-13 11:18:00', NULL, NULL),
('user_name', 1, 1, '2016-06-13 11:18:00', NULL, NULL),
('usuarios_accesos_permisos', 2, 1, '2016-06-13 11:18:06', NULL, NULL),
('usuarios_aceptar', 2, 1, '2016-06-13 11:18:06', NULL, NULL),
('usuarios_activo', 2, 1, '2016-06-13 11:18:06', NULL, NULL),
('usuarios_administrador', 2, 1, '2016-06-13 11:18:06', NULL, NULL),
('usuarios_asesor', 2, 1, '2020-11-04 00:00:00', NULL, NULL),
('usuarios_buscar', 2, 1, '2016-06-13 11:18:05', NULL, NULL),
('usuarios_busqueda', 2, 1, '2016-06-13 11:18:06', NULL, NULL),
('usuarios_cambiar_contra', 2, 1, '2016-06-13 11:18:10', NULL, NULL),
('usuarios_cambiar_contrasenia', 2, 1, '2016-06-13 11:18:10', NULL, NULL),
('usuarios_cancelar', 2, 1, '2016-06-13 11:18:06', NULL, NULL),
('usuarios_cerrar', 2, 1, '2016-06-13 11:18:09', NULL, NULL),
('usuarios_confirme_contra', 2, 1, '2016-06-13 11:18:09', NULL, NULL),
('usuarios_contrasenia', 2, 1, '2016-06-13 11:18:07', NULL, NULL),
('usuarios_contrasenia_aleatoria', 2, 1, '2016-06-13 11:18:08', NULL, NULL),
('usuarios_contras_no_coinc', 2, 1, '2016-06-13 11:18:10', NULL, NULL),
('usuarios_correo_electronico', 2, 1, '2016-06-13 11:18:08', NULL, NULL),
('usuarios_correo_electronico_invalido', 2, 1, '2016-06-13 11:18:08', NULL, NULL),
('usuarios_correo_password_asunto', 2, 1, '2016-06-13 11:18:08', NULL, NULL),
('usuarios_correo_password_mensaje', 2, 1, '2016-06-13 11:18:09', NULL, NULL),
('usuarios_cuenta', 2, 1, '2016-06-13 11:18:07', NULL, NULL),
('usuarios_cuenta_activa', 2, 1, '2016-06-13 11:18:08', NULL, NULL),
('usuarios_descripcion', 2, 1, '2016-06-13 11:18:08', NULL, NULL),
('usuarios_editar', 2, 1, '2016-06-13 11:18:07', NULL, NULL),
('usuarios_eliminar', 2, 1, '2016-06-13 11:18:06', NULL, NULL),
('usuarios_eliminar_usuario', 2, 1, '2016-06-13 11:18:09', NULL, NULL),
('usuarios_esta_seguro_eliminar_usuario', 2, 1, '2016-06-13 11:18:06', NULL, NULL),
('usuarios_femenino', 2, 1, '2016-06-13 11:18:07', NULL, NULL),
('usuarios_generar_contrasenia_aleatoria', 2, 1, '2016-06-13 11:18:08', NULL, NULL),
('usuarios_genero', 2, 1, '2016-06-13 11:18:07', NULL, NULL),
('usuarios_guardar', 2, 1, '2016-06-13 11:18:07', NULL, NULL),
('usuarios_idioma_sistema', 2, 1, '2016-06-13 11:18:07', NULL, NULL),
('usuarios_informacion', 2, 1, '2016-06-13 11:18:06', NULL, NULL),
('usuarios_masculino', 2, 1, '2016-06-13 11:18:07', NULL, NULL),
('usuarios_mi_cuenta', 1, 1, '2016-06-13 11:18:04', NULL, NULL),
('usuarios_mi_cuenta', 2, 1, '2016-06-13 11:18:09', NULL, NULL),
('usuarios_msj_ad', 2, 1, '2016-06-13 11:18:09', NULL, NULL),
('usuarios_msj_alert_del', 2, 1, '2016-06-13 11:18:09', NULL, NULL),
('usuarios_msj_alert_ins', 2, 1, '2016-06-13 11:18:09', NULL, NULL),
('usuarios_msj_alert_upd', 2, 1, '2016-06-13 11:18:09', NULL, NULL),
('usuarios_new_perfil', 2, 1, '2016-06-13 11:18:09', NULL, NULL),
('usuarios_nombre', 2, 1, '2016-06-13 11:18:05', NULL, NULL),
('usuarios_nombre_completo', 2, 1, '2016-06-13 11:18:07', NULL, NULL),
('usuarios_normal', 2, 1, '2016-06-13 11:18:06', NULL, NULL),
('usuarios_no_hay_info', 2, 1, '2016-06-13 11:18:09', NULL, NULL),
('usuarios_nuevo', 2, 1, '2016-06-13 11:18:08', NULL, NULL),
('usuarios_pais', 2, 1, '2016-06-13 11:18:07', NULL, NULL),
('usuarios_perfil', 2, 1, '2016-06-13 11:18:08', NULL, NULL),
('usuarios_perfiles_acceso', 2, 1, '2016-06-13 11:18:08', NULL, NULL),
('usuarios_perfil_eliminar', 2, 1, '2016-06-13 11:18:09', NULL, NULL),
('usuarios_perfil_registro_msj_alert_del', 2, 1, '2016-06-13 11:18:10', NULL, NULL),
('usuarios_perfil_registro_msj_alert_ins', 2, 1, '2016-06-13 11:18:10', NULL, NULL),
('usuarios_perfil_registro_msj_alert_upd', 2, 1, '2016-06-13 11:18:10', NULL, NULL),
('usuarios_perfil_repetido', 2, 1, '2016-06-13 11:18:08', NULL, NULL),
('usuarios_refrescar', 2, 1, '2016-06-13 11:18:09', NULL, NULL),
('usuarios_repetido', 2, 1, '2016-06-13 11:18:09', NULL, NULL),
('usuarios_seleccione_opcion', 2, 1, '2016-06-13 11:18:07', NULL, NULL),
('usuarios_tipo_cuenta', 2, 1, '2016-06-13 11:18:05', NULL, NULL),
('usuarios_todos', 2, 1, '2016-06-13 11:18:10', NULL, NULL),
('usuarios_usuario', 2, 1, '2016-06-13 11:18:08', NULL, NULL),
('usuarios_usuario_en_uso', 2, 1, '2016-06-13 11:18:08', NULL, NULL),
('yes', 1, 1, '2016-06-13 11:18:01', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lang_idioma`
--

DROP TABLE IF EXISTS `lang_idioma`;
CREATE TABLE IF NOT EXISTS `lang_idioma` (
  `lang` varchar(50) NOT NULL COMMENT 'PK FK',
  `modulo` int(5) UNSIGNED NOT NULL COMMENT 'PK FK',
  `idioma` int(3) UNSIGNED NOT NULL COMMENT 'PK FK',
  `valor` text NOT NULL COMMENT 'VALOR DEL LANG QUE QUEREMOS MOSTRAR ',
  PRIMARY KEY (`lang`,`modulo`,`idioma`),
  KEY `lang_idioma_lan_i` (`lang`),
  KEY `lang_idioma_mod_i` (`modulo`),
  KEY `lang_idioma_idi_i` (`idioma`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='ADMINISTRA LOS LANGS POR CADA IDIOMA';

--
-- Volcado de datos para la tabla `lang_idioma`
--

INSERT INTO `lang_idioma` (`lang`, `modulo`, `idioma`, `valor`) VALUES
('access_denied', 1, 1, '<html><head><meta http-equiv=\"content-type\" content=\"text/html; charset=iso-8859-1;\" /><!--<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">--><meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"><title>Acceso denegado</title><link rel=\"shortcut icon\" href=\"templates/idc/images/icon.jpg\"/><link rel=\"shortcut icon\" href=\"templates/idc/images/icon.jpg\"/><link href=\"libraries/bootstrap/css/bootstrap.min.css\" rel=\"stylesheet\"><link href=\"libraries/bootstrap/css/plugins/metisMenu/metisMenu.min.css\" rel=\"stylesheet\"><link href=\"libraries/bootstrap/css/plugins/dataTables.bootstrap.css\" rel=\"stylesheet\"><link href=\"libraries/bootstrap/css/sb-admin-2.min.css\" rel=\"stylesheet\"><link href=\"libraries/bootstrap/font-awesome-4.1.0/css/font-awesome.min.css\" rel=\"stylesheet\" type=\"text/css\"><link href=\"libraries/jquery/ui/jquery.ui.min.css\" rel=\"stylesheet\"><link href=\"templates/idc/styles.min.css\" rel=\"stylesheet\"><link href=\"libraries/c3/c3.min.css\" rel=\"stylesheet\" type=\"text/css\"><script src=\"libraries/jquery/jquery.min.js\" type=\"text/javascript\"></script><script src=\"libraries/bootstrap/js/bootstrap.min.js\" type=\"text/javascript\"></script><script src=\"libraries/bootstrap/js/plugins/metisMenu/metisMenu.min.js\"></script><script src=\"libraries/bootstrap/js/plugins/dataTables/jquery.dataTables.js\"></script><script src=\"libraries/bootstrap/js/plugins/dataTables/dataTables.bootstrap.js\"></script><script src=\"libraries/bootstrap/js/sb-admin-2.min.js\"></script><script src=\"libraries/jquery/ui/jquery.ui.min.js\" type=\"text/javascript\"></script><script src=\"core/core.min.js\" type=\"text/javascript\"></script><script src=\"libraries/c3/d3.v3.min.js\" charset=\"utf-8\"></script><script src=\"libraries/c3/c3.js\"></script></head><body><style>body{background-color: #dfe0e6;margin-top: 0px;}</style><div class=\"row\" style=\"background-color: #ffffff\"><div class=\"row\"><div class=\"col-xs-12 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4\">&nbsp;</div></div><div class=\"row\"><div class=\"col-xs-12 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4\"><div style=\"margin-left: 25%; margin-right: 0%;\"><img src=\"templates/idc/images/logo_login.png\" style=\"width: 50%; height: 10%\" alt=\"Acceso denegado\" ></div></div></div><div class=\"row\"><div class=\"col-xs-12 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4\">&nbsp;</div></div></div><div class=\"container\" style=\"background-color: #dfe0e6\"><div class=\"row\"><div class=\"col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4\">&nbsp;</div></div><div class=\"row\"><div class=\"col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4\">&nbsp;</div></div><div class=\"row\"><div class=\"col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4\">&nbsp;</div></div><div class=\"row\"><div class=\"col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4\">&nbsp;</div></div><div class=\"row\"><div class=\"col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4\">&nbsp;</div></div><div class=\"row\"><div class=\"col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4\"><div class=\"alert alert-danger alert-dismissible\" role=\"alert\"><span class=\"glyphicon glyphicon-warning-sign btn-sm\"></span>Acceso denegado</div></div></div><div class=\"row\"><div class=\"col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4\">&nbsp;</div></div><div class=\"row\"><div class=\"col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4\">&nbsp;</div></div><div class=\"row\"><div class=\"col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4\">&nbsp;</div></div></div></body></html>'),
('account', 1, 1, 'Mi cuenta'),
('account_cancel', 1, 1, 'Cancelar'),
('account_error_contrasena_content', 1, 1, 'La contraseña ingresada no es la contraseña actual'),
('account_error_contrasena_title', 1, 1, 'Contraseña incorrecta'),
('account_nueva_contrasena_content', 1, 1, 'Las contraseñas no coinciden'),
('account_nueva_contrasena_title', 1, 1, 'Contraseñas no coinciden'),
('account_save', 1, 1, 'Guardar'),
('campo_requerido_content', 1, 1, 'Este campo es requerido'),
('campo_requerido_title', 1, 1, 'Campo requerido'),
('config_elija_opcion', 1, 1, '...Elija una opción...'),
('config_inicio', 1, 1, 'Inicio'),
('config_modulo', 1, 1, 'Módulo'),
('config_modulo_activo', 1, 1, 'Activo'),
('config_modulo_busqueda', 1, 1, 'Búsqueda'),
('config_modulo_cancel', 1, 1, 'Cancelar'),
('config_modulo_codigo', 1, 1, 'Código'),
('config_modulo_descripcion', 1, 1, 'Descripción'),
('config_modulo_enlace', 1, 1, 'Enlace'),
('config_modulo_new', 1, 1, 'Nuevo'),
('config_modulo_nombre', 1, 1, 'Nombre'),
('config_modulo_orden', 1, 1, 'Orden'),
('config_modulo_permisos', 1, 1, 'Permisos'),
('config_modulo_privado', 1, 1, 'Privado'),
('config_modulo_publico', 1, 1, 'Público'),
('config_modulo_regresar', 1, 1, 'Regresar'),
('config_modulo_save', 1, 1, 'Guardar'),
('config_modulo_save_new', 1, 1, 'Guardar y nuevo'),
('config_modulo_tipo_campo', 1, 1, 'Tipo de campo'),
('config_modulo_usuarios', 1, 1, 'Usuarios'),
('config_modulo_valor', 1, 1, 'Valor'),
('config_nombre_menu', 1, 1, 'Nombre menú'),
('config_nombre_perfil', 1, 1, 'Nombre del perfil'),
('config_nomb_pantalla', 1, 1, 'Nombre pantalla'),
('core_editar', 1, 1, 'Editar'),
('core_marque_aqui_para_desconectar', 1, 1, 'Este usuario ya a iniciado sesión en otro dispositivo, marque aquí­ para desconectar.'),
('core_mes_1', 1, 1, 'Enero'),
('core_mes_10', 1, 1, 'Octubre'),
('core_mes_11', 1, 1, 'Noviembre'),
('core_mes_12', 1, 1, 'Diciembre'),
('core_mes_2', 1, 1, 'Febrero'),
('core_mes_3', 1, 1, 'Marzo'),
('core_mes_4', 1, 1, 'Abril'),
('core_mes_5', 1, 1, 'Mayo'),
('core_mes_6', 1, 1, 'Junio'),
('core_mes_7', 1, 1, 'Julio'),
('core_mes_8', 1, 1, 'Agosto'),
('core_mes_9', 1, 1, 'Septiembre'),
('core_modulo_dependencia', 1, 1, 'Módulo dependencia'),
('core_tipo_admin', 1, 1, 'Administrador'),
('core_tipo_normal', 1, 1, 'Normal'),
('invalid_password', 1, 1, 'Usuario y/o password incorrecto'),
('language', 1, 1, 'Idiomas'),
('login', 1, 1, 'Iniciar'),
('login_ya_conectado', 1, 1, 'Usuario ya conectado'),
('logout', 1, 1, 'Salir'),
('mensaje_sesion_expirada', 1, 1, 'Sesión expirada'),
('module_disabled', 1, 1, 'Módulo no habilitado'),
('no', 1, 1, 'NO'),
('password', 1, 1, 'Contraseña'),
('persona_activo', 1, 1, 'Activo'),
('persona_apellido1', 1, 1, 'Primer apellido'),
('persona_apellido2', 1, 1, 'Otros apellidos'),
('persona_apellido_casada', 1, 1, 'Apellido casada'),
('persona_aplica_usuario', 1, 1, 'Aplica usuario'),
('persona_bloqueado', 1, 1, 'Bloqueado'),
('persona_cambio_contrasena', 1, 1, 'Cambiar contraseña'),
('persona_contrasena_actual', 1, 1, 'Contraseña actual'),
('persona_contrasena_nueva', 1, 1, 'Nueva contraseña'),
('persona_correo', 1, 1, 'Correo electrónico'),
('persona_femenino', 1, 1, 'Femenino'),
('persona_generar_nueva_contrasena', 1, 1, 'Generar nueva contraseña'),
('persona_idioma', 1, 1, 'Idioma'),
('persona_masculino', 1, 1, 'Masculino'),
('persona_nombre1', 1, 1, 'Primer nombre'),
('persona_nombre2', 1, 1, 'Otros nombres'),
('persona_nombre_usual', 1, 1, 'Nombre usual'),
('persona_repetir_contrasena', 1, 1, 'Repetir nueva contraseña'),
('persona_sexo', 1, 1, 'Sexo'),
('persona_tipo_usuario', 1, 1, 'Tipo de usuario'),
('remember', 1, 1, 'Recordarme'),
('remember_no', 1, 1, 'No'),
('remember_yes', 1, 1, 'Si'),
('session_expired', 1, 1, '<!DOCTYPE html><html lang=\"esp\">    <head>        <title>Sesión Expirada</title>        <link rel=\"shortcut icon\" href=\"templates/default/images/icon.jpg\"/>        <link href=\"libraries/bootstrap/dist/css/bootstrap.min.css\" rel=\"stylesheet\">    </head>    <body>        <div class=\"alert alert-error\"> Sesión Expirada</div>    </body></html>'),
('sidebar_access', 1, 1, 'Accesos'),
('sidebar_configuration', 1, 1, 'Configuración'),
('sidebar_langs', 1, 1, 'Etiquetas'),
('sidebar_module', 1, 1, 'Módulo'),
('sidebar_structure', 1, 1, 'Estructura'),
('sidebar_tipo_acceso', 1, 1, 'Tipo de acceso'),
('sidebar_variables_configuracion', 1, 1, 'Variables de configuración'),
('site_name', 1, 1, 'Homeland'),
('title', 1, 1, 'Página principal'),
('user_name', 1, 1, 'Usuario'),
('usuarios_accesos_permisos', 2, 1, 'Accesos y permisos'),
('usuarios_aceptar', 2, 1, 'Aceptar'),
('usuarios_activo', 2, 1, 'Activo'),
('usuarios_administrador', 2, 1, 'Administrador'),
('usuarios_asesor', 2, 1, 'Asesor'),
('usuarios_buscar', 2, 1, 'Buscar'),
('usuarios_busqueda', 2, 1, 'Búsqueda'),
('usuarios_cambiar_contra', 2, 1, 'Cambiar contraseña'),
('usuarios_cambiar_contrasenia', 2, 1, 'Por favor cambiar de contraseña para continuar'),
('usuarios_cancelar', 2, 1, 'Cancelar'),
('usuarios_cerrar', 2, 1, 'Cerrar'),
('usuarios_confirme_contra', 2, 1, 'Confirme contraseña'),
('usuarios_contrasenia', 2, 1, 'Contraseña'),
('usuarios_contrasenia_aleatoria', 2, 1, 'Contraseña aleatoria'),
('usuarios_contras_no_coinc', 2, 1, 'Las contraseñas no coinciden'),
('usuarios_correo_electronico', 2, 1, 'Correo electrónico'),
('usuarios_correo_electronico_invalido', 2, 1, 'Correo electrónico inválido'),
('usuarios_correo_password_asunto', 2, 1, 'Contraseña de acceso al sitio'),
('usuarios_correo_password_mensaje', 2, 1, 'Estimado usuario<br><br>Por este medio le informamos que su contraseña de acceso para el sitio [Sitio], ha sido generada automáticamente por nuestro sistema.<br><br>Usuario: [Usuario]<br>Contraseña: [Contraseña]<br><br>Le recomendamos que al ingresar al sitio, se dirija a la sección \"Mi cuenta\" para cambiar la contraseña por una de su preferencia.<br><br>Atentamente,<br>IDC'),
('usuarios_cuenta', 2, 1, 'Cuenta'),
('usuarios_cuenta_activa', 2, 1, 'Cuenta activa'),
('usuarios_descripcion', 2, 1, 'Descripción'),
('usuarios_editar', 2, 1, 'Editar'),
('usuarios_eliminar', 2, 1, 'Eliminar'),
('usuarios_eliminar_usuario', 2, 1, 'Eliminar usuario'),
('usuarios_esta_seguro_eliminar_usuario', 2, 1, '¿Está seguro de eliminar este usuario?'),
('usuarios_femenino', 2, 1, 'Femenino'),
('usuarios_generar_contrasenia_aleatoria', 2, 1, 'Generar contraseña aleatoria'),
('usuarios_genero', 2, 1, 'Género'),
('usuarios_guardar', 2, 1, 'Guardar'),
('usuarios_idioma_sistema', 2, 1, 'Idioma del sistema'),
('usuarios_informacion', 2, 1, 'Información'),
('usuarios_masculino', 2, 1, 'Masculino'),
('usuarios_mi_cuenta', 1, 1, 'Mi cuenta'),
('usuarios_mi_cuenta', 2, 1, 'Mi cuenta'),
('usuarios_msj_ad', 2, 1, '¿Está seguro de eliminar el perfil de acceso?'),
('usuarios_msj_alert_del', 2, 1, 'Usuario eliminado exitosamente'),
('usuarios_msj_alert_ins', 2, 1, 'Usuario guardado exitosamente'),
('usuarios_msj_alert_upd', 2, 1, 'Usuario actualizado exitosamente'),
('usuarios_new_perfil', 2, 1, 'Nuevo Perfil'),
('usuarios_nombre', 2, 1, 'Nombre'),
('usuarios_nombre_completo', 2, 1, 'Nombre completo'),
('usuarios_normal', 2, 1, 'Normal'),
('usuarios_no_hay_info', 2, 1, 'No hay información almacenada'),
('usuarios_nuevo', 2, 1, 'Nuevo'),
('usuarios_pais', 2, 1, 'Pa'),
('usuarios_perfil', 2, 1, 'Perfil'),
('usuarios_perfiles_acceso', 2, 1, 'Perfiles de acceso'),
('usuarios_perfil_eliminar', 2, 1, 'Eliminar'),
('usuarios_perfil_registro_msj_alert_del', 2, 1, 'Perfil eliminado exitosamente'),
('usuarios_perfil_registro_msj_alert_ins', 2, 1, 'Perfil guardado exitosamente'),
('usuarios_perfil_registro_msj_alert_upd', 2, 1, 'Perfil actualizado exitosamente'),
('usuarios_perfil_repetido', 2, 1, 'Perfil repetido'),
('usuarios_refrescar', 2, 1, 'Refrescar'),
('usuarios_repetido', 2, 1, 'Usuario Repetido'),
('usuarios_seleccione_opcion', 2, 1, 'Seleccione una opción...'),
('usuarios_tipo_cuenta', 2, 1, 'Tipo de cuenta'),
('usuarios_todos', 2, 1, 'Todos'),
('usuarios_usuario', 2, 1, 'Usuario'),
('usuarios_usuario_en_uso', 2, 1, 'Este usuario ya está en uso'),
('yes', 1, 1, 'SI');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lugar`
--

DROP TABLE IF EXISTS `lugar`;
CREATE TABLE IF NOT EXISTS `lugar` (
  `lugar` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK ID QUE IDENTIFICA EL REGISTRO EN LA TABLA',
  `nombre` varchar(75) COLLATE utf8_unicode_ci NOT NULL COMMENT 'NOMBRE DEL LUGAR',
  `prefijo` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activo` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL COMMENT 'CAMPO QUE INDICA SI EL LUGAR ESTA ACTIVO O NO',
  `add_user` int(10) UNSIGNED NOT NULL COMMENT 'FK ID DEL USUARIO QUE INSERTO LA TUPLA',
  `add_fecha` datetime NOT NULL COMMENT 'FECHA EN LA QUE SE INSERTO LA TUPLA',
  `mod_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK ID DEL USUARIO QUE MODIFICO LA TUPLA',
  `mod_fecha` datetime DEFAULT NULL COMMENT 'FECHA EN LA QUE SE MODIFICO LA TUPLA',
  PRIMARY KEY (`lugar`),
  KEY `lug_add_use_f` (`add_user`),
  KEY `lug_mod_use_f` (`mod_user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `lugar`
--

INSERT INTO `lugar` (`lugar`, `nombre`, `prefijo`, `activo`, `add_user`, `add_fecha`, `mod_user`, `mod_fecha`) VALUES
(1, 'GUATEMALA', 'GT', 'Y', 1, '2016-02-16 15:05:18', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mandatario`
--

DROP TABLE IF EXISTS `mandatario`;
CREATE TABLE IF NOT EXISTS `mandatario` (
  `mandatario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK ID QUE IDENTIFICA EL REGISTRO EN LA TABLA',
  `empresa` int(10) UNSIGNED NOT NULL COMMENT 'FK ID DE LA EMPRESA, EMPRESA DEL MANDATARIO',
  `nombre` varchar(75) NOT NULL COMMENT 'NOMBRE DEL MANDATARIO',
  `puesto` varchar(75) NOT NULL COMMENT 'PUESTO DEL MANDATARIO',
  `identificacion_numero` varchar(75) NOT NULL COMMENT 'NUMERO DE IDENTIFICACION DEL MANDATARIO',
  `fecha_nacimiento` date NOT NULL COMMENT 'FECHA DE NACIMIENTO DEL MANDATARIO',
  `genero` enum('M','F') DEFAULT NULL,
  `estado_civil` enum('SOLTERO','CASADO','VIUDO','DIVORCIADO') NOT NULL COMMENT 'ESTADO CIVIL DEL MANDATARIO',
  `nacionalidad` smallint(5) UNSIGNED NOT NULL COMMENT 'FK ID DEL PAIS, NACIONALIDAD DEL MANDATARIO',
  `profesion` varchar(75) NOT NULL COMMENT 'PROFESION DEL MANDATARIO',
  `direccion` varchar(255) NOT NULL COMMENT 'DIRECCION DEL MANDATARIO',
  `direccion_pais` smallint(5) UNSIGNED NOT NULL COMMENT 'FK ID DEL PAIS, DIRECCION PAIS DEL MANDATARIO',
  `direccion_departamento` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK ID DEL DEPARTAMENTO, DIRECCION DEPARTAMENTO DEL MANDATARIO',
  `escritura_publica_numero` varchar(75) NOT NULL COMMENT 'NUMERO DE ESCRITURA PUBLICA',
  `escritura_publica_fecha` date NOT NULL COMMENT 'FECHA DE ESCRITURA PUBLICA',
  `notario` varchar(75) NOT NULL COMMENT 'NOTARIO QUE AUTORIZO LA ESCRITURA PUBLICA',
  `registro_electronico_poderes_numero` varchar(75) NOT NULL COMMENT 'NUMERO DE REGISTRO ELECTRONICO DE PODERES',
  `registro_electronico_poderes_fecha` date NOT NULL COMMENT 'FECHA DE REGISTRO ELECTRONICO DE PODERES',
  `registro_mercantil_mandatos_numero` varchar(75) NOT NULL COMMENT 'NUMERO DE REGISTRO MERCANTIL DE MANDATOS',
  `registro_mercantil_mandatos_folio` varchar(75) NOT NULL COMMENT 'FOLIO DE REGISTRO MERCANTIL DE MANDATOS',
  `registro_mercantil_mandatos_libro` varchar(75) NOT NULL COMMENT 'LIBRO DE REGISTRO MERCANTIL DE MANDATOS',
  `por_defecto` enum('Y','N') NOT NULL COMMENT 'INDICA SI ES POR DEFECTO',
  `activo` enum('Y','N') NOT NULL COMMENT 'INDICA SI ESTA ACTIVO',
  `add_user` int(10) UNSIGNED NOT NULL COMMENT 'FK ID DEL USUARIO QUE INSERTO LA TUPLA',
  `add_fecha` datetime NOT NULL COMMENT 'FECHA EN LA QUE SE INSERTO LA TUPLA',
  `mod_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK ID DEL USUARIO QUE MODIFICO LA TUPLA',
  `mod_fecha` datetime DEFAULT NULL COMMENT 'FECHA EN LA QUE SE MODIFICO LA TUPLA',
  PRIMARY KEY (`mandatario`),
  KEY `man_empresa_f` (`empresa`),
  KEY `man_nacionalidad_f` (`nacionalidad`),
  KEY `man_direccion_pais_f` (`direccion_pais`),
  KEY `man_direccion_departamento_f` (`direccion_departamento`),
  KEY `man_add_use_f` (`add_user`),
  KEY `man_mod_use_f` (`mod_user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `mandatario`
--

INSERT INTO `mandatario` (`mandatario`, `empresa`, `nombre`, `puesto`, `identificacion_numero`, `fecha_nacimiento`, `genero`, `estado_civil`, `nacionalidad`, `profesion`, `direccion`, `direccion_pais`, `direccion_departamento`, `escritura_publica_numero`, `escritura_publica_fecha`, `notario`, `registro_electronico_poderes_numero`, `registro_electronico_poderes_fecha`, `registro_mercantil_mandatos_numero`, `registro_mercantil_mandatos_folio`, `registro_mercantil_mandatos_libro`, `por_defecto`, `activo`, `add_user`, `add_fecha`, `mod_user`, `mod_fecha`) VALUES
(1, 1, 'ANA LUISA MARTINEZ-MONT MOLINA', 'MANDATARIO CON REPRESENTACIÃ?N', '2511 83289 0101', '1969-04-09', 'F', 'SOLTERO', 1, 'ABOGADA  Y NOTARIA', '13 CALLE 2-60 ZONA 10 EDIFICIO TOPACIO AZUL NIVEL 13 OF 1301', 1, 9, '35', '2014-08-28', 'LA NOTARIA ZULLY JADIRA FUENTES IZQUIERDO', '315882-E', '2014-09-02', '658843', '945', '74', 'N', 'Y', 1, '2016-08-16 16:10:48', 1, '2020-11-23 11:09:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo`
--

DROP TABLE IF EXISTS `modulo`;
CREATE TABLE IF NOT EXISTS `modulo` (
  `modulo` int(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `codigo` varchar(15) NOT NULL COMMENT 'INDICA EL CODIGO PARA IDENTIFICAR EL MODULO DENTRO DEL CODIGO',
  `orden` int(5) NOT NULL COMMENT 'ORDEN EN EL QUE APARECEN EN EL SISTEMA',
  `publico` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'INDICA SI EL MODULO SE PRESENTA EN LA PARTE P?BLICA',
  `privado` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'INDICA SI EL MODULO SE PRESENTA CUANDO EST? LOGINEADO',
  `activo` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'INDICA SI EL MODULO EST? ACTIVO',
  `icono` varchar(100) DEFAULT NULL COMMENT 'ALMACENA LA CLASE EN CSS QUE TIENE LA IMAGEN QUE REPRESENTA AL MODULO',
  `add_user` int(10) UNSIGNED NOT NULL COMMENT 'USUARIO QUE REGISTRO EL MODULO',
  `add_fecha` datetime NOT NULL COMMENT 'INDICA LA FECHA EN QUE SE INGRESO EL M?DULO',
  `mod_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'USUARIO QUE HIZO LA ULTIMA MODIFICACION EN EL REGISTRO',
  `mod_fecha` datetime DEFAULT NULL COMMENT 'FECHA EN QUE SE HIZO LA ULTIMA MODIFICACION',
  PRIMARY KEY (`modulo`),
  UNIQUE KEY `modulo_cod_u` (`codigo`),
  KEY `modulo_act_i` (`activo`),
  KEY `modulo_pub_i` (`publico`,`privado`),
  KEY `modulo_add_use_i` (`add_user`),
  KEY `modulo_mod_use_i` (`mod_user`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COMMENT='TABLA QUE ADMINISTRA LOS M?DULOS DEL SISTEMA';

--
-- Volcado de datos para la tabla `modulo`
--

INSERT INTO `modulo` (`modulo`, `codigo`, `orden`, `publico`, `privado`, `activo`, `icono`, `add_user`, `add_fecha`, `mod_user`, `mod_fecha`) VALUES
(1, 'core', 1, 'N', 'Y', 'Y', 'icon-idc-code2', 1, '2016-06-13 11:17:59', NULL, NULL),
(2, 'usuarios', 2, 'N', 'Y', 'Y', 'icon-idc-black302', 1, '2016-06-13 11:18:05', NULL, NULL),
(3, 'clientes', 4, 'N', 'Y', 'Y', 'icon-idc-group41', 1, '2016-06-13 11:18:11', NULL, NULL),
(4, 'reportes', 5, 'N', 'Y', 'Y', 'icon-idc-ascendant-bars-graphic', 1, '2016-06-13 11:19:44', NULL, NULL),
(5, 'configuracion', 3, 'N', 'Y', 'Y', 'fa fa-gears', 1, '2016-08-16 15:43:29', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo_dependencia`
--

DROP TABLE IF EXISTS `modulo_dependencia`;
CREATE TABLE IF NOT EXISTS `modulo_dependencia` (
  `modulo` int(5) UNSIGNED NOT NULL COMMENT 'PK FK',
  `dependencia` int(5) UNSIGNED NOT NULL COMMENT 'PK FK',
  PRIMARY KEY (`modulo`,`dependencia`),
  KEY `modulo_dependencia_mod_i` (`modulo`),
  KEY `modulo_dependencia_dep_i` (`dependencia`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLA QUE ADMINISTRA LAS DEPENDENCIAS DE LOS MODULOS';

--
-- Volcado de datos para la tabla `modulo_dependencia`
--

INSERT INTO `modulo_dependencia` (`modulo`, `dependencia`) VALUES
(4, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo_idioma`
--

DROP TABLE IF EXISTS `modulo_idioma`;
CREATE TABLE IF NOT EXISTS `modulo_idioma` (
  `modulo` int(5) UNSIGNED NOT NULL COMMENT 'PK FK',
  `idioma` int(3) UNSIGNED NOT NULL COMMENT 'PK FK',
  `nombre` varchar(40) NOT NULL COMMENT 'NOMBRE DEL MODULO EN EL IDIOMA ESTABLECIDO',
  PRIMARY KEY (`modulo`,`idioma`),
  KEY `modulo_idioma_mod_i` (`modulo`),
  KEY `modulo_idioma_idi_i` (`idioma`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='ALMACENA EL NOMBRE DEL MODULO EN EL IDIOMA AL QUE HACE REFER';

--
-- Volcado de datos para la tabla `modulo_idioma`
--

INSERT INTO `modulo_idioma` (`modulo`, `idioma`, `nombre`) VALUES
(1, 1, 'Sistema'),
(2, 1, 'Usuarios'),
(3, 1, 'Clientes'),
(4, 1, 'Reportes'),
(5, 1, 'Configuracion');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `moneda`
--

DROP TABLE IF EXISTS `moneda`;
CREATE TABLE IF NOT EXISTS `moneda` (
  `moneda` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK ID QUE IDENTIFICA EL REGISTRO EN LA TABLA',
  `nombre` varchar(75) COLLATE utf8_unicode_ci NOT NULL COMMENT 'INDICA EL NOMBRE DE LA MONEDA',
  `nombre_feic` varchar(75) COLLATE utf8_unicode_ci DEFAULT NULL,
  `simbolo` varchar(5) COLLATE utf8_unicode_ci NOT NULL COMMENT 'INDICA EL SIMBOLO DE LA MONEDA',
  `simbolo_feic` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activo` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL COMMENT 'INDICA SI LA MONEDA ESTA ACTIVA O NO',
  `no_cuenta_terceros` varchar(75) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'N?MERO DE CUENTA TERCEROS',
  `add_user` int(10) UNSIGNED NOT NULL COMMENT 'FK ID DEL USUARIO QUE INSERTO LA TUPLA',
  `add_fecha` datetime NOT NULL COMMENT 'FECHA EN LA QUE SE INSERTO LA TUPLA',
  `mod_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK ID DEL USUARIO QUE MODIFICO LA TUPLA',
  `mod_fecha` datetime DEFAULT NULL COMMENT 'FECHA EN LA QUE SE MODIFICO LA TUPLA',
  PRIMARY KEY (`moneda`),
  KEY `mon_add_use_f` (`add_user`),
  KEY `mon_mod_use_f` (`mod_user`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `moneda`
--

INSERT INTO `moneda` (`moneda`, `nombre`, `nombre_feic`, `simbolo`, `simbolo_feic`, `activo`, `no_cuenta_terceros`, `add_user`, `add_fecha`, `mod_user`, `mod_fecha`) VALUES
(1, 'QUETZALES', 'QUETZAL', 'Q', 'GTQ', 'Y', 'Q-66-0020127-7', 1, '2016-02-16 15:02:46', NULL, NULL),
(2, 'DOLARES', 'DOLAR ESTADOUNIDENSE', '$', 'USD', 'Y', '$-66-5806550-0', 1, '2016-02-16 15:04:41', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipio`
--

DROP TABLE IF EXISTS `municipio`;
CREATE TABLE IF NOT EXISTS `municipio` (
  `municipio` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK ID QUE IDENTIFICA EL REGISTRO EN LA TABLA',
  `departamento` int(10) UNSIGNED NOT NULL COMMENT 'FK ID DEL DEPARTAMENTO',
  `nombre` varchar(75) COLLATE utf8_unicode_ci NOT NULL COMMENT 'NOMBRE DEL MUNICIPIO',
  `activo` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL COMMENT 'CAMPO QUE INDICA SI EL MUNICIPIO ESTA ACTIVO O NO',
  `codigo_municipio` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `add_user` int(10) UNSIGNED NOT NULL COMMENT 'FK ID DEL USUARIO QUE INSERTO LA TUPLA',
  `add_fecha` datetime NOT NULL COMMENT 'FECHA EN LA QUE SE INSERTO LA TUPLA',
  `mod_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK ID DEL USUARIO QUE MODIFICO LA TUPLA',
  `mod_fecha` datetime DEFAULT NULL COMMENT 'FECHA EN LA QUE SE MODIFICO LA TUPLA',
  PRIMARY KEY (`municipio`),
  KEY `mun_dep_f` (`departamento`),
  KEY `mun_add_use_f` (`add_user`),
  KEY `mun_mod_use_f` (`mod_user`)
) ENGINE=InnoDB AUTO_INCREMENT=335 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `municipio`
--

INSERT INTO `municipio` (`municipio`, `departamento`, `nombre`, `activo`, `codigo_municipio`, `add_user`, `add_fecha`, `mod_user`, `mod_fecha`) VALUES
(1, 1, 'Cobán', 'Y', '1601', 1, '2016-03-11 16:11:37', NULL, NULL),
(2, 1, 'San Pedro Carchá', 'Y', '1609', 1, '2016-03-11 16:11:37', NULL, NULL),
(3, 1, 'San Juan Chamelco', 'Y', '1610', 1, '2016-03-11 16:11:37', NULL, NULL),
(4, 1, 'San Cristóbal Verapaz', 'Y', '1603', 1, '2016-03-11 16:11:37', NULL, NULL),
(5, 1, 'Tactic', 'Y', '1604', 1, '2016-03-11 16:11:37', NULL, NULL),
(6, 1, 'Tucurú', 'Y', '1606', 1, '2016-03-11 16:11:37', NULL, NULL),
(7, 1, 'Tamahú', 'Y', '1605', 1, '2016-03-11 16:11:37', NULL, NULL),
(8, 1, 'Panzós', 'Y', '1607', 1, '2016-03-11 16:11:37', NULL, NULL),
(9, 1, 'Senahú', 'Y', '1608', 1, '2016-03-11 16:11:37', NULL, NULL),
(10, 1, 'Cahabón', 'Y', '1612', 1, '2016-03-11 16:11:37', NULL, NULL),
(11, 1, 'Lanquín', 'Y', '1611', 1, '2016-03-11 16:11:37', NULL, NULL),
(12, 1, 'Chahal', 'Y', '1614', 1, '2016-03-11 16:11:37', NULL, NULL),
(13, 1, 'Fray Bartolomé de las Casas', 'Y', '1615', 1, '2016-03-11 16:11:37', NULL, NULL),
(14, 1, 'Chisec', 'Y', '1613', 1, '2016-03-11 16:11:37', NULL, NULL),
(15, 1, 'Santa Cruz Verapaz', 'Y', '1602', 1, '2016-03-11 16:11:37', NULL, NULL),
(16, 1, 'Santa Catalina La Tinta', 'Y', '1616', 1, '2016-03-11 16:11:37', NULL, NULL),
(17, 1, 'Raxruhá', 'Y', '1617', 1, '2016-03-11 16:11:37', NULL, NULL),
(18, 2, 'Salamá', 'Y', '1501', 1, '2016-03-11 16:11:37', NULL, NULL),
(19, 2, 'Cubulco', 'Y', '1504', 1, '2016-03-11 16:11:37', NULL, NULL),
(20, 2, 'El Chol', 'Y', '1506', 1, '2016-03-11 16:11:37', NULL, NULL),
(21, 2, 'Granados', 'Y', '1505', 1, '2016-03-11 16:11:37', NULL, NULL),
(22, 2, 'Purulha', 'Y', '1508', 1, '2016-03-11 16:11:37', NULL, NULL),
(23, 2, 'Rabinal', 'Y', '1503', 1, '2016-03-11 16:11:37', NULL, NULL),
(24, 2, 'San Gerónimo', 'Y', '1507', 1, '2016-03-11 16:11:37', NULL, NULL),
(25, 2, 'San Miguel Chicaj', 'Y', '1502', 1, '2016-03-11 16:11:37', NULL, NULL),
(26, 3, 'Chimaltenango', 'Y', '0401', 1, '2016-03-11 16:11:37', NULL, NULL),
(27, 3, 'Acatenango', 'Y', '0411', 1, '2016-03-11 16:11:37', NULL, NULL),
(28, 3, 'Comalapa', 'Y', '0404', 1, '2016-03-11 16:11:37', NULL, NULL),
(29, 3, 'El Tejar', 'Y', '0416', 1, '2016-03-11 16:11:37', NULL, NULL),
(30, 3, 'Párramos', 'Y', '0414', 1, '2016-03-11 16:11:37', NULL, NULL),
(31, 3, 'Patzicía', 'Y', '0409', 1, '2016-03-11 16:11:37', NULL, NULL),
(32, 3, 'Patzún', 'Y', '0407', 1, '2016-03-11 16:11:37', NULL, NULL),
(33, 3, 'Pochuta', 'Y', '0408', 1, '2016-03-11 16:11:37', NULL, NULL),
(34, 3, 'San Andrés Iztapa', 'Y', '0413', 1, '2016-03-11 16:11:37', NULL, NULL),
(35, 3, 'San José Poaquil', 'Y', '0402', 1, '2016-03-11 16:11:37', NULL, NULL),
(36, 3, 'San Martín Jilotepeque', 'Y', '0403', 1, '2016-03-11 16:11:37', NULL, NULL),
(37, 3, 'Santa Apolonia', 'Y', '0405', 1, '2016-03-11 16:11:37', NULL, NULL),
(38, 3, 'Santa Cruz Balanyá', 'Y', '0410', 1, '2016-03-11 16:11:37', NULL, NULL),
(39, 3, 'Tecpán', 'Y', '0406', 1, '2016-03-11 16:11:37', NULL, NULL),
(40, 3, 'Yepocapa', 'Y', '0412', 1, '2016-03-11 16:11:37', NULL, NULL),
(41, 3, 'Zaragoza', 'Y', '0415', 1, '2016-03-11 16:11:37', NULL, NULL),
(42, 4, 'Chiquimula', 'Y', '2001', 1, '2016-03-11 16:11:37', NULL, NULL),
(43, 4, 'Camotán', 'Y', '2005', 1, '2016-03-11 16:11:37', NULL, NULL),
(44, 4, 'Concepción Las Minas', 'Y', '2008', 1, '2016-03-11 16:11:37', NULL, NULL),
(45, 4, 'Esquipulas', 'Y', '2007', 1, '2016-03-11 16:11:37', NULL, NULL),
(46, 4, 'Ipala', 'Y', '2011', 1, '2016-03-11 16:11:37', NULL, NULL),
(47, 4, 'Jocotán', 'Y', '2004', 1, '2016-03-11 16:11:37', NULL, NULL),
(48, 4, 'Olopa', 'Y', '2006', 1, '2016-03-11 16:11:37', NULL, NULL),
(49, 4, 'Quetzaltepeque', 'Y', '2009', 1, '2016-03-11 16:11:37', NULL, NULL),
(50, 4, 'San José La Arada', 'Y', '2002', 1, '2016-03-11 16:11:37', NULL, NULL),
(51, 4, 'San Juan Ermita', 'Y', '2003', 1, '2016-03-11 16:11:37', NULL, NULL),
(52, 4, 'San Jacinto', 'Y', '2010', 1, '2016-03-11 16:11:37', NULL, NULL),
(53, 5, 'Flores', 'Y', '1701', 1, '2016-03-11 16:11:37', NULL, NULL),
(54, 5, 'Dolores', 'Y', '1708', 1, '2016-03-11 16:11:37', NULL, NULL),
(55, 5, 'La Libertad', 'Y', '1705', 1, '2016-03-11 16:11:37', NULL, NULL),
(56, 5, 'Melchor de Mencos', 'Y', '1711', 1, '2016-03-11 16:11:37', NULL, NULL),
(57, 5, 'Poptún', 'Y', '1712', 1, '2016-03-11 16:11:37', NULL, NULL),
(58, 5, 'San Andrés', 'Y', '1704', 1, '2016-03-11 16:11:37', NULL, NULL),
(59, 5, 'San Benito', 'Y', '1703', 1, '2016-03-11 16:11:37', NULL, NULL),
(60, 5, 'San Francisco', 'Y', '1706', 1, '2016-03-11 16:11:37', NULL, NULL),
(61, 5, 'San José', 'Y', '1702', 1, '2016-03-11 16:11:37', NULL, NULL),
(62, 5, 'San Luis', 'Y', '1709', 1, '2016-03-11 16:11:37', NULL, NULL),
(63, 5, 'Santa Ana', 'Y', '1707', 1, '2016-03-11 16:11:37', NULL, NULL),
(64, 5, 'Sayaxché', 'Y', '1710', 1, '2016-03-11 16:11:37', NULL, NULL),
(65, 5, 'Raxruhá', 'Y', '1617', 1, '2016-03-11 16:11:37', NULL, NULL),
(66, 5, 'Las Cruces', 'Y', '1713', 1, '2016-03-11 16:11:37', NULL, NULL),
(67, 6, 'Guastatoya', 'Y', '0201', 1, '2016-03-11 16:11:37', NULL, NULL),
(68, 6, 'Morazán', 'Y', '0202', 1, '2016-03-11 16:11:37', NULL, NULL),
(69, 6, 'San Agustín Acasaguastlán', 'Y', '0203', 1, '2016-03-11 16:11:37', NULL, NULL),
(70, 6, 'El Jícaro', 'Y', '0205', 1, '2016-03-11 16:11:37', NULL, NULL),
(71, 6, 'San Cristobal', 'Y', '0204', 1, '2016-03-11 16:11:37', NULL, NULL),
(72, 6, 'Sansare', 'Y', '0206', 1, '2016-03-11 16:11:37', NULL, NULL),
(73, 6, 'Sanarate', 'Y', '0207', 1, '2016-03-11 16:11:37', NULL, NULL),
(74, 6, 'San Antonio La Paz', 'Y', '0208', 1, '2016-03-11 16:11:37', NULL, NULL),
(75, 7, 'Santa Cruz del Quiché', 'Y', '1401', 1, '2016-03-11 16:11:37', NULL, NULL),
(76, 7, 'Chiché', 'Y', '1402', 1, '2016-03-11 16:11:37', NULL, NULL),
(77, 7, 'Chinique', 'Y', '1403', 1, '2016-03-11 16:11:37', NULL, NULL),
(78, 7, 'Zacualpa', 'Y', '1404', 1, '2016-03-11 16:11:37', NULL, NULL),
(79, 7, 'Chajul', 'Y', '1405', 1, '2016-03-11 16:11:37', NULL, NULL),
(80, 7, 'Chichicastenango', 'Y', '1406', 1, '2016-03-11 16:11:37', NULL, NULL),
(81, 7, 'Patzité', 'Y', '1407', 1, '2016-03-11 16:11:37', NULL, NULL),
(82, 7, 'San Antonio Ilotenango', 'Y', '1408', 1, '2016-03-11 16:11:37', NULL, NULL),
(83, 7, 'San Pedro Jocopilas', 'Y', '1409', 1, '2016-03-11 16:11:37', NULL, NULL),
(84, 7, 'Cunén', 'Y', '1410', 1, '2016-03-11 16:11:37', NULL, NULL),
(85, 7, 'San Juan Cotzal', 'Y', '1411', 1, '2016-03-11 16:11:37', NULL, NULL),
(86, 7, 'Joyabaj', 'Y', '1412', 1, '2016-03-11 16:11:37', NULL, NULL),
(87, 7, 'Nebaj', 'Y', '1413', 1, '2016-03-11 16:11:37', NULL, NULL),
(88, 7, 'San Andrés Sajcabajá', 'Y', '1414', 1, '2016-03-11 16:11:37', NULL, NULL),
(89, 7, 'Uspantán', 'Y', '1415', 1, '2016-03-11 16:11:37', NULL, NULL),
(90, 7, 'Sacapulas', 'Y', '1416', 1, '2016-03-11 16:11:37', NULL, NULL),
(91, 7, 'San Bartolomé Jocotenango', 'Y', '1417', 1, '2016-03-11 16:11:37', NULL, NULL),
(92, 7, 'Canillá', 'Y', '1418', 1, '2016-03-11 16:11:37', NULL, NULL),
(93, 7, 'Chicamán', 'Y', '1419', 1, '2016-03-11 16:11:37', NULL, NULL),
(94, 7, 'Ixcán', 'Y', '1420', 1, '2016-03-11 16:11:37', NULL, NULL),
(95, 7, 'Pachalum', 'Y', '1421', 1, '2016-03-11 16:11:37', NULL, NULL),
(96, 8, 'Escuintla', 'Y', '0501', 1, '2016-03-11 16:11:37', NULL, NULL),
(97, 8, 'Santa Lucía Cotzumalguapa', 'Y', '0502', 1, '2016-03-11 16:11:37', NULL, NULL),
(98, 8, 'La Democracia', 'Y', '1312', 1, '2016-03-11 16:11:37', NULL, NULL),
(99, 8, 'Siquinalá', 'Y', '0504', 1, '2016-03-11 16:11:37', NULL, NULL),
(100, 8, 'Masagua', 'Y', '0505', 1, '2016-03-11 16:11:37', NULL, NULL),
(101, 8, 'Tiquisate', 'Y', '0506', 1, '2016-03-11 16:11:37', NULL, NULL),
(102, 8, 'La Gomera', 'Y', '0507', 1, '2016-03-11 16:11:37', NULL, NULL),
(103, 8, 'Guanagazapa', 'Y', '0508', 1, '2016-03-11 16:11:37', NULL, NULL),
(104, 8, 'San José', 'Y', '1702', 1, '2016-03-11 16:11:37', NULL, NULL),
(105, 8, 'Iztapa', 'Y', '0510', 1, '2016-03-11 16:11:37', NULL, NULL),
(106, 8, 'Palín', 'Y', '0511', 1, '2016-03-11 16:11:37', NULL, NULL),
(107, 8, 'San Vicente Pacaya ', 'Y', '0512', 1, '2016-03-11 16:11:37', NULL, NULL),
(108, 9, 'Guatemala', 'Y', '0101', 1, '2016-03-11 16:11:37', NULL, NULL),
(109, 9, 'Amatitlán', 'Y', '0114', 1, '2016-03-11 16:11:37', NULL, NULL),
(110, 9, 'San José Pinula', 'Y', '0103', 1, '2016-03-11 16:11:37', NULL, NULL),
(111, 9, 'San Pedro Sacatepéquez', 'Y', '1202', 1, '2016-03-11 16:11:37', NULL, NULL),
(112, 9, 'Villa Nueva', 'Y', '0115', 1, '2016-03-11 16:11:37', NULL, NULL),
(113, 9, 'Chinautla', 'Y', '0106', 1, '2016-03-11 16:11:37', NULL, NULL),
(114, 9, 'Mixco', 'Y', '0108', 1, '2016-03-11 16:11:37', NULL, NULL),
(115, 9, 'San Juan Sacatepéquez', 'Y', '0110', 1, '2016-03-11 16:11:37', NULL, NULL),
(116, 9, 'San Raymundo', 'Y', '0111', 1, '2016-03-11 16:11:37', NULL, NULL),
(117, 9, 'Chuarrancho', 'Y', '0112', 1, '2016-03-11 16:11:37', NULL, NULL),
(118, 9, 'Palencia', 'Y', '0105', 1, '2016-03-11 16:11:37', NULL, NULL),
(119, 9, 'San Miguel Petapa', 'Y', '0117', 1, '2016-03-11 16:11:37', NULL, NULL),
(120, 9, 'Santa Catarina Pinula', 'Y', '0102', 1, '2016-03-11 16:11:37', NULL, NULL),
(121, 9, 'Fraijanes', 'Y', '0113', 1, '2016-03-11 16:11:37', NULL, NULL),
(122, 9, 'San José del Golfo', 'Y', '0104', 1, '2016-03-11 16:11:37', NULL, NULL),
(123, 9, 'San Pedro Ayampuc', 'Y', '0107', 1, '2016-03-11 16:11:37', NULL, NULL),
(124, 9, 'Villa Canales', 'Y', '0116', 1, '2016-03-11 16:11:37', NULL, NULL),
(125, 10, 'Aguacatán', 'Y', '1327', 1, '2016-03-11 16:11:37', NULL, NULL),
(126, 10, 'Chiantla', 'Y', '1302', 1, '2016-03-11 16:11:37', NULL, NULL),
(127, 10, 'Colotenango', 'Y', '1319', 1, '2016-03-11 16:11:37', NULL, NULL),
(128, 10, 'Concepción Huista', 'Y', '1322', 1, '2016-03-11 16:11:37', NULL, NULL),
(129, 10, 'Cuilco', 'Y', '1304', 1, '2016-03-11 16:11:37', NULL, NULL),
(130, 10, 'Huehuetenango', 'Y', '1301', 1, '2016-03-11 16:11:37', NULL, NULL),
(131, 10, 'Jacaltenango', 'Y', '1307', 1, '2016-03-11 16:11:37', NULL, NULL),
(132, 10, 'La Democracia', 'Y', '1312', 1, '2016-03-11 16:11:37', NULL, NULL),
(133, 10, 'La Libertad', 'Y', '1705', 1, '2016-03-11 16:11:37', NULL, NULL),
(134, 10, 'Malacatancito', 'Y', '1303', 1, '2016-03-11 16:11:37', NULL, NULL),
(135, 10, 'Nentón', 'Y', '1305', 1, '2016-03-11 16:11:37', NULL, NULL),
(136, 10, 'San Antonio Huista', 'Y', '1324', 1, '2016-03-11 16:11:37', NULL, NULL),
(137, 10, 'San Gaspar Ixchil', 'Y', '1329', 1, '2016-03-11 16:11:37', NULL, NULL),
(138, 10, 'San Ildefonso Ixtahuacán', 'Y', '1309', 1, '2016-03-11 16:11:37', NULL, NULL),
(139, 10, 'San Juan Atitán', 'Y', '1316', 1, '2016-03-11 16:11:37', NULL, NULL),
(140, 10, 'San Juan Ixcoy', 'Y', '1323', 1, '2016-03-11 16:11:37', NULL, NULL),
(141, 10, 'San Mateo Ixtatán', 'Y', '1318', 1, '2016-03-11 16:11:37', NULL, NULL),
(142, 10, 'San Miguel Acatán', 'Y', '1313', 1, '2016-03-11 16:11:37', NULL, NULL),
(143, 10, 'San Pedro Necta', 'Y', '1306', 1, '2016-03-11 16:11:37', NULL, NULL),
(144, 10, 'San Pedro Soloma', 'Y', '1308', 1, '2016-03-11 16:11:37', NULL, NULL),
(145, 10, 'San Rafael La Independencia', 'Y', '1314', 1, '2016-03-11 16:11:37', NULL, NULL),
(146, 10, 'San Rafael Petzal', 'Y', '1328', 1, '2016-03-11 16:11:37', NULL, NULL),
(147, 10, 'San Sebastián Coatán', 'Y', '1325', 1, '2016-03-11 16:11:37', NULL, NULL),
(148, 10, 'San Sebastián', 'Y', '1102', 1, '2016-03-11 16:11:37', NULL, NULL),
(149, 10, 'Santa Ana Huista', 'Y', '1331', 1, '2016-03-11 16:11:37', NULL, NULL),
(150, 10, 'Santa Bárbara', 'Y', '1310', 1, '2016-03-11 16:11:37', NULL, NULL),
(151, 10, 'Santa Cruz Barillas', 'Y', '1326', 1, '2016-03-11 16:11:37', NULL, NULL),
(152, 10, 'Santa Eulalia', 'Y', '1317', 1, '2016-03-11 16:11:37', NULL, NULL),
(153, 10, 'Santiago Chimaltenango', 'Y', '1330', 1, '2016-03-11 16:11:37', NULL, NULL),
(154, 10, 'Tectitán', 'Y', '1321', 1, '2016-03-11 16:11:37', NULL, NULL),
(155, 10, 'Todos Santos Cuchumatánes', 'Y', '1315', 1, '2016-03-11 16:11:37', NULL, NULL),
(156, 10, 'Unión Cantinil', 'Y', '1332', 1, '2016-03-11 16:11:37', NULL, NULL),
(157, 11, 'El Estor', 'Y', '1803', 1, '2016-03-11 16:11:37', NULL, NULL),
(158, 11, 'Puerto Barrios', 'Y', '1801', 1, '2016-03-11 16:11:37', NULL, NULL),
(159, 11, 'Livingston', 'Y', '1802', 1, '2016-03-11 16:11:37', NULL, NULL),
(160, 11, 'Los Amates', 'Y', '1805', 1, '2016-03-11 16:11:37', NULL, NULL),
(161, 11, 'Morales', 'Y', '1804', 1, '2016-03-11 16:11:37', NULL, NULL),
(162, 12, 'Jalapa', 'Y', '2101', 1, '2016-03-11 16:11:37', NULL, NULL),
(163, 12, 'San Luis Jilotepeque', 'Y', '2103', 1, '2016-03-11 16:11:37', NULL, NULL),
(164, 12, 'Mataquescuintla', 'Y', '2107', 1, '2016-03-11 16:11:37', NULL, NULL),
(165, 12, 'San Manuel Chaparrón', 'Y', '2104', 1, '2016-03-11 16:11:37', NULL, NULL),
(166, 12, 'Monjas', 'Y', '2106', 1, '2016-03-11 16:11:37', NULL, NULL),
(167, 12, 'San Pedro Pinula', 'Y', '2102', 1, '2016-03-11 16:11:37', NULL, NULL),
(168, 12, 'San Carlos Alzatate', 'Y', '2105', 1, '2016-03-11 16:11:37', NULL, NULL),
(169, 13, 'Agua Blanca', 'Y', '2204', 1, '2016-03-11 16:11:37', NULL, NULL),
(170, 13, 'Conguaco', 'Y', '2213', 1, '2016-03-11 16:11:37', NULL, NULL),
(171, 13, 'Jerez', 'Y', '2208', 1, '2016-03-11 16:11:37', NULL, NULL),
(172, 13, 'Quesada', 'Y', '2217', 1, '2016-03-11 16:11:37', NULL, NULL),
(173, 13, 'Zapotitlán', 'Y', '2210', 1, '2016-03-11 16:11:37', NULL, NULL),
(174, 13, 'Asunción Mita', 'Y', '2205', 1, '2016-03-11 16:11:37', NULL, NULL),
(175, 13, 'El Adelanto', 'Y', '2209', 1, '2016-03-11 16:11:37', NULL, NULL),
(176, 13, 'Jutiapa', 'Y', '2201', 1, '2016-03-11 16:11:37', NULL, NULL),
(177, 13, 'San José Acatempa', 'Y', '2216', 1, '2016-03-11 16:11:37', NULL, NULL),
(178, 13, 'Atescatempa', 'Y', '2207', 1, '2016-03-11 16:11:37', NULL, NULL),
(179, 13, 'El Progreso', 'Y', '2202', 1, '2016-03-11 16:11:37', NULL, NULL),
(180, 13, 'Moyuta', 'Y', '2214', 1, '2016-03-11 16:11:37', NULL, NULL),
(181, 13, 'Santa Catarina Mita', 'Y', '2203', 1, '2016-03-11 16:11:37', NULL, NULL),
(182, 13, 'Comapa', 'Y', '2211', 1, '2016-03-11 16:11:37', NULL, NULL),
(183, 13, 'Jalpatagua', 'Y', '2212', 1, '2016-03-11 16:11:37', NULL, NULL),
(184, 13, 'Pasaco', 'Y', '2215', 1, '2016-03-11 16:11:37', NULL, NULL),
(185, 13, 'Yupiltepeque', 'Y', '2206', 1, '2016-03-11 16:11:37', NULL, NULL),
(186, 14, 'Almolonga', 'Y', '0913', 1, '2016-03-11 16:11:37', NULL, NULL),
(187, 14, 'Coatepeque', 'Y', '0920', 1, '2016-03-11 16:11:37', NULL, NULL),
(188, 14, 'Flores Costa Cuca', 'Y', '0922', 1, '2016-03-11 16:11:37', NULL, NULL),
(189, 14, 'Olintepeque', 'Y', '0903', 1, '2016-03-11 16:11:37', NULL, NULL),
(190, 14, 'San Carlos Sija', 'Y', '0904', 1, '2016-03-11 16:11:37', NULL, NULL),
(191, 14, 'San Mateo', 'Y', '0910', 1, '2016-03-11 16:11:37', NULL, NULL),
(192, 14, 'Cabricán', 'Y', '0906', 1, '2016-03-11 16:11:37', NULL, NULL),
(193, 14, 'Colomba', 'Y', '0917', 1, '2016-03-11 16:11:37', NULL, NULL),
(194, 14, 'Génova', 'Y', '0921', 1, '2016-03-11 16:11:37', NULL, NULL),
(195, 14, 'Palestina de Los Altos', 'Y', '0924', 1, '2016-03-11 16:11:37', NULL, NULL),
(196, 14, 'San Francisco La Unión', 'Y', '0918', 1, '2016-03-11 16:11:37', NULL, NULL),
(197, 14, 'San Miguel Sigüilá', 'Y', '0908', 1, '2016-03-11 16:11:37', NULL, NULL),
(198, 14, 'Cajolá', 'Y', '0907', 1, '2016-03-11 16:11:37', NULL, NULL),
(199, 14, 'Concepción Chiquirichapa', 'Y', '0911', 1, '2016-03-11 16:11:37', NULL, NULL),
(200, 14, 'Huitán', 'Y', '0915', 1, '2016-03-11 16:11:37', NULL, NULL),
(201, 14, 'Quetzaltenango', 'Y', '0901', 1, '2016-03-11 16:11:37', NULL, NULL),
(202, 14, 'San Juan Ostuncalco', 'Y', '0909', 1, '2016-03-11 16:11:37', NULL, NULL),
(203, 14, 'Sibilia', 'Y', '0905', 1, '2016-03-11 16:11:37', NULL, NULL),
(204, 14, 'Cantel', 'Y', '0914', 1, '2016-03-11 16:11:37', NULL, NULL),
(205, 14, 'El Palmar', 'Y', '0919', 1, '2016-03-11 16:11:37', NULL, NULL),
(206, 14, 'La Esperanza', 'Y', '0923', 1, '2016-03-11 16:11:37', NULL, NULL),
(207, 14, 'Salcajá', 'Y', '0902', 1, '2016-03-11 16:11:37', NULL, NULL),
(208, 14, 'San Martín Sacatepéquez', 'Y', '0912', 1, '2016-03-11 16:11:37', NULL, NULL),
(209, 14, 'Zunil', 'Y', '0916', 1, '2016-03-11 16:11:37', NULL, NULL),
(210, 15, 'Champerico', 'Y', '1107', 1, '2016-03-11 16:11:37', NULL, NULL),
(211, 15, 'San Andrés Villa Seca', 'Y', '1106', 1, '2016-03-11 16:11:37', NULL, NULL),
(212, 15, 'Santa Cruz Muluá', 'Y', '1103', 1, '2016-03-11 16:11:37', NULL, NULL),
(213, 15, 'El Asintal', 'Y', '1109', 1, '2016-03-11 16:11:37', NULL, NULL),
(214, 15, 'San Felipe', 'Y', '1105', 1, '2016-03-11 16:11:37', NULL, NULL),
(215, 15, 'Nuevo San Carlos', 'Y', '1108', 1, '2016-03-11 16:11:37', NULL, NULL),
(216, 15, 'San Martín Zapotitlán', 'Y', '1104', 1, '2016-03-11 16:11:37', NULL, NULL),
(217, 15, 'Retalhuleu', 'Y', '1101', 1, '2016-03-11 16:11:37', NULL, NULL),
(218, 15, 'San Sebastián', 'Y', '1102', 1, '2016-03-11 16:11:37', NULL, NULL),
(219, 16, 'Alotenango', 'Y', '0314', 1, '2016-03-11 16:11:37', NULL, NULL),
(220, 16, 'Magdalena Milpas Altas', 'Y', '0310', 1, '2016-03-11 16:11:37', NULL, NULL),
(221, 16, 'San Lucas Sacatepéquez', 'Y', '0308', 1, '2016-03-11 16:11:37', NULL, NULL),
(222, 16, 'Santa María de Jesús', 'Y', '0311', 1, '2016-03-11 16:11:37', NULL, NULL),
(223, 16, 'La Antigua Guatemala', 'Y', '0301', 1, '2016-03-11 16:11:37', NULL, NULL),
(224, 16, 'Pastores', 'Y', '0303', 1, '2016-03-11 16:11:37', NULL, NULL),
(225, 16, 'San Miguel Dueñas', 'Y', '0313', 1, '2016-03-11 16:11:37', NULL, NULL),
(226, 16, 'Santiago Sacatepéquez', 'Y', '0306', 1, '2016-03-11 16:11:37', NULL, NULL),
(227, 16, 'Ciudad Vieja', 'Y', '0312', 1, '2016-03-11 16:11:37', NULL, NULL),
(228, 16, 'San Antonio Aguas Calientes', 'Y', '0315', 1, '2016-03-11 16:11:37', NULL, NULL),
(229, 16, 'Santa Catarina Barahona', 'Y', '0316', 1, '2016-03-11 16:11:37', NULL, NULL),
(230, 16, 'Santo Domingo Xenacoj', 'Y', '0305', 1, '2016-03-11 16:11:37', NULL, NULL),
(231, 16, 'Jocotenango', 'Y', '0302', 1, '2016-03-11 16:11:37', NULL, NULL),
(232, 16, 'San Bartolomé Milpas Altas', 'Y', '0307', 1, '2016-03-11 16:11:37', NULL, NULL),
(233, 16, 'Santa Lucía Milpas Altas', 'Y', '0309', 1, '2016-03-11 16:11:37', NULL, NULL),
(234, 16, 'Sumpango', 'Y', '0304', 1, '2016-03-11 16:11:37', NULL, NULL),
(235, 17, 'Ayutla', 'Y', '1217', 1, '2016-03-11 16:11:37', NULL, NULL),
(236, 17, 'El Quetzal', 'Y', '1220', 1, '2016-03-11 16:11:37', NULL, NULL),
(237, 17, 'Ixchiguán', 'Y', '1223', 1, '2016-03-11 16:11:37', NULL, NULL),
(238, 17, 'Ocós', 'Y', '1218', 1, '2016-03-11 16:11:37', NULL, NULL),
(239, 17, 'San Cristóbal Cucho', 'Y', '1225', 1, '2016-03-11 16:11:37', NULL, NULL),
(240, 17, 'San Miguel Ixtahuacán', 'Y', '1205', 1, '2016-03-11 16:11:37', NULL, NULL),
(241, 17, 'Sibinal', 'Y', '1208', 1, '2016-03-11 16:11:37', NULL, NULL),
(242, 17, 'Tejutla', 'Y', '1210', 1, '2016-03-11 16:11:37', NULL, NULL),
(243, 17, 'Catarina', 'Y', '1216', 1, '2016-03-11 16:11:37', NULL, NULL),
(244, 17, 'El Rodeo', 'Y', '1214', 1, '2016-03-11 16:11:37', NULL, NULL),
(245, 17, 'La Reforma', 'Y', '1221', 1, '2016-03-11 16:11:37', NULL, NULL),
(246, 17, 'Pajapita', 'Y', '1222', 1, '2016-03-11 16:11:37', NULL, NULL),
(247, 17, 'San José Ojetenam', 'Y', '1224', 1, '2016-03-11 16:11:37', NULL, NULL),
(248, 17, 'San Pablo', 'Y', '1219', 1, '2016-03-11 16:11:37', NULL, NULL),
(249, 17, 'Sipacapa', 'Y', '1226', 1, '2016-03-11 16:11:37', NULL, NULL),
(250, 17, 'Comitancillo', 'Y', '1204', 1, '2016-03-11 16:11:37', NULL, NULL),
(251, 17, 'El Tumbador', 'Y', '1213', 1, '2016-03-11 16:11:37', NULL, NULL),
(252, 17, 'Malacatán', 'Y', '1215', 1, '2016-03-11 16:11:37', NULL, NULL),
(253, 17, 'Río Blanco', 'Y', '1228', 1, '2016-03-11 16:11:37', NULL, NULL),
(254, 17, 'San Lorenzo', 'Y', '1229', 1, '2016-03-11 16:11:37', NULL, NULL),
(255, 17, 'San Pedro Sacatepéquez', 'Y', '1202', 1, '2016-03-11 16:11:37', NULL, NULL),
(256, 17, 'Tacaná', 'Y', '1207', 1, '2016-03-11 16:11:37', NULL, NULL),
(257, 17, 'Concepción Tutuapa', 'Y', '1206', 1, '2016-03-11 16:11:37', NULL, NULL),
(258, 17, 'Esquipulas Palo Gordo', 'Y', '1227', 1, '2016-03-11 16:11:37', NULL, NULL),
(259, 17, 'Nuevo Progreso', 'Y', '1212', 1, '2016-03-11 16:11:37', NULL, NULL),
(260, 17, 'San Antonio Sacatepéquez', 'Y', '1203', 1, '2016-03-11 16:11:37', NULL, NULL),
(261, 17, 'San Marcos', 'Y', '1201', 1, '2016-03-11 16:11:37', NULL, NULL),
(262, 17, 'San Rafael Pie de La Cuesta', 'Y', '1211', 1, '2016-03-11 16:11:37', NULL, NULL),
(263, 17, 'Tajumulco', 'Y', '1209', 1, '2016-03-11 16:11:37', NULL, NULL),
(264, 18, 'Barberena', 'Y', '0602', 1, '2016-03-11 16:11:37', NULL, NULL),
(265, 18, 'Guazacapán', 'Y', '0611', 1, '2016-03-11 16:11:37', NULL, NULL),
(266, 18, 'San Juan Tecuaco', 'Y', '0607', 1, '2016-03-11 16:11:37', NULL, NULL),
(267, 18, 'Santa Rosa de Lima', 'Y', '0603', 1, '2016-03-11 16:11:37', NULL, NULL),
(268, 18, 'Casillas', 'Y', '0604', 1, '2016-03-11 16:11:37', NULL, NULL),
(269, 18, 'Nueva Santa Rosa', 'Y', '0614', 1, '2016-03-11 16:11:37', NULL, NULL),
(270, 18, 'San Rafaél Las Flores', 'Y', '0605', 1, '2016-03-11 16:11:37', NULL, NULL),
(271, 18, 'Taxisco', 'Y', '0609', 1, '2016-03-11 16:11:37', NULL, NULL),
(272, 18, 'Chiquimulilla', 'Y', '0608', 1, '2016-03-11 16:11:37', NULL, NULL),
(273, 18, 'Oratorio', 'Y', '0606', 1, '2016-03-11 16:11:37', NULL, NULL),
(274, 18, 'Santa Cruz Naranjo', 'Y', '0612', 1, '2016-03-11 16:11:37', NULL, NULL),
(275, 18, 'Cuilapa', 'Y', '0601', 1, '2016-03-11 16:11:37', NULL, NULL),
(276, 18, 'Pueblo Nuevo Viñas', 'Y', '0613', 1, '2016-03-11 16:11:37', NULL, NULL),
(277, 18, 'Santa María Ixhuatán', 'Y', '0610', 1, '2016-03-11 16:11:37', NULL, NULL),
(278, 19, 'Concepción', 'Y', '0708', 1, '2016-03-11 16:11:37', NULL, NULL),
(279, 19, 'San Antonio Palopó', 'Y', '0712', 1, '2016-03-11 16:11:37', NULL, NULL),
(280, 19, 'San Marcos La Laguna', 'Y', '0716', 1, '2016-03-11 16:11:37', NULL, NULL),
(281, 19, 'Santa Catarina Palopó', 'Y', '0711', 1, '2016-03-11 16:11:37', NULL, NULL),
(282, 19, 'Santa María Visitación', 'Y', '0703', 1, '2016-03-11 16:11:37', NULL, NULL),
(283, 19, 'Nahualá', 'Y', '0705', 1, '2016-03-11 16:11:37', NULL, NULL),
(284, 19, 'San José Chacayá', 'Y', '0702', 1, '2016-03-11 16:11:37', NULL, NULL),
(285, 19, 'San Pablo La Laguna', 'Y', '0715', 1, '2016-03-11 16:11:37', NULL, NULL),
(286, 19, 'Santa Clara La Laguna', 'Y', '0707', 1, '2016-03-11 16:11:37', NULL, NULL),
(287, 19, 'Santiago Atitlán', 'Y', '0719', 1, '2016-03-11 16:11:37', NULL, NULL),
(288, 19, 'Panajachel', 'Y', '0710', 1, '2016-03-11 16:11:37', NULL, NULL),
(289, 19, 'San Juan La Laguna', 'Y', '0717', 1, '2016-03-11 16:11:37', NULL, NULL),
(290, 19, 'San Pedro La Laguna', 'Y', '0718', 1, '2016-03-11 16:11:37', NULL, NULL),
(291, 19, 'Santa Cruz La Laguna', 'Y', '0714', 1, '2016-03-11 16:11:37', NULL, NULL),
(292, 19, 'Sololá', 'Y', '0701', 1, '2016-03-11 16:11:37', NULL, NULL),
(293, 19, 'San Andrés Semetabaj', 'Y', '0709', 1, '2016-03-11 16:11:37', NULL, NULL),
(294, 19, 'San Lucas Tolimán', 'Y', '0713', 1, '2016-03-11 16:11:37', NULL, NULL),
(295, 19, 'Santa Catarina Ixtahuacan', 'Y', '0706', 1, '2016-03-11 16:11:37', NULL, NULL),
(296, 19, 'Santa Lucía Utatlán', 'Y', '0704', 1, '2016-03-11 16:11:37', NULL, NULL),
(297, 20, 'Chicacao', 'Y', '1013', 1, '2016-03-11 16:11:37', NULL, NULL),
(298, 20, 'Pueblo Nuevo', 'Y', '1019', 1, '2016-03-11 16:11:37', NULL, NULL),
(299, 20, 'San Bernardino', 'Y', '1004', 1, '2016-03-11 16:11:37', NULL, NULL),
(300, 20, 'San Juan Bautista', 'Y', '1016', 1, '2016-03-11 16:11:37', NULL, NULL),
(301, 20, 'Santa Bárbara', 'Y', '1310', 1, '2016-03-11 16:11:37', NULL, NULL),
(302, 20, 'Cuyotenango', 'Y', '1002', 1, '2016-03-11 16:11:37', NULL, NULL),
(303, 20, 'Río Bravo', 'Y', '1020', 1, '2016-03-11 16:11:37', NULL, NULL),
(304, 20, 'San Francisco Zapotitlán', 'Y', '1003', 1, '2016-03-11 16:11:37', NULL, NULL),
(305, 20, 'San Lorenzo', 'Y', '1229', 1, '2016-03-11 16:11:37', NULL, NULL),
(306, 20, 'Santo Domingo', 'Y', '1006', 1, '2016-03-11 16:11:37', NULL, NULL),
(307, 20, 'Mazatenango', 'Y', '1001', 1, '2016-03-11 16:11:37', NULL, NULL),
(308, 20, 'Samayac', 'Y', '1008', 1, '2016-03-11 16:11:37', NULL, NULL),
(309, 20, 'San Gabriel', 'Y', '1012', 1, '2016-03-11 16:11:37', NULL, NULL),
(310, 20, 'San Miguel Panán', 'Y', '1011', 1, '2016-03-11 16:11:37', NULL, NULL),
(311, 20, 'Santo Tomás La Unión', 'Y', '1017', 1, '2016-03-11 16:11:37', NULL, NULL),
(312, 20, 'Patulul', 'Y', '1014', 1, '2016-03-11 16:11:37', NULL, NULL),
(313, 20, 'San Antonio', 'Y', '1010', 1, '2016-03-11 16:11:37', NULL, NULL),
(314, 20, 'San José El Ídolo', 'Y', '1005', 1, '2016-03-11 16:11:37', NULL, NULL),
(315, 20, 'San Pablo Jocopilas', 'Y', '1009', 1, '2016-03-11 16:11:37', NULL, NULL),
(316, 20, 'Zunilito', 'Y', '1018', 1, '2016-03-11 16:11:37', NULL, NULL),
(317, 21, 'Momostenango', 'Y', '0805', 1, '2016-03-11 16:11:37', NULL, NULL),
(318, 21, 'San Francisco El Alto', 'Y', '0803', 1, '2016-03-11 16:11:37', NULL, NULL),
(319, 21, 'San Andrés Xecul', 'Y', '0804', 1, '2016-03-11 16:11:37', NULL, NULL),
(320, 21, 'Santa Lucía La Reforma', 'Y', '0807', 1, '2016-03-11 16:11:37', NULL, NULL),
(321, 21, 'San Bartolo', 'Y', '0808', 1, '2016-03-11 16:11:37', NULL, NULL),
(322, 21, 'Santa María Chiquimula', 'Y', '0806', 1, '2016-03-11 16:11:37', NULL, NULL),
(323, 21, 'San Cristóbal Totonicapán', 'Y', '0802', 1, '2016-03-11 16:11:37', NULL, NULL),
(324, 21, 'Totonicapán', 'Y', '0801', 1, '2016-03-11 16:11:37', NULL, NULL),
(325, 22, 'Cabañas', 'Y', '1907', 1, '2016-03-11 16:11:37', NULL, NULL),
(326, 22, 'La Unión', 'Y', '1909', 1, '2016-03-11 16:11:37', NULL, NULL),
(327, 22, 'Usumatlán', 'Y', '1906', 1, '2016-03-11 16:11:37', NULL, NULL),
(328, 22, 'Estanzuela', 'Y', '1902', 1, '2016-03-11 16:11:37', NULL, NULL),
(329, 22, 'Río Hondo', 'Y', '1903', 1, '2016-03-11 16:11:37', NULL, NULL),
(330, 22, 'Zacapa', 'Y', '1901', 1, '2016-03-11 16:11:37', NULL, NULL),
(331, 22, 'Gualán', 'Y', '1904', 1, '2016-03-11 16:11:37', NULL, NULL),
(332, 22, 'San Diego', 'Y', '1908', 1, '2016-03-11 16:11:37', NULL, NULL),
(333, 22, 'Huité', 'Y', '1910', 1, '2016-03-11 16:11:37', NULL, NULL),
(334, 22, 'Teculután', 'Y', '1905', 1, '2016-03-11 16:11:37', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `online`
--

DROP TABLE IF EXISTS `online`;
CREATE TABLE IF NOT EXISTS `online` (
  `online` varchar(40) NOT NULL COMMENT 'PK SESSION_ID',
  `persona` int(10) UNSIGNED DEFAULT NULL COMMENT 'PERSONA QUE EST? LOGINEADA',
  `hora` datetime NOT NULL COMMENT 'HORA QUE SE LOGINEO',
  PRIMARY KEY (`online`),
  KEY `online_per_i` (`persona`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLA QUE LLEVA EL CONTROL DE LAS PERSONAS QUE EST?N EN EL S';

--
-- Volcado de datos para la tabla `online`
--

INSERT INTO `online` (`online`, `persona`, `hora`) VALUES
('3rnkf1v3a6u0sul7dflrqt46o0', 1, '2022-05-25 04:36:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pais`
--

DROP TABLE IF EXISTS `pais`;
CREATE TABLE IF NOT EXISTS `pais` (
  `pais` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK ID QUE IDENTIFICA EL REGISTRO EN LA TABLA',
  `nombre` varchar(255) NOT NULL COMMENT 'NOMBRE DEL PAIS',
  `prefijo` varchar(2) DEFAULT NULL,
  `nacionalidad` varchar(255) NOT NULL COMMENT 'NACIONALIDAD DEL PAIS',
  `nacionalidad_contratos_m` varchar(250) DEFAULT NULL,
  `activo` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'CAMPO QUE INDICA SI EL PAIS ESTA ACTIVO O NO',
  `predeterminado` enum('N','Y') NOT NULL DEFAULT 'N',
  `add_user` int(10) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'FK ID DEL USUARIO QUE INSERTO LA TUPLA',
  `add_fecha` datetime NOT NULL COMMENT 'FECHA EN LA QUE SE INSERTO LA TUPLA',
  `mod_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK ID DEL USUARIO QUE MODIFICO LA TUPLA',
  `mod_fecha` datetime DEFAULT NULL COMMENT 'FECHA EN LA QUE SE MODIFICO LA TUPLA',
  `nacionalidad_contratos_f` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`pais`),
  KEY `pais_add_use_f` (`add_user`),
  KEY `pais_mod_use_f` (`mod_user`)
) ENGINE=InnoDB AUTO_INCREMENT=241 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pais`
--

INSERT INTO `pais` (`pais`, `nombre`, `prefijo`, `nacionalidad`, `nacionalidad_contratos_m`, `activo`, `predeterminado`, `add_user`, `add_fecha`, `mod_user`, `mod_fecha`, `nacionalidad_contratos_f`) VALUES
(1, 'GUATEMALA', 'GT', 'GUATEMALA', 'Guatemalteco', 'Y', 'Y', 1, '2016-06-13 11:18:06', 1, '2018-02-15 16:11:40', 'Guatemalteca'),
(2, 'Islas Gland', 'AX', 'Islas Gland', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(3, 'ALBANIA', 'AL', 'ALBANIA', 'Albanés', 'Y', 'N', 1, '2016-03-11 16:08:27', 1, '2016-08-08 16:48:37', 'Albanesa'),
(4, 'ALEMANIA', 'DE', 'ALEMANIA', 'Alemán', 'Y', 'N', 1, '2016-03-11 16:08:27', 1, '2016-08-08 16:48:24', 'Alemána'),
(5, 'ANDORRA', 'AD', 'ANDORRA', 'Andorrano', 'Y', 'N', 1, '2016-03-11 16:08:27', 1, '2016-08-08 16:48:46', 'Andorrana'),
(6, 'ANGOLA', 'AO', 'ANGOLA', 'Angoleño', 'Y', 'N', 1, '2016-03-11 16:08:27', 1, '2016-08-08 16:49:00', 'Angoleña'),
(7, 'Anguilla', 'AI', 'Anguilla', 'Anguilense', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Anguilense'),
(8, 'Antártida', 'AQ', 'Antártida', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(9, 'ANTIGUA Y BARBUDA', 'AG', 'ANTIGUA Y BARBUDA', 'Antiguano', 'Y', 'N', 1, '2016-03-11 16:08:27', 1, '2016-08-08 16:49:15', 'Antiguana'),
(10, 'Antillas Holandesas', 'AN', 'Antillas Holandesas', 'Antillano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Antillana'),
(11, 'ARABIA SAUDÍ', 'SA', 'ARABIA SAUDÍ', 'Saudí', 'Y', 'N', 1, '2016-03-11 16:08:27', 1, '2016-08-08 16:49:30', 'Saudí'),
(12, 'ARGELIA', 'DZ', 'ARGELIA', 'Argelino', 'Y', 'N', 1, '2016-03-11 16:08:27', 1, '2016-08-08 16:49:39', 'Argelina'),
(13, 'ARGENTINA', 'AR', 'ARGENTINA', 'Argentino', 'Y', 'N', 1, '2016-03-11 16:08:27', 1, '2016-08-08 16:49:58', 'Argentina'),
(14, 'ARMENIA', 'AM', 'ARMENIA', 'Armenio', 'Y', 'N', 1, '2016-03-11 16:08:27', 1, '2016-08-08 16:50:17', 'Armenia'),
(15, 'Aruba', 'AW', 'Aruba', 'Arubano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Arubana'),
(16, 'Australia', 'AU', 'Australia', 'Australiano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Australiana'),
(17, 'AUSTRIA', 'AT', 'AUSTRIA', 'Austriaco', 'Y', 'N', 1, '2016-03-11 16:08:27', 1, '2016-08-08 16:50:41', 'Austriaca'),
(18, 'AZERBAIYÁN', 'AZ', 'AZERBAIYÁN', 'Azerbaiyano', 'Y', 'N', 1, '2016-03-11 16:08:27', 1, '2016-08-08 16:50:49', 'Azerbaiyana'),
(19, 'BAHAMAS', 'BS', 'BAHAMAS', 'Bahameño', 'Y', 'N', 1, '2016-03-11 16:08:27', 1, '2016-08-08 16:50:57', 'Bahameña'),
(20, 'Bahréin', 'BH', 'Bahréin', 'Bareiní', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Bareiní'),
(21, 'BANGLADESH', 'BD', 'BANGLADESH', 'Bangladesí', 'Y', 'N', 1, '2016-03-11 16:08:27', 1, '2016-08-08 16:51:18', 'Bangladesí'),
(22, 'BARBADOS', 'BB', 'BARBADOS', 'Barbadense', 'Y', 'N', 1, '2016-03-11 16:08:27', 1, '2016-08-08 16:51:26', 'Barbadense'),
(23, 'Bielorrusia', 'BY', 'Bielorrusia', 'Bielorruso', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Bielorrusa'),
(24, 'Bélgica', 'BE', 'Bélgica', 'Belga', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Belga'),
(25, 'Belice', 'BZ', 'Belice', 'Beliceño', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Beliceña'),
(26, 'Benin', 'BJ', 'Benin', 'Beninés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Beninésa'),
(27, 'Bermudas', 'BM', 'Bermudas', 'Bermudeño', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Bermudeña'),
(28, 'Bhután', 'BT', 'Bhután', 'Butanés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Butanesa'),
(29, 'BOLIVIA', 'BO', 'BOLIVIA', 'Boliviano', 'Y', 'N', 1, '2016-03-11 16:08:27', 1, '2016-08-08 16:51:39', 'Boliviana'),
(30, 'Bosnia y Herzegovina', 'BA', 'Bosnia y Herzegovina', 'Bosnio', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Bosnia'),
(31, 'Botsuana', 'BW', 'Botsuana', 'Botsuano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Botsuana'),
(32, 'Isla Bouvet', 'BV', 'Isla Bouvet', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(33, 'Brasil', 'BR', 'Brasil', 'Brasileño', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Brasileña'),
(34, 'Brunéi', 'BN', 'Brunéi', 'Bruneano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Bruneana'),
(35, 'Bulgaria', 'BG', 'Bulgaria', 'Búlgaro', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Búlgara'),
(36, 'Burkina Faso', 'BF', 'Burkina Faso', 'Burkinés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Burkinés'),
(37, 'Burundi', 'BI', 'Burundi', 'Burundés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Burundesa'),
(38, 'Cabo Verde', 'CV', 'Cabo Verde', 'Caboverdiano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Caboverdiana'),
(39, 'Islas Caimán', 'KY', 'Islas Caimán', 'Caimanés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Caimanesa'),
(40, 'Camboya', 'KH', 'Camboya', 'Camboyano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Camboyana'),
(41, 'Camerún', 'CM', 'Camerún', 'Camerunés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Camerunesa'),
(42, 'Canadá', 'CA', 'Canadá', 'Canadiense', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Canadiense'),
(43, 'República Centroafricana', 'CF', 'República Centroafricana', 'Centroafricano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Centroafricana'),
(44, 'Chad', 'TD', 'Chad', 'Chadiano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Chadiana'),
(45, 'República Checa', 'CZ', 'República Checa', 'Checo', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Checa'),
(46, 'Chile', 'CL', 'Chile', 'Chileno', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Chilena'),
(47, 'China', 'CN', 'China', 'Chino', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'China'),
(48, 'Chipre', 'CY', 'Chipre', 'Chipriota', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Chipriota'),
(49, 'Isla de Navidad', 'CX', 'Isla de Navidad', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(50, 'Ciudad del Vaticano', 'VA', 'Ciudad del Vaticano', 'Vaticano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Vaticana'),
(51, 'Islas Cocos', 'CC', 'Islas Cocos', 'Cocano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Cocana'),
(52, 'Colombia', 'CO', 'Colombia', 'Colombiano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Colombiana'),
(53, 'Comoras', 'KM', 'Comoras', 'Comorense', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Comorense'),
(54, 'República Democrática del Congo', 'CD', 'República Democrática del Congo', 'Congoleño', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Congoleña'),
(55, 'Congo', 'CG', 'Congo', 'Congoleño', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Congoleña'),
(56, 'Islas Cook', 'CK', 'Islas Cook', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(57, 'Corea del Norte', 'KP', 'Corea del Norte', 'Norcoreano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Norcoreana'),
(58, 'Corea del Sur', 'KR', 'Corea del Sur', 'Surcoreano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Surcoreana'),
(59, 'Costa de Marfil', 'CI', 'Costa de Marfil', 'Marfileño', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Marfileña'),
(60, 'Costa Rica', 'CR', 'Costa Rica', 'Costarricense', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Costarricense'),
(61, 'Croacia', 'HR', 'Croacia', 'Croata', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Croata'),
(62, 'Cuba', 'CU', 'Cuba', 'Cubano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Cubana'),
(63, 'Dinamarca', 'DK', 'Dinamarca', 'Danés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Danesa'),
(64, 'Dominica', 'DM', 'Dominica', 'Dominiqués', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Dominiqués'),
(65, 'República Dominicana', 'DO', 'República Dominicana', 'Dominicano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Dominicana'),
(66, 'Ecuador', 'EC', 'Ecuador', 'Ecuatoriano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Ecuatoriana'),
(67, 'Egipto', 'EG', 'Egipto', 'Egipcio', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Egipcia'),
(68, 'El Salvador', 'SV', 'El Salvador', 'Salvadoreño', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Salvadoreña'),
(69, 'Emiratos Árabes Unidos', 'AE', 'Emiratos Árabes Unidos', 'Emiratí', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Emiratí'),
(70, 'Eritrea', 'ER', 'Eritrea', 'Eritreo', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Eritrea'),
(71, 'Eslovaquia', 'SK', 'Eslovaquia', 'Eslovaco', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Eslovaca'),
(72, 'Eslovenia', 'SI', 'Eslovenia', 'Esloveno', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Eslovena'),
(73, 'España', 'ES', 'España', 'Español', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Española'),
(74, 'Islas ultramarinas de Estados Unidos', 'VI', 'Islas ultramarinas de Estados Unidos', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(75, 'Estados Unidos', 'US', 'Estados Unidos', 'Estadounidense', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Estadounidense'),
(76, 'Estonia', 'EE', 'Estonia', 'Estonio', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Estonia'),
(77, 'Etiopía', 'ET', 'Etiopía', 'Etíope', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Etíope'),
(78, 'Islas Feroe', 'FO', 'Islas Feroe', 'Feroés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Feroesa'),
(79, 'Filipinas', 'PH', 'Filipinas', 'Filipino', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Filipina'),
(80, 'Finlandia', 'FI', 'Finlandia', 'Finlandés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Finlandesa'),
(81, 'Fiyi', 'FJ', 'Fiyi', 'Fiyiano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Fiyiana'),
(82, 'Francia', 'FR', 'Francia', 'Francés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Francesa'),
(83, 'Gabón', 'GA', 'Gabón', 'Gabonés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Gabonesa'),
(84, 'Gambia', 'GM', 'Gambia', 'Gambiano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Gambiana'),
(85, 'Georgia', 'GE', 'Georgia', 'Georgiano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Georgiana'),
(86, 'Islas Georgias del Sur y Sandwich del Sur', 'GS', 'Islas Georgias del Sur y Sandwich del Sur', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(87, 'Ghana', 'GH', 'Ghana', 'Ghanés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Ghanesa'),
(88, 'Gibraltar', 'GI', 'Gibraltar', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(89, 'Granada', 'GD', 'Granada', 'Granadino', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Granadina'),
(90, 'Grecia', 'GR', 'Grecia', 'Griego', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Griega'),
(91, 'Groenlandia', 'GL', 'Groenlandia', 'Groenlandés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Groenlandesa'),
(92, 'Guadalupe', 'GP', 'Guadalupe', 'Guadalupeño', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Guadalupeña'),
(93, 'Guam', 'GU', 'Guam', 'Guameño', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Guameña'),
(94, 'Afganistán', 'AF', 'Afganistán', 'Afgano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Afgana'),
(95, 'Guayana Francesa', 'GF', 'Guayana Francesa', 'Francoguayanés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Francoguayanesa'),
(96, 'Guinea', 'GN', 'Guinea', 'Guineano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Guineana'),
(97, 'Guinea Ecuatorial', 'GQ', 'Guinea Ecuatorial', 'Ecuatoguineano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Ecuatoguineana'),
(98, 'Guinea-Bissau', 'GW', 'Guinea-Bissau', 'Guineano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Guineana'),
(99, 'Guyana', 'GY', 'Guyana', 'Guyanés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Guyanesa'),
(100, 'Haití', 'HT', 'Haití', 'Haitiano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Haitiana'),
(101, 'Islas Heard y McDonald', 'HM', 'Islas Heard y McDonald', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(102, 'Honduras', 'HN', 'Honduras', 'Hondureño', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Hondureña'),
(103, 'Hong Kong', 'HK', 'Hong Kong', 'Hongkonés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Hongkonesa'),
(104, 'Hungría', 'HU', 'Hungría', 'Húngaro', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Húngara'),
(105, 'India', 'IN', 'India', 'Indio', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'India'),
(106, 'Indonesia', 'ID', 'Indonesia', 'Indonesio', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Indonesia'),
(107, 'Irán', 'IR', 'Irán', 'Iraní', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Iraní'),
(108, 'Iraq', 'IQ', 'Iraq', 'Iraquí', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Iraquí'),
(109, 'Irlanda', 'IE', 'Irlanda', 'Irlandés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Irlandesa'),
(110, 'Islandia', 'IS', 'Islandia', 'Islandés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Islandesa'),
(111, 'Israel', 'IL', 'Israel', 'Israelí', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Israelí'),
(112, 'Italia', 'IT', 'Italia', 'Italiano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Italiana'),
(113, 'Jamaica', 'JM', 'Jamaica', 'Jamaicano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Jamaicana'),
(114, 'Japón', 'JP', 'Japón', 'Japonés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Japonesa'),
(115, 'Jordania', 'JO', 'Jordania', 'Jordano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Jordana'),
(116, 'Kazajstán', 'KZ', 'Kazajstán', 'Kazajo', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Kazaja'),
(117, 'Kenia', 'KE', 'Kenia', 'Keniano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Keniana'),
(118, 'Kirguistán', 'KG', 'Kirguistán', 'Kirguís', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Kirguís'),
(119, 'Kiribati', 'KI', 'Kiribati', 'Kiribatiano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Kiribatiana'),
(120, 'Kuwait', 'KW', 'Kuwait', 'Kuwaití', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Kuwaití'),
(121, 'Laos', 'LA', 'Laos', 'Laosiano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Laosiana'),
(122, 'Lesotho', 'LS', 'Lesotho', 'Lesotense', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Lesotense'),
(123, 'Letonia', 'LV', 'Letonia', 'Letón', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Letona'),
(124, 'Líbano', 'LB', 'Líbano', 'Libanés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Libanesa'),
(125, 'Liberia', 'LR', 'Liberia', 'Liberiano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Liberiana'),
(126, 'Libia', 'LY', 'Libia', 'Libio', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Libia'),
(127, 'Liechtenstein', 'LI', 'Liechtenstein', 'Liechtensteiniano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Liechtensteiniana'),
(128, 'Lituania', 'LT', 'Lituania', 'Lituano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Lituana'),
(129, 'Luxemburgo', 'LU', 'Luxemburgo', 'Luxemburgués', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Luxemburguesa'),
(130, 'Macao', 'MO', 'Macao', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(131, 'ARY Macedonia', 'MK', 'ARY Macedonia', 'Macedonio', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Macedonia'),
(132, 'Madagascar', 'MG', 'Madagascar', 'Malgache', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Malgache'),
(133, 'Malasia', 'MY', 'Malasia', 'Malasio', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Malasia'),
(134, 'Malawi', 'MW', 'Malawi', 'Malauí', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Malauí'),
(135, 'Maldivas', 'MV', 'Maldivas', 'Maldivo', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Maldiva'),
(136, 'Malí', 'ML', 'Malí', 'Maliense', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Maliense'),
(137, 'Malta', 'MT', 'Malta', 'Maltés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Maltesa'),
(138, 'Islas Malvinas', 'FK', 'Islas Malvinas', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(139, 'Islas Marianas del Norte', 'MP', 'Islas Marianas del Norte', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(140, 'Marruecos', 'MA', 'Marruecos', 'Marroquí', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Marroquí'),
(141, 'Islas Marshall', 'MH', 'Islas Marshall', 'Marshalés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Marshalesa'),
(142, 'Martinica', 'MQ', 'Martinica', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(143, 'Mauricio', 'MU', 'Mauricio', 'Mauriciano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Mauriciana'),
(144, 'Mauritania', 'MR', 'Mauritania', 'Mauritano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Mauritana'),
(145, 'Mayotte', 'YT', 'Mayotte', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(146, 'MÉXICO', 'MX', 'MÉXICO', 'Mexicano', 'Y', 'N', 1, '2016-03-11 16:08:27', 1, '2016-08-08 16:53:05', 'Mexicana'),
(147, 'Micronesia', 'FM', 'Micronesia', 'Micronesio', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Micronesia'),
(148, 'Moldavia', 'MD', 'Moldavia', 'Moldavo', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Moldava'),
(149, 'Mónaco', 'MC', 'Mónaco', 'Monegasco', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Monegasca'),
(150, 'Mongolia', 'MN', 'Mongolia', 'Mongol', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Mongola'),
(151, 'Montserrat', 'MS', 'Montserrat', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(152, 'Mozambique', 'MZ', 'Mozambique', 'Mozambiqueño', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Mozambiqueña'),
(153, 'Myanmar', 'MM', 'Myanmar', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(154, 'Namibia', 'NA', 'Namibia', 'Namibio', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Namibia'),
(155, 'Nauru', 'NR', 'Nauru', 'Nauruano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Nauruana'),
(156, 'Nepal', 'NP', 'Nepal', 'Nepalés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Nepalesa'),
(157, 'Nicaragua', 'NI', 'Nicaragua', 'Nicaragüense', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Nicaragüense'),
(158, 'Níger', 'NE', 'Níger', 'Nigerino', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Nigerina'),
(159, 'Nigeria', 'NG', 'Nigeria', 'Nigeriano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Nigeriana'),
(160, 'Niue', 'NU', 'Niue', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(161, 'Isla Norfolk', 'NF', 'Isla Norfolk', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(162, 'Noruega', 'NO', 'Noruega', 'Noruego', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Noruega'),
(163, 'Nueva Caledonia', 'NC', 'Nueva Caledonia', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(164, 'Nueva Zelanda', 'NZ', 'Nueva Zelanda', 'Neozelandés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Neozelandesa'),
(165, 'Omán', 'OM', 'Omán', 'Omaní', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Omaní'),
(166, 'Países Bajos', 'NL', 'Países Bajos', 'Neerlandés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Neerlandesa'),
(167, 'Pakistán', 'PK', 'Pakistán', 'Pakistaní', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Pakistaní'),
(168, 'Palau', 'PW', 'Palau', 'Palauano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Palauana'),
(169, 'Palestina', 'PS', 'Palestina', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(170, 'Panamá', 'PA', 'Panamá', 'Panameño', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Panameña'),
(171, 'Papúa Nueva Guinea', 'PG', 'Papúa Nueva Guinea', 'Papú', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Papú'),
(172, 'Paraguay', 'PY', 'Paraguay', 'Paraguayo', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Paraguaya'),
(173, 'Perú', 'PE', 'Perú', 'Peruano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Peruana'),
(174, 'Islas Pitcairn', 'PN', 'Islas Pitcairn', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(175, 'Polinesia Francesa', 'PF', 'Polinesia Francesa', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(176, 'Polonia', 'PL', 'Polonia', 'Polaco', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Polaca'),
(177, 'Portugal', 'PT', 'Portugal', 'Portugués', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Portuguesa'),
(178, 'Puerto Rico', 'PR', 'Puerto Rico', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(179, 'Qatar', 'QA', 'Qatar', 'Catarí', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Catarí'),
(180, 'Reino Unido', 'GB', 'Reino Unido', 'Británico', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Británica'),
(181, 'Reunión', 'RE', 'Reunión', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(182, 'Ruanda', 'RW', 'Ruanda', 'Ruandés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Ruandesa'),
(183, 'Rumania', 'RO', 'Rumania', 'Rumano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Rumana'),
(184, 'Rusia', 'RU', 'Rusia', 'Ruso', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Rusa'),
(185, 'Sahara Occidental', 'EH', 'Sahara Occidental', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(186, 'Islas Salomón', 'SB', 'Islas Salomón', 'Salomonense', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Salomonense'),
(187, 'Samoa', 'WS', 'Samoa', 'Samoano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Samoana'),
(188, 'Samoa Americana', 'AS', 'Samoa Americana', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(189, 'San Cristóbal y Nevis', 'KN', 'San Cristóbal y Nevis', 'Cristobaleño', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Cristobaleña'),
(190, 'San Marino', 'SM', 'San Marino', 'Sanmarinense', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Sanmarinense'),
(191, 'San Pedro y Miquelón', 'PM', 'San Pedro y Miquelón', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(192, 'San Vicente y las Granadinas', 'VC', 'San Vicente y las Granadinas', 'Sanvicentino', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Sanvicentina'),
(193, 'Santa Helena', 'SH', 'Santa Helena', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(194, 'Santa Lucía', 'LC', 'Santa Lucía', 'Santalucense', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Santalucense'),
(195, 'Santo Tomé y Príncipe', 'ST', 'Santo Tomé y Príncipe', 'Santotomense', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Santotomense'),
(196, 'Senegal', 'SN', 'Senegal', 'Senegalés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Senegalesa'),
(197, 'Serbia y Montenegro', 'RS', 'Serbia y Montenegro', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(198, 'Seychelles', 'SC', 'Seychelles', 'Seychellense', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Seychellense'),
(199, 'Sierra Leona', 'SL', 'Sierra Leona', 'Sierraleonés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Sierraleonesa'),
(200, 'Singapur', 'SG', 'Singapur', 'Singapurense', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Singapurense'),
(201, 'Siria', 'SY', 'Siria', 'Sirio', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Siria'),
(202, 'Somalia', 'SO', 'Somalia', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(203, 'Sri Lanka', 'LK', 'Sri Lanka', 'Ceilanés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Ceilanesa'),
(204, 'Suazilandia', 'SZ', 'Suazilandia', 'Suazi', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Suazi'),
(205, 'Sudáfrica', 'ZA', 'Sudáfrica', 'Sudafricano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Sudafricana'),
(206, 'Sudán', 'SD', 'Sudán', 'Sudanés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Sudanesa'),
(207, 'Suecia', 'SE', 'Suecia', 'Sueco', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Sueca'),
(208, 'Suiza', 'CH', 'Suiza', 'Suizo', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Suiza'),
(209, 'Surinam', 'SR', 'Surinam', 'Surinamés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Surinamesa'),
(210, 'Svalbard y Jan Mayen', 'SJ', 'Svalbard y Jan Mayen', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(211, 'Tailandia', 'TH', 'Tailandia', 'Tailandés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Tailandesa'),
(212, 'Taiwán', 'TW', 'Taiwán', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(213, 'Tanzania', 'TZ', 'Tanzania', 'Tanzano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Tanzana'),
(214, 'Tayikistán', 'TJ', 'Tayikistán', 'Tayiko', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Tayika'),
(215, 'Territorio Británico del Océano Índico', 'IO', 'Territorio Británico del Océano Índico', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(216, 'Territorios Australes Franceses', 'TF', 'Territorios Australes Franceses', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(217, 'Timor Oriental', 'TL', 'Timor Oriental', 'Timorense', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Timorense'),
(218, 'Togo', 'TG', 'Togo', 'Togolés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Togolesa'),
(219, 'Tokelau', 'TK', 'Tokelau', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(220, 'Tonga', 'TO', 'Tonga', 'Tongano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Tongana'),
(221, 'Trinidad y Tobago', 'TT', 'Trinidad y Tobago', 'Trinitense', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Trinitense'),
(222, 'Túnez', 'TN', 'Túnez', 'Tunecino', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Tunecina'),
(223, 'Islas Turcas y Caicos', 'TC', 'Islas Turcas y Caicos', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(224, 'Turkmenistán', 'TM', 'Turkmenistán', 'Turcomano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Turcomana'),
(225, 'Turquía', 'TR', 'Turquía', 'Turco', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Turca'),
(226, 'Tuvalu', 'TV', 'Tuvalu', 'Tuvaluano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Tuvaluana'),
(227, 'Ucrania', 'UA', 'Ucrania', 'Ucraniano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Ucraniana'),
(228, 'Uganda', 'UG', 'Uganda', 'Ugandés', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Ugandesa'),
(229, 'Uruguay', 'UY', 'Uruguay', 'Uruguayo', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Uruguaya'),
(230, 'Uzbekistán', 'UZ', 'Uzbekistán', 'Uzbeko', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Uzbeka'),
(231, 'Vanuatu', 'VU', 'Vanuatu', 'Vanuatuense', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Vanuatuense'),
(232, 'Venezuela', 'VE', 'Venezuela', 'Venezolano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Venezolana'),
(233, 'Vietnam', 'VN', 'Vietnam', 'Vietnamita', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Vietnamita'),
(234, 'Islas Vírgenes Británicas', 'VG', 'Islas Vírgenes Británicas', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(235, 'Islas Vírgenes de los Estados Unidos', 'VI', 'Islas Vírgenes de los Estados Unidos', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(236, 'Wallis y Futuna', 'WF', 'Wallis y Futuna', NULL, 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, NULL),
(237, 'Yemen', 'YE', 'Yemen', 'Yemení', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Yemení'),
(238, 'Yibuti', 'DJ', 'Yibuti', 'Yibutiano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Yibutiana'),
(239, 'Zambia', 'ZM', 'Zambia', 'Zambiano', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Zambiana'),
(240, 'Zimbabue', 'ZW', 'Zimbabue', 'Zimbabuense', 'Y', 'N', 1, '2016-03-11 16:08:27', NULL, NULL, 'Zimbabuense');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

DROP TABLE IF EXISTS `perfil`;
CREATE TABLE IF NOT EXISTS `perfil` (
  `perfil` int(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `nombre` varchar(75) NOT NULL COMMENT 'NOMBRE DEL PERFIL',
  `descripcion` varchar(300) DEFAULT NULL COMMENT 'DESCRIPCION DEL PERFIL',
  `activo` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'INDICA SI EST? ACTIVO O NO EL PERFIL',
  `add_user` int(10) UNSIGNED NOT NULL COMMENT 'PERSONA QUE HIZO EL REGISTRO',
  `add_fecha` datetime NOT NULL COMMENT 'FECHA QUE SE HIZO EL REGISTRO',
  `mod_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'USUARIO QUE MODIFICO EL REGISTRO',
  `mod_fecha` datetime DEFAULT NULL COMMENT 'FECHA QUE SE MODIFICO EL REGISTRO',
  PRIMARY KEY (`perfil`),
  KEY `perfil_act_i` (`activo`),
  KEY `perfil_add_use_i` (`add_user`),
  KEY `perfil_mod_use_i` (`mod_user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='TABLA QUE ADMINISTRA LOS PERFILES DE ACCESOS';

--
-- Volcado de datos para la tabla `perfil`
--

INSERT INTO `perfil` (`perfil`, `nombre`, `descripcion`, `activo`, `add_user`, `add_fecha`, `mod_user`, `mod_fecha`) VALUES
(1, 'ADMINISTRADOR', 'PERMISO A TODA LA APP', 'Y', 1, '2022-05-25 04:32:08', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil_acceso`
--

DROP TABLE IF EXISTS `perfil_acceso`;
CREATE TABLE IF NOT EXISTS `perfil_acceso` (
  `perfil` int(5) UNSIGNED NOT NULL COMMENT 'PK FK',
  `acceso` int(10) UNSIGNED NOT NULL COMMENT 'PK FK',
  `tipo_acceso` int(3) UNSIGNED NOT NULL COMMENT 'TIPO DE ACCESO AL QUE TIENE PERMITIDO INGRESAR',
  PRIMARY KEY (`perfil`,`acceso`,`tipo_acceso`),
  KEY `perfil_acceso_per_i` (`perfil`),
  KEY `perfil_acceso_acc_i` (`acceso`),
  KEY `perfil_acceso_tip_acc_i` (`tipo_acceso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLA DETALLE DE LOS ACCESOS QUE PERTENECEN A UN PERFIL';

--
-- Volcado de datos para la tabla `perfil_acceso`
--

INSERT INTO `perfil_acceso` (`perfil`, `acceso`, `tipo_acceso`) VALUES
(1, 1, 1),
(1, 2, 1),
(1, 4, 1),
(1, 4, 2),
(1, 4, 3),
(1, 4, 4),
(1, 5, 1),
(1, 5, 2),
(1, 5, 3),
(1, 5, 4),
(1, 26, 1),
(1, 26, 2),
(1, 26, 3),
(1, 26, 4),
(1, 27, 1),
(1, 27, 2),
(1, 27, 3),
(1, 27, 4),
(1, 30, 1),
(1, 30, 2),
(1, 30, 3),
(1, 30, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

DROP TABLE IF EXISTS `persona`;
CREATE TABLE IF NOT EXISTS `persona` (
  `persona` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `nombre1` varchar(30) DEFAULT NULL COMMENT 'PRIMER NOMBRE DE LA PERSONA',
  `nombre2` varchar(30) DEFAULT NULL COMMENT 'OTROS NOMBRES DE LA PERSONA',
  `apellido1` varchar(30) DEFAULT NULL COMMENT 'PRIMER APELLIDO DE LA PERSONA',
  `apellido2` varchar(30) DEFAULT NULL COMMENT 'OTROS APELLIDOS DE LA PERSONA',
  `apellido_casada` varchar(30) DEFAULT NULL COMMENT 'APELLIDO DE LA PERSONA SI EST? CASADA',
  `nombre_usual` varchar(255) NOT NULL COMMENT 'NOMBRE USUAL DE LA PERSONA',
  `sexo` enum('F','M') NOT NULL DEFAULT 'F' COMMENT 'INDICA EL SEXO DE LA PERSONA',
  `pais` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'FK ID DEL PAIS CON EL QUE ESTA RELACIONADA LA PERSONA',
  `activo` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'INDICA SI LA PERSONA SE PODRA USAR A LO LARGO DEL SISTEMA',
  `eliminado` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'IDENTIFICA SI LA PERSONA ESTA ELIMINADA',
  `email` varchar(255) NOT NULL COMMENT 'EMAIL DE LA PERSONA',
  `foto` varchar(255) DEFAULT NULL COMMENT 'PATH DE LA FOTO',
  `add_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'INDICA QUE PERSONA HIZO EL REGISTRO',
  `add_fecha` datetime DEFAULT NULL COMMENT 'FECHA EN QUE SE HIZO EL REGISTRO',
  `mod_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'PERSONA QUE MODIFICO POR ULTIMA VEZ EL REGISTRO',
  `mod_fecha` datetime DEFAULT NULL COMMENT 'FECHA EN QUE SE MODIFICO EL REGISTRO POR ULTIMA VEZ',
  PRIMARY KEY (`persona`),
  KEY `persona_nom_usu_i` (`nombre_usual`),
  KEY `persona_act_i` (`activo`),
  KEY `persona_add_i` (`add_user`),
  KEY `persona_mod_i` (`mod_user`),
  KEY `persona_pai_f` (`pais`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1 COMMENT='ADMINISTRA TODA PERSONA QUE SE QUIERA INGRESAR EN EL SISTEMA';

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`persona`, `nombre1`, `nombre2`, `apellido1`, `apellido2`, `apellido_casada`, `nombre_usual`, `sexo`, `pais`, `activo`, `eliminado`, `email`, `foto`, `add_user`, `add_fecha`, `mod_user`, `mod_fecha`) VALUES
(1, 'Andre', NULL, 'Lopez', NULL, NULL, 'Andre Lopez', 'M', 1, 'Y', 'N', 'andrelopez012@gmail.com', 'attach/usuarios/foto_576c2605dd2a7_download(2).jpg', 1, '2016-06-13 11:17:57', 1, '2017-11-17 10:18:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona_perfil`
--

DROP TABLE IF EXISTS `persona_perfil`;
CREATE TABLE IF NOT EXISTS `persona_perfil` (
  `persona` int(10) NOT NULL COMMENT 'PK FK',
  `perfil` int(5) NOT NULL COMMENT 'PK FK',
  PRIMARY KEY (`persona`,`perfil`),
  KEY `persona_perfil_per_i` (`persona`),
  KEY `persona_perfil_perf_i` (`perfil`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLA QUE INDICA QU? PERFILES TIENE ACCESO UNA PERSONA';

--
-- Volcado de datos para la tabla `persona_perfil`
--

INSERT INTO `persona_perfil` (`persona`, `perfil`) VALUES
(9, 1),
(10, 1),
(16, 1),
(19, 1),
(22, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesion`
--

DROP TABLE IF EXISTS `profesion`;
CREATE TABLE IF NOT EXISTS `profesion` (
  `profesion` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK ID QUE IDENTIFICA EL REGISTRO EN LA TABLA',
  `nombre` varchar(75) NOT NULL COMMENT 'NOMBRE DE LA PROFESION',
  `activo` enum('Y','N') NOT NULL COMMENT 'CAMPO QUE INDICA SI LA PROFESION ESTA ACTIVA O NO',
  `add_user` int(10) UNSIGNED NOT NULL COMMENT 'FK ID DEL USUARIO QUE INSERTO LA TUPLA',
  `add_fecha` datetime NOT NULL COMMENT 'FECHA EN LA QUE SE INSERTO LA TUPLA',
  `mod_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK ID DEL USUARIO QUE MODIFICO LA TUPLA',
  `mod_fecha` datetime DEFAULT NULL COMMENT 'FECHA EN LA QUE SE MODIFICO LA TUPLA',
  PRIMARY KEY (`profesion`),
  KEY `prof_add_use_f` (`add_user`),
  KEY `prof_mod_use_f` (`mod_user`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `profesion`
--

INSERT INTO `profesion` (`profesion`, `nombre`, `activo`, `add_user`, `add_fecha`, `mod_user`, `mod_fecha`) VALUES
(1, 'ABOGADO Y NOTARIO', 'Y', 1, '2016-08-16 15:43:31', 1, '2018-02-15 16:13:03'),
(2, 'CONTADOR PÃ?BLICO Y AUDITOR', 'Y', 1, '2016-08-16 15:43:31', NULL, NULL),
(3, 'PERITO CONTADOR', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(4, 'ACTIVIDADES DE ASESORAMIENTO EMPRESARIAL Y EN MATERIA DE GESTIÃ?N', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(5, 'ECONOMISTA', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(6, 'ARQUITECTO', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(7, 'INGENIERO (EN TODAS SUS RAMAS)', 'N', 1, '2016-08-16 15:43:32', 11, '2020-11-05 12:02:29'),
(8, 'CONSTRUCTORES', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(9, 'ENSAYOS Y ANÃLISIS TÃ?CNICOS', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(10, 'PUBLICIDAD', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(11, 'ACTIVIDADES DE INVESTIGACIÃ?N Y SEGURIDAD', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(12, 'ACTIVIDADES DE LIMPIEZA DE EDIFICIOS', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(13, 'ACTIVIDADES DE FOTOGRAFÃA', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(14, 'ACTIVIDADES DE LA ADMINISTRACIÃ?N PÃ?BLICA EN GENERAL', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(15, 'RELACIONES EXTERIORES', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(16, 'ENSEÃ?ANZA PRIMARIA, PREPRIMARIA Y SECUNDARIA PRIVADA', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(17, 'MÃ?DICO ', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(18, 'OFTALMÃ?LOGO Y OPTOMETRISTA', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(19, 'DENTISTA Y ODONTÃ?LOGO', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(20, 'PSIQUIATRA O PSICÃ?LOGO', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(21, 'QUÃMICO BIÃ?LOGO O FARMACÃ?UTICO', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(22, 'FISIOTERAPISTA, TRAUMATÃ?LOGO', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(23, 'ENFERMERO Y PARAMÃ?DICO', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(24, 'VETERINARIO Y ZOOTECNISTA', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(25, 'ACTIVIDADES DE ORGANIZACIONES RELIGIOSAS', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(26, 'MÃ?SICO', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(27, 'PINTOR', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(28, 'MODELO', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(29, 'ARTISTA', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(30, 'SECRETARIA', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(31, 'POMPAS FÃ?NEBRES Y ACTIVIDADES CONEXAS', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(32, 'JUBILADO Y/O PENSIONADO', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(33, 'ESTUDIANTE', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(34, 'OTRA', 'Y', 1, '2016-08-16 15:43:32', NULL, NULL),
(35, 'INGENIERO EN SISTEMAS', 'Y', 11, '2018-01-31 11:34:54', NULL, NULL),
(36, 'AMA DE CASA', 'Y', 11, '2018-01-31 11:35:03', NULL, NULL),
(37, 'INGENIERO INDUSTRIAL', 'Y', 11, '2018-01-31 11:35:12', NULL, NULL),
(38, 'INGENIERO QUIMICO', 'Y', 11, '2018-01-31 11:38:34', NULL, NULL),
(39, 'INGENIERO MECÃNICO', 'Y', 11, '2018-01-31 11:38:54', NULL, NULL),
(40, 'ESTOMATOLOGO', 'Y', 11, '2018-01-31 11:43:13', NULL, NULL),
(41, 'INGENIERO ELECTRICO', 'Y', 11, '2018-02-02 10:51:41', NULL, NULL),
(42, 'MERCADOLOGO', 'Y', 11, '2018-02-06 10:49:05', NULL, NULL),
(43, 'INGENIERO EN AGRICULTURA', 'Y', 11, '2018-02-21 08:55:37', NULL, NULL),
(44, 'ADMINISTRADORA DE EMPRESAS', 'Y', 11, '2018-02-28 09:21:25', NULL, NULL),
(45, 'ADMINISTRADOR DE EMPRESAS', 'Y', 11, '2018-02-28 09:21:33', NULL, NULL),
(46, 'EJECUTIVO', 'Y', 10, '2018-08-07 09:03:43', NULL, NULL),
(47, 'COMERCIANTE', 'Y', 10, '2018-08-07 09:04:49', NULL, NULL),
(48, 'ADMINISTRADORA DE PATRIMONIO', 'Y', 10, '2018-12-07 17:27:04', NULL, NULL),
(49, 'INGENIERO CIVIL', 'Y', 11, '2019-07-09 11:29:37', NULL, NULL),
(50, 'PSICOLOGO(A) INDUSTRIAL', 'Y', 11, '2019-07-31 10:18:51', NULL, NULL),
(51, 'MÃ?DICO Y CIRUJANO', 'Y', 10, '2019-10-02 14:21:12', NULL, NULL),
(52, 'INTERNACIONALISTA', 'Y', 10, '2019-10-02 14:21:39', NULL, NULL),
(53, 'SOCIÃ?LOGO', 'Y', 10, '2019-10-02 14:21:48', NULL, NULL),
(54, 'POLITÃ?LOGO', 'Y', 10, '2019-10-02 14:21:57', NULL, NULL),
(55, 'QUÃMICO BIÃ?LOGO', 'Y', 10, '2019-10-02 14:22:28', NULL, NULL),
(56, 'QUÃMICO FARMACÃ?UTICO', 'Y', 10, '2019-10-02 14:22:40', NULL, NULL),
(57, 'HUMANISTA', 'Y', 10, '2019-10-02 14:22:54', NULL, NULL),
(58, 'INGENIERO', 'Y', 11, '2020-11-05 12:02:18', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_acceso`
--

DROP TABLE IF EXISTS `tipo_acceso`;
CREATE TABLE IF NOT EXISTS `tipo_acceso` (
  `tipo_acceso` int(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `codigo` varchar(15) NOT NULL COMMENT 'CODIGO PARA IDENTIFICAR EL TIPO DE ACCESO',
  `orden` int(3) NOT NULL COMMENT 'ORDEN EN QUE APARECE EN PANTALLA',
  `activo` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'INDICA SI EST? ACTIVO O NO ESTE TIPO DE ACCESO',
  `add_user` int(10) UNSIGNED NOT NULL COMMENT 'USUARIO QUE AGREGO EL REGISTRO',
  `add_fecha` datetime NOT NULL COMMENT 'FECHA QUE SE AGREGO EL REGISTRO',
  `mod_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'USUARIO QUE MODIFICO EL REGISTRO',
  `mod_fecha` datetime DEFAULT NULL COMMENT 'FECHA QUE SE MODIFICO EL REGISTRO',
  PRIMARY KEY (`tipo_acceso`),
  UNIQUE KEY `tipo_acceso_cod_u` (`codigo`),
  KEY `tipo_acceso_act_k` (`activo`),
  KEY `tipo_acceso_add_use_k` (`add_user`),
  KEY `tipo_acceso_mod_use_k` (`mod_user`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='LISTADO DE TIPOS DE ACCESO QUE EXISTEN EN EL SISTEMA';

--
-- Volcado de datos para la tabla `tipo_acceso`
--

INSERT INTO `tipo_acceso` (`tipo_acceso`, `codigo`, `orden`, `activo`, `add_user`, `add_fecha`, `mod_user`, `mod_fecha`) VALUES
(1, 'consultar', 1, 'Y', 1, '2016-06-13 11:17:59', NULL, NULL),
(2, 'crear', 2, 'Y', 1, '2016-06-13 11:18:04', NULL, NULL),
(3, 'modificar', 3, 'Y', 1, '2016-06-13 11:18:04', NULL, NULL),
(4, 'eliminar', 4, 'Y', 1, '2016-06-13 11:18:04', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_acceso_idioma`
--

DROP TABLE IF EXISTS `tipo_acceso_idioma`;
CREATE TABLE IF NOT EXISTS `tipo_acceso_idioma` (
  `tipo_acceso` int(3) UNSIGNED NOT NULL COMMENT 'PK FK',
  `idioma` int(3) UNSIGNED NOT NULL COMMENT 'PK FK',
  `nombre` varchar(20) NOT NULL COMMENT 'NOMBRE DEL TIPO DE ACCESO EN ESTE IDIOMA',
  PRIMARY KEY (`tipo_acceso`,`idioma`),
  KEY `tipo_acceso_idioma_tip` (`tipo_acceso`),
  KEY `tipo_acceso_idioma_idi` (`idioma`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='LISTADO DE ACCESOS CON SU NOMBRE EN EL IDIOMA INGRESADO';

--
-- Volcado de datos para la tabla `tipo_acceso_idioma`
--

INSERT INTO `tipo_acceso_idioma` (`tipo_acceso`, `idioma`, `nombre`) VALUES
(1, 1, 'Consultar'),
(2, 1, 'Crear'),
(3, 1, 'Modificar'),
(4, 1, 'Eliminar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_producto`
--

DROP TABLE IF EXISTS `tipo_producto`;
CREATE TABLE IF NOT EXISTS `tipo_producto` (
  `tipo_producto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK ID QUE IDENTIFICA EL REGISTRO EN LA TABLA',
  `nombre` varchar(75) COLLATE utf8_unicode_ci NOT NULL COMMENT 'NOMBRE DEL TIPO DE PRODUCTO',
  `activo` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL COMMENT 'INDICA SI EL TIPO DE PRODUCTO ESTA ACTIVO O NO',
  `add_user` int(10) UNSIGNED NOT NULL COMMENT 'FK ID DEL USUARIO QUE INSERTO LA TUPLA',
  `add_fecha` datetime NOT NULL COMMENT 'FECHA EN LA QUE SE INSERTO LA TUPLA',
  `mod_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK ID DEL USUARIO QUE MODIFICO LA TUPLA',
  `mod_fecha` datetime DEFAULT NULL COMMENT 'FECHA EN LA QUE SE MODIFICO LA TUPLA',
  PRIMARY KEY (`tipo_producto`),
  KEY `tip_pro_add_use_f` (`add_user`),
  KEY `tip_pro_mod_use_f` (`mod_user`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_producto`
--

INSERT INTO `tipo_producto` (`tipo_producto`, `nombre`, `activo`, `add_user`, `add_fecha`, `mod_user`, `mod_fecha`) VALUES
(1, 'PAGARES', 'N', 1, '2016-03-11 16:04:50', 11, '2020-11-05 11:53:59'),
(2, 'ACCIONES PREFERENTES', 'Y', 1, '2016-03-11 16:04:50', 1, '2018-02-15 16:10:41'),
(3, 'Acciones Comunes', 'Y', 1, '2016-03-11 16:04:50', NULL, NULL),
(4, 'Operaciones de Reporto', 'Y', 1, '2016-03-11 16:04:50', NULL, NULL),
(5, 'Operaciones de Mercado Secundario', 'Y', 1, '2016-03-11 16:04:50', NULL, NULL),
(6, 'Operaciones de Mercado Primario', 'Y', 1, '2016-03-11 16:04:50', NULL, NULL),
(7, 'Operaciones de Mercado Abierto', 'Y', 1, '2016-03-11 16:04:50', NULL, NULL),
(8, 'Licitaciones PÃºblicas', 'Y', 1, '2016-03-11 16:04:50', NULL, NULL),
(9, 'Licitaciones Privadas', 'Y', 1, '2016-03-11 16:04:50', NULL, NULL),
(10, 'Fideicomisos de InversiÃ³n', 'Y', 1, '2016-03-11 16:04:50', NULL, NULL),
(11, 'BONOS HIPOTECARIOS', 'N', 1, '2016-03-11 16:04:50', 11, '2020-11-05 11:54:23'),
(12, 'FONDOS DE INVERSION', 'Y', 1, '2016-03-11 16:04:50', 11, '2020-11-05 11:45:00'),
(13, 'BONO', 'N', 8, '2020-06-23 08:39:00', 11, '2020-11-05 11:54:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `persona` int(10) UNSIGNED NOT NULL COMMENT 'PK FK',
  `usuario` varchar(75) NOT NULL COMMENT 'ESTE ES EL USUARIO PARA ENTRAR AL SISTEMA',
  `password` varchar(40) NOT NULL COMMENT 'PASSWORD DE INGRESO A LA PAGINA',
  `isAleatorio` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'INDICA SI EL PASSWORD DEL USUARIO FUE GENERADO ALEATORIAMENTE',
  `idioma` int(3) UNSIGNED NOT NULL COMMENT 'IDIOMA EN EL QUE EL USUARIO PREFIERE VER LA PAGINA',
  `tipo` enum('normal','admin','asesor') NOT NULL DEFAULT 'normal' COMMENT 'TIPO DE USUARIO',
  `multi_session` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'INDICA SI PUEDE ESTAR LOGINEADO EN VARIAS COMPUTADORAS A LA VEZ',
  `bloqueado` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'SI ESTA BLOQUEADO NO PUEDE INGRESAR A LA PAGINA',
  `codigo` varchar(32) DEFAULT NULL COMMENT 'CODIGO PARA PODER REESTABLECER CONTRASEÃA DE ACCESO',
  `add_user` int(10) UNSIGNED NOT NULL COMMENT 'INDICA QUE PERSONA HIZO EL REGISTRO',
  `add_fecha` datetime NOT NULL COMMENT 'INDICA LA FECHA EN QUE SE REALIZO EL REGISTRO',
  `mod_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'PERSONA QUE MODIFICO EL REGISTRO POR ULTIMA VEZ',
  `mod_fecha` datetime DEFAULT NULL COMMENT 'FECHA EN QUE SE HIZO LA ULTIMA MODIFICACION',
  PRIMARY KEY (`persona`),
  KEY `usuario_idi_i` (`idioma`),
  KEY `usuario_tip_i` (`tipo`),
  KEY `usuario_blo_i` (`bloqueado`),
  KEY `usuario_add_use_i` (`add_user`),
  KEY `usuario_mod_use_i` (`mod_user`),
  KEY `usuario_mul_i` (`multi_session`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLA QUE ADMINISTRA LOS USUARIOS ';

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`persona`, `usuario`, `password`, `isAleatorio`, `idioma`, `tipo`, `multi_session`, `bloqueado`, `codigo`, `add_user`, `add_fecha`, `mod_user`, `mod_fecha`) VALUES
(1, 'anatareno', '098f6bcd4621d373cade4e832627b4f6', 'N', 1, 'admin', 'Y', 'N', NULL, 1, '2016-06-13 11:17:59', 1, '2016-06-23 12:10:13');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `acceso`
--
ALTER TABLE `acceso`
  ADD CONSTRAINT `acceso_acc_per_f` FOREIGN KEY (`acceso_pertenece`) REFERENCES `acceso` (`acceso`),
  ADD CONSTRAINT `acceso_add_use_f` FOREIGN KEY (`add_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `acceso_mod_f` FOREIGN KEY (`modulo`) REFERENCES `modulo` (`modulo`),
  ADD CONSTRAINT `acceso_mod_use_f` FOREIGN KEY (`mod_user`) REFERENCES `persona` (`persona`);

--
-- Filtros para la tabla `acceso_idioma`
--
ALTER TABLE `acceso_idioma`
  ADD CONSTRAINT `acceso_idioma_acc_f` FOREIGN KEY (`acceso`) REFERENCES `acceso` (`acceso`),
  ADD CONSTRAINT `acceso_idioma_idi_f` FOREIGN KEY (`idioma`) REFERENCES `idioma` (`idioma`);

--
-- Filtros para la tabla `acceso_tipo_permitido`
--
ALTER TABLE `acceso_tipo_permitido`
  ADD CONSTRAINT `acceso_tipo_permitido_acc_f` FOREIGN KEY (`acceso`) REFERENCES `acceso` (`acceso`),
  ADD CONSTRAINT `acceso_tipo_permitido_tip_acc_f` FOREIGN KEY (`tipo_acceso`) REFERENCES `tipo_acceso` (`tipo_acceso`);

--
-- Filtros para la tabla `agencia`
--
ALTER TABLE `agencia`
  ADD CONSTRAINT `age_add_use_f` FOREIGN KEY (`add_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `age_emp_f` FOREIGN KEY (`empresa`) REFERENCES `empresa` (`empresa`),
  ADD CONSTRAINT `age_mod_use_f` FOREIGN KEY (`mod_user`) REFERENCES `persona` (`persona`);

--
-- Filtros para la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD CONSTRAINT `configuracion_add_use_f` FOREIGN KEY (`add_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `configuracion_mod_f` FOREIGN KEY (`modulo`) REFERENCES `modulo` (`modulo`),
  ADD CONSTRAINT `configuracion_mod_use_f` FOREIGN KEY (`mod_user`) REFERENCES `persona` (`persona`);

--
-- Filtros para la tabla `configuracion_idioma`
--
ALTER TABLE `configuracion_idioma`
  ADD CONSTRAINT `configuracion_idioma_idi_f` FOREIGN KEY (`idioma`) REFERENCES `idioma` (`idioma`),
  ADD CONSTRAINT `configuracion_idioma_mod_f` FOREIGN KEY (`modulo`) REFERENCES `modulo` (`modulo`);

--
-- Filtros para la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD CONSTRAINT `dep_add_use_f` FOREIGN KEY (`add_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `dep_mod_use_f` FOREIGN KEY (`mod_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `dep_pai_f` FOREIGN KEY (`pais`) REFERENCES `pais` (`pais`);

--
-- Filtros para la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD CONSTRAINT `emp_add_use_f` FOREIGN KEY (`add_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `emp_mod_use_f` FOREIGN KEY (`mod_user`) REFERENCES `persona` (`persona`);

--
-- Filtros para la tabla `idioma`
--
ALTER TABLE `idioma`
  ADD CONSTRAINT `idioma_add_use_f` FOREIGN KEY (`add_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `idioma_mod_use_f` FOREIGN KEY (`mod_user`) REFERENCES `persona` (`persona`);

--
-- Filtros para la tabla `lang`
--
ALTER TABLE `lang`
  ADD CONSTRAINT `lang_add_use_f` FOREIGN KEY (`add_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `lang_mod_f` FOREIGN KEY (`modulo`) REFERENCES `modulo` (`modulo`),
  ADD CONSTRAINT `lang_mod_use_f` FOREIGN KEY (`mod_user`) REFERENCES `persona` (`persona`);

--
-- Filtros para la tabla `lang_idioma`
--
ALTER TABLE `lang_idioma`
  ADD CONSTRAINT `lang_idioma_idi_f` FOREIGN KEY (`idioma`) REFERENCES `idioma` (`idioma`),
  ADD CONSTRAINT `lang_idioma_mod_f` FOREIGN KEY (`modulo`) REFERENCES `modulo` (`modulo`);

--
-- Filtros para la tabla `lugar`
--
ALTER TABLE `lugar`
  ADD CONSTRAINT `lug_add_use_f` FOREIGN KEY (`add_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `lug_mod_use_f` FOREIGN KEY (`mod_user`) REFERENCES `persona` (`persona`);

--
-- Filtros para la tabla `mandatario`
--
ALTER TABLE `mandatario`
  ADD CONSTRAINT `man_add_use_f` FOREIGN KEY (`add_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `man_direccion_departamento_f` FOREIGN KEY (`direccion_departamento`) REFERENCES `departamento` (`departamento`),
  ADD CONSTRAINT `man_direccion_pais_f` FOREIGN KEY (`direccion_pais`) REFERENCES `pais` (`pais`),
  ADD CONSTRAINT `man_empresa_f` FOREIGN KEY (`empresa`) REFERENCES `empresa` (`empresa`),
  ADD CONSTRAINT `man_mod_use_f` FOREIGN KEY (`mod_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `man_nacionalidad_f` FOREIGN KEY (`nacionalidad`) REFERENCES `pais` (`pais`);

--
-- Filtros para la tabla `modulo`
--
ALTER TABLE `modulo`
  ADD CONSTRAINT `modulo_add_use_f` FOREIGN KEY (`add_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `modulo_mod_use_f` FOREIGN KEY (`mod_user`) REFERENCES `persona` (`persona`);

--
-- Filtros para la tabla `modulo_dependencia`
--
ALTER TABLE `modulo_dependencia`
  ADD CONSTRAINT `modulo_dependencia_dep_f` FOREIGN KEY (`dependencia`) REFERENCES `modulo` (`modulo`),
  ADD CONSTRAINT `modulo_dependencia_mod_f` FOREIGN KEY (`modulo`) REFERENCES `modulo` (`modulo`);

--
-- Filtros para la tabla `modulo_idioma`
--
ALTER TABLE `modulo_idioma`
  ADD CONSTRAINT `modulo_idioma_idi_f` FOREIGN KEY (`idioma`) REFERENCES `idioma` (`idioma`),
  ADD CONSTRAINT `modulo_idioma_mod_f` FOREIGN KEY (`modulo`) REFERENCES `modulo` (`modulo`);

--
-- Filtros para la tabla `moneda`
--
ALTER TABLE `moneda`
  ADD CONSTRAINT `mon_add_use_f` FOREIGN KEY (`add_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `mon_mod_use_f` FOREIGN KEY (`mod_user`) REFERENCES `persona` (`persona`);

--
-- Filtros para la tabla `municipio`
--
ALTER TABLE `municipio`
  ADD CONSTRAINT `mun_add_use_f` FOREIGN KEY (`add_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `mun_dep_f` FOREIGN KEY (`departamento`) REFERENCES `departamento` (`departamento`),
  ADD CONSTRAINT `mun_mod_use_f` FOREIGN KEY (`mod_user`) REFERENCES `persona` (`persona`);

--
-- Filtros para la tabla `pais`
--
ALTER TABLE `pais`
  ADD CONSTRAINT `pais_add_use_f` FOREIGN KEY (`add_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `pais_mod_use_f` FOREIGN KEY (`mod_user`) REFERENCES `persona` (`persona`);

--
-- Filtros para la tabla `perfil`
--
ALTER TABLE `perfil`
  ADD CONSTRAINT `perfil_add_use_f` FOREIGN KEY (`add_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `perfil_mod_use_f` FOREIGN KEY (`mod_user`) REFERENCES `persona` (`persona`);

--
-- Filtros para la tabla `perfil_acceso`
--
ALTER TABLE `perfil_acceso`
  ADD CONSTRAINT `perfil_acceso_acc_f` FOREIGN KEY (`acceso`) REFERENCES `acceso` (`acceso`),
  ADD CONSTRAINT `perfil_acceso_per_f` FOREIGN KEY (`perfil`) REFERENCES `perfil` (`perfil`),
  ADD CONSTRAINT `perfil_acceso_tip_acc_f` FOREIGN KEY (`tipo_acceso`) REFERENCES `tipo_acceso` (`tipo_acceso`);

--
-- Filtros para la tabla `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `persona_add_use_f` FOREIGN KEY (`add_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `persona_mod_use_f` FOREIGN KEY (`mod_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `persona_pai_f` FOREIGN KEY (`pais`) REFERENCES `pais` (`pais`);

--
-- Filtros para la tabla `profesion`
--
ALTER TABLE `profesion`
  ADD CONSTRAINT `prof_add_use_f` FOREIGN KEY (`add_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `prof_mod_use_f` FOREIGN KEY (`mod_user`) REFERENCES `persona` (`persona`);

--
-- Filtros para la tabla `tipo_acceso`
--
ALTER TABLE `tipo_acceso`
  ADD CONSTRAINT `tipo_acceso_add_use_f` FOREIGN KEY (`add_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `tipo_acceso_mod_use_f` FOREIGN KEY (`mod_user`) REFERENCES `persona` (`persona`);

--
-- Filtros para la tabla `tipo_acceso_idioma`
--
ALTER TABLE `tipo_acceso_idioma`
  ADD CONSTRAINT `tipo_acceso_idioma_idi_f` FOREIGN KEY (`idioma`) REFERENCES `idioma` (`idioma`),
  ADD CONSTRAINT `tipo_acceso_idioma_tip_acc_f` FOREIGN KEY (`tipo_acceso`) REFERENCES `tipo_acceso` (`tipo_acceso`);

--
-- Filtros para la tabla `tipo_producto`
--
ALTER TABLE `tipo_producto`
  ADD CONSTRAINT `tip_pro_add_use_f` FOREIGN KEY (`add_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `tip_pro_mod_use_f` FOREIGN KEY (`mod_user`) REFERENCES `persona` (`persona`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_add_use_f` FOREIGN KEY (`add_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `usuario_idi_f` FOREIGN KEY (`idioma`) REFERENCES `idioma` (`idioma`),
  ADD CONSTRAINT `usuario_mod_use_f` FOREIGN KEY (`mod_user`) REFERENCES `persona` (`persona`),
  ADD CONSTRAINT `usuario_per_f` FOREIGN KEY (`persona`) REFERENCES `persona` (`persona`);

DELIMITER $$
--
-- Eventos
--
DROP EVENT `e_cliente_vencido`$$
CREATE DEFINER=`idctest`@`localhost` EVENT `e_cliente_vencido` ON SCHEDULE EVERY 1 DAY STARTS '2016-06-07 00:00:00' ON COMPLETION NOT PRESERVE ENABLE COMMENT 'REALIZA EL CAMBIO DE ESTADO DE LOS CLIENTES A VENCIDO CUANDO LA' DO CALL sp_cliente_vencido$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
