<?php
include_once("usuarios_perfiles_acceso_view.php");
include_once("usuarios_perfiles_acceso_model.php");

class perfil_controller{

    private $objModel = NULL;
    private $objView = NULL;
    private $intPerfil;
    private $strAlert;

    public function __construct(){
        $this->objModel = new usuarios_perfiles_model();
        $this->objView = new perfiles_acceso_view();
    }

    public function setPerfil(){
        $intPerfil = isset($_GET["perfil"]) ? intval($_GET["perfil"]) : 0;
        $this->intPerfil = $intPerfil;
    }

    public function getPerfil(){
        return $this->intPerfil;
    }

    public function draw_content() {
        $this->objView->draw_listado_perfiles();
    }

    public function draw_buttons() {
        $this->objView->draw_buttons_listado();
    }

    public function process(){
        global $strAction;
        if(isset($_POST["hdnFormPerfiles"])){
            $arrPOST = $_POST;
            $intPerfil = intval($_POST["hdnPerfilId"]);
            $strNombre = db_escape(user_input_delmagic(trim($_POST["txtNombrePerfil"]),true));
            $strDescripcion = db_escape(user_input_delmagic(trim($_POST["txtDescripcion"]),true));
            $strActivo = isset($_POST["checkActivo"]) ? "Y" : "N";
            $intUser = core_getUserId();
            $boolInsert = false;
            if( $intPerfil ){
                $this->objModel->updatePerfil($intPerfil,$strNombre,$strDescripcion,$strActivo);
            }
            else{
                $intPerfil = $this->objModel->insertPerfil($strNombre,$strDescripcion,$strActivo);
                $boolInsert = true;
            }
            $intPerfil = intval($intPerfil);
            if( $intPerfil ){
                reset($_POST);
                while( $arrTMP = each($_POST) ){
                    $arrExplode = explode("_", $arrTMP["key"]);
                    if( $arrExplode[0] == "hdnModuleLoaded" ){
                        $intModulo = intval($_POST["hdnModuleLoaded_{$arrExplode[1]}"]);
                        $this->objModel->deletePerfilAccesoModulo($intPerfil,$intModulo);

                        reset($arrPOST);
                        $intPadreOriginal = 0;
                        while( $arrTMP2 = each($arrPOST) ){
                            $arrExplode2 = explode("_", $arrTMP2["key"]);

                            if( $arrExplode2[0] == "checkAcceso" && $arrExplode2[1] == $intModulo ){
                                $intAcceso = $arrExplode2[3];
                                $intTipoAcceso = $arrExplode2[4];
                                $this->objModel->insertPerfilAcceso($intPerfil,$intAcceso,$intTipoAcceso);
                                if( $intPadreOriginal != $arrExplode2[2] ){
                                    $intAcceso = $arrExplode2[2];
                                    $intTipoAcceso = 1;
                                    $this->objModel->insertPerfilAcceso($intPerfil,$intAcceso,$intTipoAcceso);
                                    $intPadreOriginal = $arrExplode2[2];
                                }

                            }

                        }

                    }

                    if( $arrExplode[0] == "hdnIdCuenta" ){
                        $intCuenta = isset($_POST["hdnIdCuenta_{$arrExplode[1]}"]) ? intval($_POST["hdnIdCuenta_{$arrExplode[1]}"]) : 0;
                        $intCuentaOriginal = isset($_POST["hdnIdOriginal_{$arrExplode[1]}"]) ? intval($_POST["hdnIdOriginal_{$arrExplode[1]}"]) : 0;

                        if( isset($_POST["hdnCuentaEliminar_{$arrExplode[1]}"]) && $_POST["hdnCuentaEliminar_{$arrExplode[1]}"] == "Y" ){
                            $this->objModel->deleteCuentaPerfil($intCuentaOriginal,$intPerfil);
                        }
                        elseif( isset($_POST["hdnIdOriginal_{$arrExplode[1]}"]) && $_POST["hdnIdOriginal_{$arrExplode[1]}"] != 0 ){
                            $this->objModel->updateCuentaPerfil($intCuentaOriginal,$intCuenta,$intPerfil);
                        }
                        elseif( isset($_POST["hdnIdOriginal_{$arrExplode[1]}"]) && $_POST["hdnIdOriginal_{$arrExplode[1]}"] == 0 ){
                            $this->objModel->insertCuentaPerfil($intCuenta,$intPerfil);
                        }
                    }

                }

            }
            //die();
        }
        elseif(isset($_POST["getEliminarPerfil"])){
            header("Content-Type: text/html; charset=iso-88591-1");
            $intPerfil = intval($_POST["intPerfil"]);
            $this->objModel->deletePerfilAccesoModulo($intPerfil,0,true);
            $this->objModel->deletePerfil($intPerfil);
          die();
        }
    }

    public function runAjax(){
        global $arrAccesos, $strAction;

        if( isset($_POST["getResultModuloPerfil"]) ){
            header("Content-Type: text/html; charset=iso-8859-1");
            $intModulo = intval($_POST["modulo"]);
            $intPerfil = intval($_POST["perfil"]);
            $boolEditar = $_POST["editar"] === "true" ? true : false;
            $this->objView->drawAccesosModulo($intModulo,$intPerfil,$boolEditar);
            die();
        }
        elseif( isset($_POST["getResultBusqueda"]) ){
            header("Content-Type: text/html; charset=iso-8859-1");
            $strTexto = db_escape(user_input_delmagic($_POST["strTexto"], true));
            $intId = intval($_POST["intId"]);
            $this->objView->draw_ajax_listado_perfiles($intId, $strTexto);
            die();
        }
        elseif( isset($_GET["sendAutoComplete"]) ){
            header("Content-Type: text/html; charset=iso-8859-1");
            $result = array();
            $strParametro = db_escape(user_input_delmagic($_GET["term"], true));
            $arrPerfiles = $this->objModel->getPerfilesSearch($strParametro);
            while ( $rTMP = each($arrPerfiles) ){
                $arrTMP = array();
                $arrTMP["id"] = $rTMP["value"]["perfil"];
                $arrTMP["value"] = utf8_encode($rTMP["value"]["nombre"]);
                array_push($result, $arrTMP);
            }
            print json_encode($result);
            die();
        }
        elseif( isset($_GET["sendAutoCuenta"]) ){
            header("Content-Type: text/html; charset=iso-8859-1");
            $result = array();
            $strParametro = user_input_delmagic($_GET["term"], true);
            $arrCuentasSearch = $this->objModel->getCuentasSearch($strParametro);
            while ( $rTMP = each($arrCuentasSearch)){
                $strValue = empty($rTMP["value"]["usuario"]) ? utf8_encode($rTMP["value"]["nombre"]) : utf8_encode($rTMP["value"]["nombre"]." - ".$rTMP["value"]["usuario"]);
                $arrTMP = array();
                $arrTMP["id"] = utf8_encode($rTMP["value"]["id"]);
                $arrTMP["value"] = $strValue;
                array_push($result,$arrTMP);

            }
            print json_encode($result);
            die();
        }
        elseif(isset($_GET["getIdPerfilesAcceso"])){
            header("Content-Type: text/html; charset=iso-8859-1");
            $intPerfil = intval($_GET["intPerfil"]);
            $this->objView->draw_content($intPerfil);
            die();
        }
    }

    public function drawAlerts(){
        global $lang, $objTemplate;
        if( isset($_GET["strAlert"]) || empty($this->strAlert) ) {
            if( $this->strAlert == "ins" ){
                $objTemplate->draw_alert("success","",$lang[MODULO]["usuarios_perfil_registro_msj_alert_ins"],true);
            }
            elseif( $this->strAlert == "upd" ){
                $objTemplate->draw_alert("info","",$lang[MODULO]["usuarios_perfil_registro_msj_alert_upd"],true);
            }
            elseif( isset($_GET["strAlert"]) && $_GET["strAlert"] == "del" ){
                $objTemplate->draw_alert("danger","",$lang[MODULO]["usuarios_perfil_registro_msj_alert_del"],true);
            }
        }
    }
}
