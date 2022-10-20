<?php

require_once("core/main.php");
require_once("core/forms.php");
$objTemplate = new template();
if( $cfg["core"]["type"]["valor"] == "Privado" ) {
    if( core_is_login() ) {
        $strIsAleatorio = core_getIsPasswordAleatorio();
        if($strIsAleatorio == "Y")
        {
            ?>
            <script>
                document.location.href = "usuarios_mi_cuenta.php?aleatorio=Y&strAlert=ale";
            </script>
            <?php    
        }
        else{
            $objTemplate->private_dashboard();    
        }
        
    }
    else {
        $objTemplate->private_index();
    }
}
else {
    $objTemplate->public_index();

}