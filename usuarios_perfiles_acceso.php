<?php
define("ACCESO","usuarios_perfiles_acceso");
define("MODULO","usuarios");
require_once("core/main.php");
require_once("core/forms.php");
include_once("modules/usuarios/clases/usuarios_perfiles_acceso_controller.php");

$objTemplate = new template();
if( !$objTemplate->check_module(MODULO,true) ) core_sesion_expirada();
if( !$objTemplate->check_access(MODULO,ACCESO) ) die($lang["core"]["access_denied"]);
$arrAccesos = $objTemplate->check_access(MODULO,ACCESO,true);

$strAction = basename(__FILE__);
$strTitle = $objTemplate->acceso_get_title(ACCESO);

$objController = new perfil_controller();
$objController->setPerfil();
$objController->runAjax();
$objController->process();
$objTemplate->template_header($strTitle);

    $objTemplate->template_button_open();

        $objController->draw_buttons();

    $objTemplate->template_button_close();

    $objTemplate->template_sidebar_open(true,false,2,4);
    $objTemplate->template_sidebar_close();

    $objTemplate->template_content_open();

       $objController->drawAlerts();
       $objController->draw_content();

    $objTemplate->template_content_close();

$objTemplate->template_footer();

?>
