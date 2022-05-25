<?php
define("MODULO","usuarios");

require_once("core/main.php");
require_once("core/forms.php");
require_once("modules/usuarios/clases/usuarios_mi_cuenta_controller.php");

$objTemplate = new template();
if( !$objTemplate->check_module(MODULO) ) core_sesion_expirada();
$arrAccesos["modificar"] = "modificar";
$arrAccesos["crear"] = "crear";
$arrAccesos["eliminar"] = "eliminar";


$objController = new usuarios_mi_cuenta_controller();

$strAction = basename(__FILE__);
$strTitle = $lang[MODULO]["usuarios_mi_cuenta"];

$objController->runAjax();
$objController->setPersona();
$objController->setAleatorio();

$objController->process();

$objTemplate->template_header($strTitle);

    $objTemplate->template_button_open();
    
        $objController->drawButtons();
    
    $objTemplate->template_button_close();

    $objTemplate->template_sidebar_open(true,false,0,0);
    $objTemplate->template_sidebar_close();

    $objTemplate->template_content_open();
    
        $objController->drawContent();
        $objController->drawAlerts();

    $objTemplate->template_content_close();
    
$objTemplate->template_footer();