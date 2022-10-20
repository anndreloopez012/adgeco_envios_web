CREATE TABLE persona (
  persona INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
  nombre1 VARCHAR(30) DEFAULT NULL COMMENT 'PRIMER NOMBRE DE LA PERSONA',
  nombre2 VARCHAR(30) DEFAULT NULL COMMENT 'OTROS NOMBRES DE LA PERSONA',
  apellido1 VARCHAR(30) DEFAULT NULL COMMENT 'PRIMER APELLIDO DE LA PERSONA',
  apellido2 VARCHAR(30) DEFAULT NULL COMMENT 'OTROS APELLIDOS DE LA PERSONA',
  apellido_casada VARCHAR(30) DEFAULT NULL COMMENT 'APELLIDO DE LA PERSONA SI ESTÁ CASADA',
  nombre_usual VARCHAR(255) NOT NULL COMMENT 'NOMBRE USUAL DE LA PERSONA',
  sexo ENUM('F','M') NOT NULL DEFAULT 'F' COMMENT 'INDICA EL SEXO DE LA PERSONA',
  activo ENUM( 'Y', 'N' ) NOT NULL DEFAULT 'Y' COMMENT 'INDICA SI LA PERSONA SE PODRA USAR A LO LARGO DEL SISTEMA',
  email VARCHAR( 300 ) NOT NULL,
  add_user INT(10) UNSIGNED DEFAULT NULL COMMENT 'INDICA QUE PERSONA HIZO EL REGISTRO',
  add_fecha datetime DEFAULT NULL COMMENT 'FECHA EN QUE SE HIZO EL REGISTRO',
  mod_user INT(10) UNSIGNED DEFAULT NULL COMMENT 'PERSONA QUE MODIFICO POR ULTIMA VEZ EL REGISTRO',
  mod_fecha datetime DEFAULT NULL COMMENT 'FECHA EN QUE SE MODIFICO EL REGISTRO POR ULTIMA VEZ',
  PRIMARY KEY (persona),
  INDEX persona_nom_usu_i ( nombre_usual ),
  INDEX persona_act_i ( activo ),
  INDEX persona_add_i ( add_user ),
  INDEX persona_mod_i ( mod_user )
) ENGINE=InnoDB CHARSET=latin1 COMMENT='ADMINISTRA TODA PERSONA QUE SE QUIERA INGRESAR EN EL SISTEMA SIN SER USUARIO';

INSERT INTO persona (persona, nombre1, nombre2, apellido1, apellido2, apellido_casada, nombre_usual, sexo, activo, email, add_user, add_fecha, mod_user, mod_fecha)
VALUES (1, 'Homeland', NULL, 'Webmaster', NULL, NULL, 'Homeland Webmaster', 'M', 'Y', 'webmaster@homeland.com.gt', NULL, NOW(), NULL, NULL);

UPDATE persona SET add_user = '1', add_fecha = NOW( ) WHERE persona.persona = 1;

ALTER TABLE persona ADD CONSTRAINT persona_add_use_f FOREIGN KEY ( add_user ) REFERENCES persona ( persona ) ON DELETE RESTRICT ON UPDATE RESTRICT ;
ALTER TABLE persona ADD CONSTRAINT persona_mod_use_f FOREIGN KEY ( mod_user ) REFERENCES persona ( persona ) ON DELETE RESTRICT ON UPDATE RESTRICT ;

CREATE TABLE idioma (
  idioma INT(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
  codigo VARCHAR(5) NOT NULL COMMENT 'CODIGO PARA IDENTIFICAR AL IDIOMA',
  nombre VARCHAR(15) NOT NULL COMMENT 'NOMBRE PARA IDENTIFICAR EL IDIOMA',
  add_user INT(10) UNSIGNED NOT NULL COMMENT 'IDENTIFICA A LA PERSONA QUE CREO EL REGISTRO',
  add_fecha DATETIME NOT NULL COMMENT 'FECHA EN QUE SE CREO EL REGISTRO',
  mod_user INT(10) UNSIGNED DEFAULT NULL COMMENT 'USUARIO QUE MODIFICO POR ULTIMA VEZ EL REGISTRO',
  mod_fecha DATETIME DEFAULT NULL COMMENT 'FECHA EN QUE SE MODIFICO POR ULTIMA VEZ EL REGISTRO',
  PRIMARY KEY (idioma),
  CONSTRAINT idioma_codigo UNIQUE (codigo),
  INDEX idioma_add_use_i (add_user),
  INDEX idioma_mod_use_i (mod_user),
  CONSTRAINT idioma_add_use_f FOREIGN KEY ( add_user ) REFERENCES persona (persona) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT idioma_mod_use_f FOREIGN KEY ( mod_user ) REFERENCES persona (persona) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB CHARSET=latin1 COMMENT='TABLA QUE ADMINISTRA LOS DIFERENTES IDIOMAS QUE MANEJARÁ EL SISTEMA';

CREATE TABLE modulo (
  modulo INT(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
  codigo VARCHAR( 15 ) NOT NULL COMMENT 'INDICA EL CODIGO PARA IDENTIFICAR EL MODULO DENTRO DEL CODIGO',
  orden INT(5) NOT NULL COMMENT 'ORDEN EN EL QUE APARECEN EN EL SISTEMA',
  publico ENUM('Y','N') NOT NULL DEFAULT 'N' COMMENT 'INDICA SI EL MODULO SE PRESENTA EN LA PARTE PÚBLICA',
  privado ENUM('Y','N') NOT NULL DEFAULT 'N' COMMENT 'INDICA SI EL MODULO SE PRESENTA CUANDO ESTÁ LOGINEADO',
  activo ENUM('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'INDICA SI EL MODULO ESTÁ ACTIVO',
  add_user INT(10) UNSIGNED NOT NULL COMMENT 'USUARIO QUE REGISTRO EL MODULO',
  add_fecha DATETIME NOT NULL COMMENT 'INDICA LA FECHA EN QUE SE INGRESO EL MÓDULO',
  mod_user INT(10) UNSIGNED DEFAULT NULL COMMENT 'USUARIO QUE HIZO LA ULTIMA MODIFICACION EN EL REGISTRO',
  mod_fecha DATETIME DEFAULT NULL COMMENT 'FECHA EN QUE SE HIZO LA ULTIMA MODIFICACION',
  PRIMARY KEY (modulo),
  CONSTRAINT modulo_cod_u UNIQUE (codigo),
  INDEX modulo_act_i (activo),
  INDEX modulo_pub_i (publico , privado ),
  INDEX modulo_add_use_i (add_user),
  INDEX modulo_mod_use_i (mod_user),
  CONSTRAINT modulo_add_use_f FOREIGN KEY ( add_user ) REFERENCES persona ( persona ) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT modulo_mod_use_f FOREIGN KEY ( mod_user ) REFERENCES persona ( persona ) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB CHARSET=latin1 COMMENT='TABLA QUE ADMINISTRA LOS MÓDULOS DEL SISTEMA';

CREATE TABLE modulo_idioma (
  modulo INT(5) UNSIGNED NOT NULL COMMENT 'PK FK',
  idioma INT(3) UNSIGNED NOT NULL COMMENT 'PK FK',
  nombre VARCHAR(40) NOT NULL COMMENT 'NOMBRE DEL MODULO EN EL IDIOMA ESTABLECIDO',
  PRIMARY KEY (modulo,idioma),
  INDEX modulo_idioma_mod_i ( modulo ),
  INDEX modulo_idioma_idi_i ( idioma ),
  CONSTRAINT modulo_idioma_mod_f FOREIGN KEY ( modulo ) REFERENCES modulo ( modulo ) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT modulo_idioma_idi_f FOREIGN KEY ( idioma ) REFERENCES idioma ( idioma ) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB CHARSET=latin1 COMMENT='ALMACENA EL NOMBRE DEL MODULO EN EL IDIOMA AL QUE HACE REFERENCIA';

CREATE TABLE usuario (
    persona INT(10) UNSIGNED NOT NULL COMMENT 'PK FK',
    usuario VARCHAR( 75 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'ESTE ES EL USUARIO PARA ENTRAR AL SISTEMA',
    password VARCHAR( 40 ) NOT NULL COMMENT 'PASSWORD DE INGRESO A LA PAGINA',
    idioma INT(3) UNSIGNED NOT NULL COMMENT 'IDIOMA EN EL QUE EL USUARIO PREFIERE VER LA PAGINA',
    tipo ENUM('normal','admin') NOT NULL DEFAULT 'normal' COMMENT 'TIPO DE USUARIO',
    multi_session ENUM('Y','N') NOT NULL DEFAULT 'N' COMMENT 'INDICA SI PUEDE ESTAR LOGINEADO EN VARIAS COMPUTADORAS A LA VEZ',
    bloqueado ENUM('Y','N') NOT NULL DEFAULT 'N' COMMENT 'SI ESTA BLOQUEADO NO PUEDE INGRESAR A LA PAGINA',
    add_user INT(10) UNSIGNED NOT NULL COMMENT 'INDICA QUE PERSONA HIZO EL REGISTRO',
    add_fecha datetime NOT NULL COMMENT 'INDICA LA FECHA EN QUE SE REALIZO EL REGISTRO',
    mod_user INT(10) UNSIGNED DEFAULT NULL COMMENT 'PERSONA QUE MODIFICO EL REGISTRO POR ULTIMA VEZ',
    mod_fecha datetime DEFAULT NULL COMMENT 'FECHA EN QUE SE HIZO LA ULTIMA MODIFICACION',
    PRIMARY KEY (persona),
    CONSTRAINT usuario_usu_u UNIQUE usuario_usuario (usuario),
    INDEX usuario_idi_i (idioma),
    INDEX usuario_tip_i (tipo),
    INDEX usuario_blo_i (bloqueado),
    INDEX usuario_add_use_i (add_user),
    INDEX usuario_mod_use_i (mod_user),
    INDEX usuario_mul_i (multi_session),
    CONSTRAINT usuario_per_f FOREIGN KEY ( persona ) REFERENCES persona ( persona ) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT usuario_idi_f FOREIGN KEY ( idioma ) REFERENCES idioma ( idioma ) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT usuario_add_use_f FOREIGN KEY ( add_user ) REFERENCES persona ( persona ) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT usuario_mod_use_f FOREIGN KEY ( mod_user ) REFERENCES persona ( persona ) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB CHARSET=latin1 COMMENT='TABLA QUE ADMINISTRA LOS USUARIOS ';

CREATE TABLE acceso (
    acceso INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
    modulo INT(5) UNSIGNED NOT NULL COMMENT 'MODULO AL QUE PERTENECE EL ACCESO',
    codigo VARCHAR(25) NOT NULL COMMENT 'CODIGO PARA IDENTIFICAR EN PROGRAMACION A QUIEN PERTENECE',
    orden INT(5) UNSIGNED NOT NULL COMMENT 'INDICA EN QUÉ ORDEN SE QUIERE MOSTRAR EN EL MENÚ',
    acceso_pertenece INT(10) UNSIGNED DEFAULT NULL COMMENT 'ACCESO PADRE AL QUE PERTENECE EL ACCESO',
    path VARCHAR(150) DEFAULT NULL COMMENT 'PATH DE A DONDE SE DIRIGE EN EL MENU',
    publico ENUM('Y','N') NOT NULL DEFAULT 'N' COMMENT 'INDICA SI ES PUBLICO O NO EL ACCESO',
    privado ENUM('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'INDICA SI SALE EN EL MENÚ YA QUE ESTÁ LOGINEADO',
    activo ENUM('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'DETERMINA SI ESTÁ ACTIVO O NO',
    add_user INT(10) UNSIGNED NOT NULL COMMENT 'USUARIO QUE HIZO EL REGISTRÓ ',
    add_fecha datetime NOT NULL COMMENT 'FECHA QUE SE HIZO EL REGISTRO',
    mod_user INT(10) UNSIGNED DEFAULT NULL COMMENT 'USUARIO QUE MODIFICO EL REGISTRO',
    mod_fecha datetime DEFAULT NULL COMMENT 'FECHA QUE SE HIZO LA MODIFICACIÓN',
    PRIMARY KEY (acceso),
    CONSTRAINT acceso_acc_cod_u UNIQUE acceso_codigo (modulo,codigo),
    INDEX acceso_mod_i (modulo),
    INDEX acceso_acc_per_i (acceso_pertenece),
    INDEX acceso_pub_i (publico),
    INDEX acceso_pri_i (privado),
    INDEX acceso_act_i (activo),
    INDEX acceso_add_use_i (add_user),
    INDEX acceso_mod_use_i (mod_user),
    CONSTRAINT acceso_mod_f FOREIGN KEY (modulo) REFERENCES modulo (modulo) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT acceso_acc_per_f FOREIGN KEY (acceso_pertenece) REFERENCES acceso (acceso) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT acceso_add_use_f FOREIGN KEY (add_user) REFERENCES persona (persona) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT acceso_mod_use_f FOREIGN KEY (mod_user) REFERENCES persona (persona) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB CHARSET=latin1 COMMENT='TABLA DE ACCESOS DE TODAS LAS PANTALLAS DEL SITIO';

CREATE TABLE acceso_idioma (
    acceso INT(10) UNSIGNED NOT NULL COMMENT 'PK',
    idioma INT(10) UNSIGNED NOT NULL COMMENT 'PK',
    nombre_menu VARCHAR(75) NOT NULL COMMENT 'NOMBRE QUE SE DESEA QUE APAREZCA EN EL MENU',
    nombre_pantalla VARCHAR(75) NOT NULL COMMENT 'NOMBRE QUE SE DESEA QUE APAREZCA EN PANTALLA',
    PRIMARY KEY (acceso,idioma),
    INDEX acceso_idioma_acc (acceso),
    INDEX acceso_idioma_idi (idioma),
    CONSTRAINT acceso_idioma_acc_f FOREIGN KEY (acceso) REFERENCES acceso (acceso) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT acceso_idioma_idi_f FOREIGN KEY (idioma) REFERENCES idioma (idioma) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLA QUE ADMINISTRA LOS NOMBRES EN CADA IDIOMA DE LAS PANTALLAS DEL SITIO';

CREATE TABLE tipo_acceso (
    tipo_acceso INT(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
    codigo VARCHAR(15) NOT NULL COMMENT 'CODIGO PARA IDENTIFICAR EL TIPO DE ACCESO',
    orden INT(3) NOT NULL COMMENT 'ORDEN EN QUE APARECE EN PANTALLA',
    activo ENUM('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'INDICA SI ESTÁ ACTIVO O NO ESTE TIPO DE ACCESO',
    add_user INT(10) UNSIGNED NOT NULL COMMENT 'USUARIO QUE AGREGO EL REGISTRO',
    add_fecha datetime NOT NULL COMMENT 'FECHA QUE SE AGREGO EL REGISTRO',
    mod_user INT(10) UNSIGNED DEFAULT NULL COMMENT 'USUARIO QUE MODIFICO EL REGISTRO',
    mod_fecha datetime DEFAULT NULL COMMENT 'FECHA QUE SE MODIFICO EL REGISTRO',
    PRIMARY KEY (tipo_acceso),
    UNIQUE KEY tipo_acceso_cod_u (codigo),
    KEY tipo_acceso_act_k (activo),
    KEY tipo_acceso_add_use_k (add_user),
    KEY tipo_acceso_mod_use_k (mod_user),
    CONSTRAINT tipo_acceso_add_use_f FOREIGN KEY (add_user) REFERENCES persona (persona) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT tipo_acceso_mod_use_f FOREIGN KEY (mod_user) REFERENCES persona (persona) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB CHARSET=latin1 COMMENT='LISTADO DE TIPOS DE ACCESO QUE EXISTEN EN EL SISTEMA';

CREATE TABLE tipo_acceso_idioma (
    tipo_acceso INT(3) UNSIGNED NOT NULL COMMENT 'PK FK',
    idioma INT(3) UNSIGNED NOT NULL COMMENT 'PK FK',
    nombre VARCHAR(20) NOT NULL COMMENT 'NOMBRE DEL TIPO DE ACCESO EN ESTE IDIOMA',
    PRIMARY KEY (tipo_acceso,idioma),
    INDEX tipo_acceso_idioma_tip (tipo_acceso),
    INDEX tipo_acceso_idioma_idi (idioma),
    CONSTRAINT tipo_acceso_idioma_tip_acc_f FOREIGN KEY (tipo_acceso) REFERENCES tipo_acceso (tipo_acceso) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT tipo_acceso_idioma_idi_f FOREIGN KEY (idioma) REFERENCES idioma (idioma) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB CHARSET=latin1 COMMENT='LISTADO DE ACCESOS CON SU NOMBRE EN EL IDIOMA INGRESADO';


CREATE TABLE acceso_tipo_permitido (
    acceso INT(10) UNSIGNED NOT NULL COMMENT 'PK FK',
    tipo_acceso INT(3) UNSIGNED NOT NULL COMMENT 'PK FK',
    PRIMARY KEY (acceso,tipo_acceso),
    INDEX acceso_tipo_permitido_acc (acceso),
    INDEX acceso_tipo_permitido_tip (tipo_acceso),
    CONSTRAINT acceso_tipo_permitido_acc_f FOREIGN KEY (acceso) REFERENCES acceso (acceso) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT acceso_tipo_permitido_tip_acc_f FOREIGN KEY (tipo_acceso) REFERENCES tipo_acceso (tipo_acceso) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB CHARSET=latin1 COMMENT='INDICA QUÉ TIPOS PERMITIDOS SON UTILIZADOS POR UN ACCESO EN PARTICULAR';

CREATE TABLE perfil (
    perfil INT(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
    nombre VARCHAR(75) NOT NULL COMMENT 'NOMBRE DEL PERFIL',
    descripcion VARCHAR(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL COMMENT 'DESCRIPCION DEL PERFIL',
    activo ENUM('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'INDICA SI ESTÁ ACTIVO O NO EL PERFIL',
    add_user INT(10) UNSIGNED NOT NULL COMMENT 'PERSONA QUE HIZO EL REGISTRO',
    add_fecha datetime NOT NULL COMMENT 'FECHA QUE SE HIZO EL REGISTRO',
    mod_user INT(10) UNSIGNED DEFAULT NULL COMMENT 'USUARIO QUE MODIFICO EL REGISTRO',
    mod_fecha datetime DEFAULT NULL COMMENT 'FECHA QUE SE MODIFICO EL REGISTRO',
    PRIMARY KEY (perfil),
    INDEX perfil_act_i (activo),
    INDEX perfil_add_use_i (add_user),
    INDEX perfil_mod_use_i (mod_user),
    CONSTRAINT perfil_add_use_f FOREIGN KEY (add_user) REFERENCES persona (persona) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT perfil_mod_use_f FOREIGN KEY (mod_user) REFERENCES persona (persona) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB CHARSET=latin1 COMMENT='TABLA QUE ADMINISTRA LOS PERFILES DE ACCESOS';

CREATE TABLE perfil_acceso (
    perfil INT(5) UNSIGNED NOT NULL COMMENT 'PK FK',
    acceso INT(10) UNSIGNED NOT NULL COMMENT 'PK FK',
    tipo_acceso INT(3) UNSIGNED NOT NULL COMMENT 'TIPO DE ACCESO AL QUE TIENE PERMITIDO INGRESAR',
    PRIMARY KEY (perfil,acceso,tipo_acceso),
    INDEX perfil_acceso_per_i (perfil),
    INDEX perfil_acceso_acc_i (acceso),
    INDEX perfil_acceso_tip_acc_i (tipo_acceso),
    CONSTRAINT perfil_acceso_per_f FOREIGN KEY (perfil) REFERENCES perfil (perfil) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT perfil_acceso_acc_f FOREIGN KEY (acceso) REFERENCES acceso (acceso) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT perfil_acceso_tip_acc_f FOREIGN KEY (tipo_acceso) REFERENCES tipo_acceso (tipo_acceso) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB CHARSET=latin1 COMMENT='TABLA DETALLE DE LOS ACCESOS QUE PERTENECEN A UN PERFIL';

CREATE TABLE persona_perfil (
    persona INT(10) NOT NULL COMMENT 'PK FK',
    perfil INT(5) NOT NULL COMMENT 'PK FK',
    PRIMARY KEY (persona,perfil),
    INDEX persona_perfil_per_i (persona),
    INDEX persona_perfil_perf_i (perfil)
) ENGINE=InnoDB CHARSET=latin1 COMMENT='TABLA QUE INDICA QUÉ PERFILES TIENE ACCESO UNA PERSONA';


CREATE TABLE online (
  online VARCHAR(40) NOT NULL COMMENT 'PK SESSION_ID',
  persona INT(10) UNSIGNED DEFAULT NULL COMMENT 'PERSONA QUE ESTÁ LOGINEADA',
  hora datetime NOT NULL COMMENT 'HORA QUE SE LOGINEO',
  PRIMARY KEY (online),
  INDEX online_per_i (persona)
) ENGINE=InnoDB CHARSET=latin1 COMMENT='TABLA QUE LLEVA EL CONTROL DE LAS PERSONAS QUE ESTÁN EN EL SISTEMA';

CREATE TABLE lang (
    lang VARCHAR(50) NOT NULL COMMENT 'PK',
    modulo INT(5) UNSIGNED NOT NULL COMMENT 'PK FK',
    add_user INT(10) UNSIGNED NOT NULL COMMENT 'PERSONA QUE HIZO EL REGISTRO ',
    add_fecha datetime NOT NULL COMMENT 'FECHA QUE SE HIZO EL REGISTRO',
    mod_user INT(10) UNSIGNED DEFAULT NULL COMMENT 'PERSONA QUE MODIFICO POR ULTIMA VEZ',
    mod_fecha datetime DEFAULT NULL COMMENT 'FECHA QUE SE MODIFICO EL REGISTRO',
    PRIMARY KEY (lang,modulo),
    INDEX lang_mod_i (modulo),
    INDEX lang_add_use_i (add_user),
    INDEX lang_mod_use_i (mod_user),
    CONSTRAINT lang_mod_f FOREIGN KEY (modulo) REFERENCES modulo (modulo) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT lang_add_use_f FOREIGN KEY (add_user) REFERENCES persona (persona) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT lang_mod_use_f FOREIGN KEY (mod_user) REFERENCES persona (persona) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB CHARSET=latin1 COMMENT='ADMINISTRA LOS LANGS QUE SE UTILIZARAN EN EL SITIO';


CREATE TABLE lang_idioma (
    lang VARCHAR(50) NOT NULL COMMENT 'PK FK',
    modulo INT(5) UNSIGNED NOT NULL COMMENT 'PK FK',
    idioma INT(3) UNSIGNED NOT NULL COMMENT 'PK FK',
    valor text NOT NULL COMMENT 'VALOR DEL LANG QUE QUEREMOS MOSTRAR ',
    PRIMARY KEY (lang,modulo,idioma),
    INDEX lang_idioma_lan_i (lang),
    INDEX lang_idioma_mod_i (modulo),
    INDEX lang_idioma_idi_i (idioma),
    CONSTRAINT lang_idioma_mod_f FOREIGN KEY ( modulo ) REFERENCES modulo ( modulo ) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT lang_idioma_idi_f FOREIGN KEY ( idioma ) REFERENCES idioma ( idioma ) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB CHARSET=latin1 COMMENT='ADMINISTRA LOS LANGS POR CADA IDIOMA';

CREATE TABLE configuracion (
    modulo INT(5) UNSIGNED NOT NULL COMMENT 'PK FK',
    codigo VARCHAR(20) NOT NULL COMMENT 'IDENTIFICADOR DE LA CONFIGURACION',
    tipo_dato ENUM('texto','fecha','descripcion','lista','checkbox') NOT NULL DEFAULT 'texto' COMMENT 'TIPO DE DATO QUE SE INGRESA EN LA CONFIGURACION',
    valores VARCHAR(300) DEFAULT NULL COMMENT 'INDICA LOS POSIBLES VALORES DEL TIPO DE DATO LISTA',
    valor text NOT NULL COMMENT 'VALOR DEL DATO QUE SE INGRESA EN LA CONFIGURACION',
    add_user INT(10) UNSIGNED NOT NULL COMMENT 'PERSONA QUE HIZO EL REGISTRO',
    add_fecha datetime NOT NULL COMMENT 'FECHA EN QUE SE HIZO EL REGISTRO',
    mod_user INT(10) UNSIGNED DEFAULT NULL COMMENT 'USUARIO QUE MODIFICO EL REGISTRO',
    mod_fecha datetime DEFAULT NULL COMMENT 'FECHA EN QUE SE MODIFICO EL REGISTRO',
    PRIMARY KEY (modulo,codigo),
    CONSTRAINT configuracion_mod_cod_u UNIQUE configuracion_modulo_codigo (modulo,codigo),
    INDEX configuracion_mod_i (modulo),
    INDEX configuracion_add_use_i (add_user),
    INDEX configuracion_mod_use_i (mod_user),
    CONSTRAINT configuracion_mod_f FOREIGN KEY (modulo) REFERENCES modulo (modulo ) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT configuracion_add_use_f FOREIGN KEY (add_user) REFERENCES persona (persona) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT configuracion_mod_use_f FOREIGN KEY (mod_user) REFERENCES persona (persona) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='ADMINISTRA LAS CONFIGURACIONES DE CADA MODULO';

CREATE TABLE configuracion_idioma (
    modulo INT(5) UNSIGNED NOT NULL COMMENT 'PK FK',
    codigo VARCHAR(20) NOT NULL COMMENT 'PK FK',
    idioma INT(3) UNSIGNED NOT NULL COMMENT 'PK FK',
    nombre VARCHAR(75) NOT NULL COMMENT 'NOMBRE DE LA CONFIGURACION EN EL IDIOMA SELECCIONADO',
    descripcion VARCHAR(350) NOT NULL COMMENT 'DESCRIPCION DE LA CONFIGURACION EN EL IDIOMA SELECCIONADO',
    PRIMARY KEY (modulo,codigo,idioma),
    INDEX configuracion_idioma_mod_i (modulo),
    INDEX configuracion_idioma_idi_i (idioma),
    CONSTRAINT configuracion_idioma_mod_f FOREIGN KEY (modulo) REFERENCES modulo (modulo) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT configuracion_idioma_idi_f FOREIGN KEY (idioma) REFERENCES idioma (idioma) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB CHARSET=latin1 COMMENT='NOMBRE DE LA CONFIGURACION EN EL IDIOMA DETERMINADO';

INSERT INTO idioma (idioma, codigo, nombre, add_user, add_fecha, mod_user, mod_fecha)
VALUES ('1', 'esp', 'Español', '1', NOW(), NULL, NULL);

INSERT INTO usuario (persona, usuario, idioma, tipo, multi_session, bloqueado, add_user, add_fecha, mod_user, mod_fecha)
VALUES ('1', 'webmaster@homeland.com.gt', '1', 'admin', 'Y', 'N', '1', NOW(), NULL, NULL);

INSERT INTO modulo (modulo, codigo, orden, publico, privado, activo, add_user, add_fecha, mod_user, mod_fecha)
VALUES ('1', 'core', '1', 'N', 'Y', 'Y', '1', NOW(), NULL, NULL);

INSERT INTO modulo_idioma (modulo, idioma, nombre) VALUES ('1', '1', 'Sistema');

INSERT INTO acceso (acceso, modulo, codigo, orden, acceso_pertenece, path, publico, privado, activo, add_user, add_fecha, mod_user, mod_fecha)
VALUES ('1', '1', 'config', '1', NULL, NULL, 'N', 'Y', 'Y', '1', NOW(), NULL, NULL);

INSERT INTO acceso_idioma (acceso, idioma, nombre_menu, nombre_pantalla)
VALUES ('1', '1', 'Configuración', 'Configuración');

INSERT INTO acceso (acceso, modulo, codigo, orden, acceso_pertenece, path, publico, privado, activo, add_user, add_fecha, mod_user, mod_fecha)
VALUES ('2', '1', 'cpanel', '1', '1', 'cpanel.php', 'N', 'Y', 'Y', '1', NOW(), NULL, NULL);

INSERT INTO acceso_idioma (acceso, idioma, nombre_menu, nombre_pantalla)
VALUES ('2', '1', 'Panel de control', 'Panel de control');

INSERT INTO tipo_acceso (tipo_acceso, codigo, orden, activo, add_user, add_fecha, mod_user, mod_fecha)
VALUES ('1', 'consultar', '1', 'Y', '1', NOW(), NULL, NULL);

INSERT INTO tipo_acceso_idioma (tipo_acceso, idioma, nombre) VALUES ('1', '1', 'Consultar');

INSERT INTO acceso_tipo_permitido (acceso, tipo_acceso) VALUES ('2', '1');

INSERT INTO configuracion (modulo, codigo, tipo_dato, valores, valor, add_user, add_fecha, mod_user, mod_fecha)
VALUES ('1', 'session_timeout', 'texto', NULL, '20', '1', NOW(), NULL, NULL),
('1', 'template', 'texto', NULL, 'default', '1', NOW(), NULL, NULL);

INSERT INTO configuracion (modulo, codigo, tipo_dato, valores, valor, add_user, add_fecha, mod_user, mod_fecha)
VALUES ('1', 'url', 'texto', NULL, 'http://localhost/HTML5/', '1', NOW(), NULL, NULL);

INSERT INTO configuracion_idioma (modulo, codigo, idioma, nombre, descripcion)
VALUES ('1', 'session_timeout', '1', 'Tiempo expirado de sesión', 'Tiempo en minutos de expirar la sesión '),
('1', 'template', '1', 'Plantilla', 'Plantilla');

INSERT INTO configuracion_idioma (modulo, codigo, idioma, nombre, descripcion)
VALUES ('1', 'url', '1', 'Sitio', 'Sitio donde está alojado el sistema');

INSERT INTO configuracion (modulo, codigo, tipo_dato, valores, valor, add_user, add_fecha, mod_user, mod_fecha)
VALUES ('1', 'type', 'lista', 'Publico,Privado', 'Privado', '1', NOW(), NULL, NULL);

INSERT INTO configuracion_idioma (modulo, codigo, idioma, nombre, descripcion)
VALUES ('1', 'type', '1', 'Tipo de sitio', 'Indica si el sitio tiene una parte pública o solo una parte privada');


INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha)
VALUES ('title', '1', '1', NOW(), NULL, NULL);

INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('title', '1', '1', 'Página principal');


INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('site_name', '1', '1', NOW(), NULL, NULL);

INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('site_name', '1', '1', 'Homeland');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('user_name', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('user_name', '1', '1', 'Usuario');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('password', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('password', '1', '1', 'Contraseña');


INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('login', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('login', '1', '1', 'Iniciar');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha)
VALUES ('remember_yes', '1', '1', NOW(), NULL, NULL), ('remember_no', '1', '1', NOW(), NULL, NULL);

INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('remember_yes', '1', '1', 'Sí'), ('remember_no', '1', '1', 'No');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('remember', '1', '1', NOW(), NULL, NULL);

INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('remember', '1', '1', 'Recordarme');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('invalid_password', '1', '1', NOW(), NULL, NULL);

INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('invalid_password', '1', '1', 'Usuario y/o password incorrecto');

UPDATE usuario SET password = '098f6bcd4621d373cade4e832627b4f6' WHERE usuario.persona = 1;

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('session_expired', '1', '1', NOW(), NULL, NULL);


INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('session_expired', '1', '1', '<!DOCTYPE html>
<html lang="esp">
    <head>
        <title>Sesión Expirada</title>
        <link rel="shortcut icon" href="templates/default/images/icon.jpg"/>
        <link href="libraries/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="alert alert-error"> Sesión Expirada</div>
    </body>
</html>');
/*--2014-02-18*/

CREATE TABLE modulo_dependencia (
    modulo INT(5) UNSIGNED NOT NULL COMMENT 'PK FK',
    dependencia INT(5) UNSIGNED NOT NULL COMMENT 'PK FK',
    PRIMARY KEY (modulo,dependencia),
    INDEX modulo_dependencia_mod_i ( modulo ),
    INDEX modulo_dependencia_dep_i ( dependencia ),
    CONSTRAINT modulo_dependencia_mod_f FOREIGN KEY (modulo) REFERENCES modulo (modulo) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT modulo_dependencia_dep_f FOREIGN KEY (dependencia) REFERENCES modulo (modulo) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB CHARSET=latin1 COMMENT='TABLA QUE ADMINISTRA LAS DEPENDENCIAS DE LOS MODULOS';

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('module_disabled', '1', '1', NOW(), NULL, NULL);

INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('module_disabled', '1', '1', 'Módulo no habilitado');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('access_denied', '1', '1', NOW(), NULL, NULL);

INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('access_denied', '1', '1', 'Acceso denegado');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('sidebar_access', '1', '1', NOW(), NULL, NULL);

INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('sidebar_access', '1', '1', 'Accesos');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('sidebar_module', '1', '1', NOW(), NULL, NULL);

INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('sidebar_module', '1', '1', 'Módulos');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('config_modulo', '1', '1', NOW(), NULL, NULL);

INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('config_modulo', '1', '1', 'Módulo');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha)
VALUES ('config_modulo_nombre', '1', '1', NOW(), NULL, NULL), ('config_modulo_publico', '1', '1', NOW(), NULL, NULL);

INSERT INTO lang_idioma (lang, modulo, idioma, valor)
VALUES ('config_modulo_nombre', '1', '1', 'Nombre'), ('config_modulo_publico', '1', '1', 'Público');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha)
VALUES ('config_modulo_privado', '1', '1', NOW(), NULL, NULL), ('config_modulo_activo', '1', '1', NOW(), NULL, NULL);

INSERT INTO lang_idioma (lang, modulo, idioma, valor)
VALUES ('config_modulo_privado', '1', '1', 'Privado'), ('config_modulo_activo', '1', '1', 'Activo');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha)
VALUES ('config_modulo_codigo', '1', '1', NOW(), NULL, NULL);

INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('config_modulo_codigo', '1', '1', 'Código');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('yes', '1', '1', NOW(), NULL, NULL), ('no', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('yes', '1', '1', 'Sí'), ('no', '1', '1', 'No');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('config_modulo_new', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('config_modulo_new', '1', '1', 'Nuevo');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('config_modulo_orden', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('config_modulo_orden', '1', '1', 'Orden');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('language', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('language', '1', '1', 'Idiomas');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('config_modulo_save', '1', '1', NOW(), NULL, NULL), ('config_modulo_save_new', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('config_modulo_save', '1', '1', 'Guardar'), ('config_modulo_save_new', '1', '1', 'Guardar y nuevo');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('config_modulo_cancel', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('config_modulo_cancel', '1', '1', 'Cancelar');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('logout', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('logout', '1', '1', 'Salir');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('account', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('account', '1', '1', 'Mi cuenta');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('account_save', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('account_save', '1', '1', 'Guardar');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('account_cancel', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('account_cancel', '1', '1', 'Cancelar');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('persona_nombre1', '1', '1', NOW(), NULL, NULL), ('persona_nombre2', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('persona_nombre1', '1', '1', 'Primer nombre'), ('persona_nombre2', '1', '1', 'Otros nombres');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('persona_apellido1', '1', '1', NOW(), NULL, NULL), ('persona_apellido2', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('persona_apellido1', '1', '1', 'Primer apellido'), ('persona_apellido2', '1', '1', 'Otros apellidos');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('persona_nombre_usual', '1', '1', NOW(), NULL, NULL), ('persona_sexo', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('persona_nombre_usual', '1', '1', 'Nombre usual'), ('persona_sexo', '1', '1', 'Sexo');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('persona_activo', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('persona_activo', '1', '1', 'Activo');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('persona_masculino', '1', '1', NOW(), NULL, NULL), ('persona_femenino', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('persona_masculino', '1', '1', 'Masculino'), ('persona_femenino', '1', '1', 'Femenino');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('persona_cambio_contrasena', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('persona_cambio_contrasena', '1', '1', 'Cambiar contraseña');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('persona_contrasena_actual', '1', '1', NOW(), NULL, NULL), ('persona_contrasena_nueva', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('persona_contrasena_actual', '1', '1', 'Contraseña actual'), ('persona_contrasena_nueva', '1', '1', 'Nueva contraseña');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('persona_repetir_contrasena', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('persona_repetir_contrasena', '1', '1', 'Repetir nueva contraseña');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('account_error_contrasena_title', '1', '1', NOW(), NULL, NULL), ('account_error_contrasena_content', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('account_error_contrasena_title', '1', '1', 'Contraseña incorrecta'), ('account_error_contrasena_content', '1', '1', 'La contraseña ingresada no es la contraseña actual');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('campo_requerido_title', '1', '1', NOW(), NULL, NULL), ('campo_requerido_content', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('campo_requerido_title', '1', '1', 'Campo requerido'), ('campo_requerido_content', '1', '1', 'Este campo es requerido');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('account_nueva_contrasena_title', '1', '1', NOW(), NULL, NULL), ('account_nueva_contrasena_content', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('account_nueva_contrasena_title', '1', '1', 'Contraseñas no coinciden'), ('account_nueva_contrasena_content', '1', '1', 'Las contraseñas no coinciden');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('persona_idioma', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('persona_idioma', '1', '1', 'Idioma');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('sidebar_configuration', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('sidebar_configuration', '1', '1', 'Configuración');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('sidebar_structure', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('sidebar_structure', '1', '1', 'Estructura');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('sidebar_langs', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('sidebar_langs', '1', '1', 'Etiquetas');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('sidebar_variables_configuracion', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('sidebar_variables_configuracion', '1', '1', 'Variables de configuración');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('config_elija_opcion', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('config_elija_opcion', '1', '1', '...Elija una opción...');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('config_modulo_tipo_campo', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('config_modulo_tipo_campo', '1', '1', 'Tipo de campo');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('config_modulo_valor', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('config_modulo_valor', '1', '1', 'Valor');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('config_modulo_descripcion', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('config_modulo_descripcion', '1', '1', 'Descripción');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('sidebar_tipo_acceso', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('sidebar_tipo_acceso', '1', '1', 'Tipo de acceso');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('config_modulo_enlace', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('config_modulo_enlace', '1', '1', 'Enlace');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('config_modulo_regresar', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('config_modulo_regresar', '1', '1', 'Regresar');


INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('config_nombre_menu', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('config_nombre_menu', '1', '1', 'Nombre menú');

INSERT INTO lang (lang, modulo, add_user, add_fecha, mod_user, mod_fecha) VALUES ('config_nomb_pantalla', '1', '1', NOW(), NULL, NULL);
INSERT INTO lang_idioma (lang, modulo, idioma, valor) VALUES ('config_nomb_pantalla', '1', '1', 'Nombre pantalla');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('config_modulo_busqueda',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('config_modulo_busqueda',1,1,'Búsqueda');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('persona_apellido_casada',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('persona_apellido_casada',1,1,'Apellido casada');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('persona_correo',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('persona_correo',1,1,'Correo electrónico');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('persona_aplica_usuario',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('persona_aplica_usuario',1,1,'Aplica usuario');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('persona_bloqueado',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('persona_bloqueado',1,1,'Bloqueado');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('persona_tipo_usuario',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('persona_tipo_usuario',1,1,'Tipo de usuario');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('core_tipo_normal',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('core_tipo_normal',1,1,'Normal');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('core_tipo_admin',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('core_tipo_admin',1,1,'Administrador');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('core_editar',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('core_editar',1,1,'Editar');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('persona_generar_nueva_contrasena',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('persona_generar_nueva_contrasena',1,1,'Generar nueva contraseña');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('config_nombre_perfil',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('config_nombre_perfil',1,1,'Nombre del perfil');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('config_modulo_permisos',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('config_modulo_permisos',1,1,'Permisos');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('config_modulo_usuarios',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('config_modulo_usuarios',1,1,'Usuarios');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('core_modulo_dependencia',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('core_modulo_dependencia',1,1,'Módulo dependencia');

/* INICIO LMARROQUIN 23-09-2014 */
ALTER TABLE acceso CHANGE codigo codigo VARCHAR( 100 ) NOT NULL COMMENT 'CODIGO PARA IDENTIFICAR EN PROGRAMACION A QUIEN PERTENECE';
INSERT INTO tipo_acceso (tipo_acceso,codigo,orden,activo,add_user,add_fecha) VALUES (2,'crear',2,'Y',1,NOW());
INSERT INTO tipo_acceso_idioma (tipo_acceso,idioma,nombre) VALUES (2,1,'Crear');
INSERT INTO tipo_acceso (tipo_acceso,codigo,orden,activo,add_user,add_fecha) VALUES (3,'modificar',3,'Y',1,NOW());
INSERT INTO tipo_acceso_idioma (tipo_acceso,idioma,nombre) VALUES (3,1,'Modificar');
INSERT INTO tipo_acceso (tipo_acceso,codigo,orden,activo,add_user,add_fecha) VALUES (4,'eliminar',4,'Y',1,NOW());
INSERT INTO tipo_acceso_idioma (tipo_acceso,idioma,nombre) VALUES (4,1,'Eliminar');
INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('config_inicio',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('config_inicio',1,1,'Inicio');
/* FIN LMARROQUIN 23-09-2014 */

/* INICIO JMILIAN 14/10/2014 */
INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('login_ya_conectado',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('login_ya_conectado',1,1,'Usuario ya conectado');
/* FIN JMILIAN 14/10/2014 */

/*INICIO SCRIPTS JMILIAN 23/10/2014 16:11*/
INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_mi_cuenta',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_mi_cuenta',1,1,'Mi cuenta');
/*FIN SCRIPTS JMILIAN 23/10/2014 16:11*/

/*INICIO SCRIPTS JMILIAN 16/12/2014 10:52*/
INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('core_marque_aqui_para_desconectar',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('core_marque_aqui_para_desconectar',1,1,'Marque aquí para desconectar');
/*FIN SCRIPTS JMILIAN 16/12/2014 10:52*/

/*INICIO SCRIPT IARRIAGA 22/01/2015*/
INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('mensaje_sesion_expirada',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('mensaje_sesion_expirada',1,1,'Sesión expirada');
/*FIN SCRIPT IARRIAGA 22/01/2015*/

/*INICIO SCRIPTS JMILIAN 22/01/2015 15.25*/
UPDATE  lang_idioma
   SET  valor = 'Este usuario ya a iniciado sesión en otro dispositivo, marque aquí para desconectar.'
 WHERE  lang = 'core_marque_aqui_para_desconectar'
   AND  modulo = 1
   AND  idioma = 1;
/*FIN SCRIPTS JMILIAN 22/01/2015 15.25*/

/*INICIO SCRIPT IARRIAGA 26/01/2015*/
UPDATE  lang_idioma
   SET  valor = '<html><head><meta http-equiv="content-type" content="text/html; charset=iso-8859-1;" /><!--<meta http-equiv="X-UA-Compatible" content="IE=edge">--><meta name="viewport" content="width=device-width, initial-scale=1"><title>Acceso denegado</title><link rel="shortcut icon" href="templates/idc/images/icon.jpg"/><link rel="shortcut icon" href="templates/idc/images/icon.jpg"/><link href="libraries/bootstrap/css/bootstrap.min.css" rel="stylesheet"><link href="libraries/bootstrap/css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet"><link href="libraries/bootstrap/css/plugins/dataTables.bootstrap.css" rel="stylesheet"><link href="libraries/bootstrap/css/sb-admin-2.min.css" rel="stylesheet"><link href="libraries/bootstrap/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"><link href="libraries/jquery/ui/jquery.ui.min.css" rel="stylesheet"><link href="templates/idc/styles.min.css" rel="stylesheet"><link href="libraries/c3/c3.min.css" rel="stylesheet" type="text/css"><script src="libraries/jquery/jquery.min.js" type="text/javascript"></script><script src="libraries/bootstrap/js/bootstrap.min.js" type="text/javascript"></script><script src="libraries/bootstrap/js/plugins/metisMenu/metisMenu.min.js"></script><script src="libraries/bootstrap/js/plugins/dataTables/jquery.dataTables.js"></script><script src="libraries/bootstrap/js/plugins/dataTables/dataTables.bootstrap.js"></script><script src="libraries/bootstrap/js/sb-admin-2.min.js"></script><script src="libraries/jquery/ui/jquery.ui.min.js" type="text/javascript"></script><script src="core/core.min.js" type="text/javascript"></script><script src="libraries/c3/d3.v3.min.js" charset="utf-8"></script><script src="libraries/c3/c3.js"></script></head><body><style>body{background-color: #dfe0e6;margin-top: 0px;}</style><div class="row" style="background-color: #ffffff"><div class="row"><div class="col-xs-12 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">&nbsp;</div></div><div class="row"><div class="col-xs-12 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4"><div style="margin-left: 25%; margin-right: 0%;"><img src="templates/idc/images/logo_login.png" style="width: 50%; height: 10%" alt="Acceso denegado" ></div></div></div><div class="row"><div class="col-xs-12 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">&nbsp;</div></div></div><div class="container" style="background-color: #dfe0e6"><div class="row"><div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">&nbsp;</div></div><div class="row"><div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">&nbsp;</div></div><div class="row"><div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">&nbsp;</div></div><div class="row"><div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">&nbsp;</div></div><div class="row"><div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">&nbsp;</div></div><div class="row"><div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4"><div class="alert alert-danger alert-dismissible" role="alert"><span class="glyphicon glyphicon-warning-sign btn-sm"></span>Acceso denegado</div></div></div><div class="row"><div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">&nbsp;</div></div><div class="row"><div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">&nbsp;</div></div><div class="row"><div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">&nbsp;</div></div></div></body></html>'
   WHERE  lang = 'access_denied'
   AND  modulo = 1
   AND  idioma = 1;
/*FIN SCRIPT IARRIAGA 26/01/2015*/

ALTER TABLE modulo ADD icono VARCHAR( 100 ) NULL COMMENT 'ALMACENA LA CLASE EN CSS QUE TIENE LA IMAGEN QUE REPRESENTA AL MODULO' AFTER activo;
ALTER TABLE acceso ADD icono VARCHAR( 100 ) NULL COMMENT 'ALMACENA LA CLASE EN CSS QUE TIENE LA IMAGEN QUE REPRESENTA AL ACCESO' AFTER activo;
ALTER TABLE acceso ADD menu ENUM( 'Y', 'N' ) NOT NULL DEFAULT 'Y' COMMENT 'INDICA SI SE DESPLIEGA EN EL MENU' AFTER icono;

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('core_mes_1',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('core_mes_1',1,1,'Enero');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('core_mes_2',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('core_mes_2',1,1,'Febrero');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('core_mes_3',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('core_mes_3',1,1,'Marzo');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('core_mes_4',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('core_mes_4',1,1,'Abril');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('core_mes_5',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('core_mes_5',1,1,'Mayo');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('core_mes_6',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('core_mes_6',1,1,'Junio');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('core_mes_7',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('core_mes_7',1,1,'Julio');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('core_mes_8',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('core_mes_8',1,1,'Agosto');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('core_mes_9',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('core_mes_9',1,1,'Septiembre');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('core_mes_10',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('core_mes_10',1,1,'Octubre');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('core_mes_11',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('core_mes_11',1,1,'Noviembre');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('core_mes_12',1,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('core_mes_12',1,1,'Diciembre');

UPDATE lang_idioma SET valor = 'SI' WHERE lang = 'yes' AND modulo = 1 AND idioma = 1;
UPDATE lang_idioma SET valor = 'NO' WHERE lang = 'no' AND modulo = 1 AND idioma = 1;
/*HASTA AQUI IDC PRODUCCION*/