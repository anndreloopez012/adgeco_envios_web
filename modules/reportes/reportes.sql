INSERT INTO modulo (modulo,codigo,orden,publico,privado,activo,add_user,add_fecha) VALUES (4,'reportes',4,'N','Y','Y',1,NOW());
INSERT INTO modulo_idioma (modulo,idioma,nombre) VALUES (4,1,'Reportes');

INSERT INTO acceso (acceso,modulo,codigo,orden,acceso_pertenece,path,publico,privado,activo,add_user,add_fecha) VALUES (13,4,'tipo_inversiones',2,NULL,'reportes_tipo_inversiones.php','N','Y','Y',1,NOW());
INSERT INTO acceso_idioma (acceso,idioma,nombre_menu,nombre_pantalla) VALUES (13,1,'Tipo de inversión','Tipo de inversión');
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (13,1);

INSERT INTO acceso (acceso,modulo,codigo,orden,acceso_pertenece,path,publico,privado,activo,add_user,add_fecha) VALUES (17,4,'reportes_estados_tiempos',1,NULL,'reportes_estados_tiempos.php','N','Y','Y',1,NOW());
INSERT INTO acceso_idioma (acceso,idioma,nombre_menu,nombre_pantalla) VALUES (17,1,'Estados y tiempos','Estados y tiempos');
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (17,1);

UPDATE modulo SET icono = "icon-idc-ascendant-bars-graphic" WHERE modulo = 4;

INSERT INTO modulo_dependencia (modulo, dependencia) VALUES ('4', '3');

UPDATE modulo SET orden = 5 WHERE modulo = 4;
/*HASTA AQUI IDC PRODUCCION*/
/*HASTA AQUI IDC TEST*/
/*INICIO ESAMAYOA 26/02/2019*/
INSERT INTO acceso (acceso,modulo,codigo,orden,acceso_pertenece,path,publico,privado,activo,add_user,add_fecha) VALUES (33,4,'renovacion_contratos',3,NULL,'reportes_renovacion_contratos.php','N','Y','Y',1,NOW());
INSERT INTO acceso_idioma (acceso,idioma,nombre_menu,nombre_pantalla) VALUES (33,1,'Renovación de contratos','Renovación de contratos');
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (33,1);
/*HASTA AQUI ESAMAYOA 26/02/2019*/
/*INICIO ESAMAYOA 27/02/2019*/
INSERT INTO acceso (acceso,modulo,codigo,orden,acceso_pertenece,path,publico,privado,activo,add_user,add_fecha) VALUES (34,4,'birthday_week',4,NULL,'reportes_birthday_week.php','N','Y','Y',1,NOW());
INSERT INTO acceso_idioma (acceso,idioma,nombre_menu,nombre_pantalla) VALUES (34,1,'Cumpleañeros de la semana','Cumpleañeros de la semana');
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (34,1);
/*HASTA AQUI ESAMAYOA 27/02/2019*/
/*INICIO ESAMAYOA 04/03/2019*/
INSERT INTO acceso (acceso,modulo,codigo,orden,acceso_pertenece,path,publico,privado,activo,add_user,add_fecha) VALUES (35,4,'transacciones',5,NULL,'reportes_transacciones.php','N','Y','Y',1,NOW());
INSERT INTO acceso_idioma (acceso,idioma,nombre_menu,nombre_pantalla) VALUES (35,1,'Inversiones','Inversiones');
INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES (35,1);
/*HASTA AQUI ESAMAYOA 04/03/2019*/