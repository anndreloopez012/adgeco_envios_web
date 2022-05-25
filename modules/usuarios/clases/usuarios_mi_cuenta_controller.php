<?php
require_once("modules/usuarios/clases/usuarios_mi_cuenta_model.php");
require_once("modules/usuarios/clases/usuarios_mi_cuenta_view.php");

class usuarios_mi_cuenta_controller{

    private $objModel;
    private $objView;

    private $strAction;
    private $strPersona;
    private $strAleatorio;

    public function __construct(){
        $this->objModel = new usuarios_mi_cuenta_model();
        $this->objView = new usuarios_mi_cuenta_view();
    }

    public function setPersona(){
        $strPersona = md5(core_getUserId());
        $this->strPersona = $strPersona;
    }

    public function getPersona(){
        return $this->strPersona;
    }

    public function setAleatorio(){
        $strAleatorio = isset($_GET["aleatorio"]) ? db_escape($_GET["aleatorio"]) : "";
        $this->strAleatorio = $strAleatorio;
    }

    public function getAleatorio(){
        return $this->strAleatorio;
    }

    public function drawButtons(){

        $this->objView->drawButtonsPersona( $this->getPersona(), $this->getAleatorio() );

    }

    public function drawContent(){

        $this->objView->drawContentPersona( $this->getPersona(), $this->getAleatorio() );

    }

    public function drawAlerts(){
        global $lang, $objTemplate;
        if( isset($_GET["strAlert"]) ){
            if( $_GET["strAlert"] == "ins" ){
                $objTemplate->draw_alert("success","",$lang[MODULO]["usuarios_msj_alert_ins"],true);
            }
            elseif( $_GET["strAlert"] == "upd" ){
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
            $intUser = core_getUserId();
            reset($_POST);
            $auxPost = $_POST;
            if( isset($_POST["hdnPersona"]) ){
                $intPersona = intval($_POST["hdnPersona"]);

                $strNombre = isset($_POST["txtNombreCompleto"]) ? db_escape(user_input_delmagic($_POST["txtNombreCompleto"])) : "";
                $strEmail = isset($_POST["txtCorreoElectronico"]) ? db_escape(user_input_delmagic($_POST["txtCorreoElectronico"])) : "";
                $strIsAleatorio = isset($_POST["hdnIsAleatorio"]) ? db_escape(user_input_delmagic($_POST["hdnIsAleatorio"])) : "N" ;

                $this->objModel->updateMiCuentaPersona($intPersona, $strNombre, $strEmail, $intUser);

                $strPassword = isset($_POST["txtPassword"]) ? db_escape(user_input_delmagic($_POST["txtPassword"])) : "";

                $this->objModel->updateMiCuentaUsuarioPassword($intPersona, $strPassword, $intUser);

                if($strIsAleatorio == "N"){
                    ?>
                    <script>
                        document.location = "<?php echo $strAction."?persona=".(md5($intPersona))."&strAlert=upd"; ?>";
                    </script>
                    <?php
                }
                else if($strIsAleatorio == "Y"){
                    ?>
                    <script>
                        //document.location = "index.php?login=true";
                        document.location = "<?php echo $strAction."?persona=".(md5($intPersona))."&strAlert=upd"; ?>";
                    </script>
                    <?php
                }
                die();
            }
        }
    }

    public function runAjax(){

    }

}
