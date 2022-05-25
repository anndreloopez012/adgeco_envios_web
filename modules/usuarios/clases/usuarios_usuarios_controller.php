<?php
require_once("modules/usuarios/clases/usuarios_usuarios_model.php");
require_once("modules/usuarios/clases/usuarios_usuarios_view.php");

class usuarios_usuarios_controller{

    private $objModel;
    private $objView;

    private $strAction;
    private $strPersona;
    private $strAlert;

    public function __construct(){
        $this->objModel = new usuarios_usuarios_model();
        $this->objView = new usuarios_usuarios_view();
    }

    public function setPersona(){
        $strPersona = isset($_GET["persona"]) ? db_escape($_GET["persona"]) : "";
        $this->strPersona = $strPersona;
    }

    public function getPersona(){
        return $this->strPersona;
    }

    public function drawButtons(){

        if( isset($_GET["persona"]) ){
            $this->objView->drawButtonsPersona( $this->getPersona() );
        }
        else{
            $this->objView->drawButtons();
        }

    }

    public function drawContent(){

        if( isset($_GET["persona"]) ){

            $this->objView->drawContentPersona( $this->getPersona() );
        }
        else{
            $this->objView->drawContent();
        }

    }

    public function drawAlerts(){
        global $lang, $objTemplate;
        if( isset($_GET["strAlert"]) || !empty($this->strAlert) ){
            if( $this->strAlert == "ins" ){
                $objTemplate->draw_alert("success","",$lang[MODULO]["usuarios_msj_alert_ins"],true);
            }
            elseif( $this->strAlert == "upd" ){
                $objTemplate->draw_alert("info","",$lang[MODULO]["usuarios_msj_alert_upd"],true);
            }
            elseif( $_GET["strAlert"] == "del" ){
                $objTemplate->draw_alert("danger","",$lang[MODULO]["usuarios_msj_alert_del"],true);
            }
        }
    }

    public function process(){
        global $lang, $strAction, $objTemplate, $cfg;

        if( isset($_POST["hdnAccion"]) ){
            $this->strAlert = '';
            $intUser = core_getUserId();
            reset($_POST);
            $auxPost = $_POST;
            $auxPost2 = $_POST;
            if( isset($_POST["hdnPersona"]) ){
                $intPersona = intval($_POST["hdnPersona"]);
                if($intPersona == 0){
                    $strTipoCuenta = isset($_POST["hdnCuentaNueva"]) ? db_escape(user_input_delmagic($_POST["hdnCuentaNueva"])) : "";
                }
                else{
                    $strTipoCuenta = isset($_POST["sltTipoCuenta"]) ? db_escape(user_input_delmagic($_POST["sltTipoCuenta"])) : "normal";
                }
                $strNombreCompleto = isset($_POST["txtNombreCompleto"]) ? db_escape(user_input_delmagic($_POST["txtNombreCompleto"])) : "";
                $strEmail = isset($_POST["txtCorreoElectronico"]) ? db_escape(user_input_delmagic($_POST["txtCorreoElectronico"])) : "";
                $strFotoAnterior = isset($_POST["hdnFoto"]) ? db_escape(user_input_delmagic($_POST["hdnFoto"])) : "";
                $boolInsert = false;
                $strSexo = "M";
                $intPais = 1;

                if( $intPersona == 0 ){
                    $strFoto = core_save_file("txtFoto",MODULO,"foto");
                    $intPersona = $this->objModel->insertPersona($strNombreCompleto, $strSexo, $intPais, $strEmail, $strFoto, $intUser);
                    $boolInsert = true;
                }
                else {
                    $strFoto = core_save_file("txtFoto",MODULO,"foto");
                    $strFoto = empty($strFoto) ? $strFotoAnterior : $strFoto;
                    $this->objModel->updatePersona($intPersona, $strNombreCompleto, $strSexo, $intPais, $strEmail, $strFoto, $intUser);
                    if( $_SESSION["hml"]["persona"] == $intPersona )
                        $_SESSION["hml"]["imagen_persona"] = $strFoto;
                }

                $strUsuario = isset($_POST["txtUsuario"]) ? db_escape(user_input_delmagic($_POST["txtUsuario"])) : "";

                $strPassword = "";
                $strAleatorio = "N";
                if( isset($_POST["chkGenerar"]) || $boolInsert ){
                    $strPassword = uniqid();
                    $arrConfigMail["from"] = $cfg[MODULO]["usuarios_correo_envi"]["valor"];
                    $arrConfigMail["subject"] = $lang[MODULO]["usuarios_correo_password_asunto"];

                    $strContenido = $lang[MODULO]["usuarios_correo_password_mensaje"];
                    $strContenido = str_replace("[Sitio]",$_SERVER["SERVER_NAME"],$strContenido);
                    $strContenido = str_replace("[Usuario]",$strUsuario,$strContenido);
                    $strContenido = str_replace("[Contraseña]",$strPassword,$strContenido);
                    $arrMailContent["html"] = $strContenido;

                    core_mail(false,$arrConfigMail,$strEmail,$strContenido);
                    $strAleatorio = "Y";

                }
                $intIdioma = 1;
                $strBloqueado = isset($_POST["chkActivo"] ) ? "N" : "Y";

                if( $boolInsert && $intPersona > 0 ){
                    $this->objModel->insertUsuario($intPersona, $strUsuario, $strPassword, $intIdioma, $strTipoCuenta, $strBloqueado, $intUser);
                }
                else{
                    $strPassword = isset($_POST["txtPassword"]) ? db_escape(user_input_delmagic($_POST["txtPassword"])) : "";
                    $this->objModel->updateUsuario($intPersona, $strPassword, $intIdioma, $strTipoCuenta, $strBloqueado, $intUser, $strAleatorio);
                }

                if( !$boolInsert ) {
                    $this->objModel->deletePersonaPerfil($intPersona);
                }
                reset($_POST);
                while( $rTMP = each($_POST) ){
                    $arrExplode = explode("_", $rTMP["key"]);
                    if( isset($arrExplode[1])  && $arrExplode[0] == 'sltPerfil' && isset($_POST["hdnPerfilDelete_{$arrExplode[1]}"]) && $_POST["hdnPerfilDelete_{$arrExplode[1]}"] == "N" ){
                        $this->objModel->insertPersonaPerfil($intPersona, $rTMP["value"]);
                    }
                }

                if( $boolInsert ){
                    $this->strAlert = 'ins';
                    $this->strPersona = md5($intPersona);
                    ?>
                    <script>
                        //document.location = "<?php echo $strAction."?persona=".(md5($intPersona))."&strAlert=ins"; ?>";
                    </script>
                    <?php
                    //die();
                }
                else {
                    $this->strAlert = 'upd';
                    ?>
                    <script>
                        //document.location = "<?php echo $strAction."?persona=".(md5($intPersona))."&strAlert=upd"; ?>";
                    </script>
                    <?php
                    //die();
                }

            }
        }
        elseif( isset($_GET["strEliminar"]) && $_GET["strEliminar"] == "Y" ){
            $strPersona = isset($_GET["persona"]) ? db_escape(user_input_delmagic($_GET["persona"])) : "";
            if( !empty($strPersona) ){
                $this->objModel->deletePersona($strPersona);
                ?>
                    <script>
                        document.location = "<?php echo $strAction; ?>?strAlert=del";
                    </script>
                    <?php
                    die();
                }
            }

        }

    public function runAjax(){
        if( isset($_POST["getResultBusqueda"]) ){

            header("Content-Type: text/html; charset=iso-8859-1");

            $strBusqueda = db_escape(user_input_delmagic($_POST["params"], true));

            $this->objView->drawListadoPersonas($strBusqueda);

            die();
        }
        elseif( isset($_GET["sendAutoComplete"]) ){
            header("Content-Type: text/html; charset=iso-8859-1");

            $arrResult = array();

            $strParametro = db_escape(user_input_delmagic($_GET["term"], true));
            $arrPersonas = $this->objModel->getPersonasSearch($strParametro);
            while ( $rTMP = each($arrPersonas) ){

                $arrTMP = array();
                $arrTMP["id"] = $rTMP["value"]["persona"];
                $arrTMP["value"] = utf8_encode($rTMP["value"]["nombre_usual"]);
                array_push($arrResult, $arrTMP);
            }

            print json_encode($arrResult);

            die();

        }
        elseif( isset($_POST["getDescripcionPerfil"]) ){
            header("Content-Type: text/html; charset=iso-8859-1");

            $intPerfil = isset($_POST["intPerfil"]) ? intval($_POST["intPerfil"]) : 0;

            $strDescripcionPerfil = $this->objModel->getDescripcionPerfil($intPerfil);

            echo $strDescripcionPerfil;

            die();
        }
        elseif( isset($_POST["getValidarUsuario"]) ){
            header("Content-Type: text/html; charset=iso-8859-1");

            $strUsuario = isset($_POST["txtUsuario"]) ? db_escape(trim($_POST["txtUsuario"])) : "";
            $intPersona = isset($_POST["intPersona"]) ? intval($_POST["intPersona"]) : 0;

            $strValido = $this->objModel->fntCheckUsuarioValido($strUsuario, $intPersona);
            echo $strValido;

            die();
        }
    }

}
