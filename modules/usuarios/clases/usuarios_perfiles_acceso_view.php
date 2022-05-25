<?php
include_once("usuarios_perfiles_acceso_model.php");

class perfiles_acceso_view {

    private $objModel = null;

    public function __construct(){
        $this->objModel = new usuarios_perfiles_model();
    }

    public function draw_buttons_listado(){
        global $objTemplate, $lang, $strAction, $arrAccesos;

        if( array_key_exists("crear",$arrAccesos) ){
            $objTemplate->draw_button("",$lang[MODULO]["usuarios_nuevo"],"fntDrawOpenModal(0);","plus","sm");
        }
        $objTemplate->draw_button("",$lang[MODULO]["usuarios_refrescar"],"document.location = '{$strAction}';","refresh","sm");
        $objTemplate->draw_button("",$lang[MODULO]["usuarios_cerrar"],"document.location = 'index.php';","remove","sm");
    }

    public function draw_listado_perfiles(){
        global $lang,$strAction,$objTemplate,$objForm,$arrAccesos;

        $arrInfoBreadCrumb = core_getInfoBreadCrumb("usuarios_perfiles_acceso",ACCESO);
        $objTemplate->draw_breadcrumb($arrInfoBreadCrumb);

        $objForm = new form("frmAdmPerfil","frmAdmPerfil","POST",$strAction,"return false;");
        $strTitleNuevo = $lang[MODULO]["usuarios_new_perfil"];
        ?>
        <div class="box box-default">
            <div class="box-header">
                <div class="row">
                    <div class="col-lg-offset-8 col-lg-4">
                        <?php
                        $objTemplate->draw_search("txtBusqueda",$lang["core"]["config_modulo_busqueda"],"fntSubmitBusqueda();");
                        $objForm->add_input_hidden("hdnBusqueda",0,true);
                        $objForm->add_input_hidden("hdnBusquedaNombre","",true);
                        ?>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div id="divContentPerfiles" class="row">&nbsp;</div>
            </div>
            <div class="box-footer">
                &nbsp;
            </div>
        </div>
        <?php
        $objTemplate->draw_modal_open("dialogContenido","lg","sm","static",false);
        $objTemplate->draw_modal_draw_header("","",true);
        $objTemplate->draw_modal_open_content("");
            ?>
            <div id="divContentResultAjax">
                &nbsp;
            </div>
            <?php
        $objTemplate->draw_modal_close_content(true);
            ?>
            <div id="divButtonsNew" style="display: none;" >
                <?php
                if( array_key_exists("crear",$arrAccesos)){
                    $objTemplate->draw_button("btnGuardarNew",$lang[MODULO]["usuarios_guardar"],"fntGuardar();","lock","sm");
                }
                $objTemplate->draw_button("btnCancelarNew",$lang[MODULO]["usuarios_cancelar"],"$('#dialogContenido').modal('hide');","remove", "sm");
                ?>
            </div>
            <div id="divButtonsExist" style="display: none;">
                <?php
                if( array_key_exists("modificar",$arrAccesos) ){
                    $objTemplate->draw_button("btnGuardar",$lang[MODULO]["usuarios_guardar"],"fntGuardar();","lock","sm");/*Guardar solo en usuarios*/
                }
                if( array_key_exists("eliminar",$arrAccesos) ){
                    $objTemplate->draw_button("btnEliminar",$lang[MODULO]["usuarios_perfil_eliminar"],"fntEliminar();","trash", "sm");
                }
                $objTemplate->draw_button("btnCancelar",$lang[MODULO]["usuarios_cancelar"],"$('#dialogContenido').modal('hide');","remove", "sm");
                ?>
            </div>
            <?php
        $objTemplate->draw_modal_close_footer(true);


        $objTemplate->draw_modal_open("dialogEliminar","md","md","static",false);
        $objTemplate->draw_modal_draw_header($lang["usuarios"]["usuarios_perfil_eliminar"],"",true,"");
        $objTemplate->draw_modal_open_content();
            ?>
            <div class="text-center ">
                <?php echo $lang[MODULO]["usuarios_msj_ad"]; ?>
            </div>
            <?php
        $objTemplate->draw_modal_close_content(true);
            ?>
            <div>
                <?php
                if( array_key_exists("eliminar",$arrAccesos) ){
                    $objTemplate->draw_button("btnGuardarDel",$lang[MODULO]["usuarios_aceptar"],"fntEliminarPerfil();","ok","sm");
                }
                $objTemplate->draw_button("btnCancelarDel",$lang[MODULO]["usuarios_cancelar"],"fntDeshacerEliminar();","remove", "sm");
                ?>
            </div>
            <?php
        $objTemplate->draw_modal_close_footer(true);
        ?>
        <script>
            var objPerfilContent;
            var objGuardar;
            var objEliminar;
            var boolRol = true;
            var boolPerfiles = false;

            function fntDestroyAlertas(){
                $("#divNombrePerfil").css("margin-bottom","10px");
                $("#txtNombrePerfil").css("border","1px solid #ccc");
                $("#divReqNombrePerfil").hide();

                $("input[id^='txtNombreCuenta_']").each(function(){
                    arrSplit = $(this).attr("id").split("_");
                    $("#txtNombreCuenta_"+arrSplit[1]).css("border", "1px solid #d3d3d3");
                    $("#divReqNombreCuenta_"+arrSplit[1]).hide();
                    $("#divRepNombreCuenta_"+arrSplit[1]).hide();
                });

            }

            function fntDrawOpenModal( intId, strTextoHeader ){
                PNotify.removeAll();
                if( !boolRol && boolPerfiles ){
                    if( objPerfilContent ) objPerfilContent.abort();
                    objPerfilContent = $.ajax({
                        url:"<?php print $strAction;?>",
                        async: false,
                        data:{
                            getIdPerfilesAcceso : true,
                            intPerfil : intId,
                        },
                        type:'get',
                        dataType:'html',
                        beforeSend:function(){
                        showImgCoreLoading();
                        },
                        success:function(data){
                            hideImgCoreLoading();
                            $("#dialogContenido").modal();
                            $("#divContentResultAjax").html("");
                            $("#divContentResultAjax").html(data);
                            if( parseInt(intId) > 0 ){
                                $("#dialogContenido .modal-dialog .modal-content .modal-header .modal-title").html(strTextoHeader);
                                $("#divButtonsNew").hide();
                                $("#divButtonsExist").show();
                            }
                            else{
                                $("#dialogContenido .modal-dialog .modal-content .modal-header .modal-title").html("<?php echo $strTitleNuevo; ?>");
                                $("#divButtonsNew").show();
                                $("#divButtonsExist").hide();
                            }
                        }
                    });
                    <?php
                    if( isset($arrAccesos["modificar"]) || isset($arrAccesos["crear"]) ){
                        ?>
                        $("#theadCuentas").removeClass("hidden");
                        $("#tbodyCuentas").removeClass("hidden");
                        $("#tblCuentas").addClass("table");
                        $("#tblCuentas").addClass("table-striped");
                        <?php
                    }
                    if( isset($arrAccesos["modificar"]) ){
                        ?>
                        $("span[id*='imgEliminarUsuario_']").removeClass("ocultar");
                        <?php
                    }

                    if( isset($arrAccesos["modificar"]) ){
                        ?>
                        $("#spanAddCuenta").removeClass("hidden");
                        $("#trInicial").removeClass("hidden");
                        $("#trRowVacio").addClass("hidden");
                        $("#trRowEspacio").addClass("hidden");
                        <?php
                    }
                    if( isset($arrAccesos["modificar"]) || isset($arrAccesos["eliminar"]) ){
                        ?>
                        $("i[id^='icoCancelarUsuario_']").show();
                        <?php
                    }
                    ?>
                }
            }

            function fntGuardar(){
                boolError = false;
                var arrValores = new Array();
                $("input[name='txtNombrePerfil']").val($.trim($("input[name='txtNombrePerfil']").val()));
                if( $("input[name='txtNombrePerfil']").val().length == 0 ){

                    $("#tabsContainer a[href=\"#tabContainerPermisos\"]").tab("show");
                    $("#divNombrePerfil").css("margin-bottom","0");
                    $("#txtNombrePerfil").css("border","1px solid #d27272");
                    $("#divReqNombrePerfil").show();
                    boolError = true;
                }

                boolErrorReq = false;
                $("input[name*='hdnIdCuenta_']").each(function(){
                    var arrSplit = $(this).attr("name").split("_");
                    $("input[name='hdnIdCuenta_"+arrSplit[1]+"']").val( $.trim($("input[name='hdnIdCuenta_"+arrSplit[1]+"']").val()));
                    $("input[name='txtNombreCuenta_"+arrSplit[1]+"']").val( $.trim($("input[name='txtNombreCuenta_"+arrSplit[1]+"']").val()));
                    $("input[name='hdnTxtNombreCuenta_"+arrSplit[1]+"']").val( $.trim($("input[name='hdnTxtNombreCuenta_"+arrSplit[1]+"']").val()));
                    var boolExisteEliminar = ($("input[name='hdnCuentaEliminar_"+arrSplit[1]+"']").length == 0) ? false : true;
                    var strEliminar = boolExisteEliminar ? $("input[name='hdnCuentaEliminar_"+arrSplit[1]+"']").val() : "N";
                    if ( (strEliminar == "N") ){

                        if( (($("input[name='txtNombreCuenta_"+arrSplit[1]+"']").val().length== 0) || ($("input[name='hdnIdCuenta_"+arrSplit[1]+"']").val() == "")) || ($("input[name='hdnTxtNombreCuenta_"+arrSplit[1]+"']").val() != $("input[name='txtNombreCuenta_"+arrSplit[1]+"']").val())   ){
                            $("#tabsContainer a[href=\"#containerUsuarios\"]").tab("show");
                            $("#divNombreCuenta_"+arrSplit[1]).css("margin-bottom","0");
                            $("#txtNombreCuenta_"+arrSplit[1]).css("border","1px solid #d27272");
                            $("#divReqNombreCuenta_"+arrSplit[1]).show();
                            boolError = true;
                            boolErrorReq = true;
                        }
                        if( !arrValores[$(this).val()] ){
                            arrValores[$(this).val()] = new Array();
                            arrValores[$(this).val()]["contador"] = 0;
                        }
                        arrValores[$(this).val()]["contador"]++;
                    }
                });
                if(!boolErrorReq){
                    $("input[name*='hdnIdCuenta_']").each(function(){
                        var arrSplit = $(this).attr("name").split("_");
                        $("input[name='hdnIdCuenta_"+arrSplit[1]+"']").val( $.trim($("input[name='hdnIdCuenta_"+arrSplit[1]+"']").val()));
                        $("input[name='txtNombreCuenta_"+arrSplit[1]+"']").val( $.trim($("input[name='txtNombreCuenta_"+arrSplit[1]+"']").val()));
                        $("input[name='hdnTxtNombreCuenta_"+arrSplit[1]+"']").val( $.trim($("input[name='hdnTxtNombreCuenta_"+arrSplit[1]+"']").val()));
                        var boolExisteEliminar = ($("input[name='hdnCuentaEliminar_"+arrSplit[1]+"']").length == 0) ? false : true;
                        var strEliminar = boolExisteEliminar ? $("input[name='hdnCuentaEliminar_"+arrSplit[1]+"']").val() : "N";

                        if ( strEliminar == "N" && arrValores[$(this).val()] && (arrValores[$(this).val()]["contador"] > 1) && ($("input[name='hdnTxtNombreCuenta_"+arrSplit[1]+"']").val() == $("input[name='txtNombreCuenta_"+arrSplit[1]+"']").val()) && $("input[name='hdnTxtNombreCuenta_"+arrSplit[1]+"']").val() != "" ){

                            $("#tabsContainer a[href=\"#containerUsuarios\"]").tab("show");
                            $("#divNombreCuenta_"+arrSplit[1]).css("margin-bottom","0");
                            $("#txtNombreCuenta_"+arrSplit[1]).css("border","1px solid #d27272");
                            $("#divRepNombreCuenta_"+arrSplit[1]).show();
                            boolError = true;
                        }
                    });
                }
                if( !boolError ){
                    if( objGuardar ) objGuardar.abort();
                    objGuardar = $.ajax({
                        url:"<?php print $strAction; ?>",
                        async: false,
                        data: $("#frmAdmPerfil").serialize(),
                        type:'post',
                        dataType:'html',
                        beforeSend:function(){
                            showImgCoreLoading();
                        },
                        success:function(){
                            $("#dialogContenido").modal("hide");
                            if($("#hdnPerfilId").val() == 0){
                                draw_Alert("success", "", "<?php print $lang[MODULO]["usuarios_perfil_registro_msj_alert_ins"]; ?>", true);
                            }
                            else{
                                draw_Alert("info", "", "<?php print $lang[MODULO]["usuarios_perfil_registro_msj_alert_upd"]; ?>", true);
                            }
                            fntSubmitBusqueda();
                            hideImgCoreLoading();
                        }
                    });


                }
            }

            function fntEliminar(){
                $("#dialogContenido").modal("hide");
                $("#dialogEliminar").modal();

            }

            function fntDeshacerEliminar(){
                $("#dialogEliminar").modal("hide");
                $("#dialogContenido").modal();
            }

            function fntEliminarPerfil(){
                if( objEliminar ) objEliminar.abort();
                objEliminar = $.ajax({
                    url:"<?php print $strAction; ?>",
                    async: false,
                    data: "getEliminarPerfil=true&intPerfil="+$("#hdnPerfilId").val(),
                    type:'post',
                    dataType:'html',
                    beforeSend:function(){
                        showImgCoreLoading();
                    },
                    success:function(){
                        hideImgCoreLoading();
                        $("#dialogEliminar").modal("hide");
                        document.location= "<?php echo "{$strAction}?strAlert=del"; ?>";
                    }
                });
            }

            function fntSubmitBusqueda(){
                $.ajax({
                    url:"<?php print $strAction; ?>",
                    async: false,
                    data:{
                        getResultBusqueda : true,
                        intId : $("input[name='hdnBusqueda']").val(),
                        strTexto : $("input[name='txtBusqueda']").val(),
                    },
                    type:'post',
                    dataType:'html',
                    beforeSend:function(){
                        showImgCoreLoading();
                    },
                    success:function(data){
                        $("#divContentPerfiles").html("");
                        $("#divContentPerfiles").html(data);
                        hideImgCoreLoading();
                    }
                });
            }

            function fntClickTab( boolClickRol ){
                if( boolClickRol ){
                    boolRol = true;
                    boolPerfiles = false;
                }
                else{
                    boolRol = false;
                    boolPerfiles = true;
                }
            }
            var intBusquedaNumLetras = 0;
            var intBusquedaNumLetrasActual = 0;
            $(function(){
                $("#txtBusqueda").keyup(function(event){
                    intBusquedaNumLetrasActual = $(this).val().length;

                    if( intBusquedaNumLetras > 0 && intBusquedaNumLetras != intBusquedaNumLetrasActual ) {
                        $("#hdnBusqueda").val("0");
                    }
                    intBusquedaNumLetras = $(this).val().length;
                }).autocomplete({
                    source: '<?php print $strAction; ?>'+"?sendAutoComplete=true",
                    minLength: 1,
                    response: function( event, ui ) {
                        $("#hdnBusqueda").val("0");
                    },
                    select: function( event, ui ) {
                        $("#hdnBusqueda").val( ui.item.id );
                        $("#txtBusqueda").val(ui.item.value);
                        intBusquedaNumLetras = $(this).val().length;
                        intBusquedaNumLetrasActual = $(this).val().length;
                    },
                    change:function( event, ui ) {
                        if( ($("#hdnBusqueda").val()*1) == 0 ) {
                            $("#txtBusqueda").val("");
                        }
                    }
                });
                $("#txtBusqueda").keypress( function(event){
                    if( event.keyCode == 13 ){
                        fntSubmitBusqueda();
                    }
                });

                fntClickTab(false);
                fntSubmitBusqueda();

            });
        </script>
        <?php
    }

    public function draw_ajax_listado_perfiles($intId = 0, $strTexto = "" ){
        global $objTemplate, $lang, $strAction,$objForm, $arrAccesos;

        $objForm = new form();
        $boolCorrelativoedit = 1;
        $arrPerfilesAcc = array();
        $strUsuariosAdmin = $this->objModel->getPersonasAdmin();
        $arrPerfilesAcc = $this->objModel->getPerfiles($intId, $strTexto, $strUsuariosAdmin);
        ?>
        <div class="col-lg-12 table-responsive">
            <?php
            if( count($arrPerfilesAcc) > 0 ){
                ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 28px"><?php print $lang[MODULO]["usuarios_nombre"]; ?></th>
                            <th style="width: 28px"><?php print $lang["core"]["config_modulo_descripcion"]; ?></th>
                            <th style="width: 24px" class="text-center"><?php print $lang["core"]["config_modulo_activo"]; ?></th>
                            <th style="width: 20px">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    while( $arrTMP = each($arrPerfilesAcc) ){
                        ?>
                        <tr>
                            <td class="break-text">
                               <a style="cursor: pointer;" onclick="fntDrawOpenModal( '<?php print $arrTMP["key"] ?>', '<?php print $arrTMP['value']['nombre'] ?>' )">
                                <?php echo $arrTMP["value"]["nombre"]; ?>
                               </a>
                            </td>
                            <td class="break-text">
                                <?php echo $arrTMP["value"]["descripcion"]; ?>
                            </td>
                            <td class="text-center">
                                <?php echo ($arrTMP["value"]["activo"]) == 'Y' ? $lang["core"]["yes"] : $lang["core"]["no"]; ?>
                            </td>
                            <td>
                                <?php
                                if( array_key_exists("modificar",$arrAccesos) || array_key_exists("crear",$arrAccesos) || array_key_exists("eliminar",$arrAccesos) || array_key_exists("consultar",$arrAccesos) ){
                                    //$objTemplate->draw_icon("imgModificar_{$boolCorrelativoedit}","fntDrawOpenModal({$arrTMP["key"]},'".(core_print($arrTMP['value']['nombre'],"",true))."');","edit","lg2",true);
                                }
                                ?>
                                <script>
                                    $("#imgModificar_<?php echo $boolCorrelativoedit; ?>").attr("rel","tooltip");
                                    $("#imgModificar_<?php echo $boolCorrelativoedit; ?>").attr("data-content","");
                                    $("#imgModificar_<?php echo $boolCorrelativoedit; ?>").attr("title","<?php print $lang[MODULO]["usuarios_editar"] ?>");
                                </script>
                            </td>
                        </tr>
                        <?php
                            ++$boolCorrelativoedit;
                        }
                    ?>
                    </tbody>
                </table>
                <script>
                    $(function(){
                       $("[rel='tooltip']").tooltip({});
                    });
                </script>
                <?php
            }
            else{
                ?>
                <div class="col-lg-12 text-center no-info">
                    <?php echo $lang[MODULO]["usuarios_no_hay_info"]; ?>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }

    public function draw_content($intPerfil){
        global $objTemplate, $lang, $strAction;
        $intPerfil = intval($intPerfil);
        $strNombrePerfil = $this->objModel->getPerfilNombre($intPerfil);
        $objForm = new form("frmAdmPerfil","frmAdmPerfil","POST",$strAction,"","form-horizontal");
        $objForm->form_setExtraTag("role","form");
        $objForm->form_openForm();
            $objForm->add_input_hidden("hdnFormPerfiles","1",true);
            $this->drawResultBusqueda($intPerfil);
        $objForm->form_closeForm();
    }

    public function drawResultBusqueda($intPerfil){
        global $objTemplate, $lang, $strAction, $arrAccesos;
        $intPerfil = intval($intPerfil);
        $objForm = new form();

        $arrInfoPerfil = array();
        $arrInfoPerfil = $this->objModel->getInfoPerfil($intPerfil);

        if( count($arrInfoPerfil) > 0 || $intPerfil == 0 ){

            $arrModulos = getModulosArreglo();

            $boolEdicion = ( (array_key_exists("modificar",$arrAccesos) && count($arrInfoPerfil) > 0) || (array_key_exists("crear",$arrAccesos) && count($arrInfoPerfil) == 0 ) ) ? false : true;
            ?>
            <script type="text/javascript">
                var intCorrelativo = 1;
                var arrControlCargas = new Array();

                objForms = new forms;

                function fntAutoComplete(intCorrelativo2){
                    $("#txtNombreCuenta_"+intCorrelativo2).autocomplete({
                        source: '<?php print $strAction; ?>'+"?sendAutoCuenta=true",
                        minLength: 1,
                        select: function( event, ui ) {

                            $("#hdnIdCuenta_"+intCorrelativo2).val(ui.item.id);
                            $("#txtNombreCuenta_"+intCorrelativo2).val(ui.item.value)
                            $("#hdnTxtNombreCuenta_"+intCorrelativo2).val(ui.item.value);

                        },
                        response: function( event, ui ) {
                            $("#hdnIdCuenta_"+intCorrelativo2).val("");
                        },
                        close: function( event, ui ){
                            if($("#hdnIdCuenta_"+intCorrelativo2).val() == "" ){
                                getDocumentLayer("txtNombreCuenta_"+intCorrelativo2).value = "";
                            }
                        },
                        change: function( event, ui ) {
                            if($("#hdnIdCuenta_"+intCorrelativo2).val() == "" ){
                                getDocumentLayer("txtNombreCuenta_"+intCorrelativo2).value = "";
                            }
                             getDocumentLayer("txtNombreCuenta_"+intCorrelativo2).value = getDocumentLayer("hdnTxtNombreCuenta_"+intCorrelativo2).value;
                        }
                    });
                }

                function fntLoadInfoPorModulos(intModulo){
                    boolEditar = false;

                    if( $("input[name*='hdnEditar']").val() == "Y" ){
                        boolEditar = true;
                    }
                    if( !arrControlCargas[intModulo] ){

                        arrControlCargas[intModulo] = intModulo;

                        $.ajax({
                            url:"<?php print $strAction; ?>",
                            async: false,
                            data:{
                                getResultModuloPerfil : true,
                                modulo : intModulo,
                                perfil : "<?php print $intPerfil; ?>",
                                editar : boolEditar
                            },
                            type:'post',
                            dataType:'html',
                            success:function(data){

                                $("#divContentModulo_"+intModulo).html("");
                                $("#divContentModulo_"+intModulo).addClass("table-responsive");
                                $("#divContentModulo_"+intModulo).html(data);
                            }
                        });
                    }
                }

                function fntAddCuenta(){
                    strHtml =   '<tr>'+
                                    '<td class="col-sm-9">'+
                                    objForms.add_input_hidden("hdnIdOriginal_"+intCorrelativo,"0","")+
                                    objForms.add_input_hidden("hdnIdCuenta_"+intCorrelativo,"","")+
                                    objForms.add_input_hidden("hdnTxtNombreCuenta_"+intCorrelativo,"","")+
                                    objForms.add_input_text("txtNombreCuenta_"+intCorrelativo,"","inputSizeComplete","","<?php echo $lang[MODULO]["usuarios_nombre"]; ?>","<?php echo $lang[MODULO]["usuarios_nombre"]; ?>")+
                                    '<div id="divReqNombreCuenta_'+intCorrelativo+'" class="form-group ocultar">'+
                                        '<div class="col-lg-12" style="color: #d27272; margin-top: 0;">'+
                                            '<?php echo "*".$lang["core"]["campo_requerido_title"]; ?>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div id="divRepNombreCuenta_'+intCorrelativo+'" class="form-group ocultar">'+
                                        '<div class="col-lg-12" style="color: #d27272; margin-top: 0;">'+
                                            '<?php echo "*".$lang[MODULO]["usuarios_usuario_en_uso"]; ?>'+
                                        '</div>'+
                                    '</div>'+
                                    '</td>'+
                                    '<td class="col-sm-3">&nbsp;&nbsp;'+
                                        objForms.draw_icon('spanTrash_'+intCorrelativo,"fntDeleteNewCuenta(this);","trash","md",true)+
                                    '</td>'+
                                '</tr>';
                                $("#tbodyCuentas").append(strHtml);
                                fntAutoComplete(intCorrelativo);
                                $("[rel=tooltip]").tooltip({});
                                intCorrelativo++;
                }

                function fntEliminarUsuario( intContador ){

                    $("input[name='hdnCuentaEliminar_"+ intContador +"']").val("Y");

                    <?php
                    if( isset($arrAccesos["modificar"]) ){
                        ?>
                        $("input[name='txtNombreCuenta_"+intContador+"']").hide();
                        $("#divReadModetxtNombreCuenta_"+intContador).show();

                        <?php
                    }
                    ?>
                    $("#imgEliminarUsuario_"+intContador).hide();
                    var objTr = $("#imgEliminarUsuario_"+intContador).parent().parent();
                    $(objTr).children().addClass("rowdelete");
                }

                function fntRevertirUsuario( intContador ){

                    $("input[name='hdnIdCuenta_"+intContador+"']").val($("input[name='hdnIdOriginal_"+intContador+"']").val());
                    $("input[name='txtNombreCuenta_"+intContador+"']").val($("input[name='hdnNombreCuenta_"+intContador+"']").val());
                    <?php
                    if( isset($arrAccesos["modificar"]) ){
                        ?>
                        $("#divReadModetxtNombreCuenta_"+intContador).hide();
                        $("input[name='txtNombreCuenta_"+intContador+"']").show();
                        <?php
                    }
                    ?>
                    $("input[name='hdnCuentaEliminar_"+ intContador +"']").val("N");
                    $("#imgEliminarUsuario_"+ intContador).show();
                    var objTr = $("#imgEliminarUsuario_"+ intContador ).parent().parent();
                    $(objTr).children().removeClass("rowdelete");
                }

                function fntDeleteNewCuenta( obj ){
                    //var arrSplit = obj.id.split("_");
                    objTable = getDocumentLayer("tblCuentas");
                    objTr = obj.parentNode.parentNode;
                    objTable.deleteRow(objTr.rowIndex);
                    //$("#divReqCuentaNombre_"+arrSplit[1]).remove();
                }

            </script>
            <?php $objForm->add_input_hidden("hdnEditar","N",true);

            $objTemplate->draw_tab_open("tabsContainer");
                $objTemplate->draw_tab_addElement($lang["core"]["config_modulo_permisos"],"tabContainerPermisos","",true);
                $objTemplate->draw_tab_addElement($lang["core"]["config_modulo_usuarios"],"containerUsuarios");
            $objTemplate->draw_tab_close();

            $objTemplate->draw_tab_open_contenido("tabContainerPermisos",true,true);

            if( count($arrModulos) ){
                ?>
                <div class="col-sm-12 table-responsive">
                        <br>
                        <div class="col-xs-12">
                            <table class="col-sm-12 table">
                                <tbody>
                                    <tr style="border: 2px solid #fff;">
                                        <td>
                                            <div id="divNombrePerfil" class="form-group">
                                                <label for="txtNombrePerfil" class="col-lg-4 control-label">
                                                    <?php print $objTemplate->drawTitleLeft($lang["core"]["config_nombre_perfil"], true); ?>
                                                </label>
                                                <div class="col-lg-5">
                                                    <?php
                                                    $strValor = isset($arrInfoPerfil["info"]["nombre"]) ? $arrInfoPerfil["info"]["nombre"] : "";
                                                    $objForm->add_input_text("txtNombrePerfil",core_print($strValor,"",true),"",false,$lang[MODULO]["usuarios_nombre"],$lang[MODULO]["usuarios_nombre"],$boolEdicion,$boolEdicion);
                                                    $objForm->add_input_extraTag("txtNombrePerfil","maxlength","75");
                                                    $objForm->draw_input_text("txtNombrePerfil");
                                                    $objForm->add_input_hidden("hdnPerfilId",$intPerfil,true);
                                                    ?>
                                                    <div id="divReqNombrePerfil" class="form-group ocultar">
                                                        <div class="col-lg-12" style="color: #d27272; margin-top: 0;">
                                                            <?php
                                                            echo "*".$lang["core"]["campo_requerido_title"];
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr style="border: 2px solid #fff;">
                                        <td>
                                            <div class="form-group">
                                                <label for="txtDescripcion" class="col-lg-4 control-label">
                                                    <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo_descripcion"], false); ?>
                                                </label>
                                                <div class="col-lg-5">
                                                    <?php
                                                    $strValorDescripcion = isset($arrInfoPerfil["info"]["descripcion"]) ? $arrInfoPerfil["info"]["descripcion"] : "";
                                                    $objForm->add_textarea("txtDescripcion",core_print($strValorDescripcion,"",true),"",false,$lang["core"]["config_modulo_descripcion"],$lang["core"]["config_modulo_descripcion"],$boolEdicion,$boolEdicion);
                                                    $objForm->add_input_extraTag("txtDescripcion","maxlength","300");
                                                    $objForm->draw_textarea("txtDescripcion");
                                                    ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr style="border: 2px solid #fff;">
                                        <td>
                                            <div class="form-group">
                                                <label for="checkActivo" class="col-lg-4 control-label">
                                                    <?php print $objTemplate->drawTitleLeft($lang["core"]["config_modulo_activo"], false); ?>
                                                </label>
                                                <div class="col-lg-7 form-control-static">
                                                    <?php
                                                    $boolChecked = isset($arrInfoPerfil["info"]["activo"]) && $arrInfoPerfil["info"]["activo"] == "N" ? false : true;
                                                    $objForm->add_input_checkbox("checkActivo",$boolChecked,"",true, $lang["core"]["config_modulo_activo"],$boolEdicion,$boolEdicion);
                                                    ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="col-sm-12 borderBottomGray">
                                &nbsp;
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <?php
                            $objTemplate->draw_accordion_open();
                                while( $arrTMP = each($arrModulos) ){
                                    $objTemplate->draw_accordion_open_element("contentModulo_{$arrTMP["key"]}",$arrTMP["value"]["nombre"],"","divContentModulo_{$arrTMP["key"]}","fntLoadInfoPorModulos('{$arrTMP["key"]}');",false,"chevron-down",true);
                                }
                            $objTemplate->draw_accordion_close();
                            ?>
                        </div>
                    <br>
                    <br>
                    </div>
                    <?php
            }
            $objTemplate->draw_tab_close_contenido();
            $objTemplate->draw_tab_open_contenido("containerUsuarios");
            $intCorrelativo = 1;
            ?>
            <div class="col-sm-12 table-responsive">
                <br/><br/>
                <table class="col-sm-12 table-striped <?php echo ( (isset($arrInfoPerfil["cuentas"]) && count($arrInfoPerfil["cuentas"]) > 0) || !$boolEdicion ) ? "table" : "" ; ?>" id="tblCuentas">
                    <thead id="theadCuentas" class="<?php echo ($boolEdicion && count($arrInfoPerfil["cuentas"]) == 0 ) ? "hidden" : ""; ?>">
                        <tr>
                            <th colspan="2"><?php print $lang[MODULO]["usuarios_nombre"]; ?></th>
                        </tr>
                    </thead>
                    <tbody id="tbodyCuentas">
                    <?php
                        if( isset($arrInfoPerfil["cuentas"]) ){
                            while( $arrTMP = each($arrInfoPerfil["cuentas"]) ) {
                                ?>
                                <tr>
                                    <td class="col-sm-9">
                                        <?php
                                        $objForm->add_input_hidden("hdnIdOriginal_{$intCorrelativo}",$arrTMP["value"]["usuariowebid"],true);
                                        $objForm->add_input_hidden("hdnIdCuenta_{$intCorrelativo}",$arrTMP["value"]["usuariowebid"],true);
                                        $objForm->add_input_hidden("hdnCuentaEliminar_{$intCorrelativo}","N",true);
                                        $objForm->add_input_hidden("hdnNombreCuenta_{$intCorrelativo}",core_print($arrTMP["value"]["nombre"],"",true),true);
                                        $objForm->add_input_hidden("hdnTxtNombreCuenta_{$intCorrelativo}",core_print($arrTMP["value"]["nombre"],"",true),true);
                                        $objForm->add_input_text("txtNombreCuenta_{$intCorrelativo}",core_print($arrTMP["value"]["nombre"],"",true),"inputSizeComplete",true,$lang[MODULO]["usuarios_nombre"],$lang[MODULO]["usuarios_nombre"],true,$boolEdicion);
                                        ?>
                                        <div id="divReqNombreCuenta_<?php echo $intCorrelativo; ?>" class="form-group ocultar">
                                            <div class="col-md-12" style="color: #d27272; margin-top: 0;">
                                                <?php
                                                echo "*".$lang["core"]["campo_requerido_title"];
                                                ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="col-sm-3 avoid-text-decoration">
                                        &nbsp;
                                        <?php
                                        if( isset($arrAccesos["eliminar"]) || isset($arrAccesos["modificar"]) ){
                                         $objTemplate->draw_icon("imgEliminarUsuario_{$intCorrelativo}","fntEliminarUsuario({$intCorrelativo});","trash","md ocultar",true);
                                        }
                                        $objTemplate->draw_icon_fa("icoCancelarUsuario_{$intCorrelativo}","undo","fntRevertirUsuario({$intCorrelativo});",true,"md","ocultar");
                                        ?>
                                        &nbsp;
                                        <script>
                                            $("#imgEliminarUsuario_<?php echo $intCorrelativo; ?>").attr("rel","tooltip");
                                            $("#imgEliminarUsuario_<?php echo $intCorrelativo; ?>").attr("data-content","");
                                            $("#imgEliminarUsuario_<?php echo $intCorrelativo; ?>").attr("title","<?php print $lang[MODULO]["usuarios_eliminar"] ?>");

                                            $("#icoCancelarUsuario_<?php echo $intCorrelativo; ?>").attr("rel","tooltip");
                                            $("#icoCancelarUsuario_<?php echo $intCorrelativo; ?>").attr("data-content","");
                                            $("#icoCancelarUsuario_<?php echo $intCorrelativo; ?>").attr("title","<?php print $lang[MODULO]["usuarios_cancelar"] ?>");

                                            <?php
                                            if( ($intCorrelativo % 2) == 0 ){
                                                ?>
                                                $(function(){
                                                    obj = $("#imgEliminar_<?php echo $intCorrelativo; ?>").parent().parent();
                                                    $(obj).children().css("background","white");
                                                });
                                                <?php
                                            }
                                            ?>
                                            $("input[name='txtNombreCuenta_<?php echo $intCorrelativo; ?>']").autocomplete({
                                                source: '<?php print $strAction; ?>'+"?sendAutoCuenta=true",
                                                minLength: 1,
                                                select: function( event, ui ) {
                                                    $("input[name='hdnIdCuenta_<?php echo $intCorrelativo; ?>']").val(ui.item.id);
                                                    $("input[name='txtNombreCuenta_<?php echo $intCorrelativo; ?>']").val(ui.item.value);
                                                    $("input[name='hdnTxtNombreCuenta_<?php echo $intCorrelativo; ?>']").val(ui.item.value);
                                                },
                                                response: function( event, ui ) {
                                                    $("input[name='hdnIdCuenta_<?php echo $intCorrelativo; ?>']").val("");
                                                },
                                                close: function( event, ui ){
                                                    if($("input[name='hdnIdCuenta_<?php echo $intCorrelativo; ?>']").val() == "" ){
                                                        $("input[name='txtNombreCuenta_<?php echo $intCorrelativo; ?>']").val("");
                                                        $("input[name='hdnTxtNombreCuenta_<?php echo $intCorrelativo; ?>']").val("");
                                                    }
                                                },
                                                change: function( event, ui ) {
                                                    if($("input[name='hdnIdCuenta_<?php echo $intCorrelativo; ?>']").val() == "" ){
                                                        $("input[name='txtNombreCuenta_<?php echo $intCorrelativo; ?>']").val("");
                                                        $("input[name='hdnTxtNombreCuenta_<?php echo $intCorrelativo; ?>']").val("");
                                                    }
                                                    $("input[name='txtNombreCuenta_<?php echo $intCorrelativo; ?>']").val($("input[name='hdnTxtNombreCuenta_<?php echo $intCorrelativo; ?>']").val());
                                                }
                                            });
                                        </script>
                                    </td>
                                </tr>
                                <?php
                                $intCorrelativo++;
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                                <?php
                                if(isset($arrAccesos["modificar"])){
                                    $strHidden = $boolEdicion ? "hidden" : "" ;
                                    $objTemplate->draw_icon("spanAddCuenta","fntAddCuenta();","plus {$strHidden}","md",true);
                                }
                                ?>
                                <script>
                                    intCorrelativo = <?php echo $intCorrelativo; ?>;
                                </script>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <?php
                                if( isset($arrInfoPerfil["cuentas"]) && count($arrInfoPerfil["cuentas"]) == 0 ){
                                    ?>
                                    <div id="trRowEspacio">
                                        <br><br>
                                    </div>
                                    <?php
                                }
                               ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <script>
                    $(function(){
                        $("[rel='tooltip']").tooltip({});
                    });
                </script>
            </div>
            <?php
            $objTemplate->draw_tab_close_contenido(true);
            ?>
            <script>

                <?php
                if( isset($arrInfoPerfil["cuentas"]) && count($arrInfoPerfil["cuentas"]) > 0 ){
                    ?>
                    $(function(){
                        $("#btnEliminar").hide();
                    });
                    <?php
                }
                else {
                    ?>
                    $(function(){
                        $("#btnEliminar").show();
                    });
                    <?php
                }
                if( ( isset($arrInfoPerfil["cuentas"]) && count($arrInfoPerfil["cuentas"]) == 0 && !isset($arrAccesos["crear"])) ||
                    ( isset($arrInfoPerfil["cuentas"]) && count($arrInfoPerfil["cuentas"]) > 0 && !isset($arrAccesos["modificar"]) && !isset($arrAccesos["crear"]) ||
                      isset($arrInfoPerfil["cuentas"]) && count($arrInfoPerfil["cuentas"]) == 0 && isset($arrAccesos["eliminar"]) ) ){
                    ?>
                    $(function(){
                        $("#btnGuardar").hide();
                    });
                    <?php
                }
                if(isset($arrAccesos["eliminar"])  && isset($arrInfoPerfil["cuentas"]) && count($arrInfoPerfil["cuentas"]) != 0){
                    ?>
                    $(function(){
                        $("#btnGuardar").show();
                    });
                    <?php
                }
                if(isset($arrInfoPerfil["cuentas"]) && $arrInfoPerfil["cuentas"] == 0){
                    ?>
                    $(function(){
                         $("#btnGuardar").hide();
                    });
                    <?php
                }
                if(isset($arrInfoPerfil) && count($arrInfoPerfil) == 0 ||
                isset($arrInfoPerfil) && count($arrInfoPerfil) == 0 && isset($arrInfoPerfil["cuentas"]) && $arrInfoPerfil["cuentas"] == 0 ||
                isset($arrInfoPerfil) && $arrInfoPerfil > 0 && isset($arrAccesos["modificar"]) && isset($arrAccesos["eliminar"]) && isset($arrAccesos["crear"]) ||
                isset($arrInfoPerfil) && $arrInfoPerfil > 0 && isset($arrAccesos["modificar"]) ){
                    ?>
                    $(function(){
                        $("#btnGuardar").show();
                    });
                    <?php
                }
                ?>

                function fntCheckAcessoHijo( obj ){

                    arrSplit = $(obj).attr("name").split("_");
                    boolTodosCheados = true;

                    $("input[name*='checkAcceso_"+arrSplit[1]+"_"+arrSplit[2]+"_"+arrSplit[3]+"_']").each(function(){
                        if( $(this).prop("checked") == false ){
                            boolTodosCheados = false;
                        }
                    });

                    if( boolTodosCheados ){
                        $("input[name*='chkAccesoFila_"+arrSplit[1]+"_"+arrSplit[2]+"_"+arrSplit[3]+"']").prop("checked",true);
                    }else{
                        $("input[name*='chkAccesoFila_"+arrSplit[1]+"_"+arrSplit[2]+"_"+arrSplit[3]+"']").prop("checked",false);
                    }

                    boolTodosCheados = true;
                        $("input[name*='checkAcceso_"+arrSplit[1]+"_"+arrSplit[2]+"_']").each(function(){
                            if( $(this).prop("checked") == false ){
                                boolTodosCheados = false;
                            }
                        });
                        if( boolTodosCheados ){
                            $("input[name='checkAccesoPadre_"+arrSplit[1]+"_"+arrSplit[2]+"']").prop("checked",true);
                        }else{
                            $("input[name='checkAccesoPadre_"+arrSplit[1]+"_"+arrSplit[2]+"']").prop("checked",false);
                        }
                }

                function fntCheckAccesoFila( obj ){
                    arrSplit = $(obj).attr("name").split("_");

                    if( $(obj).prop("checked") == true ){
                        $("input[name*='checkAcceso_"+arrSplit[1]+"_"+arrSplit[2]+"_"+arrSplit[3]+"_']").each(function(){
                            $(this).prop("checked",true);
                        });
                    }else{
                        $("input[name*='checkAcceso_"+arrSplit[1]+"_"+arrSplit[2]+"_"+arrSplit[3]+"_']").each(function(){
                            $(this).prop("checked",false);
                        });
                    }
                    boolTodosChequeados = true;
                    $("input[name*='chkAccesoFila_"+arrSplit[1]+"_"+arrSplit[2]+"_']").each(function(){
                        if( $(this).prop("checked") == false ){
                            boolTodosChequeados = false;
                        }
                    });

                    if( boolTodosChequeados ){
                        $("input[name*='checkAccesoPadre_"+arrSplit[1]+"_"+arrSplit[2]+"']").prop("checked",true);
                    }else{
                        $("input[name*='checkAccesoPadre_"+arrSplit[1]+"_"+arrSplit[2]+"']").prop("checked",false);
                    }
                }

                function fntCheckAcceso( obj ){
                    arrSplit = $(obj).attr("name").split("_");
                    boolTodosCheados = true;
                    if( $(obj).prop("checked") == true ){
                        $("input[name*='checkAcceso_"+arrSplit[1]+"_"+arrSplit[2]+"_']").each(function(){
                            $(this).prop("checked",true);
                        });
                        $("input[name*='chkAccesoFila_"+arrSplit[1]+"_"+arrSplit[2]+"_']").each(function(){
                            $(this).prop("checked",true);
                        });
                    }else{
                        $("input[name*='chkAccesoFila_"+arrSplit[1]+"_"+arrSplit[2]+"_']").each(function(){
                            $(this).prop("checked",false);
                        });
                        $("input[name*='checkAcceso_"+arrSplit[1]+"_"+arrSplit[2]+"_']").each(function(){
                            $(this).prop("checked",false);
                        });
                    }
                }
            </script>
            <?php
        }
        else {
            ?>
            <script>
                document.location = "<?php echo $strAction; ?>";
            </script>
            <?php
        }
    }

    public function drawAccesosModulo($intModulo, $intPerfil,$boolEditar){
        global $objTemplate, $lang, $strAction, $arrAccesos;
        $intModulo = intval($intModulo);
        $intPerfil = intval($intPerfil);

        $arrTipoAcceso = $this->objModel->getTiposAcceso();
        $arrAccesosModulo = $this->objModel->getAccesosModulo($intModulo);
        $arrAccesosModuloExtra = $this->objModel->getAccesosExtra($intModulo);
        $arrAccesosPerfil = $this->objModel->getPerfilAccesos($intPerfil);

        $intColsTipoAcceso = count($arrTipoAcceso);

        $objForm = new form();

        if($intPerfil > 0){
            $boolEdicion = ($intPerfil && !$boolEditar) ? true : false;
            $boolShowIcono = $boolEdicion;
            $boolEdicion = ($boolEdicion && isset($arrAccesos["modificar"])) ? false : true;
        }
        else{
            $boolEdicion = (!$intPerfil && !$boolEditar) ? false : true;
            $boolShowIcono = $boolEdicion;
            $boolEdicion = ($boolEdicion && isset($arrAccesos["modificar"])) ? true : false;
        }

        ?>
        <table width="100%" cellpadding="2" cellspacing="0" class="table table-hover table-responsive">
            <thead>
                <tr>
                    <th width="3%">
                        <?php $objForm->add_input_hidden("hdnModuleLoaded_{$intModulo}",$intModulo,true); ?>
                        &nbsp;
                    </th>
                    <th width="33%">&nbsp;</th>
                    <?php
                    reset($arrTipoAcceso);
                    while( $rTMP = each($arrTipoAcceso) ){
                        ?>
                        <th class="text-center" width="<?php print (60/$intColsTipoAcceso); ?>%"><?php print $rTMP["value"]["nombre"]; ?></th>
                        <?php
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
            <?php
            while( $arrTMP = each($arrAccesosModulo) ) {
                $intNumero = $arrTMP["key"];
                if( isset($arrTMP["value"]["info"]) ) {
                    ?>
                    <tr>
                        <td>
                            <?php
                            $objForm->add_input_checkbox("checkAccesoPadre_{$intModulo}_{$intNumero}",false,"",false,"",false,$boolEdicion);
                            $objForm->add_input_extraTag("checkAccesoPadre_{$intModulo}_{$intNumero}","onchange","fntCheckAcceso(this);");
                            $objForm->draw_input_checkbox("checkAccesoPadre_{$intModulo}_{$intNumero}");
                            ?>
                        </td>
                        <td colspan="<?php print $intColsTipoAcceso+2; ?>" class="rowgroup editTitlesLeft">
                            <?php
                            echo $arrTMP["value"]["info"]["nombre"];
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                while( $arrTMP2 = each($arrTMP["value"]["detalle"]) ){
                    ?>
                    <tr>
                        <td style="border-bottom: 1px #7F7F7F solid;">
                            <?php
                            $objForm->add_input_checkbox("chkAccesoFila_{$intModulo}_{$intNumero}_{$arrTMP2["value"]["acceso"]}",false,"",false,"",false,$boolEdicion);
                            $objForm->add_input_extraTag("chkAccesoFila_{$intModulo}_{$intNumero}_{$arrTMP2["value"]["acceso"]}","onchange","fntCheckAccesoFila(this);");
                            $objForm->draw_input_checkbox("chkAccesoFila_{$intModulo}_{$intNumero}_{$arrTMP2["value"]["acceso"]}");
                            ?>
                        </td>
                        <td style="border-bottom: 1px #7F7F7F solid;">
                            <?php
                            print $arrTMP2["value"]["nombre"];
                            ?>
                        </td>

                        <?php
                        reset($arrTipoAcceso);
                        while( $arrTMPTipoAcceso = each($arrTipoAcceso) ){
                            ?>

                            <td class="text-center" style="border-bottom: 1px #7F7F7F solid;">
                                <?php
                                if( isset($arrTMP2["value"]["tipo_acceso"][$arrTMPTipoAcceso["key"]]) ){

                                    $boolChecked = isset($arrAccesosPerfil[$arrTMP2["value"]["acceso"]][$arrTMPTipoAcceso["key"]]);
                                    $strIcon = $boolChecked ? "ok" : "remove";

                                    $objForm->add_input_checkbox("checkAcceso_{$intModulo}_{$intNumero}_{$arrTMP2["value"]["acceso"]}_{$arrTMPTipoAcceso["key"]}",$boolChecked,"",false,"",false,$boolEdicion);
                                    $objForm->add_input_extraTag("checkAcceso_{$intModulo}_{$intNumero}_{$arrTMP2["value"]["acceso"]}_{$arrTMPTipoAcceso["key"]}","onchange","fntCheckAcessoHijo(this);");
                                    $objForm->draw_input_checkbox("checkAcceso_{$intModulo}_{$intNumero}_{$arrTMP2["value"]["acceso"]}_{$arrTMPTipoAcceso["key"]}");

                                    if( $boolShowIcono && $boolChecked && $boolEdicion )
                                        $objTemplate->draw_icon("imgAccesoTipo_{$intModulo}_{$intNumero}_{$arrTMP2["value"]["acceso"]}_{$arrTMPTipoAcceso["key"]}","",$strIcon);
                                }
                                else
                                    print "&nbsp;";
                                ?>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>

                   <?php
                   if(isset($arrAccesosModuloExtra[$arrTMP2["value"]["acceso"]])){
                       while($arrTMP3 = each($arrAccesosModuloExtra[$arrTMP2["value"]["acceso"]])){

                           $intUltimo = count($arrTMP3["value"]);
                           while($arrTMP4 = each($arrTMP3["value"])){
                            $intUltimo--;
                            $strSubrayado = "";
                            if($intUltimo == 0){
                                $strSubrayado =  "border-bottom: 1px #7F7F7F solid;";
                            }
                            ?>
                            <tr>
                                <td style="<?php print $strSubrayado?>" >&nbsp;</td>
                                <td style="color: gray; <?php print $strSubrayado?> padding-left: 50px;"  align="left">
                                    <?php
                                    $objForm->add_input_checkbox("chkAccesoFila_{$intModulo}_{$intNumero}_{$arrTMP4["value"]["acceso"]}",false,"",false,"",false,true);
                                    $objForm->add_input_extraTag("chkAccesoFila_{$intModulo}_{$intNumero}_{$arrTMP4["value"]["acceso"]}","onchange","fntCheckAccesoFila(this);");
                                    $objForm->draw_input_checkbox("chkAccesoFila_{$intModulo}_{$intNumero}_{$arrTMP4["value"]["acceso"]}");
                                    print "&nbsp;&nbsp;-&nbsp;&nbsp;".$arrTMP4["value"]["nombre"];
                                    ?>
                                </td>
                                <?php
                                reset($arrTipoAcceso);
                                while( $arrTMPTipoAcceso = each($arrTipoAcceso) ){
                                    ?>
                                    <td class="text-center" style="<?php print $strSubrayado?>" >
                                        <?php
                                        if( isset($arrTMP4["value"]["tipo_acceso"][$arrTMPTipoAcceso["key"]]) ){

                                            $boolChecked = isset($arrAccesosPerfil[$arrTMP4["value"]["acceso"]][$arrTMPTipoAcceso["key"]]);
                                            $strIcon = $boolChecked ? "ok" : "remove";

                                            $objForm->add_input_checkbox("checkAcceso_{$intModulo}_{$intNumero}_{$arrTMP4["value"]["acceso"]}_{$arrTMPTipoAcceso["key"]}",$boolChecked,"",false,"",false,$boolEdicion);
                                            $objForm->add_input_extraTag("checkAcceso_{$intModulo}_{$intNumero}_{$arrTMP4["value"]["acceso"]}_{$arrTMPTipoAcceso["key"]}","onchange","fntCheckAcessoHijo(this);");
                                            $objForm->draw_input_checkbox("checkAcceso_{$intModulo}_{$intNumero}_{$arrTMP4["value"]["acceso"]}_{$arrTMPTipoAcceso["key"]}");

                                            if( $boolShowIcono && $boolChecked && $boolEdicion )
                                                $objTemplate->draw_icon("imgAccesoTipo_{$intModulo}_{$intNumero}_{$arrTMP4["value"]["acceso"]}_{$arrTMPTipoAcceso["key"]}","",$strIcon);
                                        }
                                        else
                                            print "&nbsp;";
                                        ?>
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>

                            <?php
                           }
                       }
                   }
                }
            }
            ?>
            </tbody>
        </table>

        <script>

            function fntCheckInicial<?php echo $intModulo; ?>(){
                $("input[name*='checkAccesoPadre_<?php echo $intModulo; ?>_']").each(function(){
                    objAccesoPadre = this;
                        arrSplit = $(objAccesoPadre).attr("name").split("_");
                        boolHijosChequeados = true;
                        intCantidad = 0;
                        $("input[name*='checkAcceso_"+arrSplit[1]+"_"+arrSplit[2]+"_']").each(function(){
                            objAcceso = this;
                            if( $(objAcceso).prop("checked") == false ){
                                boolHijosChequeados = false;
                            }
                            if( this ){
                                intCantidad++;
                            }
                        });
                        if( boolHijosChequeados && intCantidad > 0 ){
                            $(objAccesoPadre).prop("checked",true);
                        }else{
                            $(objAccesoPadre).prop("checked",false);
                        }
                });

                $("input[name*='chkAccesoFila_<?php echo $intModulo; ?>_']").each(function(){
                    arrSplit = $(this).attr("name").split("_");
                    boolTodosCheados = true;
                    intCantidad = 0;
                    $("input[name*='checkAcceso_"+arrSplit[1]+"_"+arrSplit[2]+"_"+arrSplit[3]+"_']").each(function(){
                        if( $(this).prop("checked") == false ){
                            boolTodosCheados = false;
                        }
                        intCantidad++;
                    });
                    if( boolTodosCheados && intCantidad ){
                        $("input[name*='chkAccesoFila_"+arrSplit[1]+"_"+arrSplit[2]+"_"+arrSplit[3]+"']").prop("checked",true);
                    }else{
                        $("input[name*='chkAccesoFila_"+arrSplit[1]+"_"+arrSplit[2]+"_"+arrSplit[3]+"']").prop("checked",false);
                    }
                });
            }

            fntCheckInicial<?php echo $intModulo; ?>()
        </script>
        <?php
    }
}

?>
