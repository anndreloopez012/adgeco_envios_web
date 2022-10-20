INSERT INTO modulo (modulo,codigo,orden,publico,privado,activo,icono,add_user,add_fecha) VALUES (5,'configuracion',2,'N','Y','Y','fa fa-gears',1,NOW());
INSERT INTO modulo_idioma (modulo,idioma,nombre) VALUES (5,1,'Configuracion');

INSERT INTO acceso (acceso,modulo,codigo,orden,acceso_pertenece,path,publico,privado,activo,add_user,add_fecha) VALUES (22,5,'configuracion_mandatario',1,NULL,'configuracion_mandatario.php','N','Y','Y',1,NOW());
INSERT INTO acceso_idioma (acceso,idioma,nombre_menu,nombre_pantalla) VALUES (22,1,'Mandatarios','Mandatarios');
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (22,1);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (22,2);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (22,3);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (22,4);

INSERT INTO acceso (acceso,modulo,codigo,orden,acceso_pertenece,path,publico,privado,activo,add_user,add_fecha) VALUES (23,5,'configuracion_representante_legal_cvn',2,NULL,'configuracion_representante_legal_cvn.php','N','Y','Y',1,NOW());
INSERT INTO acceso_idioma (acceso,idioma,nombre_menu,nombre_pantalla) VALUES (23,1,'Representante legal CVN','Representante legal CVN');
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (23,1);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (23,2);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (23,3);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (23,4);

INSERT INTO acceso (acceso,modulo,codigo,orden,acceso_pertenece,path,publico,privado,activo,add_user,add_fecha) VALUES (24,5,'configuracion_cedula_verificacion_validacion',3,NULL,'configuracion_cedula_verificacion_validacion.php','N','Y','Y',1,NOW());
INSERT INTO acceso_idioma (acceso,idioma,nombre_menu,nombre_pantalla) VALUES (24,1,'Responsable cedula de verificacion y validacion','Responsable cedula de verificacion y validacion');
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (24,1);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (24,2);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (24,3);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (24,4);

CREATE TABLE mandatario(
    mandatario INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK ID QUE IDENTIFICA EL REGISTRO EN LA TABLA',
    empresa INT UNSIGNED NOT NULL COMMENT 'FK ID DE LA EMPRESA, EMPRESA DEL MANDATARIO',
    nombre VARCHAR(75) NOT NULL COMMENT 'NOMBRE DEL MANDATARIO',
    puesto VARCHAR(75) NOT NULL COMMENT 'PUESTO DEL MANDATARIO',
    identificacion_numero VARCHAR(75) NOT NULL COMMENT 'NUMERO DE IDENTIFICACION DEL MANDATARIO',
    fecha_nacimiento DATE NOT NULL COMMENT 'FECHA DE NACIMIENTO DEL MANDATARIO',
    estado_civil ENUM('SOLTERO','CASADO','VIUDO','DIVORCIADO') NOT NULL COMMENT 'ESTADO CIVIL DEL MANDATARIO',
    nacionalidad SMALLINT UNSIGNED NOT NULL COMMENT 'FK ID DEL PAIS, NACIONALIDAD DEL MANDATARIO',
    profesion VARCHAR(75) NOT NULL COMMENT 'PROFESION DEL MANDATARIO',
    direccion VARCHAR(255) NOT NULL COMMENT 'DIRECCION DEL MANDATARIO',
    direccion_pais SMALLINT UNSIGNED NOT NULL COMMENT 'FK ID DEL PAIS, DIRECCION PAIS DEL MANDATARIO',
    direccion_departamento INT UNSIGNED NULL COMMENT 'FK ID DEL DEPARTAMENTO, DIRECCION DEPARTAMENTO DEL MANDATARIO',
    escritura_publica_numero VARCHAR(75) NOT NULL COMMENT 'NUMERO DE ESCRITURA PUBLICA',
    escritura_publica_fecha DATE NOT NULL COMMENT 'FECHA DE ESCRITURA PUBLICA',
    notario VARCHAR(75) NOT NULL COMMENT 'NOTARIO QUE AUTORIZO LA ESCRITURA PUBLICA',
    registro_electronico_poderes_numero VARCHAR(75) NOT NULL COMMENT 'NUMERO DE REGISTRO ELECTRONICO DE PODERES',
    registro_electronico_poderes_fecha DATE NOT NULL COMMENT 'FECHA DE REGISTRO ELECTRONICO DE PODERES',
    registro_mercantil_mandatos_numero VARCHAR(75) NOT NULL COMMENT 'NUMERO DE REGISTRO MERCANTIL DE MANDATOS',
    registro_mercantil_mandatos_folio VARCHAR(75) NOT NULL COMMENT 'FOLIO DE REGISTRO MERCANTIL DE MANDATOS',
    registro_mercantil_mandatos_libro VARCHAR(75) NOT NULL COMMENT 'LIBRO DE REGISTRO MERCANTIL DE MANDATOS',
    por_defecto ENUM('Y','N') NOT NULL COMMENT 'INDICA SI ES POR DEFECTO',
    activo ENUM('Y','N') NOT NULL COMMENT 'INDICA SI ESTA ACTIVO',
    add_user INT(10) UNSIGNED NOT NULL COMMENT 'FK ID DEL USUARIO QUE INSERTO LA TUPLA',
    add_fecha DATETIME NOT NULL COMMENT 'FECHA EN LA QUE SE INSERTO LA TUPLA',
    mod_user INT(10) UNSIGNED NULL COMMENT 'FK ID DEL USUARIO QUE MODIFICO LA TUPLA',
    mod_fecha DATETIME NULL COMMENT 'FECHA EN LA QUE SE MODIFICO LA TUPLA',
    PRIMARY KEY(mandatario),
    CONSTRAINT man_empresa_f FOREIGN KEY (empresa) REFERENCES empresa (empresa) ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT man_nacionalidad_f FOREIGN KEY (nacionalidad) REFERENCES pais (pais) ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT man_direccion_pais_f FOREIGN KEY (direccion_pais) REFERENCES pais (pais) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT man_direccion_departamento_f FOREIGN KEY (direccion_departamento) REFERENCES departamento (departamento) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT man_add_use_f FOREIGN KEY (add_user) REFERENCES persona (persona) ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT man_mod_use_f FOREIGN KEY (mod_user) REFERENCES persona (persona) ON UPDATE RESTRICT ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE representante_legal_cvn(
    representante_legal_cvn INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK ID QUE IDENTIFICA EL REGISTRO EN LA TABLA',
    nombre VARCHAR(75) NOT NULL COMMENT 'NOMBRE DEL REPRESENTANTE LEGAL CVN',
    puesto VARCHAR(75) NOT NULL COMMENT 'PUESTO DEL REPRESENTANTE LEGAL CVN',
    identificacion_numero VARCHAR(75) NOT NULL COMMENT 'NUMERO DE IDENTIFICACION DEL REPRESENTANTE LEGAL CVN',
    fecha_nacimiento DATE NOT NULL COMMENT 'FECHA DE NACIMIENTO DEL REPRESENTANTE LEGAL CVN',
    estado_civil ENUM('SOLTERO','CASADO','VIUDO','DIVORCIADO') NOT NULL COMMENT 'ESTADO CIVIL DEL REPRESENTANTE LEGAL CVN',
    nacionalidad SMALLINT UNSIGNED NOT NULL COMMENT 'FK ID DEL PAIS, NACIONALIDAD DEL REPRESENTANTE LEGAL CVN',
    profesion VARCHAR(75) NOT NULL COMMENT 'PROFESION DEL REPRESENTANTE LEGAL CVN',
    direccion VARCHAR(255) NOT NULL COMMENT 'DIRECCION DEL REPRESENTANTE LEGAL CVN',
    direccion_pais SMALLINT UNSIGNED NOT NULL COMMENT 'FK ID DEL PAIS, DIRECCION PAIS DEL REPRESENTANTE LEGAL CVN',
    direccion_departamento INT UNSIGNED NULL COMMENT 'FK ID DEL DEPARTAMENTO, DIRECCION DEPARTAMENTO DEL REPRESENTANTE LEGAL CVN',
    nombramiento_fecha DATE NOT NULL COMMENT 'FECHA DE NOMBRAMIENTO',
    nombramiento_notario VARCHAR(75) NOT NULL COMMENT 'NOTARIO QUE AUTORIZO LA ESCRITURA PUBLICA',
    nombramiento_registro_mercantil_numero VARCHAR(75) NOT NULL COMMENT 'NUMERO DE REGISTRO MERCANTIL DE MANDATOS',
    nombramiento_registro_mercantil_folio VARCHAR(75) NOT NULL COMMENT 'NUMERO DE REGISTRO MERCANTIL DE MANDATOS',
    nombramiento_registro_mercantil_libro VARCHAR(75) NOT NULL COMMENT 'NUMERO DE REGISTRO MERCANTIL DE MANDATOS',
    por_defecto ENUM('Y','N') NOT NULL COMMENT 'INDICA SI ES POR DEFECTO',
    activo ENUM('Y','N') NOT NULL COMMENT 'INDICA SI ESTA ACTIVO',
    add_user INT(10) UNSIGNED NOT NULL COMMENT 'FK ID DEL USUARIO QUE INSERTO LA TUPLA',
    add_fecha DATETIME NOT NULL COMMENT 'FECHA EN LA QUE SE INSERTO LA TUPLA',
    mod_user INT(10) UNSIGNED NULL COMMENT 'FK ID DEL USUARIO QUE MODIFICO LA TUPLA',
    mod_fecha DATETIME NULL COMMENT 'FECHA EN LA QUE SE MODIFICO LA TUPLA',
    PRIMARY KEY(representante_legal_cvn),
    CONSTRAINT rep_leg_cvn_nacionalidad_f FOREIGN KEY (nacionalidad) REFERENCES pais (pais) ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT rep_leg_cvn_direccion_pais_f FOREIGN KEY (direccion_pais) REFERENCES pais (pais) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT rep_leg_cvn_direccion_departamento_f FOREIGN KEY (direccion_departamento) REFERENCES departamento (departamento) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT rep_leg_cvn_add_use_f FOREIGN KEY (add_user) REFERENCES persona (persona) ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT rep_leg_cvn_mod_use_f FOREIGN KEY (mod_user) REFERENCES persona (persona) ON UPDATE RESTRICT ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE configuracion_cedula_verificacion_validacion(
    configuracion_cedula_verificacion_validacion INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK ID QUE IDENTIFICA EL REGISTRO EN LA TABLA',
    empresa INT UNSIGNED NOT NULL COMMENT 'FK ID DE LA EMPRESA, EMPRESA DE LA CEDULA DE VERIFICACION Y VALIDADCION DE DATOS',
    responsable VARCHAR(75) NOT NULL COMMENT 'NOMBRE DEL RESPONSABLE DE CEDULA DE VERICICACION Y VALIDACION DE DATOS',
    puesto VARCHAR(75) NOT NULL COMMENT 'NOMBRE DEL RESPONSABLE DE CEDULA DE VERICICACION Y VALIDACION DE DATOS',
    por_defecto ENUM('Y','N') NOT NULL COMMENT 'INDICA SI ES POR DEFECTO',
    activo ENUM('Y','N') NOT NULL COMMENT 'INDICA SI ESTA ACTIVO',
    add_user INT(10) UNSIGNED NOT NULL COMMENT 'FK ID DEL USUARIO QUE INSERTO LA TUPLA',
    add_fecha DATETIME NOT NULL COMMENT 'FECHA EN LA QUE SE INSERTO LA TUPLA',
    mod_user INT(10) UNSIGNED NULL COMMENT 'FK ID DEL USUARIO QUE MODIFICO LA TUPLA',
    mod_fecha DATETIME NULL COMMENT 'FECHA EN LA QUE SE MODIFICO LA TUPLA',
    PRIMARY KEY(configuracion_cedula_verificacion_validacion),
    CONSTRAINT con_ced_ver_val_empresa_f FOREIGN KEY (empresa) REFERENCES empresa (empresa) ON UPDATE RESTRICT ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT INTO acceso (acceso,modulo,codigo,orden,acceso_pertenece,path,publico,privado,activo,add_user,add_fecha) VALUES (26,5,'configuracion_empresa',4,NULL,'configuracion_empresa.php','N','Y','Y',1,NOW());
INSERT INTO acceso_idioma (acceso,idioma,nombre_menu,nombre_pantalla) VALUES (26,1,'Empresas','Empresas');
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (26,1);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (26,2);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (26,3);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (26,4);

INSERT INTO acceso (acceso,modulo,codigo,orden,acceso_pertenece,path,publico,privado,activo,add_user,add_fecha) VALUES (27,5,'configuracion_agencia',5,NULL,'configuracion_agencia.php','N','Y','Y',1,NOW());
INSERT INTO acceso_idioma (acceso,idioma,nombre_menu,nombre_pantalla) VALUES (27,1,'Agencias','Agencias');
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (27,1);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (27,2);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (27,3);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (27,4);

INSERT INTO acceso (acceso,modulo,codigo,orden,acceso_pertenece,path,publico,privado,activo,add_user,add_fecha) VALUES (28,5,'configuracion_tipo_producto',6,NULL,'configuracion_tipo_producto.php','N','Y','Y',1,NOW());
INSERT INTO acceso_idioma (acceso,idioma,nombre_menu,nombre_pantalla) VALUES (28,1,'Tipo de producto','Tipo de producto');
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (28,1);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (28,2);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (28,3);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (28,4);

INSERT INTO acceso (acceso,modulo,codigo,orden,acceso_pertenece,path,publico,privado,activo,add_user,add_fecha) VALUES (29,5,'configuracion_producto',7,NULL,'configuracion_producto.php','N','Y','Y',1,NOW());
INSERT INTO acceso_idioma (acceso,idioma,nombre_menu,nombre_pantalla) VALUES (29,1,'Producto','Producto');
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (29,1);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (29,2);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (29,3);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (29,4);

INSERT INTO acceso (acceso,modulo,codigo,orden,acceso_pertenece,path,publico,privado,activo,add_user,add_fecha) VALUES (30,5,'configuracion_pais',8,NULL,'configuracion_pais.php','N','Y','Y',1,NOW());
INSERT INTO acceso_idioma (acceso,idioma,nombre_menu,nombre_pantalla) VALUES (30,1,'Paises','Paises');
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (30,1);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (30,2);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (30,3);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (30,4);

INSERT INTO acceso (acceso,modulo,codigo,orden,acceso_pertenece,path,publico,privado,activo,add_user,add_fecha) VALUES (31,5,'configuracion_tiempo_estado',9,NULL,'configuracion_tiempo_estado.php','N','Y','Y',1,NOW());
INSERT INTO acceso_idioma (acceso,idioma,nombre_menu,nombre_pantalla) VALUES (31,1,'Tiempos por estado','Tiempos por estado');
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (31,1);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (31,3);

INSERT INTO acceso (acceso,modulo,codigo,orden,acceso_pertenece,path,publico,privado,activo,add_user,add_fecha) VALUES (32,5,'configuracion_profesion',10,NULL,'configuracion_profesion.php','N','Y','Y',1,NOW());
INSERT INTO acceso_idioma (acceso,idioma,nombre_menu,nombre_pantalla) VALUES (32,1,'Profesiones','Profesiones');
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (32,1);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (32,2);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (32,3);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (32,4);

UPDATE modulo SET orden = 3 WHERE modulo = 5;

CREATE TABLE profesion(
    profesion INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK ID QUE IDENTIFICA EL REGISTRO EN LA TABLA',
    nombre VARCHAR(75) NOT NULL COMMENT 'NOMBRE DE LA PROFESION',
    activo ENUM('Y','N') NOT NULL COMMENT 'CAMPO QUE INDICA SI LA PROFESION ESTA ACTIVA O NO',
    add_user INT(10) UNSIGNED NOT NULL COMMENT 'FK ID DEL USUARIO QUE INSERTO LA TUPLA',
    add_fecha DATETIME NOT NULL COMMENT 'FECHA EN LA QUE SE INSERTO LA TUPLA',
    mod_user INT(10) UNSIGNED NULL COMMENT 'FK ID DEL USUARIO QUE MODIFICO LA TUPLA',
    mod_fecha DATETIME NULL COMMENT 'FECHA EN LA QUE SE MODIFICO LA TUPLA',
    PRIMARY KEY(profesion),
    CONSTRAINT prof_add_use_f FOREIGN KEY (add_user) REFERENCES persona (persona) ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT prof_mod_use_f FOREIGN KEY (mod_user) REFERENCES persona (persona) ON UPDATE RESTRICT ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT INTO profesion VALUES ('1', 'ABOGADO Y NOTARIO', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('2', 'CONTADOR PuBLICO Y AUDITOR', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('3', 'PERITO CONTADOR', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('4', 'ACTIVIDADES DE ASESORAMIENTO EMPRESARIAL Y EN MATERIA DE GESTIoN', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('5', 'ECONOMISTA', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('6', 'ARQUITECTO', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('7', 'INGENIERO (EN TODAS SUS RAMAS)', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('8', 'CONSTRUCTORES', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('9', 'ENSAYOS Y ANaLISIS TeCNICOS', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('10', 'PUBLICIDAD', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('11', 'ACTIVIDADES DE INVESTIGACIoN Y SEGURIDAD', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('12', 'ACTIVIDADES DE LIMPIEZA DE EDIFICIOS', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('13', 'ACTIVIDADES DE FOTOGRAFiA', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('14', 'ACTIVIDADES DE LA ADMINISTRACIoN PuBLICA EN GENERAL', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('15', 'RELACIONES EXTERIORES', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('16', 'ENSEÑANZA PRIMARIA, PREPRIMARIA Y SECUNDARIA PRIVADA', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('17', 'MeDICO ', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('18', 'OFTALMoLOGO Y OPTOMETRISTA', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('19', 'DENTISTA Y ODONToLOGO', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('20', 'PSIQUIATRA O PSICoLOGO', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('21', 'QUiMICO BIoLOGO O FARMACeUTICO', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('22', 'FISIOTERAPISTA, TRAUMAToLOGO', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('23', 'ENFERMERO Y PARAMeDICO', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('24', 'VETERINARIO Y ZOOTECNISTA', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('25', 'ACTIVIDADES DE ORGANIZACIONES RELIGIOSAS', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('26', 'MuSICO', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('27', 'PINTOR', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('28', 'MODELO', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('29', 'ARTISTA', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('30', 'SECRETARIA', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('31', 'POMPAS FuNEBRES Y ACTIVIDADES CONEXAS', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('32', 'JUBILADO Y/O PENSIONADO', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('33', 'ESTUDIANTE', 'Y', '1', NOW(), NULL, NULL);
INSERT INTO profesion VALUES ('34', 'OTRA', 'Y', '1', NOW(), NULL, NULL);

UPDATE acceso SET orden = '1' WHERE acceso = 26;
UPDATE acceso SET orden = '2' WHERE acceso = 27;
UPDATE acceso SET orden = '3' WHERE acceso = 22;
UPDATE acceso SET orden = '4' WHERE acceso = 23;
UPDATE acceso SET orden = '5' WHERE acceso = 24;
/* INICIO ESAMAYOA 26-01-18*/
ALTER TABLE pais ADD COLUMN predeterminado  enum('N','Y') NOT NULL DEFAULT 'N' AFTER activo;
/*HASTA AQUI ESAMAYOA*/
/* INICIO ANDRE 26-01-18*/
CREATE TABLE `grupo`  (
  `grupo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK ID QUE IDENTIFICA EL REGISTRO EN LA TABLA',
  `nombre` varchar(75) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'NOMBRE DEL GRUPO',
  `activo` enum('Y','N') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'CAMPO QUE INDICA SI EL GRUPO ESTA ACTIVA O NO',
  `add_user` int(10) UNSIGNED NOT NULL COMMENT 'FK ID DEL USUARIO QUE INSERTO LA TUPLA',
  `add_fecha` datetime NOT NULL COMMENT 'FECHA EN LA QUE SE INSERTO LA TUPLA',
  `mod_user` int(10) UNSIGNED NULL DEFAULT NULL COMMENT 'FK ID DEL USUARIO QUE MODIFICO LA TUPLA',
  `mod_fecha` datetime NULL DEFAULT NULL COMMENT 'FECHA EN LA QUE SE MODIFICO LA TUPLA',
  PRIMARY KEY (`grupo`) USING BTREE,
  INDEX `grup_add_use_f`(`add_user`) USING BTREE,
  INDEX `grup_mod_use_f`(`mod_user`) USING BTREE,
  CONSTRAINT `grup_add_use_f` FOREIGN KEY (`add_user`) REFERENCES `persona` (`persona`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `grup_mod_use_f` FOREIGN KEY (`mod_user`) REFERENCES `persona` (`persona`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

ALTER TABLE `grupo` ADD `descripcion` TEXT CHARACTER SET latin1 COLLATE latin1_spanish_ci NULL DEFAULT NULL COMMENT 'COMENTARIO DEL GRUPO' AFTER `nombre`;
/*HASTA AQUI ANDRE*/
/*HASTA AQUI ADGECOTEST*/
/*HASTA AQUI ADGECOPRODUCCION*/