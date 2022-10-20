<?php
require_once("modules/configuracion/clases/configuracion_observacion_model.php");
require_once("modules/configuracion/clases/configuracion_observacion_view.php");

class configuracion_observacion_controller{

    private $objModel;
    private $objView;

    public function __construct(){

        $this->objModel = new configuracion_observacion_model();
        $this->objView = new configuracion_observacion_view();

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
        $observacion = isset($_POST["observacion"]) ? intval($_POST["observacion"]) : 0;

        $intUser = core_getUserId();
        $arrResultado["estado"] = "fail";
        if(core_boolPuedeEliminarDatos("observacion",$observacion) )
            $arrResultado["estado"] = "ok";
        print json_encode($arrResultado);

    }

    public function processGuardar(){

        $arrResultado = array();
        $observacion = isset($_POST["observacion"]) ? intval($_POST["observacion"]) : 0;

        $intUser = core_getUserId();
        $arrResultado["estado"] = "fail";
        if( $observacion == 0 ) {

            $arrDescribe = $this->objModel->getDescribe("observacion");
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
                    if( $arrD["key"] == "activo" ) {
                        $arrCampos[$arrD["key"]] = $arrD["key"];
                        $arrValores[$arrD["key"]] = "'Y'";
                    }else if( $arrD["key"] == "predeterminado" ) {
                        $arrCampos["predeterminado"] = "predeterminado";
                        $arrValores["predeterminado"] = "'Y'";
                        if($arrD["key"] == "predeterminado"){
                            if( isset($_POST[$arrD["key"]]))
                                $this->objModel->removePaisPredeterminado($intUser);
                        }
                    }else {
                        $arrCampos[$arrD["key"]] = $arrD["key"];
                        $arrValores[$arrD["key"]] = "'".db_escape(upper_tildes(user_input_delmagic($_POST[$arrD["key"]],true)))."'";
                    }
                }
                else if( $arrD["key"] == "activo" ) {
                    $arrCampos["activo"] = "activo";
                    $arrValores["activo"] = "'N'";
                }
                else if( $arrD["key"] == "predeterminado"){
                    $arrCampos["predeterminado"] = "predeterminado";
                    $arrValores["predeterminado"] = "'N'";
                }
            }

            $strCamposImplode = implode(",",$arrCampos);
            $strValoresImplode = upper_tildes(implode(",",$arrValores));

            $strQuery = "INSERT INTO observacion ({$strCamposImplode})
                        VALUES({$strValoresImplode})";
            db_query($strQuery);
            $arrResultado["estado"] = "ok";

        }
        else {

            $arrDescribe = $this->objModel->getDescribe("observacion");
            $strQuery = "";
            while( $arrD = each($arrDescribe) ) {
                if( isset($_POST[$arrD["key"]]) && ( $_POST[$arrD["key"]] == "0" || strlen($_POST[$arrD["key"]]) == 0 ) ) {
                    $strQuery .= empty($strQuery) ? "" : ",";
                    $strQuery .= "{$arrD["key"]} = NULL";
                }
                else if( isset($_POST[$arrD["key"]]) && strlen($_POST[$arrD["key"]]) > 0 ) {
                    if( $arrD["key"] == "por_defecto" || $arrD["key"] == "activo" || $arrD["key"] == "predeterminado") {
                        $strQuery .= empty($strQuery) ? "" : ",";
                        $strQuery .= "{$arrD["key"]} = 'Y'";
                        if($arrD["key"] == "predeterminado"){
                            if( isset($_POST[$arrD["key"]]))
                                $this->objModel->removePaisPredeterminado($intUser);
                        }
                    }else {
                        $strQuery .= empty($strQuery) ? "" : ",";
                        $strQuery .= "{$arrD["key"]} = '".db_escape(upper_tildes(user_input_delmagic($_POST[$arrD["key"]],true)))."'";
                    }
                }
                else if( $arrD["key"] == "activo" ) {
                    $strQuery .= empty($strQuery) ? "" : ",";
                    $strQuery .= "{$arrD["key"]} = 'N'";
                }
                else if( $arrD["key"] == "predeterminado"){
                    $strQuery .= empty($strQuery) ? "" : ",";
                    $strQuery .= "{$arrD["key"]} = 'N'";
                }
            }

            if( !empty($strQuery) ) {
                $strQuery .= empty($strQuery) ? "" : ",";
                $strQuery .= "mod_user = {$intUser}";
                $strQuery .= empty($strQuery) ? "" : ",";
                $strQuery .= "mod_fecha = now()";

                $strQuery ="UPDATE  observacion
                            SET     {$strQuery}
                            WHERE   observacion = {$observacion}";
                db_query($strQuery);
                $arrResultado["estado"] = "ok";
            }

        }

        print json_encode($arrResultado);
    }

    public function processEliminar(){

        $arrResultado = array();
        $observacion = isset($_POST["observacion"]) ? intval($_POST["observacion"]) : 0;
        $arrResultado["estado"] = "fail";
        if( $observacion > 0 ) {

            $strQuery = "DELETE FROM observacion WHERE observacion = {$observacion}";
            db_query($strQuery);
            $arrResultado["estado"] = "ok";

        }

        print json_encode($arrResultado);

    }

}