<?php

function core_is_login() {

    $boolLogin = false;

    if( isset($_SESSION["hml"]["persona"]) && $_SESSION["hml"]["persona"] > 0 )
        $boolLogin = $_SESSION["hml"]["logged"];

    return $boolLogin;

}


function check_session_timeout($boolLogPublic = true) {
global $cfg, $lang;

    $intUserID = (isset($_SESSION["hml"]["logged"]) && $_SESSION["hml"]["logged"]) ? intval($_SESSION["hml"]["persona"]) : 0;
    $strSessID = session_id();
    $intTimeOut = $cfg["core"]["session_timeout"]["valor"];

    $strQuery = "DELETE FROM online
                 WHERE  persona = {$intUserID}
                 AND    hora < DATE_SUB(NOW(), INTERVAL {$intTimeOut} MINUTE) ";
    db_query($strQuery);

    $strQuery = "SELECT COUNT(*) FROM online WHERE online ='{$strSessID}' AND persona = {$intUserID}";
    $intNumRows = sqlGetValueFromKey($strQuery);

    $boolReturn = false;

    if($intNumRows) {

        // Si existe el registro, actualizo el tiempo.
        $strQuery = "UPDATE online SET hora = NOW() WHERE online = '{$strSessID}' AND persona = {$intUserID}";
        db_query($strQuery);
    }
    else {

        // Si NO existe el registro.
        if ( $intUserID > 0) {

            // Si estaba en linea, lo deja afuera...
            clear_login();
            $boolReturn = true;
        }

        $strQuery = "SELECT online FROM online WHERE online = '{$strSessID}'";
        $qTMP = db_query($strQuery);
        $rTMP = db_fetch_array($qTMP);
        db_free_result($qTMP);

        if( $rTMP )
            $strQuery = "UPDATE online
                         SET    hora = NOW(),
                                persona = NULL
                         WHERE  online = '{$strSessID}'";
        else
            $strQuery = "INSERT INTO online (online , HORA, persona) VALUES('{$strSessID}', NOW(), NULL)";

        db_query($strQuery);

    }

    return $boolReturn;
}


/**
* Funcion que llena la informacion del browser en la variable de session
*
*/
function core_fillBrowserInformation() {

    $_SESSION["hml"]["browser"]["version"] = (isset($_SERVER["HTTP_USER_AGENT"]))?$_SERVER["HTTP_USER_AGENT"]:"";
    $strTMP = $_SESSION["hml"]["browser"]["version"];

    $intTMP = strpos($strTMP, "(");
    if ($intTMP) $strTMP = substr($strTMP, $intTMP);
    $strTMP2 = substr($strTMP, 1, strlen($strTMP) - 2);
    $arrTMP = explode("; ", $strTMP2);

    $arrInfo = array();
    if (isset($arrTMP[2])) {
        $arrInfo["OS"] = trim($arrTMP[2]);
        if ($arrTMP[1]=="U") {
            $intTMP = strpos($strTMP, ")", $intTMP);
            $strExtra = substr($strTMP, $intTMP + 1);
            $arrInfo["browser"] = trim($strExtra);
        }
        else {
            $arrInfo["browser"] = trim($arrTMP[1]);
        }
    }
    else {
        $arrInfo["OS"] = $arrTMP[0];
        $arrInfo["browser"] = "Unknown";
    }
    // Agrego al array las variables de chequeo de version...
    $arrInfo["boolIsWindows"] = substr($arrInfo["OS"], 0, 3) == "Win";
    $arrInfo["boolIsMac"] = !(strpos($arrInfo["OS"],"Mac")===false);

    $arrInfo["boolIsMSIE"] = substr($arrInfo["browser"], 0, 4) == "MSIE";
    $arrInfo["IEVer"] = 0;
    if ($arrInfo["boolIsMSIE"]) {
        $arrTMP = explode(" ", $arrInfo["browser"]);
        $arrInfo["IEVer"] = $arrTMP[1];
    }
    $arrInfo["boolIsChrome"]  = (!(strpos($arrInfo["browser"],"Chrome")===false));
    $arrInfo["boolIsSafari"]  = (!(strpos($arrInfo["browser"],"Safari")===false) && !$arrInfo["boolIsChrome"]);
    $arrInfo["boolIsMozilla"] = (!(strpos($arrInfo["browser"],"Gecko")===false) && !$arrInfo["boolIsSafari"] && !$arrInfo["boolIsChrome"]);

    $_SESSION["hml"]["browser"]["detail"] = $arrInfo;
}

/**
 * @return void
 * @desc Esta funcion BORRA la sesion y resetea los datos de la misma.
*/
function clear_login() {

    global $cfg;

    // All Session vars used may be listed here
    $strSessID = session_id();

    //ELIMINO EL REGISTRO DE LAS PERSONAS QUE ESTAN EN LINEA, CON EL ID DE LA SESSION
    $strQuery = "DELETE FROM online WHERE online = '{$strSessID}'";
    db_query($strQuery);

    session_unset();

    //AQUI REGISTRO LA VISITA

    $_SESSION["hml"] = array();
    $_SESSION["hml"]["persona"] = 0;
    $_SESSION["hml"]["usuario"] = "";
    $_SESSION["hml"]["nombre"] = "";
    $_SESSION["hml"]["tipo_usuario"] = "normal";
    $_SESSION["hml"]["logged"] = false;
    $_SESSION["hml"]["url"] = $cfg["core"]["url"]["valor"];
    $_SESSION["hml"]["idioma"] = 1;

    core_fillBrowserInformation();

}


/**
* Funcion que llena la variable de session con el primer ingreso de la informacion del login
* @param integer $uid: Usuario logineado
* @param: boolean $autologin: Revisa si establece los cookies del login
* @param: string $login_passwd: Password del login
*
*
*/
function fill_login( $intPersona, $boolAutoLogin = false, $strPassword = "" ) {

    global $lang;
    $intPersona = intval($intPersona);
    $strQuery = "SELECT persona.persona,
                        persona.nombre_usual,
                        persona.foto,
                        usuario.usuario,
                        usuario.tipo tipoUsuario,
                        usuario.idioma
                 FROM   persona,
                        usuario
                 WHERE  persona.persona = {$intPersona}
                 AND    persona.activo = 'Y'
                 AND    usuario.bloqueado = 'N'
                 AND    persona.persona = usuario.persona";

    //debugQuery($strQuery);

    $arrInfoPersona = sqlGetValueFromKey($strQuery);

    if( is_array($arrInfoPersona) ) {

        $_SESSION["hml"]["persona"] = $intPersona;
        $_SESSION["hml"]["usuario"] = $arrInfoPersona["usuario"];
        $_SESSION["hml"]["nombre"] = $arrInfoPersona["nombre_usual"];
        $_SESSION["hml"]["tipo_usuario"] = $arrInfoPersona["tipoUsuario"];
        $_SESSION["hml"]["logged"] = true;
        $_SESSION["hml"]["idioma"] = $arrInfoPersona["idioma"];
        $_SESSION["hml"]["imagen_persona"] = $arrInfoPersona["foto"];

        core_fillBrowserInformation();
        $strSessID = session_id();

        $strQuery = "SELECT online FROM online WHERE online = '{$strSessID}'";
        $qTMP = db_query($strQuery);
        $rTMP = db_fetch_array($qTMP);

        if( $rTMP ) {

            $strQuery = "UPDATE online
                         SET    persona = '{$intPersona}',
                                hora = NOW()
                         WHERE  online = '{$strSessID}'";

        }
        else {

            $strQuery = "INSERT INTO online
                         (online, persona, hora)
                         VALUES ('{$strSessID}', {$intPersona}, NOW())";

        }

        db_free_result($qTMP);
        db_query($strQuery);

        if( $boolAutoLogin ) {
            create_autologin($strPassword);
        }

    }
    else {

        $arrErrores["loginError"] = $lang[1]["login_no_existe"];

    }

}

/**
* Establece los cookies para el login
* @param string $passwd: Password de ingreso
*
*/

function create_autologin($strPassword){

    global $cfg;

    $sess = $_SESSION["hml"];
    if ( !isset($_SESSION["hml"]["logged"]) || !$_SESSION["hml"]["logged"]) return;

    $cookie = sprintf( "%010d", $_SESSION["hml"]["persona"] );
    $cookie .= substr(md5($_SESSION["hml"]["usuario"]),0,15);
    $cookie .= substr(md5($strPassword),0,15);

    $strName = "hml_".str_replace(array(" ","/","http://","www","."),"", $cfg["core"]["url"]["valor"]);
    setcookie($strName, $cookie, time() + 43200);
}

/**
* Elimina el cookie de ingreso de login
*
*/
function delete_autologin(){
    global $cfg;

    $strName = "hml_".str_replace(array(" ","/","http://","www","."),"", $cfg["core"]["url"]["valor"]);

    if( isset($_COOKIE[$strName]) ) {

        $cookie = $_COOKIE[$strName];
        setcookie($strName, $cookie, time()-3600);

    }

}

/**
* Revisa que se este logineado
*
*/

function check_autologin(){

    // If user not logged, try autologin
    global $cfg;

    // 20100610 AG: Esta informacion del cookie
    $strName = "hml_".str_replace(array(" ","/","http://","www","."),"", $cfg["core"]["url"]["valor"]);

    if( !isset( $_COOKIE[$strName] ) ) return;

    $cookie = $_COOKIE[$strName];

    $intPersona = intval(substr($cookie,0,10));
    $strQuery = "SELECT persona.persona, usuario.usuario, usuario.password
                 FROM   persona, usuario
                 WHERE  persona.persona = '{$intPersona}'
                 AND    persona.activo = 'Y'
                 AND    usuario.bloqueado = 'N'
                 AND    persona.persona = usuario.persona";

    $arrInfoPersona = sqlGetValueFromKey($strQuery);

    if(!is_array($arrInfoPersona)) {
        delete_autologin();
        clear_login();
        return;
    }

    $mdh = substr($cookie,10,30);
    $mdr = substr(md5($arrInfoPersona["usuario"]),0,15).substr(md5($arrInfoPersona["password"]),0,15);

    //REVISO QUE EL USUARIO NO ESTE LOGINEADO YA EN OTRA COMPUTADORA, SI LO ESTA, REVISAR QUE TENGA MULTI SESION
    $strQuery = "SELECT usuario.persona, usuario.multi_session, online.online
                 FROM   usuario
                            LEFT JOIN online
                                ON usuario.persona = online.persona
                 WHERE  usuario.persona = {$intPersona}";

    $arrInfoMultiSesion = sqlGetValueFromKey($strQuery);

    if( !is_null($arrInfoMultiSesion["online"]) && $arrInfoMultiSesion["multi_session"] == "N" ) {
        delete_autologin();
        clear_login();
    }

    if( $mdh != $mdr ){
        delete_autologin();
        clear_login();
        return;
    }

    fill_login($intPersona);

}

/**
* Funcion que Hace login
*
*/
function do_login( $strUsuario, $strPassword, $boolAutoLogin = false, $boolForceDisconect = false ){

    global $lang, $boolYaConectado, $strMensajeError;
    $boolYaConectado = false;
    $strMensajeError = "";
    clear_login();
    $strSessID = session_id();

    $strUsuario = db_escape(user_input_delmagic($strUsuario));
    $strPassword = db_escape(md5(user_input_delmagic($strPassword)));

    $strQuery = "SELECT usuario.persona
                 FROM   usuario, persona
                 WHERE  usuario.usuario = '{$strUsuario}'
                 AND    usuario.password = '{$strPassword}'
                 AND    usuario.bloqueado = 'N'
                 AND    persona.activo = 'Y'
                 AND    usuario.persona = persona.persona";

    $qTMP = db_query($strQuery);
    $rTMP = db_fetch_assoc($qTMP);
    $intPersona = 0;
    if( isset($rTMP["persona"]) ) $intPersona = intval($rTMP["persona"]);

    if( $intPersona ) {

        $boolPuedeEntrar = true;

        //REVISO QUE EL USUARIO NO ESTE LOGINEADO YA EN OTRA COMPUTADORA, SI LO ESTA, REVISAR QUE TENGA MULTI SESION
        $strQuery = "SELECT usuario.persona, usuario.multi_session, online.online
                     FROM   usuario
                                LEFT JOIN online
                                    ON usuario.persona = online.persona
                     WHERE  usuario.persona = {$intPersona}";

        $arrInfoMultiSesion = sqlGetValueFromKey($strQuery);
        //drawDebug($arrInfoMultiSesion);
        //drawDebug(( !is_null($arrInfoMultiSesion["online"]) && $arrInfoMultiSesion["multi_session"] == "N" ));
        //drawDebug($boolPuedeEntrar);

        if( !is_null($arrInfoMultiSesion["online"]) && $arrInfoMultiSesion["multi_session"] == "N" ) {
            $boolYaConectado = true;
            if( $boolForceDisconect ) {
                db_query("DELETE FROM online WHERE persona = {$intPersona}");
            }
            else {
                $boolPuedeEntrar = false;
                $boolYaConectado = true;
                $arrErrores["loginYaConectado"] = $lang["core"]["login_ya_conectado"];
                $strMensajeError = $lang["core"]["login_ya_conectado"];
            }

        }

        if( $boolPuedeEntrar ) {
            fill_login($intPersona, $boolAutoLogin, $strPassword);
        }

    }

}

