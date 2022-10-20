<?php
require_once("core/main.php");
require_once("core/forms.php");

$objTemplate = new template();
$strAction = basename(__FILE__);

$objController = new reset_password_controller();
$objController->drawAjax();
$objController->process();


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title><?php print $lang["core"]["title"]; ?></title>
        <link rel="shortcut icon" href="templates/idc/images/icon.png"/>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.4 -->
        <link href="templates/idc/libraries/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Font Awesome Icons -->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="templates/idc/libraries/AdminLTE.css" rel="stylesheet" type="text/css" />

        <link href="templates/idc/libraries/plugins/pnotify/pnotify.min.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- jQuery 2.1.4 -->
        <script src="templates/idc/libraries/jQuery-2.1.4.min.js" type="text/javascript"></script>
        <!-- Bootstrap 3.3.2 JS -->
        <script src="templates/idc/libraries/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="templates/idc/libraries/plugins/pnotify/pnotify.min.js" type="text/javascript"></script>
        <script src="core/core.js" type="text/javascript"></script>

    </head>
    <body class="login-page">
    <div class="content">
<?php

//$objTemplate->template_header($lang["core"]["title"].' - Restauracion de password');

    //$objTemplate->template_content_open();

        $objController->drawContent();
    //$objTemplate->template_content_close();

//$objTemplate->template_footer(false);
?>
</div>
</body>
</html>
<?php


class reset_password_controller {

    private $objModel = null;
    private $objView = null;

    public function __construct() {

        $this->objModel = new reset_password_model();
        $this->objView = new reset_password_view();

    }

    public function drawAjax() {

        if( isset($_POST['ajax']) && $_POST['ajax'] == 1 ) {
            $this->objView->drawAjaxCheckUsuarioEmail();
        }
        elseif( isset($_POST['ajax']) && $_POST['ajax'] == 2 ) {
            $this->objView->drawAjaxCheckCodigo();
        }

    }

    public function process() {
        global $cfg, $strAction;

        if( isset($_POST['hdnAction']) && $_POST['hdnAction'] == 1 ) {

            $intUsuarioWebId = isset($_POST['hdnUsuarioWebId']) ? intval($_POST['hdnUsuarioWebId']) : 0;
            $strUsuarioWebId = md5($intUsuarioWebId);
            $strEmail = isset($_POST['txtEmail']) ? $_POST['txtEmail'] : '';
            $strCodigo = md5(uniqid());

            $this->objModel->updateUsuarioRestauracionPassword($intUsuarioWebId, $strCodigo);
            $arrUsuarioWebXCuenta = $this->objModel->getUsuarioWebXCuenta($intUsuarioWebId);

            $strHtml = '<table width="90%" cellspacing="2" cellpading="2">'.
                '<tr>'.
                    '<td width="100%">'.
                        'Estimado(a) '.$arrUsuarioWebXCuenta['nombre'].
                    ',</td>'.
                '</tr>'.
                '<tr>'.
                    '<td>'.
                        '&nbsp;'.
                    '</td>'.
                '</tr>'.
                '<tr>'.
                    '<td>'.
                        'Para poder restaurar tu password debes de ingresar al siguiente enlace <a href="'.$_SERVER["SERVER_NAME"].'/reset_password.php?act=3&c='.$strCodigo.'&uwi='.$strUsuarioWebId.'" target="_blank">'.$_SERVER["SERVER_NAME"].'/reset_password.php?act=3&c='.$strCodigo.'&uwi='.$strUsuarioWebId.'</a> y digitar el codigo que se te proporciona a continuacion.'.
                    '</td>'.
                '</tr>'.
                '<tr>'.
                    '<td>'.
                        '&nbsp;'.
                    '</td>'.
                '</tr>'.
                '<tr>'.
                    '<td>'.
                        '<b>Codigo:</b>&nbsp;'.$strCodigo.
                    '</td>'.
                '</tr>'.
                '<tr>'.
                    '<td>'.
                        '&nbsp;'.
                    '</td>'.
                '</tr>'.
                '<tr>'.
                    '<td>'.
                        '&nbsp;'.
                    '</td>'.
                '</tr>'.
                '<tr>'.
                    '<td>'.
                        'Gracias,'.
                    '</td>'.
                '</tr>'.
                '<tr>'.
                    '<td>'.
                        'Administracion'.
                    '</td>'.
                '</tr>'.
            '</table>';

            $arrMail = array();
            $arrMail["from"] = "administracion@{$_SERVER["SERVER_NAME"]}";
            $arrMail["subject"] = "Restablecer password IDC";
            $arrMensaje = array();
            $arrMensaje["html"] = $strHtml;

            core_mail(false,$arrMail,$strEmail,$arrMensaje);

        }

        if( isset($_POST['hdnAction']) && $_POST['hdnAction'] == 2 ) {

            $intUsuarioWebId = isset($_POST['hdnUsuarioWebId']) ? intval($_POST['hdnUsuarioWebId']) : 0;
            $strNewPassword = isset($_POST['txtNewPassword']) ? db_escape(user_input_delmagic($_POST['txtNewPassword'])) : '';
            $strNewPassword = md5($strNewPassword);

            $this->objModel->updateUsuarioWebxCuentaPassword($intUsuarioWebId, $strNewPassword);

        }

    }

    public function drawContent() {
        global $lang;

        $intAct = isset($_GET['act']) ? intval($_GET['act']) : 0;
        switch ($intAct) {
            case 1:
                $this->objView->drawContentPaso1();
                break;
            case 2:
                $this->objView->drawContentPaso2();
                break;
            case 3:
                $this->objView->drawContentPaso3();
                break;
            case 4:
                $this->objView->drawContentPaso4();
                break;
            case 5:
                $this->objView->drawContentPaso5();
                break;
            default:
                db_close();
                die($lang["core"]["access_denied"]);
        }
    }

}

class reset_password_view {

    private $objModel = null;

    public function __construct() {

        $this->objModel = new reset_password_model();

    }

    public function drawContentPaso1() {
        global $strAction, $objTemplate, $strPais;
        $objForm = new form("frmResetPassword","frmResetPassword","POST",$strAction);
        $objForm->form_openForm();
        ?>
        <script>
            function fntCheckUsuarioEmail() {
                $.ajax({
                    url:"<?php print $strAction; ?>",
                    async: false,
                    data: $("#frmResetPassword").serialize()+'&ajax=1',
                    type: 'post',
                    dataType: 'json',
                    error: function() {
                        $("#hdnUsuarioWebId").val(0);
                    },
                    success: function(data) {
                        if( data.intUsuarioWebId.length > 0 ) {
                            $("#hdnUsuarioWebId").val(data.intUsuarioWebId);
                        }
                        else {
                            $("#hdnUsuarioWebId").val(0);
                        }
                    }
                });
            }
            function fntCheckForm() {
                boolValido = true;

                fntCheckUsuarioEmail();

                if( $("input[name='hdnUsuarioWebId']").val() == 0 ) {
                    boolValido = false;
                    draw_Alert("danger", "Error: ", "Usuario o correo electronico incorrecto.", true);
                }

                if( boolValido ) {
                    document.frmResetPassword.action = '<?php print $strAction; ?>?act=2';
                    document.frmResetPassword.submit();
                }
            }
        </script>
        <div class="row">
            <div class="col-lg-2 col-lg-offset-5">
                <br>
                <a href="index.php">
                    <img src="<?php print core_getImagePath("logo_login.png"); ?>" alt="" class="img-responsive center-block" style="">
                </a>
                <br>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4">
                <br>
                <h3>RESTAURACION DE PASSWORD</h3>
                <br>
                <p><?php print "Olvido su password?<br><br>Por favor ingrese usuario y correo electronico registrado en el sitio.<br><br>Esto generara un link y un codigo que seran enviados a su correo electronico para poder realizar el cambio de password.<br><br>Si no cuenta con un correo electronico por favor comunicarse con administracion."; ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">&nbsp;</div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-lg-offset-5">
                <?php
                $objForm->add_input_hidden('hdnAction', '1', true);
                $objForm->add_input_hidden('hdnUsuarioWebId', '0', true);
                $objForm->add_input_text('txtUsuario', '', '', true, 'Usuario');
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-lg-offset-5">
                <?php
                $objForm->add_input_text('txtEmail', '', '', true, 'Correo electronico');
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-lg-offset-5 text-center">
                <?php
                $objTemplate->draw_button('btnRestaurarContrasena', 'Restaurar password', 'fntCheckForm()', '', 'sm', '');
                ?>
            </div>
        </div>
        <?php
        $objForm->form_closeForm();

    }

    public function drawContentPaso2() {

        ?>
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4">
                <br>
                <a href="index.php">
                    <img src="<?php print core_getImagePath("logo_login.png"); ?>" alt="" class="img-responsive center-block">
                </a>
                <br>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                <h3>RESTAURACION DE PASSWORD</h3>
                <p><?php print "Se ha enviado un mensaje a su correo electronico para continuar con la restauracion de password."; ?></p>
            </div>
        </div>
        <?php

    }

    public function drawContentPaso3() {

        global $lang, $strAction, $objTemplate;
        $intUsuarioWebId = $this->objModel->getUserId($_GET['uwi']);
        $strCodigo = isset($_GET['c']) ? $_GET['c'] : '';

        if( $this->objModel->checkCodigo($intUsuarioWebId, $strCodigo) > 0 ) {

            $objForm = new form("frmResetPassword","frmResetPassword","POST",$strAction);
            $objForm->form_openForm();
            ?>
            <script>
                function fntCheckCodigo() {
                    $.ajax({
                        url:"<?php print $strAction; ?>",
                        async: false,
                        data: $("#frmResetPassword").serialize()+'&ajax=2',
                        type: 'post',
                        dataType: 'json',
                        error: function() {
                            $("input[name='hdnValido']").val(0);
                        },
                        success: function(data) {
                            if( data.intUsuarioWebId.length > 0 ) {
                                $("input[name='hdnValido']").val(data.intUsuarioWebId);
                            }
                            else {
                                $("input[name='hdnValido']").val(0);
                            }
                        }
                    });
                }
                function fntCheckForm() {
                    boolValido = true;
                    fntCheckCodigo();
                    if( $("input[name='hdnValido']").val() == 0 ) {
                        boolValido = false;
                        draw_Alert("danger", "Error: ", "Codigo ingresado incorrecto o no esta activo.", true);
                    }
                    if( boolValido ) {
                        document.frmResetPassword.action = '<?php print $strAction; ?>?act=4&c=<?php print $_GET['c']; ?>&uwi=<?php print $_GET['uwi']; ?>';
                        document.frmResetPassword.submit();
                    }
                }
            </script>
            <div class="row">
                <div class="col-lg-4 col-lg-offset-4">
                    <br>
                    <a href="index.php">
                        <img src="<?php print core_getImagePath("logo_login.png"); ?>" alt="" class="img-responsive center-block">
                    </a>
                    <br>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-lg-offset-4 text-center">
                    <h3>RESTAURACIoN DE PASSWORD</h3>
                    <p><?php print "Ingresa el codigo que fue enviado a tu correo electronico."; ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-lg-offset-4">
                    <?php
                    $objForm->add_input_hidden('hdnAction', '0', true);
                    $objForm->add_input_hidden('hdnUsuarioWebId', $intUsuarioWebId, true);
                    $objForm->add_input_hidden('hdnValido', '0', true);
                    $objForm->add_input_text('txtCodigo', '', '', true, 'Codigo');
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-lg-offset-4 text-center">
                    <?php $objTemplate->draw_button('btnVerificarCodigo', 'Siguiente', 'fntCheckForm()', '', 'sm', ''); ?>
                </div>
            </div>
            <?php
            $objForm->form_closeForm();
        }
        else {
            db_close();
            die($lang["core"]["access_denied"]);
        }

    }

    public function drawContentPaso4() {

        global $lang, $strAction, $objTemplate;
        $intUsuarioWebId = $this->objModel->getUserId($_GET['uwi']);
        $strCodigo = isset($_GET['c']) ? $_GET['c'] : '';

        if( $this->objModel->checkCodigo($intUsuarioWebId, $strCodigo) > 0 ) {
            $objForm = new form("frmResetPassword","frmResetPassword","POST",$strAction);
            $objForm->form_openForm();
            $objForm->add_input_hidden('hdnAction', '2', true);
            $objForm->add_input_hidden('hdnUsuarioWebId', $intUsuarioWebId, true);
            ?>
            <script>
                function fntCheckForm() {
                    boolValido = true;
                    if( $("input[name='txtNewPassword']").val().length < 8 ) {
                        boolValido = false;
                        draw_Alert("danger", "Error: ", "La password debe contener 8 caracteres como minimo.", true);
                    }

                    if( $("input[name='txtNewPassword']").val().length >= 8 && $("input[name='txtNewPassword']").val() != $("input[name='txtNewPasswordConfirmar']").val() ) {
                        boolValido = false;
                        draw_Alert("danger", "Error: ", "Las passwords no coinciden.", true);
                    }

                    if( boolValido ) {
                        document.frmResetPassword.action = '<?php print $strAction; ?>?act=5&c=<?php print $_GET['c']; ?>&uwi=<?php print $_GET['uwi']; ?>';
                        document.frmResetPassword.submit();
                    }
                }
            </script>
            <div class="row">
                <div class="col-lg-4 col-lg-offset-4">
                    <br>
                    <a href="index.php">
                        <img src="<?php print core_getImagePath("logo_login.png"); ?>" alt="" class="img-responsive center-block">
                    </a>
                    <br>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-lg-offset-4">
                    <h3>RESTAURACIoN DE PASSWORD</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-lg-offset-4">
                    <label>Nueva password</label>
                    <?php
                    $objForm->add_input_password('txtNewPassword', '', false);
                    $objForm->add_input_extraTag("txtNewPassword","maxlength","50");
                    $objForm->draw_input_password('txtNewPassword');
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-lg-offset-4">
                    <label>Confirmar password</label>
                    <?php
                    $objForm->add_input_password('txtNewPasswordConfirmar', '', false);
                    $objForm->add_input_extraTag("txtNewPasswordConfirmar","maxlength","50");
                    $objForm->draw_input_password('txtNewPasswordConfirmar');
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-lg-offset-4 text-center">
                    <?php $objTemplate->draw_button('btnSiguiente', 'Siguiente', 'fntCheckForm()', '', 'sm', ''); ?>
                </div>
            </div>
            <?php
            $objForm->form_closeForm();
        }
        else {
            db_close();
            die($lang["core"]["access_denied"]);
        }
    }

    public function drawContentPaso5() {

        global $strAction, $objTemplate;
        $intUsuarioWebId = $this->objModel->getUserId($_GET['uwi']);
        $objForm = new form("frmResetPassword","frmResetPassword","POST",$strAction);
        $objForm->form_openForm();
        ?>
        <script>
            function fntSiguiente() {
                <?php session_destroy(); ?>
                document.location = 'index.php';
            }
        </script>
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4">
                <br>
                <a href="index.php">
                    <img src="<?php print core_getImagePath("logo_login.png"); ?>" alt="" class="img-responsive center-block">
                </a>
                <br>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4">
                <h3>Tu password ha sido restablecida</h3>
                <p><?php print "Ahora puedes iniciar sesion con tu usuario."; ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4 text-center">
                <?php
                $objTemplate->draw_button('btnSiguiente', 'Siguiente', 'fntSiguiente();', '', 'sm', '');
                ?>
            </div>
        </div>
        <?php
        $objForm->form_closeForm();
    }

    public function drawAjaxCheckUsuarioEmail() {

        header('Content-Type: application/json');
        $strUsuario = isset($_POST['txtUsuario']) ? $_POST['txtUsuario'] : '';
        $strEmail = isset($_POST['txtEmail']) ? $_POST['txtEmail'] : '';
        $intUsuarioWebId = 0;
        $strReturn = array();
        if( !empty($strUsuario) && !empty($strEmail) ) {

            $intUsuarioWebId = $this->objModel->checkUsuarioEmail($strUsuario, $strEmail);

        }
        $strReturn["intUsuarioWebId"] = utf8_encode($intUsuarioWebId);
        print json_encode($strReturn);
        db_close();
        exit();
    }

    public function drawAjaxCheckCodigo() {

        header('Content-Type: application/json');
        $intUsuarioWebId = isset($_POST['hdnUsuarioWebId']) ? intval($_POST['hdnUsuarioWebId']) : 0;
        $strCodigo = isset($_POST['txtCodigo']) ? $_POST['txtCodigo'] : '';
        $intUsuarioWebId2 = 0;
        $strReturn = array();
        if( $intUsuarioWebId > 0 && !empty($strCodigo) ) {

            $intUsuarioWebId2 = $this->objModel->checkCodigo($intUsuarioWebId, $strCodigo);

        }
        $strReturn["intUsuarioWebId"] = utf8_encode($intUsuarioWebId2);
        print json_encode($strReturn);
        db_close();
        exit();
    }

}

class reset_password_model {

    public function __construct(){

    }

    public function checkUsuarioEmail($strUsuario, $strEmail) {
        $strUsuario = db_escape(user_input_delmagic($strUsuario));
        $strEmail = db_escape(user_input_delmagic($strEmail));
        $intReturn = 0;
        $strQuery ="SELECT  usuario.persona
                    FROM    usuario
                            INNER JOIN persona
                                ON usuario.persona = persona.persona
                    WHERE   usuario.usuario = '{$strUsuario}'
                    AND     persona.email = '{$strEmail}'";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ) {
            $intReturn = $rTMP['persona'];
        }
        db_free_result($qTMP);

        return $intReturn;
    }

    public function checkCodigo($intUsuarioWebId, $strCodigo) {
        $intUsuarioWebId = intval($intUsuarioWebId);
        $strCodigo = db_escape(user_input_delmagic($strCodigo));
        $intReturn = 0;
        $strQuery ="SELECT  persona
                    FROM    usuario
                    WHERE   persona = {$intUsuarioWebId}
                    AND     codigo = '{$strCodigo}'";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ) {
            $intReturn = $rTMP['persona'];
        }
        db_free_result($qTMP);

        return $intReturn;
    }

    public function getUsuarioWebXCuenta($intUsuarioWebId) {
        $intUsuarioWebId = intval($intUsuarioWebId);
        $arrData = array();
        $strQuery ="SELECT  usuario.persona,
                            CONCAT_WS(' ', persona.nombre1, persona.nombre2, persona.apellido1, persona.apellido2) AS nombre,
                            persona.email
                    FROM    usuario
                            INNER JOIN persona
                                ON usuario.persona = persona.persona
                    WHERE   usuario.persona = {$intUsuarioWebId}";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ) {
            $arrData = $rTMP;
            $arrData["persona"] = $rTMP["persona"];
            $arrData["nombre"] = $rTMP["nombre"];
            $arrData["email"] = $rTMP["email"];
        }
        db_free_result($qTMP);

        return $arrData;
    }

    public function updateUsuarioRestauracionPassword($intUsuarioWebId, $strCodigo) {
        $intUsuarioWebId = intval($intUsuarioWebId);
        $strCodigo = db_escape(user_input_delmagic($strCodigo));

        if( $intUsuarioWebId > 0 && !empty($strCodigo) ) {
            $strQuery ="UPDATE  usuario
                        SET     codigo = '{$strCodigo}',
                                mod_user = {$intUsuarioWebId},
                                mod_fecha = now()
                        WHERE   persona = {$intUsuarioWebId}";
            db_query($strQuery);
        }
    }

    public function updateUsuarioWebxCuentaPassword($intUsuarioWebId, $strNewPassword) {
        $intUsuarioWebId = intval($intUsuarioWebId);
        $strNewPassword = db_escape(user_input_delmagic($strNewPassword));
        if( $intUsuarioWebId > 0 && !empty($strNewPassword) ) {
            $strQuery ="UPDATE  usuario
                        SET     password = '{$strNewPassword}',
                                mod_user = {$intUsuarioWebId}
                        WHERE   persona = {$intUsuarioWebId}";
            db_query($strQuery);

            $strQuery2 ="UPDATE  usuario
                        SET    codigo = NULL
                        WHERE   persona = {$intUsuarioWebId}";
            db_query($strQuery2);
        }
    }

    public function getUserId( $strUserId ){
        $intUser = 0;

        if( $strUserId != ""){
            $strQuery = "SELECT persona
                        FROM usuario
                        WHERE md5(persona) = '{$strUserId}'";
            $intUser = sqlGetValueFromKey($strQuery);
        }

        return $intUser;
    }

}
?>