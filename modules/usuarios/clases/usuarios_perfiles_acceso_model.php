<?php
class  usuarios_perfiles_model{

    public function __contruct(){

    }

    public function getId($strTableName, $strKeyField, $strIdentificador){

        $intReturn = "";
        $strTableName = trim($strTableName);
        $strKeyField = trim($strKeyField);
        $strIdentificador = trim($strIdentificador);

        if( !empty($strTableName) && !empty($strKeyField) && !empty($strIdentificador) ){
            $strQuery = "SELECT {$strKeyField} FROM {$strTableName} WHERE {$strKeyField} = '{$strIdentificador}'";
            $intReturn = sqlGetValueFromKey($strQuery);
        }

        return $intReturn;

    }

    public function getPerfiles( $intId = 0, $strTexto = "", $strUsuarios = ""){

        $arrData = array();

        $strFilterAdmin = "";

        if($_SESSION["hml"]["tipo_usuario"] != "admin" && !empty($strTexto)){
            $strFilterAdmin = "AND add_user NOT IN({$strUsuarios})";
        }
        else if($_SESSION["hml"]["tipo_usuario"] != "admin" && empty($strTexto) && $intId == 0){
            $strFilterAdmin = "AND add_user NOT IN({$strUsuarios})";
        }

        $strWhere = "";
        $strFilter = "";
        $strCampos = "nombre";
        $boolAddAnd = false;

        if( $intId == 0 ){
            $strFilter = getFilterQuery($strCampos,$strTexto,$boolAddAnd);
            $strWhere = "AND {$strFilter}";
        }
        else if($intId > 0){
            $strWhere = "AND perfil = {$intId}";
        }
        $strQuery ="SELECT  perfil,
                            nombre,
                            descripcion,
                            activo,
                            add_user
                    FROM    perfil
                    WHERE   1 = 1
                            {$strWhere}
                            {$strFilterAdmin}
                    ORDER BY nombre";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ) {
            $arrData[$rTMP["perfil"]] = array();
            $arrData[$rTMP["perfil"]]["nombre"] = $rTMP["nombre"];
            $arrData[$rTMP["perfil"]]["descripcion"] = $rTMP["descripcion"];
            $arrData[$rTMP["perfil"]]["activo"] = $rTMP["activo"];
        }
        db_free_result($qTMP);


        return $arrData;

    }

    public function getPerfilesSearch($strParametro){

        $arrInfo = array();

        if( !empty($strParametro) ){

            $strFilter = getFilterQuery("nombre",$strParametro,false);

            $strQuery = "SELECT perfil, nombre
            FROM   perfil
            WHERE  {$strFilter}
            ORDER BY nombre";
            $qTMP = db_query($strQuery);
            while( $rTMP = db_fetch_assoc($qTMP) ) {
                $arrInfo[$rTMP["perfil"]]["perfil"] = $rTMP["perfil"];
                $arrInfo[$rTMP["perfil"]]["nombre"] = $rTMP["nombre"];
            }
            db_free_result($qTMP);

        }

        return $arrInfo;

    }

    public function getCuentasSearch($strParametro){

        $arrInfo = array();

        if( !empty($strParametro) ){

            $intPersona = $_SESSION["hml"]["persona"];

            $strFilter = getFilterQuery("persona.nombre_usual,usuario.usuario",$strParametro,false);

            $strQuery = "SELECT persona.persona,
            nombre_usual,
            usuario.usuario
            FROM   persona
            INNER JOIN usuario
                ON    persona.persona = usuario.persona
            WHERE  {$strFilter}
            AND    persona.eliminado = 'N'
            AND    usuario.tipo NOT IN ('admin')
            AND    persona.persona NOT IN({$intPersona})
            ORDER BY nombre_usual";
            $qTMP = db_query($strQuery);
            while( $rTMP = db_fetch_assoc($qTMP) ) {
                $arrInfo[$rTMP["persona"]]["id"] = $rTMP["persona"];
                $arrInfo[$rTMP["persona"]]["usuario"] = $rTMP["usuario"];
                $arrInfo[$rTMP["persona"]]["nombre"] = $rTMP["nombre_usual"];
            }
            db_free_result($qTMP);

        }

        return $arrInfo;

    }

    public function getPerfilNombre($intPerfil){

        $intPerfil = intval($intPerfil);

        $strNombre = "";

        if( $intPerfil ){

            $strQuery = "SELECT nombre FROM perfil WHERE perfil = {$intPerfil}";
            $strNombre = sqlGetValueFromKey($strQuery);

        }

        return $strNombre;

    }

    public function getInfoPerfil($intPerfil){

        $intPerfil = intval($intPerfil);
        $arrInfo = array();

        if( $intPerfil ){

            $strQuery = "SELECT DISTINCT perfil.perfil,
            CONCAT_WS(' ', persona.nombre1, persona.nombre2, persona.apellido1, persona.apellido2, CONCAT('de ',persona.apellido_casada) ) AS full_name,
            persona.nombre_usual,
            perfil.nombre AS nombre_perfil,
            perfil.descripcion,
            perfil.activo,
            persona.persona,
            persona.nombre_usual,
            usuario.usuario
            FROM   perfil
            LEFT JOIN persona_perfil
            ON  perfil.perfil = persona_perfil.perfil
            LEFT JOIN persona
            ON  persona_perfil.persona = persona.persona
            AND persona.eliminado = 'N'
            LEFT JOIN usuario
            ON  usuario.persona = persona.persona
            WHERE  perfil.perfil = {$intPerfil}
            ORDER BY persona.nombre_usual";
            $qTMP = db_query($strQuery);
            while( $rTMP = db_fetch_assoc($qTMP) ) {

                $arrInfo["info"]["perfil"] = $rTMP["perfil"];
                $arrInfo["info"]["nombre"] = $rTMP["nombre_perfil"];
                $arrInfo["info"]["activo"] = $rTMP["activo"];
                $arrInfo["info"]["descripcion"] = $rTMP["descripcion"];

                if( !isset($arrInfo["cuentas"]) )
                    $arrInfo["cuentas"] = array();

                if( intval($rTMP["persona"]) ){
                    $arrInfo["cuentas"][$rTMP["persona"]]["usuariowebid"] = $rTMP["persona"];
                    $strValue = empty($rTMP["usuario"]) ? $rTMP["nombre_usual"] : db_escape(user_input_delmagic($rTMP["nombre_usual"]." - ".$rTMP["usuario"]));
                    $arrInfo["cuentas"][$rTMP["persona"]]["nombre"] = $strValue;
                    $arrInfo["cuentas"][$rTMP["persona"]]["usuario"] = $rTMP["usuario"];
                }

            }
            db_free_result($qTMP);

        }

        return $arrInfo;

    }

    public function getTiposAcceso(){

        $arrTiposAcceso = array();

        $intIdioma = core_get_idioma();

        $strQuery = "SELECT tipo_acceso.tipo_acceso, tipo_acceso.codigo, tipo_acceso.orden, tipo_acceso.activo,
        tipo_acceso_idioma.nombre
        FROM   tipo_acceso_idioma, tipo_acceso
        WHERE  tipo_acceso_idioma.idioma = {$intIdioma}
        AND    tipo_acceso_idioma.tipo_acceso = tipo_acceso.tipo_acceso
        AND    tipo_acceso.activo = 'Y'
        ORDER BY tipo_acceso.orden, tipo_acceso_idioma.nombre";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ) {
            $arrTiposAcceso[$rTMP["tipo_acceso"]]["tipo_acceso"] = $rTMP["tipo_acceso"];
            $arrTiposAcceso[$rTMP["tipo_acceso"]]["nombre"] = $rTMP["nombre"];
        }
        db_free_result($qTMP);

        return $arrTiposAcceso;

    }

    public function getAccesosModulo($intModulo){

        $intModulo = intval($intModulo);

        $arrInfo = array();

        if( $intModulo ){

            $intIdioma = core_get_idioma();
            $strQuery = "SELECT acceso.acceso,
                                acceso.codigo,
                                acceso.orden,
                                acceso.acceso_pertenece,
                                acceso_idioma.nombre_menu,
                                acceso_tipo_permitido.tipo_acceso,
                                acceso.path
                        FROM    acceso
                                LEFT JOIN acceso_tipo_permitido
                                    ON  acceso.acceso = acceso_tipo_permitido.acceso,
                                acceso_idioma
                        WHERE   acceso.modulo = {$intModulo}
                        AND     acceso_idioma.acceso = acceso.acceso
                        AND     acceso_idioma.idioma = {$intIdioma}
                        AND     acceso.acceso_extra = 'N'
                        ORDER BY acceso_pertenece, orden";
            $qTMP = db_query($strQuery);
            while( $rTMP = db_fetch_assoc($qTMP) ){

                if( intval($rTMP["acceso_pertenece"]) == 0 && !empty($rTMP["path"]) ) {
                    $arrInfo[$rTMP["acceso"]]["detalle"][$rTMP["acceso"]]["acceso"] = $rTMP["acceso"];
                    $arrInfo[$rTMP["acceso"]]["detalle"][$rTMP["acceso"]]["codigo"] = $rTMP["codigo"];
                    $arrInfo[$rTMP["acceso"]]["detalle"][$rTMP["acceso"]]["nombre"] = $rTMP["nombre_menu"];

                    if( !isset($arrInfo[$rTMP["acceso"]]["detalle"][$rTMP["acceso"]]["tipo_acceso"]) )
                        $arrInfo[$rTMP["acceso"]]["detalle"][$rTMP["acceso"]]["tipo_acceso"] = array();

                    if( intval($rTMP["tipo_acceso"]) )
                        $arrInfo[$rTMP["acceso"]]["detalle"][$rTMP["acceso"]]["tipo_acceso"][$rTMP["tipo_acceso"]] = $rTMP["tipo_acceso"];
                }
                else if( intval($rTMP["acceso_pertenece"]) > 0 && !empty($rTMP["path"]) ) {
                    $arrInfo[$rTMP["acceso_pertenece"]]["detalle"][$rTMP["acceso"]]["acceso"] = $rTMP["acceso"];
                    $arrInfo[$rTMP["acceso_pertenece"]]["detalle"][$rTMP["acceso"]]["codigo"] = $rTMP["codigo"];
                    $arrInfo[$rTMP["acceso_pertenece"]]["detalle"][$rTMP["acceso"]]["nombre"] = $rTMP["nombre_menu"];

                    if( !isset($arrInfo[$rTMP["acceso_pertenece"]]["detalle"][$rTMP["acceso"]]["tipo_acceso"]) )
                        $arrInfo[$rTMP["acceso_pertenece"]]["detalle"][$rTMP["acceso"]]["tipo_acceso"] = array();

                    if( intval($rTMP["tipo_acceso"]) )
                        $arrInfo[$rTMP["acceso_pertenece"]]["detalle"][$rTMP["acceso"]]["tipo_acceso"][$rTMP["tipo_acceso"]] = $rTMP["tipo_acceso"];
                }
                else{
                    $arrInfo[$rTMP["acceso"]]["info"]["acceso"] = $rTMP["acceso"];
                    $arrInfo[$rTMP["acceso"]]["info"]["codigo"] = $rTMP["codigo"];
                    $arrInfo[$rTMP["acceso"]]["info"]["nombre"] = $rTMP["nombre_menu"];
                    if( !isset($arrInfo[$rTMP["acceso"]]["detalle"]) ) $arrInfo[$rTMP["acceso"]]["detalle"] = array();
                }
            }
        }

        return $arrInfo;

    }

    public function getAccesosExtra($intModulo){


        $intModulo = intval($intModulo);

        $arrInfo = array();

        if( $intModulo ){

            $intIdioma = core_get_idioma();
            $strQuery = "SELECT acceso.acceso, acceso.codigo, acceso.orden, acceso.acceso_pertenece,
            acceso_idioma.nombre_menu, acceso_tipo_permitido.tipo_acceso
            FROM   acceso
            LEFT JOIN acceso_tipo_permitido
            ON  acceso.acceso = acceso_tipo_permitido.acceso,
            acceso_idioma
            WHERE  acceso.modulo = {$intModulo}
            AND    acceso_idioma.acceso = acceso.acceso
            AND    acceso_idioma.idioma = {$intIdioma}
            AND    acceso.acceso_extra = 'Y'
            ORDER BY acceso_pertenece, orden";

            $qTMP = db_query($strQuery);
            while( $rTMP = db_fetch_assoc($qTMP) ){

                if( intval($rTMP["acceso_pertenece"]) ){
                    $arrInfo[$rTMP["acceso_pertenece"]]["detalle"][$rTMP["acceso"]]["acceso"] = $rTMP["acceso"];
                    $arrInfo[$rTMP["acceso_pertenece"]]["detalle"][$rTMP["acceso"]]["codigo"] = $rTMP["codigo"];
                    $arrInfo[$rTMP["acceso_pertenece"]]["detalle"][$rTMP["acceso"]]["nombre"] = $rTMP["nombre_menu"];

                    if( !isset($arrInfo[$rTMP["acceso_pertenece"]]["detalle"][$rTMP["acceso"]]["tipo_acceso"]) )
                        $arrInfo[$rTMP["acceso_pertenece"]]["detalle"][$rTMP["acceso"]]["tipo_acceso"] = array();

                    if( intval($rTMP["tipo_acceso"]) )
                        $arrInfo[$rTMP["acceso_pertenece"]]["detalle"][$rTMP["acceso"]]["tipo_acceso"][$rTMP["tipo_acceso"]] = $rTMP["tipo_acceso"];
                }

            }
        }

        return $arrInfo;
    }

    public function getPerfilAccesos($intPerfil){

        $intPerfil = intval($intPerfil);
        $arrInfo = array();

        if( $intPerfil ){

            $strQuery = "SELECT acceso, tipo_acceso
            FROM   perfil_acceso
            WHERE  perfil = {$intPerfil}";
            $qTMP = db_query($strQuery);
            while( $rTMP = db_fetch_assoc($qTMP) ) {
                $arrInfo[$rTMP["acceso"]][$rTMP["tipo_acceso"]] = $rTMP["tipo_acceso"];
            }
            db_free_result($qTMP);

        }

        return $arrInfo;

    }

    public function insertPerfil($strNombre,$strDescripcion,$strActivo){

        $strNombre = substr($strNombre, 0, 75);
        $intPerfil = 0;

        if( !empty($strNombre) ){

            $intUser = isset($_SESSION["wt"]["originalUserToTest"]) ? intval($_SESSION["wt"]["originalUserToTest"]) : intval($_SESSION["hml"]["persona"]);

            $strQuery = "call sp_perfil_insert('{$strNombre}','{$strDescripcion}', '{$strActivo}',{$intUser}, @intID )";
            db_query($strQuery);
            $strQuery ="SELECT @intID";
            $qTMP = db_query($strQuery);
            while ( $rTMP = db_fetch_assoc($qTMP)) {
                $intPerfil = $rTMP["@intID"];
            }
            db_free_result($qTMP);
        }
        return $intPerfil;
    }

    public function updatePerfil($intPerfil,$strNombre,$strDescripcion,$strActivo){

        $intPerfil = intval($intPerfil);
        $strNombre = substr($strNombre, 0, 75);

        if( $intPerfil && !empty($strNombre) ){

            $intUser = isset($_SESSION["wt"]["originalUserToTest"]) ? intval($_SESSION["wt"]["originalUserToTest"]) : intval($_SESSION["hml"]["persona"]);

            $strQuery = "call sp_perfil_update({$intPerfil},'{$strNombre}', '{$strDescripcion}', '{$strActivo}', {$intUser});";

            db_query($strQuery);
        }

    }

    public function deletePerfil($intPerfil){
        $intPerfil = intval($intPerfil);
        if( $intPerfil ){
            $strQuery = "call sp_perfil_delete({$intPerfil});";
            db_query($strQuery);

        }
    }

    public function deletePerfilAccesoModulo($intPerfil,$intModulo,$boolTodoPerfil = false){
        $intPerfil = intval($intPerfil);
        $intModulo = intval($intModulo);
        $strModulo = ($intModulo > 0) ? " AND acceso IN( SELECT acceso FROM acceso WHERE modulo = {$intModulo}) " : '';
        if( $intPerfil && ($boolTodoPerfil || (!$boolTodoPerfil && !empty($strModulo))  ) ){
            $strQuery = "call sp_perfil_acceso_delete({$intPerfil},{$intModulo});";
            db_query($strQuery);
        }
    }

    public function insertPerfilAcceso($intPerfil,$intAcceso,$intTipoAcceso){

        $intPerfil = intval($intPerfil);
        $intAcceso = intval($intAcceso);
        $intTipoAcceso = intval($intTipoAcceso);

        if( $intPerfil && $intAcceso && $intTipoAcceso ){
            $intUser = isset($_SESSION["wt"]["originalUserToTest"]) ? intval($_SESSION["wt"]["originalUserToTest"]) : intval($_SESSION["hml"]["persona"]);
            $strQuery = "call sp_perfil_acceso_insert({$intPerfil},{$intAcceso},{$intTipoAcceso})";
            db_query($strQuery);

        }

    }

    public function insertCuentaPerfil($intPersona,$intPerfil){

        $intPersona = intval($intPersona);
        $intPerfil = intval($intPerfil);

        if( $intPerfil && $intPersona ){
            $strQuery = "call sp_perfil_cuenta_insert({$intPersona},{$intPerfil})";
            db_query($strQuery);

        }

    }

    public function updateCuentaPerfil($intPersonaOriginal,$intPersona,$intPerfil){


        $intPersonaOriginal = intval($intPersonaOriginal);
        $intPersona = intval($intPersona);
        $intPerfil = intval($intPerfil);

        if( $intPersonaOriginal && $intPerfil && $intPersona ){
            $strQuery = "call sp_perfil_cuenta_update({$intPersona}, {$intPerfil},{$intPersonaOriginal});";
            db_query($strQuery);
        }
    }

    public function deleteCuentaPerfil($intPersona,$intPerfil){
        $intPerfil = intval($intPerfil);

        if( $intPerfil ){
            $strQuery = $strQuery = "call sp_perfil_cuenta_delete({$intPersona},{$intPerfil});";
            db_query($strQuery);
        }
    }

    public function getPersonasAdmin(){
        $strUsuario = "";
        $boolFirstTime = true;

        $strQuery = "SELECT persona
        FROM   usuario
        WHERE  tipo = 'admin'";
        $qTMP = db_query($strQuery);
        while($rTMP = db_fetch_assoc($qTMP)){
            if($boolFirstTime){
                $strUsuario = $rTMP["persona"];
                $boolFirstTime = false;
            }
            else{
                $strUsuario .= " , ".$rTMP["persona"];
            }
        }

        return $strUsuario;
    }

}