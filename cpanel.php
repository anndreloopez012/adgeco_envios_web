<?php

define("ACCESO","cpanel");
require_once("core/main.php");
require_once("core/forms.php");
require_once("modules/core/clases/cpanel_controller.php");

$objTemplate = new template();
if( !$objTemplate->check_module("core",false) ) core_sesion_expirada();
if( !$objTemplate->check_access("core",ACCESO) ) die($lang["core"]["access_denied"]);

$strAction = basename(__FILE__);
$strTitle = $objTemplate->acceso_get_title(ACCESO);

$strQuerysPrint = "";

$objCpanel = new cpanel_controller();
$objCpanel->complements();

$objTemplate->template_header($strTitle);
    
    $objTemplate->template_button_open();
        $objCpanel->drawButtons();
    $objTemplate->template_button_close();

    $objCpanel->process();

    $objTemplate->template_sidebar_open(true,false,1,2);
    $objTemplate->template_sidebar_close();

    $objTemplate->template_content_open();
        $objCpanel->drawSidebar();
        $objCpanel->drawContent();
    $objTemplate->template_content_close();

$objTemplate->template_footer();