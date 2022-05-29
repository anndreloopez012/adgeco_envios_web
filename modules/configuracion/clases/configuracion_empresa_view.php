<?php
require_once("modules/configuracion/clases/configuracion_empresa_model.php");

class configuracion_empresa_view{

    private $objModel;

    public function __construct(){
        $this->objModel = new configuracion_empresa_model();
    }

    public function drawButtons(){
        global $lang,$strAction,$objTemplate,$arrAccesos;
        if( isset($arrAccesos["crear"]) )
            $objTemplate->draw_button("btnAgregar","Agregar","fntOpen(0);","plus","sm ");
        $objTemplate->draw_button("btnRefrescar","Refrescar","fntGetContentListado();","refresh","sm ");
    }

    public function drawContent() {
        global $objTemplate, $strAction, $arrAccesos;

        $objTemplate->draw_modal_open("divModalGeneral","lg","lg");
        $objTemplate->draw_modal_draw_header("Detalle","",true,"");
        $objTemplate->draw_modal_open_content("divModalGeneralContenido");
        $objTemplate->draw_modal_close_content();
        $objTemplate->draw_modal_open_footer();
            $strOcultar = "";
            if( isset($arrAccesos["modificar"]) ) {
                $objTemplate->draw_button("btnEditar","Editar","fntEditar()","pencil","sm");
                $strOcultar = "ocultar";
            }
            if( isset($arrAccesos["crear"]) || isset($arrAccesos["modificar"]) )
                $objTemplate->draw_button("btnGuardar","Guardar","fntGuardar()","floppy-disk","sm {$strOcultar}");
            if( isset($arrAccesos["eliminar"]) )
                $objTemplate->draw_button("btnEliminar","Eliminar","fntEliminar()","trash","sm");
                $objTemplate->draw_button("btnCerrar","Cerrar","fntCerrar()","remove","sm");
        $objTemplate->draw_modal_close_footer();
        $objTemplate->draw_modal_close();
        ?>
        <div class="box box-solid">
            <div class="box-body">
                <div class="table-responsive" id="divContentListado">&nbsp;</div>
            </div>
        </div>
        <script>
            PNotify.prototype.options.styling = "bootstrap3";
            function fntGetContentListado() {
                showImgCoreLoading();
                $.ajax({
                     url: "<?php print $strAction; ?>",
                     async: true,
                     type: "post",
                     dataType: "html",
                     data: "metodo=drawContentListado",
                     success: function( data ){
                         hideImgCoreLoading();
                         $("#divContentListado").html("");
                         $("#divContentListado").html(data);
                     },
                     error: function() {
                         hideImgCoreLoading();
                     }
                });
            }
            function fntOpen(intId) {
                showImgCoreLoading();
                if( intId == 0 ){
                    $("#btnGuardar").show();
                    $("#btnEditar").hide();
                    $("#btnEliminar").hide();
                    edicion = "Y";
                }
                else {
                    $("#btnEliminar").show();
                    $.ajax({
                         url: "<?php print $strAction; ?>",
                         async: false,
                         type: "post",
                         dataType: "json",
                         data: "metodo=checkEliminar&empresa="+intId,
                         success: function( data ){
                             if( data.estado == "fail" ) {
                                 $("#btnEliminar").hide();
                             }
                         },
                         error: function() {
                             hideImgCoreLoading();
                         }
                    });

                    $("#btnGuardar").hide();
                    $("#btnEditar").show();
                    edicion = "N";
                }

                $.ajax({
                     url: "<?php print $strAction; ?>",
                     async: true,
                     type: "post",
                     dataType: "html",
                     data: "metodo=drawContentModal&empresa="+intId+"&edicion="+edicion,
                     success: function( data ){
                         hideImgCoreLoading();
                         $("#divModalGeneralContenido").html("");
                         $("#divModalGeneralContenido").html(data);
                         $("#divModalGeneral").modal("show");
                     },
                     error: function() {
                         hideImgCoreLoading();
                     }
                });
            }
            function fntEditar() {
                $("div[id*='divReadMode']").hide();
                $(".form-control").show();
                $("#por_defecto").show();
                $("#activo").show();
                $("#btnEditar").hide();
                $("#btnGuardar").show();
                $("#edicion").val("Y");
            }
            function fntGuardar() {
                var boolError = false;
                $("#divnombre").removeClass("has-error");
                $("#spannombre").hide();
                if( $("#nombre").val().length == 0 ) {
                    $("#divnombre").addClass("has-error");
                    $("#spannombre").show();
                    boolError = true;
                }
                $("#divgiin").removeClass("has-error");
                $("#spangiin").hide();
                if( $("#giin").val().length == 0 ) {
                    $("#divgiin").addClass("has-error");
                    $("#spangiin").show();
                    boolError = true;
                }
                if( !boolError ) {
                    showImgCoreLoading();
                    $.ajax({
                         url: "<?php print $strAction; ?>",
                         async: true,
                         type: "post",
                         dataType: "html",
                         data: "metodo=processGuardar&"+$("#frmGeneral").serialize(),
                         success: function( data ){
                             fntCerrar();
                             fntGetContentListado();
                         },
                         error: function() {
                             hideImgCoreLoading();
                         }
                    });
                }
            }
            function fntEliminar() {
                (new PNotify({
                    title: 'Confirmar',
                    text: '�Esta seguro de eliminar la empresa?',
                    icon: 'glyphicon glyphicon-question-sign',
                    hide: false,
                    type: 'info',
                    confirm: {
                        confirm: true,
                        buttons: [
                            {
                                text: "Si",
                                addClass: "",
                                promptTrigger: true,
                                click: function(notice, value){
                                    notice.remove();
                                    notice.get().trigger("pnotify.confirm", [notice, value]);
                                }
                            },
                            {
                                text: "No",
                                addClass: "",
                                click: function(notice){
                                    notice.remove();
                                    notice.get().trigger("pnotify.cancel", notice);
                                }
                            }
                        ]
                    },
                    buttons: {
                        closer: false,
                        sticker: false
                    },
                    history: {
                        history: false
                    },
                    addclass: 'stack-modal',
                    stack: {'dir1': 'down', 'dir2': 'right', 'modal': true}
                })).get().on('pnotify.confirm', function() {
                    fntEliminarAceptar();
                }).on('pnotify.cancel', function(){

                });
            }
            function fntEliminarAceptar() {
                showImgCoreLoading();
                $.ajax({
                     url: "<?php print $strAction; ?>",
                     async: true,
                     type: "post",
                     dataType: "html",
                     data: "metodo=processEliminar&"+$("#frmGeneral").serialize(),
                     success: function( data ){
                         fntCerrar();
                         fntGetContentListado();
                     },
                     error: function() {
                         hideImgCoreLoading();
                     }
                });
            }
            function fntCerrar() {
                $("#divModalGeneral").modal("hide");
            }
            $(function() {
                fntGetContentListado();
            });
        </script>
        <?php
    }

    public function drawContentListado(){
        global $objTemplate, $strAction;

        $arrInfo = $this->objModel->getInfo();
        ?>
        <table class="table">
            <tr>
                <th style="width: 40%;">Nombre</th>
                <th style="width: 20%;">Codigo de la persona obligada (GIIN)</th>
                <th style="width: 20%; text-align: center;">Default</th>
                <th style="width: 20%; text-align: center;">Activo</th>
            </tr>
            <?php
            reset($arrInfo);
            while( $arrI = each($arrInfo) ) {
                ?>
                <tr>
                    <td><a class="cursor" onclick="fntOpen(<?php print $arrI["key"]; ?>);"><?php print $arrI["value"]["nombre"]; ?></a></td>
                    <td><?php print $arrI["value"]["giin"]; ?></td>
                    <td style="text-align: center;"><?php print $arrI["value"]["por_defecto"] == "Y" ? "SI" : "NO"; ?></td>
                    <td style="text-align: center;"><?php print $arrI["value"]["activo"] == "Y" ? "SI" : "NO"; ?></td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }

    public function drawContentModal(){
        global $objTemplate, $strAction, $arrAccesos;

        $boolLectura = (isset($_POST["edicion"]) && $_POST["edicion"] == "Y") ? false : true;
        $edicion = isset($_POST["edicion"]) ? $_POST["edicion"] : "N";

        $intId = isset($_POST["empresa"]) ? intval($_POST["empresa"]) : 0;
        if( $intId > 0 )
            $arrInfo = $this->objModel->getInfo($intId);
        if( isset($arrInfo[$intId]) ) $arrInfo = $arrInfo[$intId];

        $nombre = isset($arrInfo["nombre"]) ? $arrInfo["nombre"] : "";
        $giin = isset($arrInfo["giin"]) ? $arrInfo["giin"] : "";
        $por_defecto = isset($arrInfo["por_defecto"]) && $arrInfo["por_defecto"] == "Y" ? true : false;
        $activo = $intId == 0 ? true : false;
        $activo = isset($arrInfo["activo"]) && $arrInfo["activo"] == "Y" ? true : $activo;


        $objForm = new form("frmGeneral","frmGeneral","post");
        $objForm->form_openForm();
        $objForm->add_input_hidden("edicion",$edicion,true);
        $objForm->add_input_hidden("empresa",$intId,true);
        ?>
        <div class="row">
            <?php
            $this->drawClienteCampoText("Razon social y Nombre Comercial","nombre",$nombre,"col-lg-4 col-lg-offset-1",$objForm,true,"","",$boolLectura,$boolLectura);
            $this->drawClienteCampoText("Codigo de la persona obligada (GIIN)","giin",$giin,"col-lg-4 col-lg-offset-2",$objForm,true,"","",$boolLectura,$boolLectura);
            ?>
        </div>
        <div class="row">
            <div class="col-lg-4 col-lg-offset-1">
                <label for="por_defecto">
                    <?php $objTemplate->drawTitleLeft("Default"); ?>
                </label><br>
                <?php
                $objForm->add_input_checkbox("por_defecto",$por_defecto,"",true,"",$boolLectura,$boolLectura);
                ?>
                <br>
            </div>
            <div class="col-lg-4 col-lg-offset-2">
                <label for="por_defecto">
                    <?php $objTemplate->drawTitleLeft("Activo"); ?>
                </label><br>
                <?php
                $objForm->add_input_checkbox("activo",$activo,"",true,"",$boolLectura,$boolLectura);
                ?>
                <br>
            </div>
        </div>

        <script>
            function fntCheckDefault() {
                showImgCoreLoading();
                empresa = $("#empresa").val();
                $.ajax({
                     url: "<?php print $strAction; ?>",
                     async: false,
                     type: "post",
                     dataType: "json",
                     data: "metodo=checkDefault&empresa="+empresa,
                     success: function( data ){
                         if( data.resultado == "true" ) {
                            (new PNotify({
                                title: 'Confirmar',
                                text: 'Ya exite una empresa default. �Esta seguro de cambiarla?',
                                icon: 'glyphicon glyphicon-question-sign',
                                hide: false,
                                type: 'info',
                                confirm: {
                                    confirm: true,
                                    buttons: [
                                        {
                                            text: "Si",
                                            addClass: "",
                                            promptTrigger: true,
                                            click: function(notice, value){
                                                notice.remove();
                                                notice.get().trigger("pnotify.confirm", [notice, value]);
                                            }
                                        },
                                        {
                                            text: "No",
                                            addClass: "",
                                            click: function(notice){
                                                notice.remove();
                                                notice.get().trigger("pnotify.cancel", notice);
                                            }
                                        }
                                    ]
                                },
                                buttons: {
                                    closer: false,
                                    sticker: false
                                },
                                history: {
                                    history: false
                                },
                                addclass: 'stack-modal',
                                stack: {'dir1': 'down', 'dir2': 'right', 'modal': true}
                            })).get().on('pnotify.confirm', function() {

                            }).on('pnotify.cancel', function(){
                                $("#por_defecto").prop("checked",false);
                            });
                         }
                         hideImgCoreLoading();
                     },
                     error: function() {
                         hideImgCoreLoading();
                     }
                });
            }
            $(function() {
                $("#por_defecto").change(function() {
                    if( $(this).prop("checked") ) {
                        fntCheckDefault();
                    }
                });
            });
        </script>
        <?php
        $objForm->form_closeForm();
    }

    public function drawClienteCampoText($strTitulo,$strCampo,$strValor,$strColumnaClass,$objForm,$boolRequerido=false,$strPlaceHolder = "", $strToolTip = "", $boolIncludeDiv = false, $boolReadMode = false) {
        global $objTemplate;
        ?>
        <div class="<?php print $strColumnaClass; ?>" id="div<?php print $strCampo; ?>">
            <label for="<?php print $strCampo; ?>">
                <?php $objTemplate->drawTitleLeft($strTitulo,$boolRequerido); ?>
            </label>
            <?php
            $objForm->add_input_text($strCampo,$strValor,"text-uppercase",false,$strPlaceHolder,$strToolTip,$boolIncludeDiv,$boolReadMode);
            if($boolRequerido)
                $objForm->add_input_extraTag($strCampo,"required","required");
            $objForm->draw_input_text($strCampo);
            if($boolRequerido) {
                ?>
                <script>
                    $("#<?php print $strCampo; ?>").each(function() {
                      this.setCustomValidity(" ");
                    });
                </script>
                <?php
            }
            ?>
            <span id="span<?php print $strCampo; ?>" class="help-block ocultar"><span class="fa fa-warning"></span>&nbsp;Por favor ingresa la infomacion solicitada.</span>
        </div>
        <?php
    }

    public function drawClienteCampoSelect($strTitulo,$strCampo,$arrValores,$strValorSeleccionado,$strColumnaClass,$objForm,$strPlaceHolder="", $strToolTip="", $boolIncludeDiv=false, $boolReadMode=false,$boolRequerido=false) {
        global $objTemplate;
        ?>
        <div class="<?php print $strColumnaClass; ?>" id="div<?php print $strCampo; ?>">
            <label for="<?php print $strCampo; ?>">
                <?php $objTemplate->drawTitleLeft($strTitulo,$boolRequerido); ?>
            </label>
            <?php
            if( count($arrValores) == 1 ){
                print isset($arrValores[$strValorSeleccionado]) ? "<p class='text-uppercase'>".$arrValores[$strValorSeleccionado]["texto"]."</p>" : "<p class='text-uppercase'>".$arrValores[key($arrValores)]["texto"]."</p>";
                $objForm->add_input_hidden($strCampo,key($arrValores),true);
            }
            else {
                $objForm->add_select($strCampo,$arrValores,"text-uppercase",false,$strToolTip,$boolIncludeDiv,$boolReadMode);
                if($boolRequerido)
                    $objForm->add_input_extraTag($strCampo,"required","required");
                $objForm->draw_select($strCampo);
            }
            if($boolRequerido) {
                ?>
                <script>
                    $("#<?php print $strCampo; ?>").each(function() {
                      this.setCustomValidity(" ");
                    });
                </script>
                <?php
            }
            ?>
            <span id="span<?php print $strCampo; ?>" class="help-block ocultar"><span class="fa fa-warning"></span>&nbsp;Por favor ingresa la infomacion solicitada.</span>
        </div>
        <?php
    }

}