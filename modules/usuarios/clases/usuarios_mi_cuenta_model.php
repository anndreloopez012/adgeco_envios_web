<?php
class usuarios_mi_cuenta_model{

    public function __construct(){

    }

    public function getPersonas($strBusqueda = "", $intBanco = 0){

        $arrData = array();

        $strWhere = "";
        $boolAddAnd = false;
        $strLimit = "";
        $strOrderBy = "";

        if( !empty($strBusqueda) ){
            $strFilter = getFilterQuery("nombre_usual",$strBusqueda,false);
            $strWhere = "AND  ".$strFilter;
            $strOrderBy = "ORDER BY nombre_usual";
        }
        else{
            $strOrderBy = "ORDER BY fecha_orden";
            $strLimit = "LIMIT  10";
        }
        $strQuery = "SELECT persona.persona,
                            persona.nombre_usual,
                            persona.activo,
                            usuario.tipo,
                            IFNULL(persona.mod_fecha,persona.add_fecha) fecha_orden
                     FROM   persona
                            LEFT JOIN usuario
                                ON  persona.persona = usuario.persona
                     WHERE  persona.eliminado = 'N'
                     {$strWhere}
                     {$strOrderBy}
                     {$strLimit}";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ) {
            $arrData[$rTMP["persona"]] = array();
            $arrData[$rTMP["persona"]]["nombre_usual"] = $rTMP["nombre_usual"];
            $arrData[$rTMP["persona"]]["tipo"] = $rTMP["tipo"];
            $arrData[$rTMP["persona"]]["activo"] = $rTMP["activo"];
        }
        db_free_result($qTMP);


        return $arrData;

    }

    public function getPersonasSearch($strBusqueda, $intBanco = 0){

        $arrInfo = array();

        if( !empty($strBusqueda) ){

            $strFilter = getFilterQuery("persona.nombre_usual",$strBusqueda,false);

            $strQuery = "SELECT persona.persona, persona.nombre_usual
                         FROM   persona
                                LEFT JOIN usuario
                                    ON persona.persona = usuario.persona
                         WHERE  {$strFilter}
                         AND    persona.eliminado = 'N'
                         ORDER BY persona.nombre_usual";
            $qTMP = db_query($strQuery);
            while( $rTMP = db_fetch_assoc($qTMP) ) {
                $arrInfo[$rTMP["persona"]]["persona"] = $rTMP["persona"];
                $arrInfo[$rTMP["persona"]]["nombre_usual"] = $rTMP["nombre_usual"];
            }
            db_free_result($qTMP);

        }

        return $arrInfo;

    }

    public function getInfoBusqueda($strBusqueda = "", $intBusqueda = 0){

        $strFilter = "";
        if ( !empty($strBusqueda) ) $strFilter = getFilterQuery("nombre1,nombre2,apellido1,apellido2,apellido_casada,nombre_usual", $strBusqueda);
        else if ( $intBusqueda ) $strFilter = " AND persona = '{$intBusqueda}' ";

        $arrInfo = array();
        $strQuery = "SELECT persona, nombre1, nombre2, apellido1, apellido2, apellido_casada, nombre_usual,activo
                     FROM   persona
                     WHERE  nombre_usual IS NOT NULL
                     {$strFilter}
                     AND    persona.eliminado = 'N'
                     ORDER  BY nombre1, nombre2, apellido1, apellido2, apellido_casada, nombre_usual";
        $qTMP = db_query($strQuery);
        while ( $rTMP = db_fetch_assoc($qTMP) ){
            $arrInfo[$rTMP["persona"]]["persona"] = $rTMP["persona"];
            $arrInfo[$rTMP["persona"]]["activo"] = $rTMP["activo"];
            $arrInfo[$rTMP["persona"]]["persona_md5"] = md5($rTMP["persona"]);
            $arrInfo[$rTMP["persona"]]["nombre"] = $rTMP["nombre1"].(!empty($rTMP["nombre2"]) ? " ".$rTMP["nombre2"] : "")." ".$rTMP["apellido1"].(!empty($rTMP["apellido2"]) ? " ".$rTMP["apellido2"] : "").(!empty($rTMP["apellido_casada"]) ? " ".$rTMP["apellido_casada"] : "")." (".$rTMP["nombre_usual"].")";
        }
        db_free_result($qTMP);

        return $arrInfo;

    }

    public function getPersonaIdFromMd5( $strPersona ){
        $strQuery = "SELECT persona FROM persona WHERE MD5(persona) = '{$strPersona}'";
        $intPersona = intval(sqlGetValueFromKey($strQuery));
        return $intPersona;
    }

    public function getInfoPersona( $strPersona = "" ){
        $arrData = array();

        $strQuery = "SELECT persona.persona,
                            persona.nombre_usual,
                            persona.activo,
                            persona.email,
                            usuario.usuario,
                            usuario.tipo,
                            usuario.idioma,
                            usuario.bloqueado
                    FROM    persona,
                            usuario
                    WHERE   persona.persona = usuario.persona
                    AND     MD5(persona.persona) = '{$strPersona}'";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ){
            $arrData["persona"] = $rTMP["persona"];
            $arrData["nombre_usual"] = core_print($rTMP["nombre_usual"],"",true);
            $arrData["activo"] = $rTMP["activo"];
            $arrData["usuario"] = core_print($rTMP["usuario"],"",true);
            $arrData["tipo"] = $rTMP["tipo"];
            $arrData["idioma"] = $rTMP["idioma"];
            $arrData["bloqueado"] = $rTMP["bloqueado"];
            $arrData["email"] = core_print($rTMP["email"],"",true);
        }
        db_free_result($qTMP);

        return $arrData;
    }

    public function fntCheckUsuarioValido( $strUsuario, $intPersona ){
        $strUsuario = trim($strUsuario);
        $intPersona = intval($intPersona);

        $strAnd = "";
        if( $intPersona ){
            $strAnd = "AND persona <> {$intPersona}";
        }

        $strQuery = "SELECT persona FROM usuario WHERE usuario = '{$strUsuario}' {$strAnd}";
        $intPersonaExiste = sqlGetValueFromKey($strQuery,true);

        $strReturn = !$intPersonaExiste ? "Y" : "N";

        return $strReturn;
    }

    public function getTipo( $arrSelected = array() ){
        $arrData = array();
        $strQuery = "SELECT tipo
                     FROM   usuario";
        $qTMP = db_query($strQuery);

        while( $rTMP = db_fetch_assoc($qTMP) ){
            $arrData[$rTMP["tipo"]]["tipo"] = $rTMP["tipo"];
        }
        db_free_result($qTMP);
        
        return $arrData;
    }

    public function getIdiomas($intIdiomaSelected = 0){
        global $lang;
        $intIdiomaSelected = intval($intIdiomaSelected);

        $arrInfo = array();
        $arrInfo[0]["texto"] = $lang[MODULO]["usuarios_seleccione_opcion"];
        $arrInfo[0]["selected"] = false;

        $strQuery = "SELECT idioma, nombre
                     FROM   idioma
                     ORDER  BY nombre";
        $qTMP = db_query($strQuery);
        while ( $rTMP = db_fetch_assoc($qTMP) ){
            $arrInfo[$rTMP["idioma"]]["texto"] = $rTMP["nombre"];
            $arrInfo[$rTMP["idioma"]]["selected"] = $intIdiomaSelected ? ( ($intIdiomaSelected == $rTMP["idioma"]) ? true : false ) : false;
        }
        db_free_result($qTMP);

        return $arrInfo;

    }

    public function getTextoSelected( $arrArray = array() ){
        $strTextoSelected = "";

        if( !is_array($arrArray) ){
            $arrArray = array();
        }

        while( $rTMP = each($arrArray) ){
            if( $rTMP["value"]["selected"] ){
                $strTextoSelected = $rTMP["value"]["texto"];
            }
        }

        return $strTextoSelected;
    }

    public function updateMiCuentaPersona( $intPersona, $strNombre, $strEmail, $intUser ){
        $strNombre = substr($strNombre,0,255);
        $strEmail = substr($strEmail,0,255);

        if( $intPersona > 0 && !empty($strEmail) && !empty($strNombre) && $intUser > 0 ){
            $strQuery = "call sp_mi_cuenta_persona_update({$intPersona}, '{$strNombre}', '{$strEmail}', {$intUser})";
            db_query($strQuery);
        }

    }

    public function updateMiCuentaUsuarioPassword( $intPersona, $strPassword, $intUser ){
        $strPassword = substr($strPassword,0,40);

        if( $intPersona > 0 && !empty($strPassword) && $intUser > 0 ){
            $strQuery = "call sp_mi_cuenta_usuario_password_update({$intPersona}, '{$strPassword}', {$intUser})";
            db_query($strQuery);
            $strQuery = "call sp_usuario_password_aleatorio_update({$intPersona},'N')";
            db_query($strQuery);
        }

    }

    public function deletePersona( $strPersona ){
        $intPersona = 0;
        $strQuery = "SELECT persona FROM persona WHERE MD5(persona) = '{$strPersona}'";
        $intPersona = sqlGetValueFromKey($strQuery);

        if( $intPersona > 0 ){
            $strQuery = "call sp_persona_delete({$intPersona})";
            db_query($strQuery);
        }
    }

    public function insertUsuario( $intPersona, $strUsuario, $strPassword, $intIdioma, $strTipoUsuario, $strBloqueado, $intUser ){
        $intPersona = intval($intPersona);
        $strUsuario = substr($strUsuario,0,75);
        $intIdioma = intval($intIdioma);
        $strTipoUsuario = substr($strTipoUsuario,0,12);

        if( $intPersona > 0 && !empty($strUsuario) && !empty($strPassword) && $intIdioma > 0 && !empty($strTipoUsuario) && $intUser > 0 ){
            $strQuery = "call sp_usuario_insert({$intPersona},'{$strUsuario}','{$strPassword}',{$intIdioma},'{$strTipoUsuario}','{$strBloqueado}',{$intUser})";
            db_query($strQuery);
        }
    }

    public function updateUsuario( $intPersona, $strPassword, $intIdioma, $strTipoUsuario, $strBloqueado, $intUser ){
        $intPersona = intval($intPersona);
        $intIdioma = intval($intIdioma);
        $strTipoUsuario = substr($strTipoUsuario,0,12);

        if( $intPersona > 0 && $intIdioma > 0 && !empty($strTipoUsuario) && $intUser > 0 ){
            $strQuery = "call sp_usuario_update({$intPersona},'{$strPassword}',{$intIdioma},'{$strTipoUsuario}','{$strBloqueado}',{$intUser})";
            db_query($strQuery);
        }
    }

    public function insertPersonaPerfil( $intPersona, $intPerfil ){
        $intPersona = intval($intPersona);
        $intPerfil = intval($intPerfil);
        if( $intPersona > 0 && $intPerfil > 0){
            $strQuery = "call sp_persona_perfil_insert({$intPersona}, {$intPerfil})";
            db_query($strQuery);
        }
    }

    public function deletePersonaPerfil( $intPersona ){
        $intPersona = intval($intPersona);
        if( $intPersona > 0 ){
            $strQuery = "call sp_persona_perfil_delete({$intPersona})";
            db_query($strQuery);
        }
    }

}
