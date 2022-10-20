<?php
define("MODULO","clientes");
define("ACCESO","clientes_cliente");
require_once("core/main.php");
require_once("core/forms.php");
require_once("modules/".MODULO."/clases/".ACCESO."_controller.php");

$objTemplate = new template();
if( !$objTemplate->check_module(MODULO) ) core_sesion_expirada();
if( !$objTemplate->check_access(MODULO,ACCESO) ) die($lang["core"]["access_denied"]);
$arrAccesos = $objTemplate->check_access(MODULO,ACCESO, true);

$strCliente = isset($_GET["cliente"]) ? $_GET["cliente"] : -1;
$strClienteHistorico = isset($_GET["historico"]) ? $_GET["historico"] : "cfcd208495d565ef66e7dff9f98764da";

$objController = new clientes_cliente_controller($strCliente,$strClienteHistorico);
$objController->runAjax();

$strAction = basename(__FILE__);
$strTitle = $objTemplate->acceso_get_title(ACCESO);

$objTemplate->template_header($strTitle);
$objTemplate->template_include_libreria("bootstrap-toggle/bootstrap-toggle");
    $objTemplate->template_button_open();
        $objController->drawButtons();
    $objTemplate->template_button_close();

    $objTemplate->template_sidebar_open(true,false,3,40);
    $objTemplate->template_sidebar_close();

    $objTemplate->template_content_open();
        $objController->drawContent();
    $objTemplate->template_content_close();

$objTemplate->template_footer();