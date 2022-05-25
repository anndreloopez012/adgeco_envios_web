<?php
require_once("cpanel_model.php");

class cpanel_view {

    private $objModel = null;

    function __construct() {

        $this->objModel = new cpanel_model();

    }

    public function drawSidebar() {

        global $objTemplate, $lang, $strAction;
        $strContent = isset($_GET["typeContent"]) ? $_GET["typeContent"] : "";
        ?>
        <div class="row">
            <div class="col-lg-4 col-lg-offset-2 text-center">
                <h1><?php print $lang["core"]["sidebar_structure"]; ?></h1>
                <div class="btn-group" role="group" aria-label="...">
                    <button type="button" class="btn btn-default <?php print ( $strContent == "modulos" || $strContent == "modulosEdit" ) ? "active" : ""; ?>" onclick="document.location='<?php print "{$strAction}?typeContent=modulos"; ?>'"><?php print $lang["core"]["sidebar_module"]; ?></button>
                    <button type="button" class="btn btn-default <?php print ( $strContent == "tipoAcceso" || $strContent == "tipoAccesoEdit" ) ? "active" : ""; ?>" onclick="document.location='<?php print "{$strAction}?typeContent=tipoAcceso"; ?>'"><?php print $lang["core"]["sidebar_tipo_acceso"]; ?></button>
                    <button type="button" class="btn btn-default <?php print ( $strContent == "accesos" || $strContent == "accesosEdit" ) ? "active" : ""; ?>" onclick="document.location='<?php print "{$strAction}?typeContent=accesos"; ?>'"><?php print $lang["core"]["sidebar_access"]; ?></button>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <h1><?php print $lang["core"]["sidebar_configuration"]; ?></h1>
                <div class="btn-group" role="group" aria-label="...">
                    <button type="button" class="btn btn-default <?php print ( $strContent == "idiomas" || $strContent == "idiomasEdit"  ) ? "active" : ""; ?>" onclick="document.location='<?php print "{$strAction}?typeContent=idiomas"; ?>'"><?php print $lang["core"]["language"]; ?></button>
                    <button type="button" class="btn btn-default <?php print ( $strContent == "langs" || $strContent == "langsEdit" ) ? "active" : ""; ?>" onclick="document.location='<?php print "{$strAction}?typeContent=langs"; ?>'"><?php print $lang["core"]["sidebar_langs"]; ?></button>
                    <button type="button" class="btn btn-default <?php print ( $strContent == "config" || $strContent == "configEdit" ) ? "active" : ""; ?>" onclick="document.location='<?php print "{$strAction}?typeContent=config"; ?>'"><?php print $lang["core"]["sidebar_variables_configuracion"]; ?></button>
                    <button type="button" class="btn btn-default <?php print ( $strContent == "query" || $strContent == "queryEdit" ) ? "active" : ""; ?>" onclick="document.location='<?php print "{$strAction}?typeContent=query"; ?>'"><?php print "Query"; ?></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">&nbsp;</div>
        </div>
        <?php

    }


    public function drawModulos() {

        global $objTemplate, $lang, $strAction;

        $arrModulos = $this->objModel->getModulos();

        ?>
        <div class="row">
            <div class="col-lg-2">&nbsp;</div>
            <div class="col-lg-8">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="col-sm-3"><?php print $lang["core"]["config_modulo_codigo"]; ?></th>
                                <th class="col-sm-3"><?php print $lang["core"]["config_modulo_nombre"]; ?></th>
                                <th class="col-sm-2"><?php print $lang["core"]["config_modulo_privado"]; ?></th>
                                <th class="col-sm-2"><?php print $lang["core"]["config_modulo_publico"]; ?></th>
                                <th class="col-sm-1"><?php print $lang["core"]["config_modulo_activo"]; ?></th>
                                <th class="col-sm-1">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while( $arrTMP = each($arrModulos) ) {
                                ?>
                                <tr>
                                    <td><?php print $arrTMP["value"]["codigo"]; ?></td>
                                    <td><?php print $arrTMP["value"]["nombre"]; ?></td>
                                    <td><?php print $arrTMP["value"]["privado"] == "Y" ? $lang["core"]["yes"] : $lang["core"]["no"]; ?></td>
                                    <td><?php print $arrTMP["value"]["publico"] == "Y" ? $lang["core"]["yes"] : $lang["core"]["no"]; ?></td>
                                    <td><?php print $arrTMP["value"]["activo"] == "Y" ? $lang["core"]["yes"] : $lang["core"]["no"]; ?></td>
                                    <td>
                                        <?php $objTemplate->draw_icon("","document.location.href='{$strAction}?typeContent=modulosEdit&modulo=".(md5($arrTMP["value"]["modulo"]))."'","pencil","sm",true); ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-2">&nbsp;</div>
        </div>
        <?php

    }
    public function drawEditModule($intModulo) {

        global $objTemplate, $lang, $strAction;

        $intModulo = intval($intModulo);
        $boolEdit = ($intModulo);
        $arrModulo = $this->objModel->getInfoModulo($intModulo);
        $arrModulosList = $this->objModel->getModulos($intModulo);

        $arrIdiomas = getIdiomasArreglo();

        ?>

        <script type="text/javascript">

            $(function(){
                $("[rel=tooltip]").tooltip();
            });

            function fntSubmitModule(){

                var boolError = false;

                $("input[name='txtCodigo']").val($.trim($("input[name='txtCodigo']").val()));
                $("input[name='txtOrden']").val($.trim($("input[name='txtOrden']").val()));

                $("input[name='txtCodigo']").popover("hide");
                $("input[name='txtOrden']").popover("hide");

                if( $("input[name='txtCodigo']").val().length == 0 ) {

                    $("input[name='txtCodigo']").popover("destroy");

                    $("input[name='txtCodigo']").popover({
                        title: "<?php print $lang["core"]["campo_requerido_title"]; ?>",
                        placement: 'right',
                        content: "<?php print $lang["core"]["campo_requerido_content"]; ?>",
                        animation: true
                    });

                    $("input[name='txtCodigo']").popover("show");
                    boolError = true;
                }

                if( $("input[name='txtOrden']").val().length == 0 ) {

                    $("input[name='txtOrden']").popover("destroy");

                    $("input[name='txtOrden']").popover({
                        title: "<?php print $lang["core"]["campo_requerido_title"]; ?>",
                        placement: 'right',
                        content: "<?php print $lang["core"]["campo_requerido_content"]; ?>",
                        animation: true
                    });

                    $("input[name='txtOrden']").popover("show");
                    boolError = true;
                }

                $("input[name^='txtIdioma_']").each(function(){

                    $(this).val($.trim($(this).val()));
                    $(this).popover("hide");

                    if( $(this).val().length == 0 ) {

                        $(this).popover("destroy");

                        $(this).popover({
                            title: "<?php print $lang["core"]["campo_requerido_title"]; ?>",
                            placement: 'right',
                            content: "<?php print $lang["core"]["campo_requerido_content"]; ?>",
                            animation: true
                        });

                        $(this).popover("show");
                        boolError = true;
                    }

                });

                if( !boolError ){
                    getDocumentLayer("frmEditModule").submit();
                }

            }

        </script>

        <?php

        $objForm = new form("frmEditModule","frmEditModule","POST","{$strAction}?typeContent=modulos","fntSubmitModule();");
        $objForm->form_openForm();

            $objForm->add_input_hidden("hidFormEditModule", 1, true);
            $objForm->add_input_hidden("hidEditModuleId", $intModulo, true);
            ?>
            <div class="row">
                <div class="col-lg-2">&nbsp;</div>
                <div class="col-lg-8">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td style="width: 15%;" class="text-right">
                                        <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo_codigo"], true); ?>
                                    </td>
                                    <td style="width: 40;">
                                        <?php
                                        $strValor = $boolEdit ? $arrModulo["codigo"] : "";
                                        $objForm->add_input_text("txtCodigo",$strValor,"inputSizeComplete",true,$lang["core"]["config_modulo_codigo"],$lang["core"]["config_modulo_codigo"]);
                                        ?>
                                    </td>
                                    <td style="width: 45%">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="text-right">
                                        <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo_orden"], true); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $strValor = $boolEdit ? $arrModulo["orden"] : "";
                                        $objForm->add_input_text("txtOrden",$strValor,"inputSizeComplete",true,$lang["core"]["config_modulo_orden"],$lang["core"]["config_modulo_orden"]);
                                        ?>
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="text-right">
                                        <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo_privado"]); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $boolChecked = ($boolEdit ? ( ($arrModulo["privado"] == "Y") ? true : false) : false);
                                        $objForm->add_input_checkbox("chkPrivado",$boolChecked,"",true, $lang["core"]["config_modulo_privado"]);
                                        ?>
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="text-right">
                                        <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo_publico"]); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $boolChecked = ($boolEdit ? ( ($arrModulo["publico"] == "Y") ? true : false) : false);
                                        $objForm->add_input_checkbox("chkPublico",$boolChecked,"",true, $lang["core"]["config_modulo_publico"]);
                                        ?>
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="text-right">
                                        <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo_activo"]); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $boolChecked = ($boolEdit ? ( ($arrModulo["activo"] == "Y") ? true : false) : true);
                                        $objForm->add_input_checkbox("chkActivo",$boolChecked,"",true, $lang["core"]["config_modulo_activo"]);
                                        ?>
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>
                                        <?php
                                        print $lang["core"]["language"];
                                        ?>
                                    </th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while( $arrTMP = each($arrIdiomas) ) {
                                    ?>
                                    <tr>
                                        <td style="width: 15%;" class="text-right">
                                            <?php print $objTemplate->drawTitleLeft($arrTMP["value"]["nombre"], true); ?>
                                        </td>
                                        <td style="width: 40%;">
                                            <?php
                                            $strValor = ( $boolEdit && isset($arrModulo["idiomas"][$arrTMP["key"]]) ? $arrModulo["idiomas"][$arrTMP["key"]]["nombre"] : "" );
                                            $objForm->add_input_text("txtIdioma_{$arrTMP["key"]}",$strValor,"inputSizeComplete",true,$arrTMP["value"]["nombre"]);
                                            ?>
                                        </td>
                                        <td style="width: 45%;">&nbsp;</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 15%">&nbsp;</th>
                                    <th style="width: 40%">
                                        <?php
                                        print $lang["core"]["core_modulo_dependencia"];
                                        ?>
                                    </th>
                                    <th style="width: 45%">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while( $arrTMP = each($arrModulosList) ) {
                                    ?>
                                    <tr>
                                        <td class="text-right">
                                            <?php print $objTemplate->drawTitleLeft($arrTMP["value"]["nombre"], true); ?>
                                        </td>
                                        <td>
                                            <?php
                                            $boolChecked = ($boolEdit && isset($arrModulo["modulo_dependencia"][$arrTMP["key"]])) ? true : false;
                                            $objForm->add_input_checkbox("chkModuloDependencia_{$arrTMP["key"]}",$boolChecked,"",true);
                                            ?>
                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-2">&nbsp;</div>
            </div>
            <?php
        $objForm->form_closeForm();
    }
    public function drawButtonsModulos($boolEdit = false) {

        global $objTemplate, $lang, $strAction;

        if( $boolEdit ){

            $objTemplate->draw_button("btnSave",$lang["core"]["config_modulo_save"],"fntSubmitModule();","floppy-save","sm","success");
            $objTemplate->draw_button("btnCancel",$lang["core"]["config_modulo_cancel"],"document.location.href='{$strAction}?typeContent=modulos'","remove");

        }
        else{

            $objTemplate->draw_button("btnNew",$lang["core"]["config_modulo_new"],"document.location.href='{$strAction}?typeContent=modulosEdit'","plus");

        }

    }

    public function drawTipoAcceso() {

        global $objTemplate, $lang, $strAction;

        $arrTiposAcceso = $this->objModel->getTiposAcceso();

        ?>
        <div class="row">
            <div class="col-lg-2">&nbsp;</div>
            <div class="col-lg-8">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?php print $lang["core"]["config_modulo_orden"]; ?></th>
                                <th class="col-sm-3"><?php print $lang["core"]["config_modulo_codigo"]; ?></th>
                                <th class="col-sm-4"><?php print $lang["core"]["config_modulo_nombre"]; ?></th>
                                <th class="col-sm-2"><?php print $lang["core"]["config_modulo_activo"]; ?></th>
                                <th class="col-sm-1">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while( $arrTMP = each($arrTiposAcceso) ) {
                                ?>
                                <tr>
                                    <td><?php print $arrTMP["value"]["orden"]; ?></td>
                                    <td><?php print $arrTMP["value"]["codigo"]; ?></td>
                                    <td><?php print $arrTMP["value"]["nombre"]; ?></td>
                                    <td><?php print $arrTMP["value"]["activo"] == "Y" ? $lang["core"]["yes"] : $lang["core"]["no"]; ?></td>
                                    <td>
                                        <?php $objTemplate->draw_icon("","document.location.href='{$strAction}?typeContent=tipoAccesoEdit&tipoAcceso=".(md5($arrTMP["value"]["tipo_acceso"]))."'","pencil","sm",true); ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-2">&nbsp;</div>
        </div>
        <?php

    }
    public function drawEditTipoAcceso($intTipoAcceso) {

        global $objTemplate, $lang, $strAction;

        $intTipoAcceso = intval($intTipoAcceso);
        $boolEdit = ($intTipoAcceso);

        $arrTipoAcceso = $this->objModel->getInfoTipoAcceso($intTipoAcceso);

        $arrIdiomas = getIdiomasArreglo();

        ?>

        <script type="text/javascript">

            $(function(){
                $("[rel=tooltip]").tooltip();
            });

            function fntSubmitTipoAcceso(){

                var boolError = false;

                $("input[name='txtCodigo']").val($.trim($("input[name='txtCodigo']").val()));
                $("input[name='txtOrden']").val($.trim($("input[name='txtOrden']").val()));

                $("input[name='txtCodigo']").popover("hide");
                $("input[name='txtOrden']").popover("hide");

                if( $("input[name='txtCodigo']").val().length == 0 ) {

                    $("input[name='txtCodigo']").popover("destroy");

                    $("input[name='txtCodigo']").popover({
                        title: "<?php print $lang["core"]["campo_requerido_title"]; ?>",
                        placement: 'right',
                        content: "<?php print $lang["core"]["campo_requerido_content"]; ?>",
                        animation: true
                    });

                    $("input[name='txtCodigo']").popover("show");
                    boolError = true;
                }

                if( $("input[name='txtOrden']").val().length == 0 ) {

                    $("input[name='txtOrden']").popover("destroy");

                    $("input[name='txtOrden']").popover({
                        title: "<?php print $lang["core"]["campo_requerido_title"]; ?>",
                        placement: 'right',
                        content: "<?php print $lang["core"]["campo_requerido_content"]; ?>",
                        animation: true
                    });

                    $("input[name='txtOrden']").popover("show");
                    boolError = true;
                }

                $("input[name^='txtIdioma_']").each(function(){

                    $(this).val($.trim($(this).val()));
                    $(this).popover("hide");

                    if( $(this).val().length == 0 ) {

                        $(this).popover("destroy");

                        $(this).popover({
                            title: "<?php print $lang["core"]["campo_requerido_title"]; ?>",
                            placement: 'right',
                            content: "<?php print $lang["core"]["campo_requerido_content"]; ?>",
                            animation: true
                        });

                        $(this).popover("show");
                        boolError = true;
                    }

                });

                if( !boolError ){
                    getDocumentLayer("frmEditTipoAcceso").submit();
                }

            }

        </script>

        <?php

        $objForm = new form("frmEditTipoAcceso","frmEditTipoAcceso","POST","{$strAction}?typeContent=tipoAcceso","fntSubmitTipoAcceso();");
        $objForm->form_openForm();

            $objForm->add_input_hidden("hidFormEditTipoAcceso", 1, true);
            $objForm->add_input_hidden("hidEditTipoAccesoId", $intTipoAcceso, true);

            ?>
            <div class="row">
                <div class="col-lg-2">&nbsp;</div>
                <div class="col-lg-8">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td class="col-sm-4 text-right">
                                        <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo_orden"], true); ?>
                                    </td>
                                    <td class="col-sm-8">
                                        <?php
                                        $strValor = $boolEdit ? $arrTipoAcceso["orden"] : "";
                                        $objForm->add_input_text("txtOrden",$strValor,"inputSizeComplete",true,$lang["core"]["config_modulo_orden"],$lang["core"]["config_modulo_orden"]);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right">
                                        <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo_codigo"], true); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $strValor = $boolEdit ? $arrTipoAcceso["codigo"] : "";
                                        $objForm->add_input_text("txtCodigo",$strValor,"inputSizeComplete",true,$lang["core"]["config_modulo_codigo"],$lang["core"]["config_modulo_codigo"]);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right">
                                        <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo_activo"]); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $boolChecked = $boolEdit ? ($arrTipoAcceso["activo"] == "Y") : false;
                                        $objForm->add_input_checkbox("chkActivo",$boolChecked,"",true, $lang["core"]["config_modulo_activo"]);
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <?php
                        $objTemplate->drawTitle($lang["core"]["language"]);
                        ?>

                        <table class="table table-hover">
                            <tbody>
                                <?php
                                while( $arrTMP = each($arrIdiomas) ) {
                                    ?>
                                    <tr>
                                        <td class="col-sm-4 text-right">
                                            <?php print $objTemplate->drawTitleLeft($arrTMP["value"]["nombre"], true); ?>
                                        </td>
                                        <td class="col-sm-8">
                                            <?php
                                            $strValor = ( $boolEdit && isset($arrTipoAcceso["idiomas"][$arrTMP["key"]]) ? $arrTipoAcceso["idiomas"][$arrTMP["key"]]["nombre"] : "" );
                                            $objForm->add_input_text("txtIdioma_{$arrTMP["key"]}",$strValor,"inputSizeComplete",true,$arrTMP["value"]["nombre"]);
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-2">&nbsp;</div>
            </div>
            <?php

        $objForm->form_closeForm();

    }
    public function drawButtonsTipoAcceso($boolEdit = false) {

        global $objTemplate, $lang, $strAction;

        if( $boolEdit ){

            $objTemplate->draw_button("btnSave",$lang["core"]["config_modulo_save"],"fntSubmitTipoAcceso();","floppy-save","sm","success");
            $objTemplate->draw_button("btnCancel",$lang["core"]["config_modulo_cancel"],"document.location.href='{$strAction}?typeContent=tipoAcceso'","remove");

        }
        else{

            $objTemplate->draw_button("btnNew",$lang["core"]["config_modulo_new"],"document.location.href='{$strAction}?typeContent=tipoAccesoEdit'","plus");

        }

    }

    public function drawAccesos(){

        global $objTemplate, $lang, $strAction;

        $arrModulos = getModulosArreglo();

        $arrContenido = array();
        $arrContenido[0]["texto"] = $lang["core"]["config_elija_opcion"];
        $arrContenido[0]["selected"] = true;
        while( $arrTMP = each($arrModulos) ) {

            $arrContenido[$arrTMP["key"]]["texto"] = $arrTMP["value"]["nombre"];
            $arrContenido[$arrTMP["key"]]["selected"] = false;

        }

        ?>

        <script type="text/javascript">

            function fntGetResultAccesos(intAcceso){

                intAcceso = !intAcceso ? 0 : intAcceso;

                if( $("select[name='selectModuloSearch']").val() > 0 ){

                    $.ajax({
                        url:"<?php print $strAction; ?>",
                        async: false,
                        data:{
                            getResultAccesos : true,
                            modulo : $("select[name='selectModuloSearch']").val(),
                            acceso : intAcceso
                        },
                        type:'post',
                        dataType:'html',
                        success:function(data){
                            $("#tdContainerAccesos").html(data);
                        }
                    });

                }
                else{
                    $("#tdContainerAccesos").html("");
                }

            }

            function fntCreateNewAcceso(){

                var strModulo = ( $("input[name='hidAccesoModuloId']") ) ? $("input[name='hidAccesoModuloId']").val() : 0;
                var strAcceso = ( $("input[name='hidAccesoId']") ) ? $("input[name='hidAccesoId']").val() : 0;

                if( strModulo != "" && strModulo != undefined )
                    document.location.href="<?php print $strAction; ?>?typeContent=accesosEdit&modulo="+strModulo+"&accesoPertenece="+strAcceso;
                else alert("ALERTA!!! Elija modulo PASARLO A LAS NUEVAS ALERTAS");

            }

        </script>
        <div class="row">
            <div class="col-lg-2">&nbsp;</div>
            <div class="col-lg-8">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="col-sm-2">&nbsp;</th>
                                <th class="col-sm-2">
                                    <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo"]); ?>
                                </th>
                                <th class="col-sm-4">
                                    <?php
                                    $objForm = new form();
                                    $objForm->add_select("selectModuloSearch",$arrContenido,"inputSizeComplete",false);
                                    $objForm->add_input_extraTag("selectModuloSearch","onchange","fntGetResultAccesos();");
                                    $objForm->draw_select("selectModuloSearch");
                                    ?>
                                </th>
                                <th class="col-sm-4">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" id="tdContainerAccesos"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-2">&nbsp;</div>
        </div>
        <?php

    }
    public function drawResultAccesos($intModulo,$intAcceso = 0){

        global $objTemplate, $lang, $strAction;

        $intModulo = intval($intModulo);
        $intAcceso = intval($intAcceso);

        $arrAccesos = $this->objModel->getAccesos($intModulo,$intAcceso);

        ?>

        <div class="table-responsive col-lg-11">

            <?php
            if( $intAcceso ){
                $intAccesoPertenece = $this->objModel->getAccesoPertenece($intAcceso);
                $objTemplate->template_draw_link("",$lang["core"]["config_modulo_regresar"],"fntGetResultAccesos({$intAccesoPertenece});");
            }

            $objForm = new form();
            $objForm->add_input_hidden("hidAccesoId",md5($intAcceso),true);
            $objForm->add_input_hidden("hidAccesoModuloId",md5($intModulo),true);

            ?>

            <table class="table table-hover table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="col-sm-1"><?php print $lang["core"]["config_modulo_orden"]; ?></th>
                        <th class="col-sm-2"><?php print $lang["core"]["config_modulo_codigo"]; ?></th>
                        <th class="col-sm-3"><?php print $lang["core"]["config_modulo_nombre"]; ?></th>
                        <th class="col-sm-2"><?php print $lang["core"]["config_modulo_enlace"]; ?></th>
                        <th class="col-sm-1"><?php print $lang["core"]["config_modulo_privado"]; ?></th>
                        <th class="col-sm-1"><?php print $lang["core"]["config_modulo_publico"]; ?></th>
                        <th class="col-sm-1"><?php print $lang["core"]["config_modulo_activo"]; ?></th>
                        <th class="col-sm-1">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while( $arrTMP = each($arrAccesos) ) {
                        ?>
                        <tr>
                            <td><?php print $arrTMP["value"]["orden"]; ?></td>
                            <td><?php $objTemplate->template_draw_link("",$arrTMP["value"]["codigo"],"fntGetResultAccesos({$arrTMP["value"]["acceso"]});"); ?></td>
                            <td><?php print $arrTMP["value"]["nombre"]; ?></td>
                            <td><?php print !empty($arrTMP["value"]["enlace"]) ? $arrTMP["value"]["enlace"] : "&nbsp;"; ?></td>
                            <td><?php print $arrTMP["value"]["privado"] == "Y" ? $lang["core"]["yes"] : $lang["core"]["no"]; ?></td>
                            <td><?php print $arrTMP["value"]["publico"] == "Y" ? $lang["core"]["yes"] : $lang["core"]["no"]; ?></td>
                            <td><?php print $arrTMP["value"]["activo"] == "Y" ? $lang["core"]["yes"] : $lang["core"]["no"]; ?></td>
                            <td>
                                <?php $objTemplate->draw_icon("","document.location.href='{$strAction}?typeContent=accesosEdit&modulo=".(md5($arrTMP["value"]["modulo"]))."&acceso=".(md5($arrTMP["value"]["acceso"]))."'","pencil","sm",true); ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <?php

    }
    public function drawEditAccesos($intModulo,$intAcceso,$intAccesoPertenece){

        global $objTemplate, $lang, $strAction;

        $intModulo = intval($intModulo);
        $intAcceso = intval($intAcceso);
        $intAccesoPertenece = intval($intAccesoPertenece);
        $boolEdit = ($intModulo && !empty($intAcceso));

        $arrInfoAcceso = $this->objModel->getInfoAccesos($intModulo, $intAcceso);

        $arrIdiomas = getIdiomasArreglo();
        $arrModulos = getModulosArreglo();
        $arrTiposAcceso = $this->objModel->getTiposAcceso();


        $objForm = new form("frmEditAcceso","frmEditAcceso","POST","{$strAction}?typeContent=accesos","fntSubmitAccesos();");

        ?>

        <script type="text/javascript">

            $(function(){
                $("[rel=tooltip]").tooltip();
            });

            function fntSubmitAccesos(){

                var boolError = false;

                $("input[name='txtOrden']").val($.trim($("input[name='txtOrden']").val()));
                $("input[name='txtCodigo']").val($.trim($("input[name='txtCodigo']").val()));

                $("input[name='txtOrden']").popover("hide");
                $("input[name='txtCodigo']").popover("hide");

                if( $("input[name='txtOrden']").val().length == 0 ) {

                    $("input[name='txtOrden']").popover("destroy");

                    $("input[name='txtOrden']").popover({
                        title: "<?php print $lang["core"]["campo_requerido_title"]; ?>",
                        placement: 'right',
                        content: "<?php print $lang["core"]["campo_requerido_content"]; ?>",
                        animation: true
                    });

                    $("input[name='txtOrden']").popover("show");
                    boolError = true;
                }

                if( $("input[name='txtCodigo']").val().length == 0 ) {

                    $("input[name='txtCodigo']").popover("destroy");

                    $("input[name='txtCodigo']").popover({
                        title: "<?php print $lang["core"]["campo_requerido_title"]; ?>",
                        placement: 'right',
                        content: "<?php print $lang["core"]["campo_requerido_content"]; ?>",
                        animation: true
                    });

                    $("input[name='txtCodigo']").popover("show");
                    boolError = true;
                }

                $("input[name^='txtIdiomaNombreMenu_']").each(function(){

                    $(this).val($.trim($(this).val()));
                    $(this).popover("hide");

                    if( $(this).val().length == 0 ) {

                        $(this).popover("destroy");

                        $(this).popover({
                            title: "<?php print $lang["core"]["campo_requerido_title"]; ?>",
                            placement: 'right',
                            content: "<?php print $lang["core"]["campo_requerido_content"]; ?>",
                            animation: true
                        });

                        $(this).popover("show");
                        boolError = true;
                    }

                });

                $("input[name^='txtIdiomaNombrePantalla_']").each(function(){

                    $(this).val($.trim($(this).val()));
                    $(this).popover("hide");

                    if( $(this).val().length == 0 ) {

                        $(this).popover("destroy");

                        $(this).popover({
                            title: "<?php print $lang["core"]["campo_requerido_title"]; ?>",
                            placement: 'right',
                            content: "<?php print $lang["core"]["campo_requerido_content"]; ?>",
                            animation: true
                        });

                        $(this).popover("show");
                        boolError = true;
                    }

                });

                if( !boolError ){
                    getDocumentLayer("frmEditAcceso").submit();
                }

            }


        </script>

        <?php

        $objForm->form_openForm();

            $objForm->add_input_hidden("hidFormEditAcceso", 1, true);
            $objForm->add_input_hidden("hidEditAccesoModuloId", $intModulo, true);
            $objForm->add_input_hidden("hidEditAccesoId", $intAcceso, true);
            $objForm->add_input_hidden("hidEditAccesoPerteneceId", $intAccesoPertenece, true);

            ?>
            <div class="row">
                <div class="col-lg-2">&nbsp;</div>
                <div class="col-lg-8">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td class="col-sm-4">
                                        <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo"], true); ?>
                                    </td>
                                    <td class="col-sm-8"><?php print $arrModulos[$intModulo]["nombre"]; ?></td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo_orden"], true); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $strValor = $boolEdit ? $arrInfoAcceso["orden"] : "";
                                        $objForm->add_input_text("txtOrden",$strValor,"inputSizeComplete",true,$lang["core"]["config_modulo_orden"],$lang["core"]["config_modulo_orden"]);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo_codigo"], true); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $strValor = $boolEdit ? $arrInfoAcceso["codigo"] : "";
                                        $objForm->add_input_text("txtCodigo",$strValor,"inputSizeComplete",true,$lang["core"]["config_modulo_codigo"],$lang["core"]["config_modulo_codigo"]);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo_enlace"]); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $strValor = $boolEdit ? $arrInfoAcceso["path"] : "";
                                        $objForm->add_input_text("txtEnlace",$strValor,"inputSizeComplete",true,$lang["core"]["config_modulo_enlace"],$lang["core"]["config_modulo_enlace"]);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo_privado"]); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $boolChecked = ($boolEdit ? ( ($arrInfoAcceso["privado"] == "Y") ? true : false) : false);
                                        $objForm->add_input_checkbox("chkPrivado",$boolChecked,"",true, $lang["core"]["config_modulo_privado"]);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo_publico"]); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $boolChecked = ($boolEdit ? ( ($arrInfoAcceso["publico"] == "Y") ? true : false) : false);
                                        $objForm->add_input_checkbox("chkPublico",$boolChecked,"",true, $lang["core"]["config_modulo_publico"]);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo_activo"]); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $boolChecked = ($boolEdit ? ( ($arrInfoAcceso["activo"] == "Y") ? true : false) : true);
                                        $objForm->add_input_checkbox("chkActivo",$boolChecked,"",true, $lang["core"]["config_modulo_activo"]);
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <?php $objTemplate->drawTitle($lang["core"]["language"]); ?>

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="col-sm-2">&nbsp;</th>
                                    <th class="col-sm-6"><?php print $lang["core"]["config_nombre_menu"]; ?></th>
                                    <th class="col-sm-4"><?php print $lang["core"]["config_nomb_pantalla"]; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while( $arrTMP = each($arrIdiomas) ) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?php print $objTemplate->drawTitleLeft($arrTMP["value"]["nombre"], true); ?>
                                        </td>
                                        <td>
                                            <?php
                                            $strValor = ( $boolEdit && isset($arrInfoAcceso["idiomas"][$arrTMP["key"]]) ? $arrInfoAcceso["idiomas"][$arrTMP["key"]]["nombre_menu"] : "" );
                                            $objForm->add_input_text("txtIdiomaNombreMenu_{$arrTMP["key"]}",$strValor,"inputSizeComplete",true,$arrTMP["value"]["nombre"]);
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $strValor = ( $boolEdit && isset($arrInfoAcceso["idiomas"][$arrTMP["key"]]) ? $arrInfoAcceso["idiomas"][$arrTMP["key"]]["nombre_pantalla"] : "" );
                                            $objForm->add_input_text("txtIdiomaNombrePantalla_{$arrTMP["key"]}",$strValor,"inputSizeComplete",true,$arrTMP["value"]["nombre"]);
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>

                        <?php $objTemplate->drawTitle($lang["core"]["sidebar_tipo_acceso"]); ?>

                        <table class="table table-hover">
                            <tbody>
                                <?php
                                while( $arrTMP = each($arrTiposAcceso) ) {
                                    ?>
                                    <tr>
                                        <td class="col-sm-4">
                                            <?php print $objTemplate->drawTitleLeft($arrTMP["value"]["nombre"]); ?>
                                        </td>
                                        <td class="col-sm-8">
                                            <?php
                                            $boolChecked = ($boolEdit ? isset($arrInfoAcceso["acceso_permitido"][$arrTMP["key"]]) : false);
                                            $objForm->add_input_checkbox("chkTipoAcceso_{$arrTMP["key"]}",$boolChecked,"",true);
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-2">&nbsp;</div>
            </div>
            <?php

        $objForm->form_closeForm();


    }
    public function drawButtonsAccesos($boolEdit = false) {

        global $objTemplate, $lang, $strAction;

        if( $boolEdit ){

            $objTemplate->draw_button("btnSave",$lang["core"]["config_modulo_save"],"fntSubmitAccesos();","floppy-save","sm","success");
            $objTemplate->draw_button("btnCancel",$lang["core"]["config_modulo_cancel"],"document.location.href='{$strAction}?typeContent=accesos'","remove");

        }
        else{

            $objTemplate->draw_button("btnNew",$lang["core"]["config_modulo_new"],"fntCreateNewAcceso();","plus");
            //$objTemplate->draw_button("btnNew",$lang["core"]["config_modulo_new"],"document.location.href='{$strAction}?typeContent=accesosEdit'","plus");

        }

    }

    public function drawIdiomas() {

        global $objTemplate, $lang, $strAction;

        $arrIdiomas = getIdiomasArreglo();

        ?>
        <div class="row">
            <div class="col-lg-2">&nbsp;</div>
            <div class="col-lg-8">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 40%"><?php print $lang["core"]["config_modulo_codigo"]; ?></th>
                                <th style="width: 50%"><?php print $lang["core"]["config_modulo_nombre"]; ?></th>
                                <th style="width: 10%">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while( $arrTMP = each($arrIdiomas) ) {
                                ?>
                                <tr>
                                    <td><?php print $arrTMP["value"]["codigo"]; ?></td>
                                    <td><?php print $arrTMP["value"]["nombre"]; ?></td>
                                    <td class="text-center">
                                        <?php $objTemplate->draw_icon("","document.location.href='{$strAction}?typeContent=idiomasEdit&idioma=".(md5($arrTMP["key"]))."'","pencil","sm",true); ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-2">&nbsp;</div>
        </div>
        <?php

    }
    public function drawEditIdiomas($intIdioma) {

        global $objTemplate, $lang, $strAction;

        $intIdioma = intval($intIdioma);
        $boolEdit = ($intIdioma);

        $arrIdioma = getIdiomasArreglo($intIdioma);

        ?>

        <script type="text/javascript">

            $(function(){
                $("[rel=tooltip]").tooltip();
            });

            function fntSubmitIdioma(){

                var boolError = false;

                $("input[name='txtCodigo']").val($.trim($("input[name='txtCodigo']").val()));
                $("input[name='txtNombre']").val($.trim($("input[name='txtNombre']").val()));

                $("input[name='txtCodigo']").popover("hide");
                $("input[name='txtNombre']").popover("hide");

                if( $("input[name='txtCodigo']").val().length == 0 ) {

                    $("input[name='txtCodigo']").popover("destroy");

                    $("input[name='txtCodigo']").popover({
                        title: "<?php print $lang["core"]["campo_requerido_title"]; ?>",
                        placement: 'right',
                        content: "<?php print $lang["core"]["campo_requerido_content"]; ?>",
                        animation: true
                    });

                    $("input[name='txtCodigo']").popover("show");
                    boolError = true;
                }

                if( $("input[name='txtNombre']").val().length == 0 ) {

                    $("input[name='txtNombre']").popover("destroy");

                    $("input[name='txtNombre']").popover({
                        title: "<?php print $lang["core"]["campo_requerido_title"]; ?>",
                        placement: 'right',
                        content: "<?php print $lang["core"]["campo_requerido_content"]; ?>",
                        animation: true
                    });

                    $("input[name='txtNombre']").popover("show");
                    boolError = true;
                }

                if( !boolError ){
                    getDocumentLayer("frmEditIdioma").submit();
                }

            }

        </script>

        <?php

        $objForm = new form("frmEditIdioma","frmEditIdioma","POST","{$strAction}?typeContent=idiomas","fntSubmitIdioma();");
        $objForm->form_openForm();

            $objForm->add_input_hidden("hidFormEditIdioma", 1, true);
            $objForm->add_input_hidden("hidEditIdiomaId", $intIdioma, true);

            ?>
            <div class="row">
                <div class="col-lg-2">&nbsp;</div>
                <div class="col-lg-8">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td style="width: 40%;" class="text-right">
                                        <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo_codigo"], true); ?>
                                    </td>
                                    <td style="width: 60%;">
                                        <?php
                                        $strValor = $boolEdit ? $arrIdioma[$intIdioma]["codigo"] : "";
                                        $objForm->add_input_text("txtCodigo",$strValor,"inputSizeComplete",true,$lang["core"]["config_modulo_codigo"],$lang["core"]["config_modulo_codigo"]);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right">
                                        <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo_nombre"], true); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $strValor = $boolEdit ? $arrIdioma[$intIdioma]["nombre"] : "";
                                        $objForm->add_input_text("txtNombre",$strValor,"inputSizeComplete",true,$lang["core"]["config_modulo_nombre"],$lang["core"]["config_modulo_nombre"]);
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <?php

        $objForm->form_closeForm();


    }
    public function drawButtonsIdiomas($boolEdit = false) {

        global $objTemplate, $lang, $strAction;

        if( $boolEdit ){

            $objTemplate->draw_button("btnSave",$lang["core"]["config_modulo_save"],"fntSubmitIdioma();","floppy-save","sm","success");
            $objTemplate->draw_button("btnCancel",$lang["core"]["config_modulo_cancel"],"document.location.href='{$strAction}?typeContent=idiomas'","remove");

        }
        else{

            $objTemplate->draw_button("btnNew",$lang["core"]["config_modulo_new"],"document.location.href='{$strAction}?typeContent=idiomasEdit'","plus");

        }

    }

    public function drawEtiquetas(){

        global $objTemplate, $lang, $strAction;

        $arrModulos = getModulosArreglo();

        $arrContenido = array();
        $arrContenido[0]["texto"] = $lang["core"]["config_elija_opcion"];
        $arrContenido[0]["selected"] = true;
        while( $arrTMP = each($arrModulos) ) {

            $arrContenido[$arrTMP["key"]]["texto"] = $arrTMP["value"]["nombre"];
            $arrContenido[$arrTMP["key"]]["selected"] = false;

        }

        ?>

        <script type="text/javascript">

            function fntGetResultEtiquetas(){

                if( $("select[name='selectModuloSearch']").val() > 0 ){

                    $.ajax({
                        url:"<?php print $strAction; ?>",
                        async: false,
                        data:{
                            getResultLangs : true,
                            modulo : $("select[name='selectModuloSearch']").val()
                        },
                        type:'post',
                        dataType:'html',
                        success:function(data){

                            $("#tdContainerLangs").html(data);

                        }
                    });

                }
                else{
                    $("#tdContainerLangs").html("");
                }

            }

        </script>
        <div class="row">
            <div class="col-lg-2">&nbsp;</div>
            <div class="col-lg-8">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="col-sm-2">&nbsp;</th>
                                <th class="col-sm-3">
                                    <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo"]); ?>
                                </th>
                                <th class="col-sm-4">
                                    <?php
                                    $objForm = new form();
                                    $objForm->add_select("selectModuloSearch",$arrContenido,"inputSizeComplete",false);
                                    $objForm->add_input_extraTag("selectModuloSearch","onchange","fntGetResultEtiquetas();");
                                    $objForm->draw_select("selectModuloSearch");
                                    ?>
                                </th>
                                <th class="col-sm-3">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" id="tdContainerLangs"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-2">&nbsp;</div>
        </div>
        <?php

    }
    public function drawResultEtiquetas($intModulo){

        global $objTemplate, $lang, $strAction;

        $arrEtiquetas = $this->objModel->getEtiquetas($intModulo);
        ?>

        <div class="col-lg-12">
            <table class="table table-hover table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="col-sm-5"><?php print $lang["core"]["config_modulo_codigo"]; ?></th>
                        <th class="col-sm-6"><?php print $lang["core"]["config_modulo_nombre"]; ?></th>
                        <th class="col-sm-1">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while( $arrTMP = each($arrEtiquetas) ) {
                        ?>
                        <tr>
                            <td><?php print $arrTMP["value"]["codigo"]; ?></td>
                            <td><?php print htmlentities($arrTMP["value"]["valor"], ENT_QUOTES, "ISO8859-1"); ?></td>
                            <td>
                                <?php $objTemplate->draw_icon("","document.location.href='{$strAction}?typeContent=langsEdit&modulo=".(md5($intModulo))."&lang=".(md5($arrTMP["value"]["codigo"]))."'","pencil","sm",true); ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <?php

    }
    public function drawEditEtiquetas($intModulo = 0, $strLang = "") {

        global $objTemplate, $lang, $strAction;

        $intModulo = intval($intModulo);
        $boolEdit = ($intModulo && !empty($strLang));

        $arrEtiqueta = $this->objModel->getInfoEtiqueta($intModulo, $strLang);
        $strCodigoLang = isset($arrEtiqueta["codigo"]) ? $arrEtiqueta["codigo"] : "";

        $arrIdiomas = getIdiomasArreglo();
        $arrModulos = getModulosArreglo();

        $arrContenido = array();
        while( $arrTMP = each($arrModulos) ) {

            $arrContenido[$arrTMP["key"]]["texto"] = $arrTMP["value"]["nombre"];
            $arrContenido[$arrTMP["key"]]["selected"] = $arrTMP["key"] == $intModulo;

        }

        ?>

        <script type="text/javascript">

            $(function(){
                $("[rel=tooltip]").tooltip();
            });

            function fntSubmitLang(){

                var boolError = false;

                $("input[name='txtCodigo']").val($.trim($("input[name='txtCodigo']").val()));

                $("input[name='txtCodigo']").popover("hide");

                if( $("input[name='txtCodigo']").val().length == 0 ) {

                    $("input[name='txtCodigo']").popover("destroy");

                    $("input[name='txtCodigo']").popover({
                        title: "<?php print $lang["core"]["campo_requerido_title"]; ?>",
                        placement: 'right',
                        content: "<?php print $lang["core"]["campo_requerido_content"]; ?>",
                        animation: true
                    });

                    $("input[name='txtCodigo']").popover("show");
                    boolError = true;
                }

                $("input[name^='txtIdioma_']").each(function(){

                    $(this).val($.trim($(this).val()));
                    $(this).popover("hide");

                    if( $(this).val().length == 0 ) {

                        $(this).popover("destroy");

                        $(this).popover({
                            title: "<?php print $lang["core"]["campo_requerido_title"]; ?>",
                            placement: 'right',
                            content: "<?php print $lang["core"]["campo_requerido_content"]; ?>",
                            animation: true
                        });

                        $(this).popover("show");
                        boolError = true;
                    }

                });

                if( !boolError ){
                    getDocumentLayer("frmEditLang").submit();
                }

            }

        </script>

        <?php

        $objForm = new form("frmEditLang","frmEditLang","POST","{$strAction}?typeContent=langs","fntSubmitLang();");
        $objForm->form_openForm();

            $objForm->add_input_hidden("hidFormEditLang", 1, true);
            $objForm->add_input_hidden("hidEditLangModuloId", $intModulo, true);
            $objForm->add_input_hidden("hidEditLangId", $strCodigoLang, true);

            ?>
            <div class="row">
                <div class="col-lg-2">&nbsp;</div>
                <div class="col-lg-8">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td class="col-sm-4">
                                        <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo"], true); ?>
                                    </td>
                                    <td class="col-sm-8">
                                        <?php

                                        if( $boolEdit ){

                                            $objForm->add_select("selectModulo",$arrContenido,"inputSizeComplete",true,"",true, true);

                                        }
                                        else{

                                            $objForm->add_select("selectModulo",$arrContenido,"inputSizeComplete",true,$lang["core"]["config_modulo"]);

                                        }

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo_codigo"], true); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $strValor = $boolEdit ? $arrEtiqueta["codigo"] : "";
                                        $objForm->add_input_text("txtCodigo",$strValor,"inputSizeComplete",true,$lang["core"]["config_modulo_codigo"],$lang["core"]["config_modulo_codigo"]);
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <?php
                        $objTemplate->drawTitle($lang["core"]["language"]);
                        ?>

                        <table class="table table-hover">
                            <tbody>
                                <?php
                                while( $arrTMP = each($arrIdiomas) ) {
                                    ?>
                                    <tr>
                                        <td class="col-sm-4">
                                            <?php print $objTemplate->drawTitleLeft($arrTMP["value"]["nombre"], true); ?>
                                        </td>
                                        <td class="col-sm-8">
                                            <?php
                                            $strValor = ( $boolEdit && isset($arrEtiqueta["idiomas"][$arrTMP["key"]]) ? htmlentities($arrEtiqueta["idiomas"][$arrTMP["key"]]["nombre"], ENT_QUOTES, "ISO8859-1") : "" );
                                            $objForm->add_input_text("txtIdioma_{$arrTMP["key"]}",$strValor,"inputSizeComplete",true,$arrTMP["value"]["nombre"]);
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-2">&nbsp;</div>
            </div>
            <?php

        $objForm->form_closeForm();


    }
    public function drawButtonsEtiquetas($boolEdit = false) {

        global $objTemplate, $lang, $strAction;

        if( $boolEdit ){

            $objTemplate->draw_button("btnSave",$lang["core"]["config_modulo_save"],"fntSubmitLang();","floppy-save","sm","success");
            $objTemplate->draw_button("btnCancel",$lang["core"]["config_modulo_cancel"],"document.location.href='{$strAction}?typeContent=langs'","remove");

        }
        else{

            $objTemplate->draw_button("btnNew",$lang["core"]["config_modulo_new"],"document.location.href='{$strAction}?typeContent=langsEdit'","plus");

        }

    }

    public function drawConfig(){

        global $objTemplate, $lang, $strAction;

        $arrModulos = getModulosArreglo();

        $arrContenido = array();
        $arrContenido[0]["texto"] = $lang["core"]["config_elija_opcion"];
        $arrContenido[0]["selected"] = true;
        while( $arrTMP = each($arrModulos) ) {

            $arrContenido[$arrTMP["key"]]["texto"] = $arrTMP["value"]["nombre"];
            $arrContenido[$arrTMP["key"]]["selected"] = false;

        }

        ?>

        <script type="text/javascript">

            function fntGetResultVariablesConfig(){

                if( $("select[name='selectModuloSearch']").val() > 0 ){

                    $.ajax({
                        url:"<?php print $strAction; ?>",
                        async: false,
                        data:{
                            getResultCofiguracion : true,
                            modulo : $("select[name='selectModuloSearch']").val()
                        },
                        type:'post',
                        dataType:'html',
                        success:function(data){

                            $("#tdContainerVariablesConfig").html(data);

                        }
                    });

                }
                else{
                    $("#tdContainerVariablesConfig").html("");
                }

            }

        </script>

        <div class="row">
            <div class="col-lg-2">&nbsp;</div>
            <div class="col-lg-8">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="col-sm-2">&nbsp;</th>
                                <th class="col-sm-2">
                                    <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo"]); ?>
                                </th>
                                <th class="col-sm-4">
                                    <?php
                                    $objForm = new form();
                                    $objForm->add_select("selectModuloSearch",$arrContenido,"inputSizeComplete",false);
                                    $objForm->add_input_extraTag("selectModuloSearch","onchange","fntGetResultVariablesConfig();");
                                    $objForm->draw_select("selectModuloSearch");
                                    ?>
                                </th>
                                <th class="col-sm-4">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" id="tdContainerVariablesConfig"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-2">&nbsp;</div>
        </div>
        <?php

    }
    public function drawResultConfig($intModulo){

        global $objTemplate, $lang, $strAction;

        $arrVariables = $this->objModel->getVariablesConfig($intModulo);
        ?>

        <div class="col-lg-12">
            <table class="table table-hover table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="col-sm-1"><?php print $lang["core"]["config_modulo_codigo"]; ?></th>
                        <th class="col-sm-3"><?php print $lang["core"]["config_modulo_nombre"]; ?></th>
                        <th class="col-sm-1"><?php print $lang["core"]["config_modulo_tipo_campo"]; ?></th>
                        <th class="col-sm-3"><?php print $lang["core"]["config_modulo_valor"]; ?></th>
                        <th class="col-sm-3"><?php print $lang["core"]["config_modulo_descripcion"]; ?></th>
                        <th class="col-sm-1">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while( $arrTMP = each($arrVariables) ) {
                        ?>
                        <tr>
                            <td><?php print $arrTMP["value"]["codigo"]; ?></td>
                            <td><?php print $arrTMP["value"]["nombre"]; ?></td>
                            <td><?php print ucfirst($arrTMP["value"]["tipo_dato"]); ?></td>
                            <td><?php print $arrTMP["value"]["valor"]; ?></td>
                            <td><?php print $arrTMP["value"]["descripcion"]; ?></td>
                            <td>
                                <?php $objTemplate->draw_icon("","document.location.href='{$strAction}?typeContent=configEdit&modulo=".(md5($intModulo))."&codigo=".(md5($arrTMP["value"]["codigo"]))."'","pencil","sm",true); ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <?php

    }
    public function drawEditConfig($intModulo = 0, $strCodigo = "") {

        global $objTemplate, $lang, $strAction;

        $intModulo = intval($intModulo);
        $boolEdit = ($intModulo && !empty($strCodigo));

        $arrInfoConfig = $this->objModel->getInfoVariablesConfig($intModulo, $strCodigo);
        $arrTiposDato = $this->objModel->getTiposDato();

        $arrIdiomas = getIdiomasArreglo();
        $arrModulos = getModulosArreglo();

        $arrContenido = array();
        while( $arrTMP = each($arrModulos) ) {

            $arrContenido[$arrTMP["key"]]["texto"] = $arrTMP["value"]["nombre"];
            $arrContenido[$arrTMP["key"]]["selected"] = $arrTMP["key"] == $intModulo;

        }


        $arrContenidoTipoDato = array();
        while( $arrTMP = each($arrTiposDato) ) {

            $arrContenidoTipoDato[$arrTMP["key"]]["texto"] = $arrTMP["value"];
            $arrContenidoTipoDato[$arrTMP["key"]]["selected"] = ( $boolEdit ? ( $arrTMP["key"] == $arrInfoConfig["tipo_dato"] ) : false );

        }


        $objForm = new form("frmEditConfig","frmEditConfig","POST","{$strAction}?typeContent=config","fntSubmitConfig();");

        ?>

        <script type="text/javascript">

            var intCountLista = 0;

            $(function(){
                $("[rel=tooltip]").tooltip();
                fntSetDisplayTipoCampo();
            });

            function fntSubmitConfig(){

                var boolError = false;

                $("input[name='txtCodigo']").val($.trim($("input[name='txtCodigo']").val()));

                $("input[name='txtCodigo']").popover("hide");

                if( $("input[name='txtCodigo']").val().length == 0 ) {

                    $("input[name='txtCodigo']").popover("destroy");

                    $("input[name='txtCodigo']").popover({
                        title: "<?php print $lang["core"]["campo_requerido_title"]; ?>",
                        placement: 'right',
                        content: "<?php print $lang["core"]["campo_requerido_content"]; ?>",
                        animation: true
                    });

                    $("input[name='txtCodigo']").popover("show");
                    boolError = true;
                }

                $("input[name='textValor']").val($.trim($("input[name='textValor']").val()));

                $("input[name='textValor']").popover("hide");

                if( $("input[name='textValor']").val().length == 0 ) {

                    $("input[name='textValor']").popover("destroy");

                    $("input[name='textValor']").popover({
                        title: "<?php print $lang["core"]["campo_requerido_title"]; ?>",
                        placement: 'right',
                        content: "<?php print $lang["core"]["campo_requerido_content"]; ?>",
                        animation: true
                    });

                    $("input[name='textValor']").popover("show");
                    boolError = true;
                }

                $("input[name^='txtIdioma_']").each(function(){

                    $(this).val($.trim($(this).val()));
                    $(this).popover("hide");

                    if( $(this).val().length == 0 ) {

                        $(this).popover("destroy");

                        $(this).popover({
                            title: "<?php print $lang["core"]["campo_requerido_title"]; ?>",
                            placement: 'right',
                            content: "<?php print $lang["core"]["campo_requerido_content"]; ?>",
                            animation: true
                        });

                        $(this).popover("show");
                        boolError = true;
                    }

                });

                if( !boolError ){
                    getDocumentLayer("frmEditConfig").submit();
                }

            }

            function fntSetDisplayTipoCampo(){

                strTipo = $("select[name='selectTipoDato']").val();

                $("input[name='textValor']").hide();
                $("input[name='chkValor']").hide();
                $("textarea[name='textareaValor']").hide();
                $("#tblContentListado").hide();

                if( strTipo == "texto" || strTipo == "fecha" ){
                    $("input[name='textValor']").show();
                }
                else if( strTipo == "descripcion" ){
                    $("textarea[name='textareaValor']").show();
                }
                else if( strTipo == "checkbox" ){
                    $("input[name='chkValor']").show();
                }
                else if( strTipo == "lista" ){
                    $("input[name='textValor']").show();
                    $("#tblContentListado").show();
                }


            }

            function fntAddListado(){

                var strTable = "tblContentListado";

                var arrElements = new Array();
                var arrOptions = new Array();

                var objForm = new forms();

                var intCountCell = 0;

                intCountLista++;

                arrElements[intCountCell] = new Array();
                arrElements[intCountCell]["contenido"] = objForm.add_input_text("txtListaOpc_"+intCountLista,"","inputSizeComplete");

                intCountCell++;

                arrElements[intCountCell] = new Array();
                arrElements[intCountCell]["contenido"] = objForm.draw_icon("imgDelete_"+intCountLista,"fntDeleteRow(this);","remove","sm",true);


                addDynamicRow(strTable,arrElements,arrOptions);

            }

            function fntDeleteRow( objImg ){

                objRow = objImg.parentNode.parentNode;

                getDocumentLayer("tblContentListado").deleteRow(objRow.rowIndex);

            }


        </script>

        <?php

        $objForm->form_openForm();

            $objForm->add_input_hidden("hidFormEditConfig", 1, true);
            $objForm->add_input_hidden("hidEditConfigModuloId", $intModulo, true);
            $objForm->add_input_hidden("hidEditConfigId", $strCodigo, true);

            $intCountLista = 0;

            ?>
            <div class="row">
                <div class="col-lg-2">&nbsp;</div>
                <div class="col-lg-8">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td class="col-sm-4">
                                        <?php $objTemplate->drawTitleLeft($lang["core"]["config_modulo"], true); ?>
                                    </td>
                                    <td class="col-sm-8">
                                        <?php

                                        if( $boolEdit ){

                                            $objForm->add_select("selectModulo",$arrContenido,"inputSizeComplete",true,"",true, true);

                                        }
                                        else{

                                            $objForm->add_select("selectModulo",$arrContenido,"inputSizeComplete",true,$lang["core"]["config_modulo"]);

                                        }

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php $objTemplate->drawTitleLeft($lang["core"]["config_modulo_codigo"], true); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $strValor = $boolEdit ? $arrInfoConfig["codigo"] : "";
                                        $objForm->add_input_text("txtCodigo",$strValor,"inputSizeComplete",true,$lang["core"]["config_modulo_codigo"],$lang["core"]["config_modulo_codigo"]);
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php $objTemplate->drawTitleLeft($lang["core"]["config_modulo_tipo_campo"], true); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $objForm->add_select("selectTipoDato",$arrContenidoTipoDato,"inputSizeComplete",false,$lang["core"]["config_modulo_tipo_campo"]);
                                        $objForm->add_input_extraTag("selectTipoDato","onchange","fntSetDisplayTipoCampo();");
                                        $objForm->draw_select("selectTipoDato");
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php $objTemplate->drawTitleLeft($lang["core"]["config_modulo_valor"], true); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $boolChecked = true;
                                        $strValor = "";
                                        if( isset($arrInfoConfig["tipo_dato"]) && ($arrInfoConfig["tipo_dato"] == "texto" || $arrInfoConfig["tipo_dato"] == "fecha" || $arrInfoConfig["tipo_dato"] == "lista" || $arrInfoConfig["tipo_dato"] == "descripcion") ){
                                            $strValor = $arrInfoConfig["valor"];
                                        }
                                        elseif( isset($arrInfoConfig["tipo_dato"]) && $arrInfoConfig["tipo_dato"] == "checkbox" ){
                                            $boolChecked = $arrInfoConfig["tipo_dato"] == "true" ? true : false;
                                        }
                                        //enum('texto', 'fecha', 'descripcion', 'lista', 'checkbox')
                                        $objForm->add_input_text("textValor",$strValor,"inputSizeComplete",true);
                                        $objForm->add_textarea("textareaValor",$strValor,"inputSizeComplete",true,"","",false,true);
                                        $objForm->add_input_checkbox("chkValor",$boolChecked,true,true,"",false,true);
                                        ?>

                                        <table class="table inputSizeComplete" id="tblContentListado" style="display: none;">
                                            <thead>
                                                <tr>
                                                    <th>Opciones</th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if( isset($arrInfoConfig["valores"]) ){
                                                    $arrValores = explode(",",$arrInfoConfig["valores"]);
                                                    while( $arrTMP = each($arrValores) ){
                                                        $intCountLista++;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?php
                                                                $objForm->add_input_text("txtListaOpc_{$intCountLista}",$arrTMP["value"],"inputSizeComplete",true);
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $objTemplate->draw_icon("imgDelete_{$intCountLista}","fntDeleteRow(this);","remove","sm",true);
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td class="col-sm-10">
                                                        <?php $objTemplate->draw_icon("imgAddListado","fntAddListado();","plus","sm",true); ?>
                                                    </td>
                                                    <td class="col-sm-2">&nbsp;</td>
                                                </tr>
                                            </tfoot>
                                        </table>

                                        <script type="text/javascript">
                                            intCountLista = "<?php $intCountLista; ?>";
                                        </script>

                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <?php

                        $objTemplate->drawTitle($lang["core"]["language"]);
                        ?>

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="col-sm-2">&nbsp;</th>
                                    <th class="col-sm-6"><?php print $lang["core"]["config_modulo_nombre"]; ?></th>
                                    <th class="col-sm-4"><?php print $lang["core"]["config_modulo_descripcion"]; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while( $arrTMP = each($arrIdiomas) ) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?php print $objTemplate->drawTitleLeft($arrTMP["value"]["nombre"], true); ?>
                                        </td>
                                        <td>
                                            <?php
                                            $strValor = ( $boolEdit && isset($arrInfoConfig["idiomas"][$arrTMP["key"]]) ? $arrInfoConfig["idiomas"][$arrTMP["key"]]["nombre"] : "" );
                                            $objForm->add_input_text("txtIdioma_{$arrTMP["key"]}",$strValor,"inputSizeComplete",true,$arrTMP["value"]["nombre"]);
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $strValor = ( $boolEdit && isset($arrInfoConfig["idiomas"][$arrTMP["key"]]) ? $arrInfoConfig["idiomas"][$arrTMP["key"]]["descripcion"] : "" );
                                            $objForm->add_textarea("textareaIdiomaDescripcion_{$arrTMP["key"]}",$strValor,"inputSizeComplete",true,$arrTMP["value"]["nombre"]);
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-2">&nbsp;</div>
            </div>
            <?php

        $objForm->form_closeForm();


    }
    public function drawButtonsConfig($boolEdit = false) {

        global $objTemplate, $lang, $strAction;

        if( $boolEdit ){

            $objTemplate->draw_button("btnSave",$lang["core"]["config_modulo_save"],"fntSubmitConfig();","floppy-save","sm","success");
            $objTemplate->draw_button("btnCancel",$lang["core"]["config_modulo_cancel"],"document.location.href='{$strAction}?typeContent=config'","remove");

        }
        else{

            $objTemplate->draw_button("btnNew",$lang["core"]["config_modulo_new"],"document.location.href='{$strAction}?typeContent=configEdit'","plus");

        }

    }

    public function drawQuery() {
        global $objTemplate, $lang, $strAction;
        $objForm = new form("frmQuery","frmQuery","POST");
        $objForm->form_openForm();
        ?>
        <div class="row">
            <div class="col-lg-12">
                <?php
                $strValue = isset($_POST["txtQuery"]) ? $_POST["txtQuery"] : "";
                $objForm->add_textarea("txtQuery",$strValue,"");
                $objForm->add_input_extraTag("txtQuery","style","height:250px");
                $objForm->draw_textarea("txtQuery");
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center">
                <?php
                $objTemplate->draw_button("btnSubmit","Consultar","document.frmQuery.submit();")
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php
                if( !empty($_POST["txtQuery"]) ){
                    @debugQuery($_POST["txtQuery"]);
                    if( mysql_errno() > 0 )
                        drawDebug( mysql_errno() . ": " . mysql_error() );
                }
                ?>
            </div>
        </div>
        <script>
            $(function() {
                setTimeout(function(){ document.getElementById("txtQuery").select(); }, 500);
            });
        </script>
        <?php
        $objForm->form_closeForm();
    }

}