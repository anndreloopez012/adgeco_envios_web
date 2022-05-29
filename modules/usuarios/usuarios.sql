INSERT INTO modulo (modulo,codigo,orden,publico,privado,activo,add_user,add_fecha) VALUES (2,'usuarios',2,'N','Y','Y',1,NOW());
INSERT INTO modulo_idioma (modulo,idioma,nombre) VALUES (2,1,'Usuarios');

INSERT INTO acceso (acceso,modulo,codigo,orden,acceso_pertenece,path,publico,privado,activo,add_user,add_fecha) VALUES (3,2,'usuarios_administrar',1,NULL,'','N','Y','Y',1,NOW());
INSERT INTO acceso_idioma (acceso,idioma,nombre_menu,nombre_pantalla) VALUES (3,1,'Administrar','Administrar');

INSERT INTO acceso (acceso,modulo,codigo,orden,acceso_pertenece,path,publico,privado,activo,add_user,add_fecha) VALUES (4,2,'usuarios_perfiles_acceso',2,3,'usuarios_perfiles_acceso.php','N','Y','Y',1,NOW());
INSERT INTO acceso_idioma (acceso,idioma,nombre_menu,nombre_pantalla) VALUES (4,1,'Perfiles de acceso','Perfiles de acceso');
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (4,1);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (4,2);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (4,3);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (4,4);

INSERT INTO acceso (acceso,modulo,codigo,orden,acceso_pertenece,path,publico,privado,activo,add_user,add_fecha) VALUES (5,2,'usuarios_usuarios',3,3,'usuarios_usuarios.php','N','Y','Y',1,NOW());
INSERT INTO acceso_idioma (acceso,idioma,nombre_menu,nombre_pantalla) VALUES (5,1,'Usuarios','Usuarios');
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (5,1);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (5,2);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (5,3);
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (5,4);

/*INICIO SCRIPTS JMILIAN 01/12/2014 17.45*/
INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_buscar',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_buscar',2,1,'Buscar');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_nombre',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_nombre',2,1,'Nombre');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_tipo_cuenta',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_tipo_cuenta',2,1,'Tipo de cuenta');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_activo',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_activo',2,1,'Activo');

CREATE TABLE pais(
    pais SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK ID QUE IDENTIFICA EL REGISTRO EN LA TABLA',
    nombre VARCHAR(255) NOT NULL COMMENT 'NOMBRE DEL PAIS',
    activo ENUM('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'CAMPO QUE INDICA SI EL PAIS ESTA ACTIVO O NO',
    add_user INT(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'FK ID DEL USUARIO QUE INSERTO LA TUPLA',
    add_fecha DATETIME NOT NULL COMMENT 'FECHA EN LA QUE SE INSERTO LA TUPLA',
    mod_user INT(10) UNSIGNED NULL COMMENT 'FK ID DEL USUARIO QUE MODIFICO LA TUPLA',
    mod_fecha DATETIME NULL COMMENT 'FECHA EN LA QUE SE MODIFICO LA TUPLA',
    PRIMARY KEY(pais),
    CONSTRAINT pais_add_use_f FOREIGN KEY (add_user) REFERENCES persona (persona) ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT pais_mod_use_f FOREIGN KEY (mod_user) REFERENCES persona (persona) ON UPDATE RESTRICT ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT INTO pais(nombre,activo,add_user,add_fecha)
VALUES('Guatemala','Y',1,NOW());

ALTER TABLE persona
    ADD COLUMN pais SMALLINT UNSIGNED NULL COMMENT 'FK ID DEL PAIS CON EL QUE ESTA RELACIONADA LA PERSONA' AFTER sexo,
    ADD CONSTRAINT persona_pai_f FOREIGN KEY (pais) REFERENCES pais (pais) ON UPDATE RESTRICT ON DELETE RESTRICT;

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_busqueda',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_busqueda',2,1,'Busqueda');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_aceptar',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_aceptar',2,1,'Aceptar');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_cancelar',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_cancelar',2,1,'Cancelar');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_esta_seguro_eliminar_usuario',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_esta_seguro_eliminar_usuario',2,1,'¿Esta seguro de eliminar este usuario?');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_informacion',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_informacion',2,1,'Informacion');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_accesos_permisos',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_accesos_permisos',2,1,'Accesos y permisos');


INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_administrador',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_administrador',2,1,'Administrador');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_normal',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_normal',2,1,'Normal');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_eliminar',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_eliminar',2,1,'Eliminar');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_guardar',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_guardar',2,1,'Guardar');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_editar',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_editar',2,1,'Editar');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_nombre_completo',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_nombre_completo',2,1,'Nombre completo');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_genero',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_genero',2,1,'Genero');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_seleccione_opcion',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_seleccione_opcion',2,1,'Seleccione una opcion...');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_masculino',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_masculino',2,1,'Masculino');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_femenino',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_femenino',2,1,'Femenino');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_pais',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_pais',2,1,'Pais');

ALTER TABLE persona
    MODIFY COLUMN email VARCHAR(255) NOT NULL COMMENT 'EMAIL DE LA PERSONA',
    ADD COLUMN eliminado ENUM('Y','N') NOT NULL DEFAULT 'N' COMMENT 'IDENTIFICA SI LA PERSONA ESTA ELIMINADA' AFTER activo;

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_cuenta',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_cuenta',2,1,'Cuenta');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_contrasenia',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_contrasenia',2,1,'Contraseña');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_idioma_sistema',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_idioma_sistema',2,1,'Idioma del sistema');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_cuenta_activa',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_cuenta_activa',2,1,'Cuenta activa');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_generar_contrasenia_aleatoria',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_generar_contrasenia_aleatoria',2,1,'Generar contraseña aleatoria');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_contrasenia_aleatoria',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_contrasenia_aleatoria',2,1,'Contraseña aleatoria');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_nuevo',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_nuevo',2,1,'Nuevo');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_perfiles_acceso',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_perfiles_acceso',2,1,'Perfiles de acceso');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_descripcion',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_descripcion',2,1,'Descripcion');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_usuario',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_usuario',2,1,'Usuario');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_usuario_en_uso',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_usuario_en_uso',2,1,'Este usuario ya esta en uso');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_correo_electronico',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_correo_electronico',2,1,'Correo electronico');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_correo_electronico_invalido',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_correo_electronico_invalido',2,1,'Correo electronico invalido');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_perfil',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_perfil',2,1,'Perfil');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_perfil_repetido',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_perfil_repetido',2,1,'Perfil repetido');

DELIMITER $$
CREATE PROCEDURE sp_persona_insert(
    IN  strNombreUsual  VARCHAR(255),
    IN  strSexo         VARCHAR(10),
    IN  intPais         SMALLINT UNSIGNED,
    IN  strEmail        VARCHAR(255),
    IN  strFoto         VARCHAR(255),
    IN  intUser         INT(10) UNSIGNED,
    OUT intID           INT(10) UNSIGNED
)
    NO SQL
BEGIN

    INSERT INTO persona ( nombre_usual, sexo, pais, email, foto, add_user, add_fecha )
    VALUES ( strNombreUsual, strSexo, intPais, strEmail, strFoto, intUser, now());
    SET intID = LAST_INSERT_ID();

END
$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_persona_update(
    IN  intPersona      INT(10) UNSIGNED,
    IN  strNombreUsual  VARCHAR(255),
    IN  strSexo         VARCHAR(10),
    IN  intPais         SMALLINT UNSIGNED,
    IN  strEmail        VARCHAR(255),
    IN  strFoto         VARCHAR(255),
    IN  intUser         INT(10) UNSIGNED
)
    NO SQL
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

END
$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_usuario_insert(
    IN  intPersona      INT(10) UNSIGNED,
    IN  strUsuario      VARCHAR(25),
    IN  strPassword     VARCHAR(35),
    IN  intIdioma       INT(3) UNSIGNED,
    IN  strTipo         VARCHAR(12),
    IN  strBloqueado    VARCHAR(1),
    IN  intUser         INT(10) UNSIGNED
)
    NO SQL
BEGIN

    INSERT INTO usuario ( persona, usuario, password, idioma, tipo, bloqueado, add_user, add_fecha)
    VALUES ( intPersona, strUsuario, MD5(strPassword), intIdioma, strTipo, strBloqueado, intUser, now());

END
$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_usuario_update(
    IN  intPersona      INT(10) UNSIGNED,
    IN  strPassword     VARCHAR(40),
    IN  intIdioma       INT(3) UNSIGNED,
    IN  strTipo         VARCHAR(12),
    IN  strBloqueado    VARCHAR(1),
    IN  intUser         INT(10) UNSIGNED
)
    NO SQL
BEGIN

    IF strPassword != '' THEN
        UPDATE  usuario
           SET  password = MD5(strPassword),
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

END
$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_persona_perfil_insert(
    IN  intPersona    INT(10) UNSIGNED,
    IN  intPerfil    INT(5) UNSIGNED
)
    NO SQL
BEGIN

    INSERT INTO persona_perfil ( persona, perfil )
    VALUES ( intPersona, intPerfil );

END
$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_persona_perfil_delete(
    IN  intPersona    INT(10) UNSIGNED
)
    NO SQL
BEGIN

    DELETE FROM persona_perfil WHERE persona = intPersona;

END
$$
DELIMITER ;

INSERT INTO configuracion (modulo,codigo,tipo_dato,valores,valor,add_user,add_fecha) VALUES (2,'usuarios_correo_envi','texto','','info@idctest.homelandplanet.com',1,NOW());
INSERT INTO configuracion_idioma (modulo,codigo,idioma,nombre,descripcion) VALUES (2,'usuarios_correo_envi',1,'Correo electronico para enviar la contraseña','Correo electronico desde el que se enviara la contraseña');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_correo_password_asunto',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_correo_password_asunto',2,1,'Contraseña de acceso al sitio');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_correo_password_mensaje',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_correo_password_mensaje',2,1,'Estimado usuario<br><br>Por este medio le informamos que su contraseña de acceso para el sitio [Sitio], ha sido generada automaticamente por nuestro sistema.<br><br>Usuario: [Usuario]<br>Contraseña: [Contraseña]<br><br>Le recomendamos que al ingresar al sitio, se dirija a la seccion "Mi cuenta" para cambiar la contraseña por una de su preferencia.<br><br>Atentamente,<br>IDC');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_eliminar_usuario',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_eliminar_usuario',2,1,'Eliminar usuario');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_msj_alert_ins',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_msj_alert_ins',2,1,'Usuario guardado exitosamente');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_msj_alert_upd',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_msj_alert_upd',2,1,'Usuario actualizado exitosamente');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_msj_alert_del',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_msj_alert_del',2,1,'Usuario eliminado exitosamente');

DELIMITER $$
CREATE PROCEDURE sp_persona_delete(
    IN  intPersona    INT(10) UNSIGNED
)
    NO SQL
BEGIN

    UPDATE  persona
       SET  eliminado = 'Y',
            activo = 'N'
     WHERE  persona = intPersona;

    UPDATE  usuario
       SET  bloqueado = 'Y'
     WHERE  persona = intPersona;

END
$$
DELIMITER ;
/*FIN SCRIPTS JMILIAN 01/12/2014 17.45*/

/*INICIO SCRIPTS IARRIAGA 01/12/2014*/

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_refrescar',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_refrescar',2,1,'Refrescar');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_cerrar',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_cerrar',2,1,'Cerrar');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_new_perfil',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_new_perfil',2,1,'Nuevo Perfil');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_repetido',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_repetido',2,1,'Usuario Repetido');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_no_hay_info',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_no_hay_info',2,1,'No hay informacion almacenada');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_perfil_eliminar',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_perfil_eliminar',2,1,'Eliminar');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_msj_ad',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_msj_ad',2,1,'¿Esta seguro de eliminar el perfil de acceso?');

DELIMITER $$
CREATE PROCEDURE sp_perfil_insert(
    IN  strNombre       VARCHAR(75),
    IN  strDescripcion  VARCHAR(300),
    IN  strActivo       VARCHAR(1),
    IN  intUser         INT(10) UNSIGNED,
    OUT intID           INT(12) UNSIGNED
)
    NO SQL
BEGIN

     INSERT INTO perfil ( nombre, descripcion, activo, add_user, add_fecha)
        VALUES ( strNombre, strDescripcion, strActivo, intUser, now());
        SET intID = LAST_INSERT_ID();

END
$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE sp_perfil_update(
    IN  intPerfil       INT(5) UNSIGNED,
    IN  strNombre       VARCHAR(75),
    IN  strDescripcion  VARCHAR(300),
    IN  strActivo       VARCHAR(1),
    IN  intUser         INT(10) UNSIGNED
)
    NO SQL
BEGIN
        UPDATE  perfil
          SET   nombre = strNombre,
                descripcion = strDescripcion,
                activo = strActivo,
                mod_user = intUser,
                mod_fecha = now()
        WHERE   perfil = intPerfil;

END
$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_perfil_delete (IN  intPerfil SMALLINT UNSIGNED )
    NO SQL
BEGIN

    DELETE FROM perfil WHERE perfil = intPerfil;

END
$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_perfil_acceso_insert(
    IN  intPerfil        INT(5) UNSIGNED,
    IN  intAcceso        INT(10),
    IN  intTipoAcceso    INT(3)
)
    NO SQL
BEGIN

     INSERT INTO perfil_acceso ( perfil, acceso, tipo_acceso)
        VALUES ( intPerfil, intAcceso, intTipoAcceso);

END
$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE sp_perfil_acceso_delete(
    IN  intPerfil INT UNSIGNED,
    IN  intModulo INT UNSIGNED
)
NO SQL
BEGIN

    IF intModulo > 0 THEN
        DELETE FROM perfil_acceso
        WHERE  perfil = intPerfil
        AND acceso IN( SELECT acceso FROM acceso WHERE modulo = intModulo);
    ELSE
        DELETE FROM perfil_acceso
        WHERE  perfil = intPerfil;
    END IF;

END
$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE sp_perfil_cuenta_insert(
    IN  intPersona INT(10),
    IN  intPerfil  INT(5) UNSIGNED
)
    NO SQL
BEGIN

     INSERT INTO persona_perfil ( persona, perfil)
        VALUES ( intPersona, intPerfil);

END
$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE sp_perfil_cuenta_update(
    IN  intPersona          INT(10),
    IN  intPerfil           INT(5) UNSIGNED,
    IN  intPersonaOriginal  INT(10)

)
    NO SQL
BEGIN
        UPDATE  persona_perfil
          SET   persona = intPersona,
                perfil = intPerfil
        WHERE   persona = intPersonaOriginal;


END
$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_perfil_cuenta_delete (IN  intPersona INT, IN intPerfil INT)
    NO SQL
BEGIN

    DELETE FROM persona_perfil WHERE perfil = intPerfil AND persona = intPersona;

END
$$
DELIMITER ;

/*FIN SCRIPTS IARRIAGA 01/12/2014*/

/*INICIO SCRIPTS IARRIAGA 02/12/2014*/
ALTER TABLE acceso
ADD acceso_extra ENUM('Y','N') NOT NULL DEFAULT 'N' COMMENT 'INDICA QUE EL ACCESO ES PARA UN TAB DE UN ACCESO';
/*FIN SCRIPTS IARRIAGA 02/12/2014*/

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_mi_cuenta',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_mi_cuenta',2,1,'Mi cuenta');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_confirme_contra',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_confirme_contra',2,1,'Confirme contraseña');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_cambiar_contra',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_cambiar_contra',2,1,'Cambiar contraseña');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_contras_no_coinc',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_contras_no_coinc',2,1,'Las contraseñas no coinciden');

DELIMITER $$
CREATE PROCEDURE sp_mi_cuenta_persona_update(
    IN  intPersona      INT(10) UNSIGNED,
    IN  strNombre        VARCHAR(255),
    IN  strEmail        VARCHAR(255),
    IN  intUser         INT(10) UNSIGNED
)
    NO SQL
BEGIN

    UPDATE  persona
       SET  nombre_usual = strNombre,
            email = strEmail,
            mod_user = intUser,
            mod_fecha = NOW()
     WHERE  persona = intPersona;

END
$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_mi_cuenta_usuario_password_update(
    IN  intPersona      INT(10) UNSIGNED,
    IN  strPassword     VARCHAR(255),
    IN  intUser         INT(10) UNSIGNED
)
    NO SQL
BEGIN

    UPDATE  usuario
       SET  password = MD5(strPassword),
            mod_user = intUser,
            mod_fecha = NOW()
     WHERE  persona = intPersona;

END
$$
DELIMITER ;
/*FIN SCRIPTS JMILIAN 22/12/2014 8.25AM*/

/*INICIO SCRIPT IARRIAGA*/
INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_perfil_registro_msj_alert_ins',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_perfil_registro_msj_alert_ins',2,1,'Perfil guardado exitosamente');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_perfil_registro_msj_alert_upd',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_perfil_registro_msj_alert_upd',2,1,'Perfil actualizado exitosamente');

INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_perfil_registro_msj_alert_del',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_perfil_registro_msj_alert_del',2,1,'Perfil eliminado exitosamente');
/*FIN SCRIPT IARRIAGA*/
/*HASTA AQUi LMARROQUIN*/

/* INICIO SCRIPT TDELEON 13/01/2015*/
DROP PROCEDURE IF EXISTS sp_usuario_insert;
DROP PROCEDURE IF EXISTS sp_usuario_update;

DELIMITER $$
CREATE PROCEDURE sp_usuario_insert(
    IN  intPersona      INT(10) UNSIGNED,
    IN  strUsuario      VARCHAR(75),
    IN  strPassword     VARCHAR(40),
    IN  intIdioma       INT(3) UNSIGNED,
    IN  strTipo         VARCHAR(20),
    IN  strBloqueado    VARCHAR(1),
    IN  intUser         INT(10) UNSIGNED
)
    NO SQL
BEGIN

    INSERT INTO usuario ( persona, usuario, password, idioma, tipo, bloqueado, add_user, add_fecha)
    VALUES ( intPersona, strUsuario, MD5(strPassword), intIdioma, strTipo, strBloqueado, intUser, now());

END
$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_usuario_update(
    IN  intPersona      INT(10) UNSIGNED,
    IN  strPassword     VARCHAR(40),
    IN  intIdioma       INT(3) UNSIGNED,
    IN  strTipo         VARCHAR(20),
    IN  strBloqueado    VARCHAR(1),
    IN  intUser         INT(10) UNSIGNED
)
    NO SQL
BEGIN

    IF strPassword != '' THEN
        UPDATE  usuario
           SET  password = MD5(strPassword),
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

END
$$
DELIMITER ;

UPDATE lang_idioma SET valor = 'Descripcion' WHERE lang_idioma.lang = 'usuarios_descripcion' AND lang_idioma.modulo =2 AND lang_idioma.idioma =1;
/*FIN SCRIPTS TDELEON 13/01/2015 10:21 38*/

/* INICIO SCRIPT TDELEON 15/01/2015*/
UPDATE lang_idioma SET valor = '¿Esta seguro de eliminar este usuario?' WHERE lang_idioma.lang = 'usuarios_esta_seguro_eliminar_usuario' AND lang_idioma.modulo =2 AND lang_idioma.idioma =1;
/*FIN SCRIPTS TDELEON 15/01/2015 11:45 am*/
/*HASTA AQUI TDELEON*/

/*INICIO SCRIPTS JMILIAN 22/01/2015 11.46*/
INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_todos',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_todos',2,1,'Todos');

UPDATE  lang_idioma
   SET  valor = 'Busqueda'
 WHERE  lang = 'usuarios_busqueda'
   AND  modulo = 2
   AND  idioma = 1;
/*FIN SCRIPTS JMILIAN 22/01/2015 11.46*/

/*INICIO SCRIPT IARRIAGA 16/02/2015*/
ALTER TABLE usuario
ADD isAleatorio ENUM('Y','N') NOT NULL DEFAULT 'N' COMMENT 'INDICA SI EL PASSWORD DEL USUARIO FUE GENERADO ALEATORIAMENTE' AFTER password;

DELIMITER $$
CREATE PROCEDURE sp_usuario_password_aleatorio_update(
    IN intPersona        SMALLINT UNSIGNED,
    IN strIsAleatoria     CHAR(1)
)
    NO SQL
BEGIN
    UPDATE usuario
    SET isAleatorio = strIsAleatoria
    WHERE persona = intPersona;
END
$$
DELIMITER ;
/*FIN SCRIPT IARRIAGA 16/02/2015*/

/*INICIO SCRIPT IARRIAGA 20/02/2015*/
INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('usuarios_cambiar_contrasenia',2,1,NOW());
INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('usuarios_cambiar_contrasenia',2,1,'Por favor cambiar de contraseña para continuar');
/*FIN SCRIPT IARRIAGA 20/02/2015*/
/*HASTA AQUi AMUÑOZ*/

/*INICIO SCRIPTS JMILIAN 30/03/2015 14.11*/
DROP PROCEDURE sp_persona_delete;

DELIMITER $$
CREATE PROCEDURE sp_persona_delete(
    IN  intPersona    INT(10) UNSIGNED
)
    NO SQL
BEGIN

    UPDATE  persona
       SET  eliminado = 'Y',
            activo = 'N'
     WHERE  persona = intPersona;

    UPDATE  usuario
       SET  bloqueado = 'Y'
     WHERE  persona = intPersona;

    DELETE FROM persona_perfil WHERE persona = intPersona;

END
$$
DELIMITER ;
/*FIN SCRIPTS JMILIAN 30/03/2015 14.11*/

ALTER TABLE pais ADD nacionalidad VARCHAR( 255 ) NOT NULL COMMENT 'NACIONALIDAD DEL PAIS' AFTER nombre;
ALTER TABLE usuario DROP INDEX usuario_usuario;
ALTER TABLE persona ADD foto VARCHAR( 255 ) NULL COMMENT 'PATH DE LA FOTO' AFTER email;

DROP PROCEDURE sp_persona_insert;
DROP PROCEDURE sp_persona_update;

DELIMITER $$
CREATE PROCEDURE sp_persona_insert(
    IN  strNombreUsual  VARCHAR(255),
    IN  strSexo         VARCHAR(10),
    IN  intPais         SMALLINT UNSIGNED,
    IN  strEmail        VARCHAR(255),
    IN  strFoto         VARCHAR(255),
    IN  intUser         INT(10) UNSIGNED,
    OUT intID           INT(10) UNSIGNED
)
    NO SQL
BEGIN

    INSERT INTO persona ( nombre_usual, sexo, pais, email, foto, add_user, add_fecha )
    VALUES ( strNombreUsual, strSexo, intPais, strEmail, strFoto, intUser, now());
    SET intID = LAST_INSERT_ID();

END
$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_persona_update(
    IN  intPersona      INT(10) UNSIGNED,
    IN  strNombreUsual  VARCHAR(255),
    IN  strSexo         VARCHAR(10),
    IN  intPais         SMALLINT UNSIGNED,
    IN  strEmail        VARCHAR(255),
    IN  strFoto         VARCHAR(255),
    IN  intUser         INT(10) UNSIGNED
)
    NO SQL
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

END
$$
DELIMITER ;

UPDATE acceso SET acceso_pertenece = NULL WHERE acceso = 4;
UPDATE acceso SET acceso_pertenece = NULL WHERE acceso = 5;
DELETE FROM acceso_idioma WHERE acceso = 3 AND idioma = 1;
DELETE FROM perfil_acceso WHERE acceso = 3;
DELETE FROM acceso WHERE acceso = 3;

ALTER TABLE usuario ADD codigo VARCHAR( 32 ) NULL COMMENT 'CODIGO PARA PODER REESTABLECER CONTRASEÑA DE ACCESO' AFTER bloqueado;
/*HASTA AQUI IDC PRODUCCION*/