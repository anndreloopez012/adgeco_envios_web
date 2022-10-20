<?php

if (strstr($_SERVER["PHP_SELF"], "/core/")) die ("You can't access this file directly...");
date_default_timezone_set("America/Guatemala");
require_once("db.php");
require_once("functions.php");
require_once("login.php");
require_once("config.php");

session_start();
$arrGlobalNotFreedQueries = array();
$cfg = array();
$lang = array();
$arrAccesosPersonaToCheck = array();

$objResource = db_connect($arrConfigSite["db"]["host"],$arrConfigSite["db"]["database"],$arrConfigSite["db"]["user"],$arrConfigSite["db"]["password"]);

//SI HUBO ERROR EN LA BASE DE DATOS, ENTONCES LO MANDO A LA PANTALLA DE CONSTRUCCION
if( $objResource ) {

    core_load_configuracion("core");
    core_load_lang("core");

    if( file_exists("templates/{$cfg["core"]["template"]["valor"]}/main.php") ) {
        require_once("templates/{$cfg["core"]["template"]["valor"]}/main.php");
        $strUsuario = isset($_SESSION["hml"]["usuario"]) ? $_SESSION["hml"]["usuario"] : "";
        //AQUI HAGO LAS VALIDACIONES DE LOGIN
        //reviso que si se ha cambiado de URL limpie el login
        if(isset($_SESSION["hml"])) {

            if($_SESSION["hml"]["url"] != $cfg["core"]["url"]["valor"]) {
                clear_login();
            }

        }
        else {
            clear_login();
        }

        if( check_session_timeout() ) {

            // Si estaba loginieado
            check_autologin(); // Limpia las variables de sesion
            if(!$_SESSION["hml"]["logged"]) {
                // Notifica que la sesion expiró
                $_SESSION["hml"]["sesion_expirada"] = "true";
                $_SESSION["hml"]["usuario"] = $strUsuario;
                ?>
                <script>
                    document.location.href = "index.php"
                </script>
                <?php
                die();

            }
            
        }

        if( isset($_POST["login_name"]) && !empty($_POST["login_name"]) && isset($_POST["login_passwd"]) && $_POST["login_passwd"] ) {

            do_login($_POST["login_name"],$_POST["login_passwd"],( isset($_POST["login_auto"]) && intval($_POST["login_auto"]) == 1 ), isset($_POST["force_disconnect"]));

        }

        //si no está logineado que lo revise en el cookie
        if(!$_SESSION["hml"]["logged"]) {
            check_autologin();
        }

        //si le dio logout me salgo de todos lados
        if(isset($_GET["act"]) && $_GET["act"]=="logout") {
            clear_login();
            delete_autologin();
            //session_destroy();
        }

        //AQUI TERMINA LAS VALIDACIONES DE LOGIN

    }
    else
        header("Location: under_construction.html");
        
}
else
    header("Location: under_construction.html");

class main {

    public function __construct() {

    }

    public function check_module($strModulo, $boolIncludeMain = true) {
        
        $boolCheckModule = false;
        $strWhere = "";
        
        if( core_is_login() )
            $strWhere .= " AND privado = 'Y'";
        else
            $strWhere .= " AND publico = 'Y'";
        
        $strQuery = "SELECT modulo FROM modulo 
                     WHERE  codigo = '{$strModulo}' 
                     AND    activo = 'Y'
                     {$strWhere}";
                     
        $intModulo = sqlGetValueFromKey($strQuery);
        $intModulo = intval($intModulo);
        
        if( $intModulo ) {
            
            $strQuery = "SELECT modulo.codigo
                         FROM   modulo_dependencia, modulo
                         WHERE  modulo_dependencia.dependencia = modulo.modulo
                         AND    modulo_dependencia.modulo = {$intModulo}";
                         
            $qTMP = db_query($strQuery);
            $boolDependencia = true;
            while( $rTMP = db_fetch_assoc($qTMP) ) {
                
                $boolTMP = $this->check_module($rTMP["codigo"],$boolIncludeMain);
                if( !$boolTMP )
                    $boolDependencia = false;
                
            }
            
            if( $boolDependencia )
                $boolCheckModule = true;
            else
                $boolCheckModule = false;
                
            
            if( $boolCheckModule && $boolIncludeMain ) {
                
                include_once("modules/{$strModulo}/main.php");
                
            }
            
        }
        
        return $boolCheckModule;
        
    }

    public function check_access($strModulo, $strAcceso, $boolReturnTipos = false) {
        
        global $arrAccesosPersonaToCheck;
        
        if( !isset($arrAccesosPersonaToCheck[$strAcceso]) ) {
            
            $arrAccesosPersonaToCheck[$strAcceso] = array();
            
            //$boolTieneAcceso = false;
            if( core_is_login() ) {
                
                if( $_SESSION["hml"]["tipo_usuario"] == "admin" ){
                    
                    //$boolTieneAcceso = true;
                    
                    $strQuery = "SELECT codigo codigo_tipo_acceso
                                 FROM   tipo_acceso
                                 WHERE  activo = 'Y'
                                 ORDER BY orden";
                    
                }
                else {
                    
                    $strQuery = "SELECT tipo_acceso.codigo codigo_tipo_acceso
                                 FROM   perfil_acceso,
                                        perfil,
                                        persona_perfil,
                                        acceso,
                                        modulo,
                                        tipo_acceso
                                 WHERE  acceso.activo = 'Y'
                                 AND    perfil.activo = 'Y'
                                 AND    modulo.activo = 'Y'
                                 AND    acceso.privado = 'Y'
                                 AND    acceso.codigo = '{$strAcceso}'
                                 AND    modulo.codigo = '{$strModulo}'
                                 AND    persona_perfil.persona = {$_SESSION["hml"]["persona"]}
                                 AND    perfil_acceso.perfil = perfil.perfil
                                 AND    perfil_acceso.acceso = acceso.acceso
                                 AND    acceso.modulo = modulo.modulo
                                 AND    persona_perfil.perfil = perfil.perfil
                                 AND    tipo_acceso.tipo_acceso = perfil_acceso.tipo_acceso
                                 ORDER BY tipo_acceso.orden";
                    
                    /*$strQuery = "SELECT acceso.acceso
                                 FROM   perfil_acceso,
                                        perfil,
                                        persona_perfil,
                                        acceso,
                                        modulo
                                 WHERE  acceso.activo = 'Y'
                                 AND    perfil.activo = 'Y'
                                 AND    modulo.activo = 'Y'
                                 AND    acceso.privado = 'Y'
                                 AND    acceso.codigo = '{$strAcceso}'
                                 AND    modulo.codigo = '{$strModulo}'
                                 AND    persona_perfil.persona = {$_SESSION["hml"]["persona"]}
                                 AND    perfil_acceso.tipo_acceso = 1
                                 AND    perfil_acceso.perfil = perfil.perfil
                                 AND    perfil_acceso.acceso = acceso.acceso
                                 AND    acceso.modulo = modulo.modulo
                                 AND    persona_perfil.perfil = perfil.perfil";*/
                    
                    /*$intAcceso = sqlGetValueFromKey($strQuery);
                    $intAcceso = intval($intAcceso);
                    
                    if( $intAcceso ) $boolTieneAcceso = true;*/
                    
                }
                
                $qTMP = db_query($strQuery);
                while( $rTMP = db_fetch_array($qTMP) ) {
                    $arrAccesosPersonaToCheck[$strAcceso][$rTMP["codigo_tipo_acceso"]] = $rTMP["codigo_tipo_acceso"];
                }
                db_free_result($qTMP);
                
                
                
            }
            else {
                
                $strQuery = "SELECT acceso.acceso
                             FROM   acceso,
                                    modulo
                             WHERE  acceso.activo = 'Y'
                             AND    modulo.activo = 'Y'
                             AND    acceso.codigo = '{$strAcceso}'
                             AND    modulo.codigo = '{$strModulo}'
                             AND    acceso.publico = 'Y'
                             AND    acceso.modulo = modulo.modulo";
                             
                
                $intAcceso = sqlGetValueFromKey($strQuery);
                $intAcceso = intval($intAcceso);
                
                if( $intAcceso ){
                    
                    //$boolTieneAcceso = true;
                    
                    $arrAccesosPersonaToCheck[$strAcceso]["consultar"] = "consultar";
                    
                }
            }
            
            
            
        }
        
        //return $boolTieneAcceso;
        
        if( $boolReturnTipos )
            $varReturn = $arrAccesosPersonaToCheck[$strAcceso];
        else
            $varReturn = isset($arrAccesosPersonaToCheck[$strAcceso]["consultar"]);

        return $varReturn;
        
        
    }

    public function acceso_get_title($strAcceso) {
        
        $intIdioma = core_get_idioma();
        
        $strQuery = "SELECT acceso_idioma.nombre_pantalla
                     FROM   acceso_idioma,
                            acceso
                     WHERE  acceso.codigo = '{$strAcceso}'
                     AND    acceso_idioma.idioma = {$intIdioma}
                     AND    acceso.acceso = acceso_idioma.acceso";
                     
        $strNombrePantalla = sqlGetValueFromKey($strQuery);
        return $strNombrePantalla;
        
    }

}