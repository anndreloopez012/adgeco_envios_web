<?php
require_once("cpanel_model.php");
require_once("cpanel_view.php");

class cpanel_controller {

    private $objModel = null;
    private $objView = null;

    function __construct() {

        $this->objModel = new cpanel_model();
        $this->objView = new cpanel_view();

    }


    public function drawSidebar() {

        $this->objView->drawSidebar();

    }

    public function process() {

        global $strQuerysPrint, $objTemplate;

        if( isset($_POST["hidFormEditModule"]) ){

            $intModulo = intval($_POST["hidEditModuleId"]);

            $strCodigo = db_escape(trim($_POST["txtCodigo"]));
            $intOrden = intval($_POST["txtOrden"]);
            $strPrivado = isset($_POST["chkPrivado"]) ? "Y" : "N";
            $strPublico = isset($_POST["chkPublico"]) ? "Y" : "N";
            $strActivo = isset($_POST["chkActivo"]) ? "Y" : "N";
            $boolInsert = false;

            if( $intModulo ){
                $this->objModel->updateModulo($intModulo,$strCodigo,$intOrden,$strPrivado,$strPublico,$strActivo);
            }
            else{
                $boolInsert = true;
                $intModulo = $this->objModel->insertModulo($strCodigo,$intOrden,$strPrivado,$strPublico,$strActivo);
            }

            $intModulo = intval($intModulo);

            if( $intModulo ){

                if( !$boolInsert ){
                    $this->objModel->deleteModuloIdioma($intModulo);
                    $this->objModel->deleteModuloDependencia($intModulo);
                }

                reset($_POST);
                while( $arrTMP = each($_POST) ){

                    $arrExplode = explode("_", $arrTMP["key"]);

                    if( $arrExplode[0] == "txtIdioma" ){

                        $intIdioma = intval($arrExplode[1]);
                        $strNombre = db_escape(trim($_POST["txtIdioma_{$arrExplode[1]}"]));

                        $this->objModel->insertModuloIdioma($intModulo, $intIdioma, $strNombre);

                    }

                    if( $arrExplode[0] == "chkModuloDependencia" ){

                        $intModuloDependencia = intval($arrExplode[1]);

                        $this->objModel->insertModuloDependencia($intModulo, $intModuloDependencia);

                    }


                }

            }

        }
        elseif( isset($_POST["hidFormEditIdioma"]) ){

            $intIdioma = intval($_POST["hidEditIdiomaId"]);

            $strCodigo = db_escape(trim($_POST["txtCodigo"]));
            $strNombre = db_escape(trim($_POST["txtNombre"]));

            if( $intIdioma )
                $this->objModel->updateIdioma($intIdioma,$strCodigo,$strNombre);
            else
                $intIdioma = $this->objModel->insertIdioma($strCodigo,$strNombre);

        }
        elseif( isset($_POST["hidFormEditLang"]) ){

            $intModulo = intval($_POST["hidEditLangModuloId"]);
            $strLang = db_escape(trim($_POST["hidEditLangId"]));

            $strCodigo = db_escape(trim($_POST["txtCodigo"]));
            $boolInsert = false;

            if( $intModulo && !empty($strLang) ){
                $this->objModel->updateLang($intModulo,$strLang,$strCodigo);
            }
            else{
                $boolInsert = true;
                $intModulo = intval($_POST["selectModulo"]);
                $strLang = $this->objModel->insertLang($intModulo,$strCodigo);
            }

            if( $intModulo && !empty($strLang) ){

                if( !$boolInsert ){
                    $this->objModel->deleteLangIdioma($intModulo, $strLang);
                }

                reset($_POST);
                while( $arrTMP = each($_POST) ){

                    $arrExplode = explode("_", $arrTMP["key"]);

                    if( $arrExplode[0] == "txtIdioma" ){

                        $intIdioma = intval($arrExplode[1]);
                        $strNombre = db_escape(trim($_POST["txtIdioma_{$arrExplode[1]}"]));

                        $this->objModel->insertLangIdioma($intModulo, $strCodigo, $intIdioma, $strNombre);

                    }

                }

            }

        }
        elseif( isset($_POST["hidFormEditTipoAcceso"]) ){

            $intTipoAcceso = intval($_POST["hidEditTipoAccesoId"]);

            $intOrden = intval($_POST["txtOrden"]);
            $strCodigo = db_escape(trim($_POST["txtCodigo"]));
            $strActivo = isset($_POST["chkActivo"]) ? "Y" : "N";
            $boolInsert = false;

            if( $intTipoAcceso ){
                $this->objModel->updateTipoAcceso($intTipoAcceso,$intOrden,$strCodigo,$strActivo);
            }
            else{
                $boolInsert = true;
                $intTipoAcceso = $this->objModel->insertTipoAcceso($intOrden,$strCodigo,$strActivo);
            }

            $intTipoAcceso = intval($intTipoAcceso);

            if( $intTipoAcceso ){

                if( !$boolInsert ){
                    $this->objModel->deleteTipoAccesoIdioma($intTipoAcceso);
                }

                reset($_POST);
                while( $arrTMP = each($_POST) ){

                    $arrExplode = explode("_", $arrTMP["key"]);

                    if( $arrExplode[0] == "txtIdioma" ){

                        $intIdioma = intval($arrExplode[1]);
                        $strNombre = db_escape(trim($_POST["txtIdioma_{$arrExplode[1]}"]));

                        $this->objModel->insertTipoAccesoIdioma($intTipoAcceso, $intIdioma, $strNombre);

                    }

                }

            }

        }
        elseif( isset($_POST["hidFormEditAcceso"]) ){

            $intAcceso = intval($_POST["hidEditAccesoId"]);
            $intAccesoPertenece = intval($_POST["hidEditAccesoPerteneceId"]);

            $intModulo = intval($_POST["hidEditAccesoModuloId"]);
            $intOrden = intval($_POST["txtOrden"]);
            $strCodigo = db_escape(trim($_POST["txtCodigo"]));
            $strPath = db_escape(trim($_POST["txtEnlace"]));
            $strPrivado = isset($_POST["chkPrivado"]) ? "Y" : "N";
            $strPublico = isset($_POST["chkPublico"]) ? "Y" : "N";
            $strActivo = isset($_POST["chkActivo"]) ? "Y" : "N";
            $boolInsert = false;

            if( $intAcceso ){
                $this->objModel->updateAcceso($intAcceso,$intOrden,$strCodigo,$strPath,$strPrivado,$strPublico,$strActivo);
            }
            else{
                $boolInsert = true;
                $intAcceso = $this->objModel->insertAcceso($intModulo, $intAccesoPertenece,$intOrden,$strCodigo,$strPath,$strPrivado,$strPublico,$strActivo);
            }

            $intAcceso = intval($intAcceso);

            if( $intAcceso ){

                if( !$boolInsert ){
                    $this->objModel->deleteAccesoIdioma($intAcceso);
                    $this->objModel->deleteAccesoPermitido($intAcceso);
                }

                reset($_POST);
                while( $arrTMP = each($_POST) ){

                    $arrExplode = explode("_", $arrTMP["key"]);

                    if( $arrExplode[0] == "txtIdiomaNombreMenu" ){

                        $intIdioma = intval($arrExplode[1]);
                        $strNombreMenu = db_escape(trim($_POST["txtIdiomaNombreMenu_{$arrExplode[1]}"]));
                        $strNombrePantalla = db_escape(trim($_POST["txtIdiomaNombrePantalla_{$arrExplode[1]}"]));

                        $this->objModel->insertAccesoIdioma($intAcceso, $intIdioma, $strNombreMenu, $strNombrePantalla);

                    }

                    if( $arrExplode[0] == "chkTipoAcceso" ){

                        $intTipoAcceso = intval($arrExplode[1]);
                        $this->objModel->insertAccesoTipoAcceso($intAcceso, $intTipoAcceso);

                    }

                }

            }

        }
        elseif( isset($_POST["hidFormEditConfig"]) ){

            $intModulo = intval($_POST["hidEditConfigModuloId"]);
            $strConfig = db_escape(trim($_POST["hidEditConfigId"]));

            $strCodigo = db_escape(trim($_POST["txtCodigo"]));
            $strTipoDato = db_escape(trim($_POST["selectTipoDato"]));
            $strValor = "";
            $strValores = "";

            switch($strTipoDato){
                case "texto":
                    $strValor = db_escape(trim($_POST["textValor"]));
                break;
                case "fecha":
                    $strValor = db_escape(trim($_POST["textValor"]));
                break;
                case "descripcion":
                    $strValor = db_escape(trim($_POST["textareaValor"]));
                break;
                case "lista":
                    $strValor = db_escape(trim($_POST["textValor"]));

                    reset($_POST);
                    while( $arrTMP = each($_POST) ){

                        $arrExplode = explode("_", $arrTMP["key"]);

                        if( $arrExplode[0] == "txtListaOpc" ){
                            $strValTMP = db_escape(trim($_POST["txtListaOpc_{$arrExplode[1]}"]));
                            if( !empty($strValTMP) )
                                $strValores .= ( empty($strValores) ? "" : "," ).$strValTMP;
                        }
                    }

                break;
                case "checkbox":
                    $strValor = isset($_POST["chkValor"]) ? "true" : "false";
                    $strValores = "true,false";
                break;
            }

            $boolInsert = false;

            if( $intModulo && !empty($strConfig) ){
                $this->objModel->updateConfig($intModulo,$strConfig,$strCodigo,$strTipoDato,$strValor,$strValores);
            }
            else{
                $boolInsert = true;
                $intModulo = intval($_POST["selectModulo"]);
                $strConfig = $this->objModel->insertConfig($intModulo,$strCodigo,$strTipoDato,$strValor,$strValores);
            }

            if( $intModulo && !empty($strConfig) ){

                if( !$boolInsert ){
                    $this->objModel->deleteConfigIdioma($intModulo, $strConfig);
                }

                reset($_POST);
                while( $arrTMP = each($_POST) ){

                    $arrExplode = explode("_", $arrTMP["key"]);

                    if( $arrExplode[0] == "txtIdioma" ){

                        $intIdioma = intval($arrExplode[1]);
                        $strNombre = db_escape(trim($_POST["txtIdioma_{$arrExplode[1]}"]));
                        $strDescripcion = db_escape(trim($_POST["textareaIdiomaDescripcion_{$arrExplode[1]}"]));

                        $this->objModel->insertConfigIdioma($intModulo, $strCodigo, $intIdioma, $strNombre, $strDescripcion);

                    }

                }
            }


        }

        if( !empty($strQuerysPrint) ){
            $objTemplate->draw_alert("info","",$strQuerysPrint);
        }

    }

    public function drawContent() {

        if( isset($_GET["typeContent"]) ) {

            $strContent = $_GET["typeContent"];

            if( $strContent == "modulos" ) {
                $this->objView->drawModulos();
            }
            elseif( $strContent == "modulosEdit" ) {
                $intModulo = ( isset($_GET["modulo"]) ) ? $this->objModel->getIdFromMD5("modulo", "modulo", $_GET["modulo"]) : 0;
                $this->objView->drawEditModule($intModulo);
            }
            elseif( $strContent == "idiomas" ) {
                $this->objView->drawIdiomas();
            }
            elseif( $strContent == "idiomasEdit" ) {
                $intIdioma = ( isset($_GET["idioma"]) ) ? $this->objModel->getIdFromMD5("idioma", "idioma", $_GET["idioma"]) : 0;
                $this->objView->drawEditIdiomas($intIdioma);
            }
            elseif( $strContent == "langs" ) {
                $this->objView->drawEtiquetas();
            }
            elseif( $strContent == "langsEdit" ) {
                $intModulo = ( isset($_GET["modulo"]) ) ? $this->objModel->getIdFromMD5("modulo", "modulo", $_GET["modulo"]) : 0;
                $strLang = ( isset($_GET["lang"]) ) ? $this->objModel->getIdFromMD5("lang", "lang", $_GET["lang"]) : "";
                $this->objView->drawEditEtiquetas($intModulo, $strLang);
            }
            elseif( $strContent == "config" ) {
                $this->objView->drawConfig();
            }
            elseif( $strContent == "configEdit" ) {
                $intModulo = ( isset($_GET["modulo"]) ) ? $this->objModel->getIdFromMD5("modulo", "modulo", $_GET["modulo"]) : 0;
                $strCodigo = ( isset($_GET["codigo"]) ) ? $this->objModel->getIdFromMD5("configuracion", "codigo", $_GET["codigo"]) : "";
                $this->objView->drawEditConfig($intModulo, $strCodigo);
            }
            elseif( $strContent == "tipoAcceso" ) {
                $this->objView->drawTipoAcceso();
            }
            elseif( $strContent == "tipoAccesoEdit" ) {
                $intTipoAcceso = ( isset($_GET["tipoAcceso"]) ) ? $this->objModel->getIdFromMD5("tipo_acceso", "tipo_acceso", $_GET["tipoAcceso"]) : 0;
                $this->objView->drawEditTipoAcceso($intTipoAcceso);
            }
            elseif( $strContent == "accesos" ) {
                $this->objView->drawAccesos();
            }
            elseif( $strContent == "accesosEdit" ) {
                $intModulo = ( isset($_GET["modulo"]) ) ? $this->objModel->getIdFromMD5("modulo", "modulo", $_GET["modulo"]) : 0;
                $intAcceso = ( isset($_GET["acceso"]) ) ? $this->objModel->getIdFromMD5("acceso", "acceso", $_GET["acceso"]) : 0;
                $intAccesoPertenece = ( isset($_GET["accesoPertenece"]) ) ? $this->objModel->getIdFromMD5("acceso", "acceso", $_GET["accesoPertenece"]) : 0;
                $this->objView->drawEditAccesos($intModulo,$intAcceso,$intAccesoPertenece);
            }
            elseif( $strContent == "query" ) {
                $this->objView->drawQuery();
            }

        }

    }

    public function drawButtons() {

        if( isset($_GET["typeContent"]) ) {

            $strContent = $_GET["typeContent"];

            if( $strContent == "modulos" ) {
                $this->objView->drawButtonsModulos();
            }
            elseif( $strContent == "modulosEdit" ) {
                $this->objView->drawButtonsModulos(true);
            }
            elseif( $strContent == "idiomas" ) {
                $this->objView->drawButtonsIdiomas();
            }
            elseif( $strContent == "idiomasEdit" ) {
                $this->objView->drawButtonsIdiomas(true);
            }
            elseif( $strContent == "langs" ) {
                $this->objView->drawButtonsEtiquetas();
            }
            elseif( $strContent == "langsEdit" ) {
                $this->objView->drawButtonsEtiquetas(true);
            }
            elseif( $strContent == "config" ) {
                $this->objView->drawButtonsConfig();
            }
            elseif( $strContent == "configEdit" ) {
                $this->objView->drawButtonsConfig(true);
            }
            elseif( $strContent == "tipoAcceso" ) {
                $this->objView->drawButtonsTipoAcceso();
            }
            elseif( $strContent == "tipoAccesoEdit" ) {
                $this->objView->drawButtonsTipoAcceso(true);
            }
            elseif( $strContent == "accesos" ) {
                $this->objView->drawButtonsAccesos();
            }
            elseif( $strContent == "accesosEdit" ) {
                $this->objView->drawButtonsAccesos(true);
            }

        }

    }

    public function complements(){

        if( isset($_POST["getResultLangs"]) ){

            header("Content-Type: text/html; charset=iso-8859-1");

            $intModulo = intval($_POST["modulo"]);

            $this->objView->drawResultEtiquetas($intModulo);

            die();
        }
        elseif( isset($_POST["getResultCofiguracion"]) ){

            header("Content-Type: text/html; charset=iso-8859-1");

            $intModulo = intval($_POST["modulo"]);

            $this->objView->drawResultConfig($intModulo);

            die();
        }
        elseif( isset($_POST["getResultAccesos"]) ){

            header("Content-Type: text/html; charset=iso-8859-1");

            $intModulo = intval($_POST["modulo"]);
            $intAcceso = intval($_POST["acceso"]);

            $this->objView->drawResultAccesos($intModulo,$intAcceso);

            die();
        }

    }


}