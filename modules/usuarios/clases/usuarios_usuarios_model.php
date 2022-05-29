<?php
class usuarios_usuarios_model{

    public function __construct(){

    }

    public function getPersonas($strBusqueda = ""){

        $arrData = array();

        $strWhere = "";
        $strLimit = "";
        $strOrderBy = "";
        $strAndAdmin = "";

        if( $_SESSION["hml"]["tipo_usuario"] != "admin" ){
            $strAndAdmin = "AND usuario.tipo NOT LIKE 'admin'";
        }

        if( !empty($strBusqueda) ){
            $strFilter = getFilterQuery("nombre_usual",$strBusqueda,false);
            $strWhere = "AND  ".$strFilter;
            $strOrderBy = "ORDER BY nombre_usual";
        }
        else{
            $strOrderBy = "ORDER BY fecha_orden DESC, nombre_usual";
            $strLimit = " LIMIT  10";
        }
        $strQuery = "SELECT persona.persona,
                            persona.nombre_usual,
                            usuario.bloqueado,
                            usuario.tipo,
                            IF(persona.mod_fecha IS NULL,persona.add_fecha,persona.mod_fecha) fecha_orden
                     FROM   persona
                            LEFT JOIN usuario
                                ON  persona.persona = usuario.persona
                     WHERE  persona.eliminado = 'N'
                     {$strWhere}
                     {$strAndAdmin}
                     {$strOrderBy}
                     {$strLimit}";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ) {
            $arrData[$rTMP["persona"]] = array();
            $arrData[$rTMP["persona"]]["nombre_usual"] = $rTMP["nombre_usual"];
            $arrData[$rTMP["persona"]]["tipo"] = $rTMP["tipo"];
            $arrData[$rTMP["persona"]]["bloqueado"] = $rTMP["bloqueado"];
        }
        db_free_result($qTMP);


        return $arrData;

    }

    public function getPersonasSearch($strBusqueda){

        $arrInfo = array();

        if( !empty($strBusqueda) ){

            $strFilter = getFilterQuery("persona.nombre_usual",$strBusqueda,false);

            $strAndAdmin = "";

            if( $_SESSION["hml"]["tipo_usuario"] != "admin" ){
                $strAndAdmin = "AND usuario.tipo NOT LIKE 'admin'";
            }

            $strQuery = "SELECT persona.persona, persona.nombre_usual
                         FROM   persona
                                LEFT JOIN usuario
                                    ON persona.persona = usuario.persona
                         WHERE  {$strFilter}
                         AND    persona.eliminado = 'N'
                         {$strAndAdmin}
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
                            persona.foto,
                            usuario.usuario,
                            usuario.tipo,
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
            $arrData["bloqueado"] = $rTMP["bloqueado"];
            $arrData["email"] = core_print($rTMP["email"],"",true);
            $arrData["foto"] = $rTMP["foto"];
        }
        db_free_result($qTMP);

        $strQuery = "SELECT perfil.perfil,
                            perfil.nombre,
                            perfil.descripcion
                    FROM    perfil,
                            persona_perfil
                    WHERE   perfil.perfil = persona_perfil.perfil
                    AND     MD5(persona_perfil.persona) = '{$strPersona}'";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ){
            if( !array_key_exists("perfiles",$arrData) ){
                $arrData["perfiles"] = array();
            }
            $arrData["perfiles"][$rTMP["perfil"]]["nombre"] = $rTMP["nombre"];
            $arrData["perfiles"][$rTMP["perfil"]]["descripcion"] = $rTMP["descripcion"];
        }
        db_free_result($qTMP);

        return $arrData;
    }

    public function fntCheckUsuarioValido( $strUsuario, $intPersona ){
        $strUsuario = trim($strUsuario);
        $intPersona = intval($intPersona);

        $strAnd = "";
        if( $intPersona ){
            $strAnd = "AND usuario.persona <> {$intPersona}";
        }

        $strQuery = "SELECT usuario.persona
                    FROM usuario
                    INNER JOIN persona
                        ON usuario.persona = persona.persona
                    WHERE usuario = '{$strUsuario}'
                    {$strAnd}
                    AND persona.eliminado = 'N'";
        $intPersonaExiste = sqlGetValueFromKey($strQuery,true);

        $strReturn = !$intPersonaExiste ? "Y" : "N";

        return $strReturn;
    }

    public function getTiposCuenta( $strTipoCuentaSelected = "" ){
        global $lang;
        $arrData = array();

        $arrData[""]["texto"] = $lang[MODULO]["usuarios_seleccione_opcion"];
        $arrData[""]["selected"] = false;

        $arrData["admin"]["texto"] = $lang[MODULO]["usuarios_administrador"];
        $arrData["admin"]["selected"] = false;

        $arrData["normal"]["texto"] = $lang[MODULO]["usuarios_normal"];
        $arrData["normal"]["selected"] = false;

        $arrData["mensajero"]["texto"] = $lang[MODULO]["usuarios_asesor"];
        $arrData["mensajero"]["selected"] = false;

        if( $_SESSION["hml"]["tipo_usuario"] != "admin" ){
            unset($arrData["admin"]);
        }

        if( array_key_exists($strTipoCuentaSelected,$arrData) ){
            $arrData[$strTipoCuentaSelected]["selected"] = true;
        }

        return $arrData;
    }

    public function getDescripcionPerfil( $intPerfil ){
        $intPerfil = intval($intPerfil);
        $strDescripcion = "";

        $strQuery = "SELECT descripcion FROM perfil WHERE perfil = {$intPerfil}";
        $strDescripcion = sqlGetValueFromKey($strQuery);

        return core_print($strDescripcion,"",true);
    }

    public function getSexos( $strSelected = "" ){
        global $lang;
        $arrData = array();

        $arrData[""]["texto"] = $lang[MODULO]["usuarios_seleccione_opcion"];
        $arrData[""]["selected"] = false;

        $arrData["M"]["texto"] = $lang[MODULO]["usuarios_masculino"];
        $arrData["M"]["selected"] = false;

        $arrData["F"]["texto"] = $lang[MODULO]["usuarios_femenino"];
        $arrData["F"]["selected"] = false;

        if( array_key_exists($strSelected,$arrData) ){
            $arrData[$strSelected]["selected"] = true;
        }

        return $arrData;

    }

    public function getInfoPais( $intPersona = 0 ){
        $arrData["pais"] = array();
        $intPersona = intval($intPersona);

        $strQuery = "SELECT pais.pais,
                            pais.nombre
                    FROM    pais,
                            persona_pais
                    WHERE   pais.pais = persona_pais.pais
                    AND     persona_pais.persona = {$intPersona}";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ){

            $arrData["pais"][$rTMP["pais"]] = $rTMP["nombre"];

        }
        db_free_result($qTMP);
        return $arrData;
    }

    public function getPerfiles(){
        $arrData = array();
        $strQuery = "SELECT perfil,
                            nombre,
                            activo
                    FROM    perfil";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ){
            $arrData[$rTMP["perfil"]]["nombre"] = $rTMP["nombre"];
            $arrData[$rTMP["perfil"]]["activo"] = $rTMP["activo"];
        }
        db_free_result($qTMP);
        return $arrData;
    }

    public function getFilterPerfiles( $arrPerfiles, $intPaisSelected = 0 ){
        global $lang;
        $intPaisSelected = intval($intPaisSelected);

        $arrData = array();

        $arrData[0]["texto"] = $lang[MODULO]["usuarios_seleccione_opcion"];
        $arrData[0]["selected"] = false;

        while( $rTMP = each($arrPerfiles) ){
            if( $rTMP["value"]["activo"] == "Y" || $rTMP["key"] == $intPaisSelected ){
                $arrData[$rTMP["key"]]["texto"] = core_print($rTMP["value"]["nombre"],"",true);
                $arrData[$rTMP["key"]]["selected"] = $rTMP["key"] == $intPaisSelected ? true : false;
            }
        }

        return $arrData;
    }

    public function getPais($intPaisSelected = 0){
        global $lang;
        $intPaisSelected = intval($intPaisSelected);

        $arrInfo = array();
        $arrInfo[0]["texto"] = $lang[MODULO]["usuarios_seleccione_opcion"];
        $arrInfo[0]["selected"] = false;

        $strQuery = "SELECT pais, nombre
                     FROM   pais
                     ORDER  BY nombre";
        $qTMP = db_query($strQuery);
        while ( $rTMP = db_fetch_assoc($qTMP) ){
            $arrInfo[$rTMP["pais"]]["texto"] = $rTMP["nombre"];
            $arrInfo[$rTMP["pais"]]["selected"] = $intPaisSelected ? ( ($intPaisSelected == $rTMP["pais"]) ? true : false ) : false;
        }
        db_free_result($qTMP);

        return $arrInfo;

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

    public function getPaises(){
        global $lang;
        $arrData = array();

        $arrData[0]["texto"] = $lang[MODULO]["usuarios_seleccione_opcion"];
        $arrData[0]["selected"] = false;

        $strQuery = "SELECT pais,
                            nombre
                    FROM    pais
                    WHERE   activo = 'Y'";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ){
            $arrData[$rTMP["pais"]] = $rTMP["nombre"];
        }
        db_free_result($qTMP);
        return $arrData;
    }

    public function getBoolEditar( $intPersona ){
        $intPersona = intval($intPersona);
        $strQuery = "SELECT persona.persona
                    FROM    persona,
                            usuario
                    WHERE   persona.persona = {$intPersona}
                    AND     persona.add_user = usuario.persona
                    AND     usuario.tipo = 'admin'";
        $intExiste = sqlGetValueFromKey($strQuery,true);
        $boolEditar = !($intExiste) ? true : false;
        return $boolEditar;
    }

    public function insertPersona( $strNombreUsual, $strSexo, $intPais, $strEmail, $strFoto, $intUser ){
        $intPersona = 0;

        $strNombreUsual = substr($strNombreUsual,0,255);
        $strSexo = substr($strSexo,0,10);
        $intPais = intval($intPais);
        $strEmail = substr($strEmail,0,255);

        if( !empty($strNombreUsual) && !empty($strSexo) && $intPais > 0 && !empty($strEmail) && $intUser > 0 ){
            $strQuery = "call sp_persona_insert('{$strNombreUsual}', '{$strSexo}', {$intPais}, '{$strEmail}', '{$strFoto}', {$intUser}, @intID)";
            db_query($strQuery);
            $strQuery ="SELECT @intID";
            $intPersona = sqlGetValueFromKey($strQuery);
        }

        return $intPersona;
    }

    public function updatePersona( $intPersona, $strNombreUsual, $strSexo, $intPais, $strEmail, $strFoto, $intUser ){

        $strNombreUsual = substr($strNombreUsual,0,255);
        $strSexo = substr($strSexo,0,10);
        $intPais = intval($intPais);
        $strEmail = substr($strEmail,0,255);

        if( $intPersona > 0 && !empty($strNombreUsual) && !empty($strSexo) && $intPais > 0 && !empty($strEmail) && $intUser > 0 ){
            $strQuery = "call sp_persona_update({$intPersona}, '{$strNombreUsual}', '{$strSexo}', {$intPais}, '{$strEmail}', '{$strFoto}', {$intUser})";
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

    public function insertUsuario( $intPersona, $strUsuario, $strPassword, $intIdioma, $strTipoUsuario, $strBloqueado, $intUser){
        $intPersona = intval($intPersona);
        $strUsuario = substr($strUsuario,0,75);
        $intIdioma = intval($intIdioma);
        $strTipoUsuario = substr($strTipoUsuario,0,20);

        if( $intPersona > 0 && !empty($strUsuario) && !empty($strPassword) && $intIdioma > 0 && !empty($strTipoUsuario) && $intUser > 0 ){
            $strQuery = "call sp_usuario_insert({$intPersona},'{$strUsuario}','{$strPassword}',{$intIdioma},'{$strTipoUsuario}','{$strBloqueado}',{$intUser})";
            db_query($strQuery);
            $strQuery = "call sp_usuario_password_aleatorio_update({$intPersona},'Y')";
            db_query($strQuery);

        }
    }

    public function updateUsuario( $intPersona, $strPassword, $intIdioma, $strTipoUsuario, $strBloqueado, $intUser, $strAleatorio ){
        $intPersona = intval($intPersona);
        $intIdioma = intval($intIdioma);
        $strTipoUsuario = substr($strTipoUsuario,0,20);

        if( $intPersona > 0 && $intIdioma > 0 && !empty($strTipoUsuario) && $intUser > 0 ){
            $strQuery = "call sp_usuario_update({$intPersona},'{$strPassword}',{$intIdioma},'{$strTipoUsuario}','{$strBloqueado}',{$intUser})";
            db_query($strQuery);
            if($strAleatorio == "Y"){
                $strQuery = "call sp_usuario_password_aleatorio_update({$intPersona},'Y')";
                db_query($strQuery);
            }
            else if($strAleatorio == "N"){
                $strQuery = "call sp_usuario_password_aleatorio_update({$intPersona},'N')";
                db_query($strQuery);
            }
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