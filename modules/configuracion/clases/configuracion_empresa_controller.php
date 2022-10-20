<?php
require_once("modules/configuracion/clases/configuracion_empresa_model.php");
require_once("modules/configuracion/clases/configuracion_empresa_view.php");

class configuracion_empresa_controller{

    private $objModel;
    private $objView;

    public function __construct(){

        $this->objModel = new configuracion_empresa_model();
        $this->objView = new configuracion_empresa_view();

    }

    public function runAjax() {

        if( isset($_POST["metodo"]) ) {
            header("Content-Type: text/html; charset=iso-8859-1");

            $strMetodo = isset($_POST["metodo"]) ? $_POST["metodo"] : "";

            if( method_exists($this->objView,$strMetodo) ) {

                $this->objView->{$strMetodo}();

            }
            else if( method_exists($this,$strMetodo) ) {

                $this->{$strMetodo}();

            }
            else {

                print "Defina el metodo";

            }
            db_close();
            die();
        }

    }

    public function drawButtons() {

        $this->objView->drawButtons();

    }

    public function drawContent() {

        $this->objView->drawContent();

    }

    public function checkEliminar(){

        $arrResultado = array();
        $empresa = isset($_POST["empresa"]) ? intval($_POST["empresa"]) : 0;

        $intUser = core_getUserId();
        $arrResultado["estado"] = "fail";
        if(core_boolPuedeEliminarDatos("empresa",$empresa) )
            $arrResultado["estado"] = "ok";
        print json_encode($arrResultado);

    }

    public function checkDefault(){

        $arrResultado = array();
        $empresa = isset($_POST["empresa"]) ? intval($_POST["empresa"]) : 0;
        $arrInfo = $this->objModel->getInfoDefault($empresa);
        $arrResultado["resultado"] = "false";
        if( !empty($arrInfo) )
            $arrResultado["resultado"] = "true";
        print json_encode($arrResultado);

    }

    public function processGuardar(){

        $arrResultado = array();
        $empresa = isset($_POST["empresa"]) ? intval($_POST["empresa"]) : 0;
        $por_defecto = isset($_POST["por_defecto"]) ? 'Y' : 'N';

        if( $por_defecto == 'Y' ) {
            $strQuery ="UPDATE  empresa
                        SET     por_defecto = 'N'
                        WHERE   por_defecto = 'Y'";
            db_query($strQuery);
        }
        $intUser = core_getUserId();
        $arrResultado["estado"] = "fail";
        if( $empresa == 0 ) {

            $arrDescribe = $this->objModel->getDescribe("empresa");
            $arrCampos = array();
            $arrCampos["add_user"] = "add_user";
            $arrValores["add_user"] = $intUser;
            $arrCampos["add_fecha"] = "add_fecha";
            $arrValores["add_fecha"] = "now()";
            while( $arrD = each($arrDescribe) ) {
                if( isset($_POST[$arrD["key"]]) && ( $_POST[$arrD["key"]] == "0" || strlen($_POST[$arrD["key"]]) == 0 ) ) {
                    $arrCampos[$arrD["key"]] = $arrD["key"];
                    $arrValores[$arrD["key"]] = "NULL";
                }
                else if( isset($_POST[$arrD["key"]]) && strlen($_POST[$arrD["key"]]) > 0 ) {
                    if( $arrD["key"] == "por_defecto" || $arrD["key"] == "activo" ) {
                        $arrCampos[$arrD["key"]] = $arrD["key"];
                        $arrValores[$arrD["key"]] = "'Y'";
                    }
                    else {
                        $arrCampos[$arrD["key"]] = $arrD["key"];
                        $arrValores[$arrD["key"]] = "'".db_escape(upper_tildes(user_input_delmagic($_POST[$arrD["key"]],true)))."'";
                    }
                }
                else if( $arrD["key"] == "por_defecto" ) {
                    $arrCampos["por_defecto"] = "por_defecto";
                    $arrValores["por_defecto"] = "'N'";
                }
                else if( $arrD["key"] == "activo" ) {
                    $arrCampos["activo"] = "activo";
                    $arrValores["activo"] = "'N'";
                }
            }

            $strCamposImplode = implode(",",$arrCampos);
            $strValoresImplode = upper_tildes(implode(",",$arrValores));

            $strQuery = "INSERT INTO empresa ({$strCamposImplode})
                        VALUES({$strValoresImplode})";
            db_query($strQuery);
            $arrResultado["estado"] = "ok";

        }
        else {

            $arrDescribe = $this->objModel->getDescribe("empresa");
            $strQuery = "";
            while( $arrD = each($arrDescribe) ) {
                if( isset($_POST[$arrD["key"]]) && ( $_POST[$arrD["key"]] == "0" || strlen($_POST[$arrD["key"]]) == 0 ) ) {
                    $strQuery .= empty($strQuery) ? "" : ",";
                    $strQuery .= "{$arrD["key"]} = NULL";
                }
                else if( isset($_POST[$arrD["key"]]) && strlen($_POST[$arrD["key"]]) > 0 ) {
                    if( $arrD["key"] == "por_defecto" || $arrD["key"] == "activo" ) {
                        $strQuery .= empty($strQuery) ? "" : ",";
                        $strQuery .= "{$arrD["key"]} = 'Y'";
                    }
                    else {
                        $strQuery .= empty($strQuery) ? "" : ",";
                        $strQuery .= "{$arrD["key"]} = '".db_escape(upper_tildes(user_input_delmagic($_POST[$arrD["key"]],true)))."'";
                    }
                }
                else if( $arrD["key"] == "por_defecto" || $arrD["key"] == "activo" ) {
                    $strQuery .= empty($strQuery) ? "" : ",";
                    $strQuery .= "{$arrD["key"]} = 'N'";
                }
            }

            if( !empty($strQuery) ) {
                $strQuery .= empty($strQuery) ? "" : ",";
                $strQuery .= "mod_user = {$intUser}";
                $strQuery .= empty($strQuery) ? "" : ",";
                $strQuery .= "mod_fecha = now()";

                $strQuery ="UPDATE  empresa
                            SET     {$strQuery}
                            WHERE   empresa = {$empresa}";
                db_query($strQuery);
                $arrResultado["estado"] = "ok";
            }

        }

        print json_encode($arrResultado);

    }

    public function processEliminar(){

        $arrResultado = array();
        $empresa = isset($_POST["empresa"]) ? intval($_POST["empresa"]) : 0;
        $arrResultado["estado"] = "fail";
        if( $empresa > 0 ) {

            $strQuery = "DELETE FROM empresa WHERE empresa = {$empresa}";
            db_query($strQuery);
            $arrResultado["estado"] = "ok";

        }

        print json_encode($arrResultado);

    }

}