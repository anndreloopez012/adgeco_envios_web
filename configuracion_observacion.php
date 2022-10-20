<?php
define("MODULO","configuracion");
define("ACCESO","configuracion_observacion");
require_once("core/main.php");
require_once("core/forms.php");
require_once("modules/".MODULO."/clases/".ACCESO."_controller.php");

$objTemplate = new template();
if( !$objTemplate->check_module(MODULO) ) core_sesion_expirada();
if( !$objTemplate->check_access(MODULO,ACCESO) ) die($lang["core"]["access_denied"]);
$arrAccesos = $objTemplate->check_access(MODULO,ACCESO, true);

$objController = new configuracion_observacion_controller();
$objController->runAjax();

$strAction = basename(__FILE__);
$strTitle = $objTemplate->acceso_get_title(ACCESO);

$objTemplate->template_header($strTitle);

    $objTemplate->template_button_open();
        $objController->drawButtons();
    $objTemplate->template_button_close();

    $objTemplate->template_sidebar_open(true,false,5,42);
    $objTemplate->template_sidebar_close();

    $objTemplate->template_content_open();
        $objController->drawContent();
    $objTemplate->template_content_close();

$objTemplate->template_footer();