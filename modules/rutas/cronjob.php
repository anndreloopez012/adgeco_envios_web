<?php
function clientes_notificacion_fuera_de_tiempo() {

    global $cfg, $objConexion, $strModulo, $arrConfigModule;

    if( $objConexion ){
        $strQuery ="SELECT  a.cliente,
                            a.tipo_persona,
                            a.estado,
                            CASE a.estado
                            WHEN 'PROCESO_LLENADO' THEN 1
                            WHEN 'DEPARTAMENTO_COMERCIAL' THEN 2
                            WHEN 'REVISION_DOCUMENTOS' THEN 3
                            WHEN 'UNIDAD_CUMPLIMIENTO' THEN 4
                            WHEN 'PENDIENTE_FIRMAS' THEN 5
                            WHEN 'PENDIENTE_AUTORIZAR' THEN 6
                            WHEN 'AUTORIZADO' THEN 7
                            WHEN 'POR_VENCER' THEN 8
                            WHEN 'VENCIDO' THEN 9
                            END AS estado_orden,
                            CONCAT_WS( ' DE ', CONCAT_WS(' ',
                            b.solicitante_primer_nombre,
                            b.solicitante_segundo_nombre,
                            b.solicitante_otros_nombres,
                            b.solicitante_primer_apellido,
                            b.solicitante_segundo_apellido),
                            b.solicitante_apellido_casada
                            ) AS nombre,
                            b.solicitante_nit AS nit,
                            IF(a.mod_fecha IS NULL,a.add_fecha,a.mod_fecha) AS fecha,
                            DATE_FORMAT(c.fecha_inicio,'%Y-%m-%d') AS fecha_inicio,
                            IF(c.fecha_fin IS NULL, DATE_FORMAT(now(),'%Y-%m-%d'), DATE_FORMAT(c.fecha_fin,'%Y-%m-%d')) AS fecha_fin,
                            IF(c.fecha_fin IS NULL,TIMESTAMPDIFF(MINUTE,c.fecha_inicio,now()),TIMESTAMPDIFF(MINUTE,c.fecha_inicio,c.fecha_fin)) AS minutos,
                            d.intervalo
                    FROM    cliente AS a
                            INNER JOIN cliente_individual_solicitante AS b
                                ON  b.cliente = a.cliente
                            INNER JOIN cliente_log AS c
                                ON  c.cliente = a.cliente
                                AND c.estado = a.estado
                            INNER JOIN cliente_estado_tiempo AS d
                                ON  d.estado = a.estado
                    UNION ALL
                    SELECT  a.cliente,
                            a.tipo_persona,
                            a.estado,
                            CASE a.estado
                            WHEN 'PROCESO_LLENADO' THEN 1
                            WHEN 'DEPARTAMENTO_COMERCIAL' THEN 2
                            WHEN 'REVISION_DOCUMENTOS' THEN 3
                            WHEN 'UNIDAD_CUMPLIMIENTO' THEN 4
                            WHEN 'PENDIENTE_FIRMAS' THEN 5
                            WHEN 'PENDIENTE_AUTORIZAR' THEN 6
                            WHEN 'AUTORIZADO' THEN 7
                            WHEN 'POR_VENCER' THEN 8
                            WHEN 'VENCIDO' THEN 9
                            END AS estado_orden,
                            b.nombre_comercial AS nombre,
                            b.nit AS nit,
                            IF(a.mod_fecha IS NULL,a.add_fecha,a.mod_fecha) AS fecha,
                            DATE_FORMAT(c.fecha_inicio,'%Y-%m-%d') AS fecha_inicio,
                            IF(c.fecha_fin IS NULL, DATE_FORMAT(now(),'%Y-%m-%d'), DATE_FORMAT(c.fecha_fin,'%Y-%m-%d'))  AS fecha_fin,
                            IF(c.fecha_fin IS NULL,TIMESTAMPDIFF(MINUTE,c.fecha_inicio,now()),TIMESTAMPDIFF(MINUTE,c.fecha_inicio,c.fecha_fin)) AS minutos,
                            d.intervalo
                    FROM    cliente AS a
                            INNER JOIN cliente_juridico_solicitante AS b
                                ON  b.cliente = a.cliente
                            INNER JOIN cliente_log AS c
                                ON  c.cliente = a.cliente
                                AND c.estado = a.estado
                            INNER JOIN cliente_estado_tiempo AS d
                                ON  d.estado = a.estado";
        $qTMP = mysql_query($strQuery);
        //debugQuery($strQuery);
        while( $rTMP = mysql_fetch_assoc($qTMP) ) {

            $intDiasFinDeSemana = 0;
            $intMinutosFinDeSemana = 0;
            for( $i=$rTMP["fecha_inicio"]; $i<=$rTMP["fecha_fin"]; $i = date("Y-m-d", strtotime($i ."+ 1 days")) ) {
                $intDia = date("w", strtotime($i));
                if( $intDia == 0 || $intDia == 6 ) {
                    $intDiasFinDeSemana++;
                }
            }
            $intMinutosFinDeSemana = $intDiasFinDeSemana * 1440;
            //$intMinutosFinDeSemana = 0;

            $arrData[$rTMP["estado"]][$rTMP["cliente"]]["cliente"] = $rTMP["cliente"];
            $arrData[$rTMP["estado"]][$rTMP["cliente"]]["codigo"] = str_pad($rTMP["cliente"],10,"0",STR_PAD_LEFT);
            $arrData[$rTMP["estado"]][$rTMP["cliente"]]["tipo_persona"] = $rTMP["tipo_persona"] == "JURIDICA" ? "JURÍDICA" : "INDIVIDUAL";
            $arrData[$rTMP["estado"]][$rTMP["cliente"]]["nombre"] = $rTMP["nombre"];
            $arrData[$rTMP["estado"]][$rTMP["cliente"]]["nit"] = $rTMP["nit"];

            if( isset($arrData[$rTMP["estado"]][$rTMP["cliente"]][$rTMP["estado"]]) )
                      $arrData[$rTMP["estado"]][$rTMP["cliente"]][$rTMP["estado"]] += ($rTMP["minutos"] - $intMinutosFinDeSemana);
            else
                      $arrData[$rTMP["estado"]][$rTMP["cliente"]][$rTMP["estado"]] = ($rTMP["minutos"] - $intMinutosFinDeSemana);

            $intDias = 0;
            if( $arrData[$rTMP["estado"]][$rTMP["cliente"]][$rTMP["estado"]] > 0 ) {
                $intDias = $arrData[$rTMP["estado"]][$rTMP["cliente"]][$rTMP["estado"]] / 1440;
                $intDias = ceil($intDias);
            }
            $arrData[$rTMP["estado"]][$rTMP["cliente"]]["dias"] = $intDias;

            if( $arrData[$rTMP["estado"]][$rTMP["cliente"]]["dias"] > intval($rTMP["intervalo"]) && ( $rTMP["estado"] != "AUTORIZADO" && $rTMP["estado"] != "POR_VENCER" && $rTMP["estado"] != "VENCIDO" ) )
                $arrData[$rTMP["estado"]][$rTMP["cliente"]]["fuera_tiempo"] = true;
            else
                $arrData[$rTMP["estado"]][$rTMP["cliente"]]["fuera_tiempo"] = false;

        }
        mysql_free_result($qTMP);

        reset($arrData);
        while( $arrE = each($arrData) ) {

            if( $arrE["key"] == "PROCESO_LLENADO" ) {
                $arrPersona = getPersonasByAcceso(14);
            }
            elseif( $arrE["key"] == "DEPARTAMENTO_COMERCIAL" ) {
                $arrPersona = getPersonasByAcceso(8);
            }
            elseif( $arrE["key"] == "REVISION_DOCUMENTOS" ) {
                $arrPersona = getPersonasByAcceso(9);
            }
            elseif( $arrE["key"] == "UNIDAD_CUMPLIMIENTO" ) {
                $arrPersona = getPersonasByAcceso(10);
            }
            elseif( $arrE["key"] == "PENDIENTE_FIRMAS" ) {
                $arrPersona = getPersonasByAcceso(18);
            }
            elseif( $arrE["key"] == "PENDIENTE_AUTORIZAR" ) {
                $arrPersona = getPersonasByAcceso(11);
            }

            while( $arrC = each($arrE["value"]) ) {
                if( $arrC["value"]["fuera_tiempo"] ) {
                    $strCliente = md5($arrC["value"]["cliente"]);
                    $strSubject = "Formulario en fuera de tiempo Cod. {$arrC["value"]["codigo"]}";
                    $strHtml = <<<EOF
                    Le informarnos que el siguiente formulario se encuntra en fuera de tiempo:<br><br>
                    <table style="width:100%">
                        <tr>
                            <td style="color:rgb(36,64,98);border-bottom: 1px solid black;font-weight: bold;">Código</td>
                            <td style="color:rgb(36,64,98);border-bottom: 1px solid black;font-weight: bold;">Nombre</td>
                            <td style="color:rgb(36,64,98);border-bottom: 1px solid black;font-weight: bold;">Tipo</td>
                            <td style="color:rgb(36,64,98);border-bottom: 1px solid black;font-weight: bold;">NIT</td>
                        </tr>
                        <tr>
                            <td>{$arrC["value"]["codigo"]}</td>
                            <td>{$arrC["value"]["nombre"]}</td>
                            <td>{$arrC["value"]["tipo_persona"]}</td>
                            <td>{$arrC["value"]["nit"]}</td>
                        </tr>
                    </table><br><br>
                    Para poder revisarlo ingrese al siguiente link: {$arrConfigModule[1]["url"]["valor"]}clientes_consulta_cliente.php?cliente={$strCliente}
EOF;

                    if( !empty($arrPersona) ) {
                        reset($arrPersona);
                        while( $arrP = each($arrPersona) ) {
                            $arrMailConfig = array();
                            $arrMailConfig["from"] = $arrConfigModule[2]["usuarios_correo_envi"]["valor"];
                            $arrMailConfig["subject"] = $strSubject;
                            $arrMailConfig["mail_individual"] = true;
                            $arrMailContent["html"] = $strHtml;
                            core_mail(false,$arrMailConfig,$arrP["value"],$arrMailContent["html"]);
                        }
                    }
                }
            }
        }
        /*print "<pre>";
        print_r($arrData);
        print "</pre>";*/

    }

}

function getPersonasByAcceso($intAcceso) {
        $arrReturn = array();
        $strQuery ="SELECT  c.persona,
                            c.email
                    FROM    perfil_acceso AS a
                            INNER JOIN persona_perfil AS b
                                ON  a.perfil = b.perfil
                            INNER JOIN persona AS c
                                ON  b.persona = c.persona
                    WHERE   a.acceso = {$intAcceso}
                    AND     a.tipo_acceso = 1
                    AND     c.email IS NOT NULL";
        $qTMP = mysql_query($strQuery);
        while( $rTMP = mysql_fetch_assoc($qTMP) ){
            $arrReturn[$rTMP["persona"]] = $rTMP["email"];
            unset($rTMP);
        }
        mysql_free_result($qTMP);
        return $arrReturn;
}


clientes_notificacion_fuera_de_tiempo();