<?php
require_once("modules/configuracion/clases/configuracion_pais_model.php");

class configuracion_pais_view{

    private $objModel;

    public function __construct(){
        $this->objModel = new configuracion_pais_model();
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

        $objTemplate->draw_modal_open("divAlertaCambioPredeterminado","md","md");
        $objTemplate->draw_modal_draw_header("Cambio de pa�s predeterminado","",true,"");
        $objTemplate->draw_modal_open_content("divAlertaCambioPredeterminado");
        ?>
        <div class="text-center">Ya hay un pa�s predeterminado, solo puedo haber un pa�s predeterminado</div>
        <div class="text-center">�Desea cambiarlo?</div>
        <?php
        $objTemplate->draw_modal_close_content();
        $objTemplate->draw_modal_open_footer();
        $objTemplate->draw_button("btnAceptarCambio","Aceptar",'fntAcpRecCambioPredterminado(true)',"check","sm");
        $objTemplate->draw_button("btnRechazarCambio","Rechazar",'fntAcpRecCambioPredterminado(false)',"remove","sm");
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
                         data: "metodo=checkEliminar&pais="+intId,
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
                     data: "metodo=drawContentModal&pais="+intId+"&edicion="+edicion,
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
                $("#predeterminado").show();
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
                $("#divnacionalidad").removeClass("has-error");
                $("#spannacionalidad").hide();
                if( $("#nacionalidad").val().length == 0 ) {
                    $("#divnacionalidad").addClass("has-error");
                    $("#spannacionalidad").show();
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
                    text: '�Est� seguro de eliminar el pais?',
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
                <th style="width: 50%;">Nombre</th>
                <th style="width: 25%;">Nacionalidad</th>
                <th style="width: 12.5%; text-align: center;">Activo</th>
                <th style="width: 12.5%; text-align: center;">Predeterminado</th>
            </tr>
            <?php
            reset($arrInfo);
            while( $arrI = each($arrInfo) ) {
                ?>
                <tr>
                    <td class="text-uppercase"><a class="cursor" onclick="fntOpen(<?php print $arrI["key"]; ?>);"><?php print $arrI["value"]["nombre"]; ?></a></td>
                    <td class="text-uppercase"><?php print $arrI["value"]["nacionalidad"]; ?></td>
                    <td style="text-align: center;"><?php print $arrI["value"]["activo"] == "Y" ? "SI" : "NO"; ?></td>
                    <td style="text-align: center;">
                        <?php
                        print $arrI["value"]["predeterminado"] == "Y" ? "SI" : "NO";
                        if($arrI["value"]["predeterminado"] == "Y"){
                            ?><input id="hdnPredeterminado_<?php print $arrI["value"]["pais"]; ?>" type="text" value="<?php print $arrI["value"]["predeterminado"];?>" class="ocultar" />
                        <?php
                        }
                        ?>
                    </td>
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

        $intId = isset($_POST["pais"]) ? intval($_POST["pais"]) : 0;
        if( $intId > 0 )
            $arrInfo = $this->objModel->getInfo($intId);
        if( isset($arrInfo[$intId]) ) $arrInfo = $arrInfo[$intId];

        $nombre = isset($arrInfo["nombre"]) ? $arrInfo["nombre"] : "";
        $nacionalidad = isset($arrInfo["nacionalidad"]) ? $arrInfo["nacionalidad"] : "";
        $activo = $intId == 0 ? true : false;
        $activo = isset($arrInfo["activo"]) && $arrInfo["activo"] == "Y" ? true : $activo;
        $predeterminado = !empty($arrInfo["predeterminado"]) && $arrInfo["predeterminado"] == "Y" ? true : false;


        $objForm = new form("frmGeneral","frmGeneral","post");
        $objForm->form_openForm();
        $objForm->add_input_hidden("edicion",$edicion,true);
        $objForm->add_input_hidden("pais",$intId,true);
        ?>
        <div class="row">
            <?php
            $this->drawClienteCampoText("Nombre","nombre",$nombre,"col-lg-4 col-lg-offset-1",$objForm,true,"","",$boolLectura,$boolLectura);
            $this->drawClienteCampoText("Nacionalidad","nacionalidad",$nacionalidad,"col-lg-4 col-lg-offset-2",$objForm,true,"","",$boolLectura,$boolLectura);
            ?>
        </div>
        <div class="row">
            <div class="col-lg-4 col-lg-offset-1">
                <label for="por_defecto">
                    <?php $objTemplate->drawTitleLeft("Activo"); ?>
                </label><br>
                <?php
                $objForm->add_input_checkbox("activo",$activo,"",true,"",$boolLectura,$boolLectura);
                ?>
                <br>
            </div>
            <div class="col-lg-4 col-lg-offset-2">
                <label for="por_defecto">
                    <?php $objTemplate->drawTitleLeft("Predeterminado"); ?>
                </label><br>
                <?php
                $objForm->add_input_checkbox("predeterminado",$predeterminado,"",true,"",$boolLectura,$boolLectura);
                ?>
                <br>
            </div>
        </div>
        <script>
            function fntAcpRecCambioPredterminado(boolAcpRech = false){
                if(boolAcpRech)
                    $("#predeterminado").prop("checked",true);
                else
                    $("#predeterminado").prop("checked",false);

                $("#divAlertaCambioPredeterminado").modal('hide');
            }
            $(function(){
                $("input[id^='predeterminado']").on("change",function(){
                    var hdnPredeterminado = $("input[id^='hdnPredeterminado_'");
                    if( hdnPredeterminado.val() == "Y" ) {
                        var split = hdnPredeterminado.attr("id").split("_");
                        if(split.length > 1) {
                            if (split[1] != $("#pais").val()) {
                                if ($(this).prop("checked") == true) {
                                    $("#divAlertaCambioPredeterminado").modal();
                                }
                            }
                        }
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
            <span id="span<?php print $strCampo; ?>" class="help-block ocultar"><span class="fa fa-warning"></span>&nbsp;Por favor ingresa la infomaci�n solicitada.</span>
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
            <span id="span<?php print $strCampo; ?>" class="help-block ocultar"><span class="fa fa-warning"></span>&nbsp;Por favor ingresa la infomaci�n solicitada.</span>
        </div>
        <?php
    }

}