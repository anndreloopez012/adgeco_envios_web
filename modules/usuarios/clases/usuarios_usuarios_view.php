<?php
require_once("modules/usuarios/clases/usuarios_usuarios_model.php");

class usuarios_usuarios_view{

    private $objModel;

    public function __construct(){
        $this->objModel = new usuarios_usuarios_model();
    }

    public function drawButtons(){
        global $lang, $strAction, $objTemplate,$arrAccesos;
        if( isset($arrAccesos["crear"]) ){
            $objTemplate->draw_button("btnNuevo",$lang["core"]["config_modulo_new"],"document.location.href='{$strAction}?persona=new'","plus","sm","");
        }
        $objTemplate->draw_button("",$lang[MODULO]["usuarios_refrescar"],"document.location = '{$strAction}';","refresh","sm");
        $objTemplate->draw_button("",$lang[MODULO]["usuarios_cerrar"],"document.location = 'index.php';","remove","sm");
    }

    public function drawContent(){
        global $lang, $strAction, $objTemplate;

        $arrBreadCrumb = core_getInfoBreadCrumb(ACCESO,true);
        $objTemplate->draw_breadcrumb($arrBreadCrumb);

        $objForm = new form("frmListadoPersonas","frmListadoPersonas","POST",$strAction,"return false;","form-horizontal");
        $objForm->form_setExtraTag("role","form");
        $objForm->form_openForm();
        ?>
        <div class="box box-default">
            <div class="box-header">
                <div class="row">
                    <div class="col-lg-offset-8 col-lg-4">
                        <?php
                        $objTemplate->draw_search("txtBusqueda",$lang[MODULO]["usuarios_busqueda"],"fntSubmitBusqueda();");
                        ?>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div id="divContentUsuarios">&nbsp;</div>
            </div>
            <div class="box-footer">
                &nbsp;
            </div>
        </div>
        <script>
            function fntSubmitBusqueda(){
                showImgCoreLoading();
                $.ajax({
                    url:"<?php print $strAction; ?>",
                    async: false,
                    data:{
                        getResultBusqueda : true,
                        params : $("#txtBusqueda").val()
                    },
                    type:'post',
                    dataType:'html',
                    beforeSend: function() {

                    },
                    success:function(data) {
                        $("#divContentUsuarios").html("");
                        $("#divContentUsuarios").html(data);
                        hideImgCoreLoading();
                    }
                });
            }

            function fntCreateAutocomplete(){
                $("input[name='txtBusqueda']").autocomplete({
                    source: '<?php print $strAction; ?>'+"?sendAutoComplete=true",
                    minLength: 1,
                    select: function( event, ui ) {
                        setTimeout(function(){ fntSubmitBusqueda() },50);
                    }
                });
            }

            $(function(){
                fntCreateAutocomplete();
                $("input[name='txtBusqueda']").keypress( function(event){
                    if( event.which == 13 ){
                        fntSubmitBusqueda();
                    }
                });
                fntSubmitBusqueda();
            });
        </script>
        <?php
        $objForm->form_closeForm();
    }

    public function drawListadoPersonas( $strBusqueda = "" ){
        global $objTemplate, $lang, $strAction;

        $arrPersonas = $this->objModel->getPersonas($strBusqueda);

        $arrTiposCuenta = $this->objModel->getTiposCuenta();
        if(count($arrPersonas)){
            ?>
            <div class="table-responsive">
                <table class="table-striped table">
                    <thead>
                        <tr>
                            <th style="width: 40%"><?php echo $lang[MODULO]["usuarios_nombre"]; ?></th>
                            <th style="width: 40%"><?php echo $lang[MODULO]["usuarios_tipo_cuenta"]; ?></th>
                            <th style="width: 20%" class="text-center"><?php echo $lang[MODULO]["usuarios_activo"]; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while( $arrTMP = each($arrPersonas) ){
                            ?>
                            <tr>
                                <td class="break-text">
                                   <a href="<?php echo $strAction."?persona=".md5($arrTMP["key"]); ?>" class="link"><?php echo $arrTMP["value"]["nombre_usual"]; ?></a>
                                </td>
                                <td class="break-text">
                                    <?php echo array_key_exists($arrTMP["value"]["tipo"],$arrTiposCuenta) ? $arrTiposCuenta[$arrTMP["value"]["tipo"]]["texto"] : ""; ?>
                                 </td>
                                <td class="text-center break-text">
                                    <?php echo ($arrTMP["value"]["bloqueado"]) == 'N' ? $lang["core"]["yes"] : $lang["core"]["no"]; ?>
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
        else{
            ?>
            <div class="no-info text-center ">
                <?php echo $lang[MODULO]["usuarios_no_hay_info"] ?>
            </div>
            <?php
        }
    }

    public function drawButtonsPersona( $strPersona ){
        global $lang, $strAction, $objTemplate, $arrAccesos;

        $intPersona = $this->objModel->getPersonaIdFromMd5( $strPersona );

        //$strTieneClientes = $this->objModel->getClientesUsuario($intPersona);

        if( $intPersona > 0 ){
            $boolEditar = $this->objModel->getBoolEditar($intPersona);
            if( isset($arrAccesos["modificar"]) || ( $_SESSION["hml"]["tipo_usuario"] == 'admin' ) ){
                $objTemplate->draw_button("btnEditar",$lang[MODULO]["usuarios_editar"],"fntEditar();","pencil","sm");
                $objTemplate->draw_button("btnGuardar",$lang[MODULO]["usuarios_guardar"],"fntGuardar();","lock","sm ocultar");
            }
            if( (isset($arrAccesos["eliminar"]) || $_SESSION["hml"]["tipo_usuario"] == 'admin') ){
                $objTemplate->draw_button("btnEliminar",$lang[MODULO]["usuarios_eliminar"],"fntEliminar();","trash","sm");
            }
        }
        else{
            if( isset($arrAccesos["crear"])){
                $objTemplate->draw_button("btnGuardar",$lang[MODULO]["usuarios_guardar"],"fntGuardar();","lock","sm");
            }
        }

        $strClassBotonC = $intPersona > 0 ? "ocultar" : "";
        $strClassBotonR = $intPersona > 0 ? "" : "ocultar";
        $objTemplate->draw_button("btnRegresar","Regresar","document.location = '{$strAction}';","arrow-left","sm {$strClassBotonR}");
        $objTemplate->draw_button("btnCancel",$lang[MODULO]["usuarios_cancelar"],"fntCancelar();","remove","sm {$strClassBotonC}");

    }

    public function drawContentPersona( $strPersona ){
        global $lang, $strAction, $objTemplate, $arrAccesos, $cfg;
        $boolEdicion = false;

        $arrInfoPersona = array();
        $arrInfoPersona = $this->objModel->getInfoPersona($strPersona);

        $intPersona = isset($arrInfoPersona["persona"]) ? intval($arrInfoPersona["persona"]) : 0;
        $strNombre = isset($arrInfoPersona["nombre_usual"]) ? $arrInfoPersona["nombre_usual"] : "";
        $strTipoCuenta = isset($arrInfoPersona["tipo"]) ? $arrInfoPersona["tipo"] : "";
        $intCorrelativo = 1;

        $arrTablasDescartadas = array();
        $arrTablasDescartadas["persona_perfil"] = array();
        $arrTablasDescartadas["usuario"] = array();
        $boolPuedeEliminar = core_boolPuedeEliminarDatos("persona",$intPersona,$arrTablasDescartadas);

        $arrTiposCuenta = $this->objModel->getTiposCuenta($strTipoCuenta);
        $arrPerfiles = $this->objModel->getPerfiles();

        $arrInfoExtra[0]["nombre"] = empty($strNombre) ? $lang[MODULO]["usuarios_nuevo"] : $strNombre;
        $arrInfoExtra[0]["link"] = "{$strAction}?persona={$strPersona}";
        $arrInfoExtra[0]["activo"] = true;
        $arrBreadCrumb = core_getInfoBreadCrumb(ACCESO,true,$arrInfoExtra);
        $objTemplate->draw_breadcrumb($arrBreadCrumb);

        $objTemplate->draw_modal_open("dialogEliminar","md","md","static",false);
        $objTemplate->draw_modal_draw_header($lang[MODULO]["usuarios_eliminar_usuario"],"",true);
        $objTemplate->draw_modal_open_content();
            echo "<div class=\"row\">";
                echo "<div class=\"text-center col-lg-12\">";
                    echo $lang[MODULO]["usuarios_esta_seguro_eliminar_usuario"];
                echo "</div>";
            echo "</div>";
        $objTemplate->draw_modal_close_content(true);
            $objTemplate->draw_button("",$lang[MODULO]["usuarios_aceptar"],"document.location = '{$strAction}?strEliminar=Y&persona={$strPersona}';","ok","sd", "");
            $objTemplate->draw_button("",$lang[MODULO]["usuarios_cancelar"],"$('#dialogEliminar').modal('hide');","remove", "sd", "");
        $objTemplate->draw_modal_close_footer(true);

        $strClass = $boolEdicion ? "" : "no-visible";
        $boolModoEdicion = ($intPersona > 0) ? true : false;

        $objForm = new form("frmPersona","frmPersona","POST",$strAction."?persona={$strPersona}","return false;","form-horizontal");
        $objForm->form_setExtraTag("enctype","multipart/form-data");
        $objForm->form_setExtraTag("role","form");
        $objForm->form_openForm();

        $objForm->add_input_hidden("hdnPersona",$intPersona,true);
        $objForm->add_input_hidden("hdnAccion",1,true);
        $objForm->add_input_hidden("hdnUpdate","N",true);
        $objForm->add_input_hidden("hdnPuedeEliminar",$boolPuedeEliminar,true);

        if( ( isset($arrAccesos["crear"]) && count($arrInfoPersona) == 0) || (count($arrInfoPersona) > 0) ){
            $objForm->add_input_hidden("hdnTipoCuentaNormal",$strTipoCuenta,true);
            $objTemplate->draw_panel_open("divContent");
                $objTemplate->draw_panel_open_header();
                    echo empty($strNombre) ? $lang[MODULO]["usuarios_nuevo"] : $strNombre;
                $objTemplate->draw_panel_close_header();
                $objTemplate->draw_panel_open_body();
                ?>
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-6">
                        <?php
                        $objForm->add_input_hidden("hdnCuentaNueva","normal",true);
                        if( $_SESSION["hml"]["tipo_usuario"] == "admin" ){
                            ?>
                            <div id="divTipoCuenta" class="form-group">
                                <label for="sltTipoCuenta" class="col-md-6 control-label">
                                    <?php $objTemplate->drawTitleLeft($lang[MODULO]["usuarios_tipo_cuenta"],true); ?>
                                </label>
                                <div class="col-md-6">
                                    <?php
                                    $objForm->add_select("sltTipoCuenta",$arrTiposCuenta,"",false,$lang[MODULO]["usuarios_tipo_cuenta"],$boolModoEdicion,$boolModoEdicion);
                                    $objForm->add_input_extraTag("sltTipoCuenta","onchange","fntChangeTipoCuenta()");
                                    $objForm->draw_select("sltTipoCuenta",$arrTiposCuenta,"");
                                    ?>
                                    <div id="divReqTipo" class="form-group ocultar">
                                        <div class="col-md-12" style="color: #d27272; margin-top: 0;">
                                            <?php
                                            echo "*".$lang["core"]["campo_requerido_title"];
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        else{
                            $objForm->add_input_hidden("hdnTipoCuenta",md5($strTipoCuenta),true);
                        }
                        ?>
                        <div id="divNombre" class="form-group">
                            <label for="txtNombreCompleto" class="col-md-6 control-label">
                                <?php $objTemplate->drawTitleLeft($lang[MODULO]["usuarios_nombre_completo"],true); ?>
                            </label>
                            <div class="col-md-6">
                                <?php
                                $strTexto = isset($arrInfoPersona["nombre_usual"]) ? $arrInfoPersona["nombre_usual"] : "";
                                $objForm->add_input_text("txtNombreCompleto",$strTexto,"",false,$lang[MODULO]["usuarios_nombre_completo"],$lang[MODULO]["usuarios_nombre_completo"],$boolModoEdicion,$boolModoEdicion);
                                $objForm->add_input_extraTag("txtNombreCompleto","maxlength","255");
                                $objForm->draw_input_text("txtNombreCompleto");
                                ?>
                                <div id="divReqNombre" class="form-group ocultar">
                                    <div class="col-md-12" style="color: #d27272; margin-top: 0;">
                                        <?php
                                        echo "*".$lang["core"]["campo_requerido_title"];
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="divCorreo" class="form-group">
                            <label for="txtCorreoElectronico" class="col-md-6 control-label">
                                <?php $objTemplate->drawTitleLeft($lang[MODULO]["usuarios_correo_electronico"],true); ?>
                            </label>
                            <div class="col-md-6">
                                <?php
                                $strTexto = isset($arrInfoPersona["email"]) ? $arrInfoPersona["email"] : "";
                                $objForm->add_input_text("txtCorreoElectronico",$strTexto,"",false,$lang[MODULO]["usuarios_correo_electronico"],$lang[MODULO]["usuarios_correo_electronico"],$boolModoEdicion,$boolModoEdicion);
                                $objForm->add_input_extraTag("txtCorreoElectronico","maxlength","255");
                                $objForm->draw_input_text("txtCorreoElectronico");
                                ?>
                                <div id="divReqCorreo" class="form-group ocultar">
                                    <div class="col-md-12" style="color: #d27272; margin-top: 0;">
                                        <?php
                                        echo "*".$lang["core"]["campo_requerido_title"];
                                        ?>
                                    </div>
                                </div>
                                <div id="divInvCorreo" class="form-group ocultar">
                                    <div class="col-md-12" style="color: #d27272; margin-top: 0;">
                                        <?php
                                        echo "*".$lang[MODULO]["usuarios_correo_electronico_invalido"];
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="divUsuario" class="form-group">
                            <label for="txtUsuario" class="col-md-6 control-label">
                                <?php $objTemplate->drawTitleLeft("Usuario",true); ?>
                            </label>
                            <div class="col-md-6 <?php echo $intPersona == 0 ? "" : "form-control-static"; ?>">
                                <?php
                                $strTexto = isset($arrInfoPersona["usuario"]) ? $arrInfoPersona["usuario"] : "";
                                if( $intPersona == 0 ){
                                    $objForm->add_input_hidden("hdnUsuarioValido","N",true);
                                    $objForm->add_input_text("txtUsuario",$strTexto,"",false,$lang[MODULO]["usuarios_usuario"],$lang[MODULO]["usuarios_usuario"],$boolModoEdicion,$boolModoEdicion);
                                    $objForm->add_input_extraTag("txtUsuario","maxlength","75");
                                    $objForm->add_input_extraTag("txtUsuario","onchange","fntValidarUsuario();");
                                    $objForm->draw_input_text("txtUsuario");
                                }
                                else{
                                    $objForm->add_input_hidden("txtUsuario", $strTexto, true );
                                    echo $strTexto;
                                }
                                ?>
                                <div id="divReqUsuario" class="form-group ocultar">
                                    <div class="col-md-12" style="color: #d27272; margin-top: 0;">
                                        <?php
                                        echo "*".$lang["core"]["campo_requerido_title"];
                                        ?>
                                    </div>
                                </div>
                                <div id="divUsuarioRep" class="form-group ocultar">
                                    <div class="col-md-12" style="color: #d27272; margin-top: 0;">
                                        <?php
                                        echo "*".$lang[MODULO]["usuarios_usuario_en_uso"];
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        if($intPersona > 0){
                            ?>
                            <div id="divPass" class="form-group">
                                <label for="txtPassword" class="col-md-6 control-label">
                                    <?php $objTemplate->drawTitleLeft($lang[MODULO]["usuarios_contrasenia"]); ?>
                                </label>
                                <div class="col-md-6">
                                    <?php
                                    $objForm->add_input_hidden("hdnChangePassword","N",true);
                                    if( $intPersona ){
                                        ?>
                                        <div id="lnkPassword" class="ocultar form-control-static" onclick="fntChangePassword();">
                                            <?php
                                            $objTemplate->template_draw_link("",$lang[MODULO]["usuarios_cambiar_contra"],"","");
                                            ?>
                                        </div>
                                        <div id="divPassword" class="form-control-static">
                                            <?php
                                            if( isset($arrInfoPersona["usuario"]) && !empty($arrInfoPersona["usuario"]) ){
                                                echo "********";
                                            }
                                            ?>
                                        </div>
                                        <?php
                                        $objForm->add_input_password("txtPassword"," ocultar",false,$lang[MODULO]["usuarios_contrasenia"],$lang[MODULO]["usuarios_contrasenia"],false,$boolModoEdicion);
                                        $objForm->add_input_extraTag("txtPassword","maxlength","40");
                                        $objForm->add_input_extraTag("txtPassword","autocomplete","off");
                                        $objForm->draw_input_password("txtPassword");
                                    }
                                    else{
                                        $objForm->add_input_password("txtPassword","",false,$lang[MODULO]["usuarios_contrasenia"],$lang[MODULO]["usuarios_contrasenia"],false,false);
                                        $objForm->add_input_extraTag("txtPassword","maxlength","40");
                                        $objForm->add_input_extraTag("txtPassword","autocomplete","off");
                                        $objForm->draw_input_password("txtPassword");
                                    }
                                    ?>
                                    <div id="divReqPass" class="form-group ocultar">
                                        <div class="col-md-12" style="color: #d27272; margin-top: 0;">
                                            <?php
                                            echo "*".$lang["core"]["campo_requerido_title"];
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="divConfirmarPass" class="form-group" style="<?php echo ($boolModoEdicion) ? "display:none;" : ""; ?>;">
                                <label for="txtConfirmaPassword" class="col-md-6 control-label">
                                    <?php $objTemplate->drawTitleLeft($lang[MODULO]["usuarios_confirme_contra"],true); ?>
                                </label>
                                <div class="col-md-6">
                                    <?php
                                    $objForm->add_input_password("txtConfirmaPassword","",false,$lang["usuarios"]["usuarios_confirme_contra"],$lang["usuarios"]["usuarios_confirme_contra"],false,false);
                                    $objForm->add_input_extraTag("txtConfirmaPassword","maxlength","40");
                                    $objForm->add_input_extraTag("txtConfirmaPassword","autocomplete","off");
                                    $objForm->draw_input_password("txtConfirmaPassword");
                                    ?>
                                    <div id="divReqPass2" class="form-group ocultar">
                                        <div class="col-md-12" style="color: #d27272; margin-top: 0;">
                                            <?php
                                            echo "*".$lang["core"]["campo_requerido_title"];
                                            ?>
                                        </div>
                                    </div>
                                    <div id="divNCPass2" class="form-group ocultar">
                                        <div class="col-md-12" style="color: #d27272; margin-top: 0;">
                                            <?php
                                            echo "*".$lang[MODULO]["usuarios_contras_no_coinc"];
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        else {
                            ?>
                            <div class="form-group">
                                <label for="chkGenerar" class="col-md-6 control-label">
                                    <?php $objTemplate->drawTitleLeft($lang[MODULO]["usuarios_contrasenia"],true); ?>
                                </label>
                                <div class="col-md-6 <?php echo $intPersona == 0 ? "form-control-static" : ""; ?>">
                                    <?php
                                    echo $lang[MODULO]["usuarios_contrasenia_aleatoria"];
                                    if( isset($arrAccesos["modificar"]) ){
                                        ?>
                                        <div class="col-xs-12 ocultar form-control-static" id="GenerarAleatoria">
                                            <?php
                                            $objForm->add_input_checkbox("chkGenerar",true,"",true,$lang[MODULO]["usuarios_generar_contrasenia_aleatoria"],false,false);
                                            echo $lang[MODULO]["usuarios_generar_contrasenia_aleatoria"];
                                            ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <div id="divActivo" class="form-group">
                            <label for="chkActivo" class="col-md-6 control-label">
                                <?php $objTemplate->drawTitleLeft($lang[MODULO]["usuarios_activo"]); ?>
                            </label>
                            <div class="col-md-6 form-control-static">
                                <?php
                                $boolChecked = isset($arrInfoPersona["bloqueado"]) && $arrInfoPersona["bloqueado"] == "Y" ? false : true;
                                $objForm->add_input_checkbox("chkActivo",$boolChecked,"",true,$lang[MODULO]["usuarios_activo"],$boolModoEdicion,$boolModoEdicion);
                                ?>
                            </div>
                        </div>
                        <div id="divFoto" class="form-group">
                            <label for="txtFoto" class="col-md-6 control-label">
                                <?php $objTemplate->drawTitleLeft("Fotografia"); ?>
                            </label>
                            <div class="col-md-6">
                                <?php
                                $strFoto = isset($arrInfoPersona["foto"]) ? $arrInfoPersona["foto"] : "";
                                $objForm->add_file("txtFoto","","",true,false,"",$boolModoEdicion,$boolModoEdicion);
                                $objForm->add_input_hidden("hdnFoto",$strFoto,true);
                                ?>
                                <div id="divReqFoto" class="form-group ocultar">
                                    <div class="col-md-12" style="color: #d27272; margin-top: 0;">
                                        <?php
                                        print "Tipo de archivo invalido, archivos validos (png, jpg y gif).";
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        if( file_exists($strFoto) ) {
                            ?>
                            <div class="form-group">
                                <label class="col-md-6 control-label">
                                    &nbsp;
                                </label>
                                <div class="col-md-6">
                                    <img src="<?php print $strFoto; ?>" alt="" style="width: 90px; height: 90px;">
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
                $objTemplate->draw_accordion_open("divMasterPerfilesAcc");
                $objTemplate->draw_accordion_open_element("accPerfilesAcc","Perfiles de acceso","divTitlePerfilesAcc","divContentPerfilesAcc","",true,"chevron-down");
                    $arrPerfilesPersona = array_key_exists("perfiles",$arrInfoPersona) ? $arrInfoPersona["perfiles"] : array();
                    ?>
                    <div class="form-group">
                        <div class="col-lg-12 table-responsive">
                            <table id="tblPerfiles" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="30%">
                                            <?php echo $lang[MODULO]["usuarios_nombre"]; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </th>
                                        <th width="50%">
                                            <?php echo $lang[MODULO]["usuarios_descripcion"]; ?>
                                        </th>
                                        <th width="20%">
                                            &nbsp;
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while( $rTMP = each($arrPerfilesPersona) ){
                                        ?>
                                        <tr>
                                            <td width="40%">
                                                <?php
                                                $arrFilterPerfiles = $this->objModel->getFilterPerfiles( $arrPerfiles, $rTMP["key"] );
                                                $objForm->add_select("sltPerfil_{$intCorrelativo}",$arrFilterPerfiles,"",false,$lang[MODULO]["usuarios_nombre"],$boolModoEdicion,$boolModoEdicion);
                                                $objForm->add_input_extraTag("sltPerfil_{$intCorrelativo}","rel","combobox");
                                                $objForm->draw_select("sltPerfil_{$intCorrelativo}");
                                                $objForm->add_input_hidden("hdnPerfilOriginal_{$intCorrelativo}",$rTMP["key"],true);
                                                $objForm->add_input_hidden("hdnPerfilDelete_{$intCorrelativo}","N",true);
                                                $objForm->add_input_hidden("hdnPerfilUpdate_{$intCorrelativo}","N",true);
                                                $objForm->add_input_hidden("hdnPerfilDescripcion_{$intCorrelativo}",$rTMP["value"]["descripcion"],true);
                                                ?>
                                                <span class="ocultar" id="spnAlertaReq_<?php echo $intCorrelativo; ?>" style="color: #d27272; margin-top: 0;">
                                                    <?php
                                                    echo "*".$lang["core"]["campo_requerido_title"];
                                                    ?>
                                                </span>
                                                <span class="ocultar" id="spnAlertaRep_<?php echo $intCorrelativo; ?>" style="color: #d27272; margin-top: 0;">
                                                    <?php
                                                    echo "*".$lang["core"]["usuarios_perfil_repetido"];
                                                    ?>
                                                </span>
                                            </td>
                                            <td width="40%" id="tdPerfilDescripcion_<?php echo $intCorrelativo; ?>">
                                                <?php core_print($rTMP["value"]["descripcion"]); ?>
                                            </td>
                                            <td  width="20%" class=" avoid-text-decoration">
                                                <?php
                                                if( isset($arrAccesos["eliminar"]) || isset($arrAccesos["modificar"]) ){
                                                    $objTemplate->draw_icon("imgPerfilDelete_{$intCorrelativo}","fntPerfilEliminar({$intCorrelativo});","trash","md ocultar",true);
                                                }
                                                $objTemplate->draw_icon_fa("imgPerfilReturn_{$intCorrelativo}","undo","fntPerfilCancelar({$intCorrelativo});",true,"","ocultar");
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $intCorrelativo++;
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3">
                                            <?php
                                            if( isset($arrAccesos["crear"]) || isset($arrAccesos["modificar"]) ){
                                                $strClass = ( count($arrPerfilesPersona) == 0 && !$boolModoEdicion) ? "" : "ocultar";
                                                $objTemplate->draw_icon("imgAddPerfil","fntNewPerfil();","plus","lg {$strClass}",true);
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="col-lg-12 text-center no-info <?php echo ( count($arrPerfilesPersona) == 0 && $boolModoEdicion ) ? "" : "ocultar"; ?>" id="divtblPerfilesNoInfo">
                                <?php echo $lang[MODULO]["usuarios_no_hay_info"]; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                $objTemplate->draw_accordion_close_element();
                $objTemplate->draw_accordion_close();
            ?>
            <script>
                var boolEditar = false;
                var intCorrelativo = parseInt("<?php echo $intCorrelativo; ?>");
                var arrAjaxDescripcionPerfil = new Array();
                var objAjaxValidarUsuario;

                function fntChangePassword(){
                    $("#lnkPassword").hide();
                    $("input[name='txtPassword']").show();
                    $("#divConfirmarPass").show();
                    $("input[name='hdnChangePassword']").val("Y");
                }

                function fntEditar(){
                    PNotify.removeAll();
                    boolEditar = true;
                    $("#btnGuardar").show();
                    $("#btnEditar").hide();
                    $("#btnRegresar").hide();
                    $("#btnCancel").show();

                    $("#divPassword").hide();
                    $("#lnkPassword").show();

                    <?php
                    if( $_SESSION["hml"]["tipo_usuario"] == "admin" ){
                        ?>
                        fntChangeTipoCuenta();
                        <?php
                    }

                    if( array_key_exists("modificar",$arrAccesos) ){
                        ?>
                        $("[id^='divReadMode']").each(function(){
                            arrSplit = $(this).attr("id").split("divReadMode");
                            if( arrSplit[1] != 'sltTipoPersona' ){
                                $(this).hide();
                            }
                            if( ($("#"+arrSplit[1]).attr("type") == "password") || ($("#"+arrSplit[1]).attr("type") == "file") || ($("#"+arrSplit[1]).attr("rel") == "combobox") || $("#"+arrSplit[1]).attr("multiple") == "multiple" ){
                                if( $("#"+arrSplit[1]).attr("type") == "file" ){
                                    $("#Content"+arrSplit[1]).show();
                                }
                                if( $("#"+arrSplit[1]).attr("rel") == "combobox" ){
                                    fntMakeCombobox(arrSplit[1]);
                                }
                                if( $("#"+arrSplit[1]).attr("multiple") == "multiple" ){
                                    $("#DivContent"+arrSplit[1]).show();
                                }
                            }
                            else{
                                if( arrSplit[1] != 'sltTipoPersona' ){
                                    $("#"+arrSplit[1]).show();
                                }
                            }
                        });

                        $("div[id^='divDisable_']").each(function(){
                            arrSplit = $(this).attr("id").split("divDisable_");
                            $(this).hide();
                            if( arrSplit[1] ){
                                $("#divEnable_"+arrSplit[1]).removeClass("hide");
                            }
                        });

                        $("input[name*='Update'][type='hidden']").val("Y");
                        <?php
                    }
                    ?>

                    <?php
                    if( array_key_exists("crear",$arrAccesos) || array_key_exists("modificar",$arrAccesos) ){
                        ?>
                        $("#divtblPerfilesNoInfo").hide();
                        $("#tblPerfiles").show();
                        $("#imgAddPerfil").show();
                        <?php
                    }
                    ?>

                    <?php
                    if( array_key_exists("eliminar",$arrAccesos) || array_key_exists("modificar",$arrAccesos) ){
                        ?>
                        $("span[id^='imgPerfilDelete_']").show();
                        <?php
                    }
                    ?>

                }

                function destroyAlertas(){
                    $("#divTipoCuenta").css("margin-bottom","10px");
                    $("#sltTipoCuenta").css("border","1px solid #ccc");
                    $("#divReqTipo").hide();

                    $("#divNombre").css("margin-bottom","10px");
                    $("#txtNombreCompleto").css("border","1px solid #ccc");
                    $("#divReqNombre").hide();

                    $("#divCorreo").css("margin-bottom","10px");
                    $("#txtCorreoElectronico").css("border","1px solid #ccc");
                    $("#divReqCorreo").hide();
                    $("#divInvCorreo").hide();

                    $("#divUsuario").css("margin-bottom","10px");
                    $("#txtUsuario").css("border","1px solid #ccc");
                    $("#divReqUsuario").hide();
                    $("#divUsuarioRep").hide();

                    $("#divPass").css("margin-bottom","10px");
                    $("#txtPassword").css("border","1px solid #ccc");
                    $("#divReqPass").hide();

                    $("#divConfirmarPass").css("margin-bottom","10px");
                    $("#txtConfirmaPassword").css("border","1px solid #ccc");
                    $("#divReqConfirmarPass").hide();
                    $("#divNCConfirmarPass").hide();

                    $("#divFoto").css("margin-bottom","10px");
                    $("#txtFoto").css("border","1px solid #ccc");
                    $("#divReqFoto").hide();

                    $("select[id^='sltPerfil_']").each(function(){
                        arrSplit = $(this).attr("id").split("_");
                        $("#sltPerfil_"+arrSplit[1]).siblings().children().css("border", "1px solid #d3d3d3");
                        $("#spnAlertaRep_"+arrSplit[1]).hide();
                        $("#spnAlertaReq_"+arrSplit[1]).hide();
                    });
                }

                function fntValidarUsuario(){
                    if( objAjaxValidarUsuario ) objAjaxValidarUsuario.abort();
                    objAjaxValidarUsuario = $.ajax({
                        url:"<?php print $strAction; ?>",
                        async: false,
                        data:{
                            getValidarUsuario : true,
                            txtUsuario : $("input[name='txtUsuario']").val(),
                            intPersona : '<?php echo $intPersona; ?>'
                        },
                        type:'post',
                        dataType:'html',
                        success:function(data){
                            $("input[name='hdnUsuarioValido']").val("");
                            $("input[name='hdnUsuarioValido']").val(data);

                            strResult = data.toString();

                            $("#divUsuarioRep").hide();
                            $("#divUsuario").css("margin-bottom","10px");
                            $("#txtUsuario").css("border","1px solid #ccc");
                            if( strResult == "N" ){
                                $("#divUsuarioRep").show();
                                $("#txtUsuario").css("border","1px solid #d27272");
                                $("#divUsuario").css("margin-bottom","0");
                            }
                        }
                    });
                }

                function ajaxGetDescripcionPerfil( intId ){
                    if( arrAjaxDescripcionPerfil[intId] ) arrAjaxDescripcionPerfil[intId].abort();
                    arrAjaxDescripcionPerfil[intId] = $.ajax({
                        url:"<?php print $strAction; ?>",
                        async: false,
                        data:{
                            "getDescripcionPerfil" : true,
                            "intPerfil" : $("#sltPerfil_"+intId).val()
                        },
                        type:'post',
                        dataType:'html',
                        success:function(data){
                            $("#tdPerfilDescripcion_"+intId).html("");
                            $("#tdPerfilDescripcion_"+intId).html(data);
                        }
                    });
                }

                function fntMakeCombobox( strSelectId ){
                    $("#"+strSelectId).combobox({
                        select: function( event, ui ) {
                            arrSplit2 = $(this).attr("id").split("_");
                            setTimeout(function(){ ajaxGetDescripcionPerfil( arrSplit2[1] ) } , 5);
                        }
                    });
                }

                function fntCancelar(){
                    if( boolEditar ){
                        document.location = '<?php echo $strAction; ?>?persona=<?php echo $strPersona; ?>';
                    }
                    else{
                        document.location = '<?php echo $strAction; ?>';
                    }
                }

                function fntGuardar(){
                    boolCorrecto = true;
                    destroyAlertas();
                    <?php
                    if( $_SESSION["hml"]["tipo_usuario"] == "admin" ){
                        ?>
                        $("#sltTipoCuenta").val( $.trim( $("#sltTipoCuenta").val() ) );
                        if( $("#sltTipoCuenta").val().length == 0 ){
                            $("#divTipoCuenta").css("margin-bottom","0");
                            $("#sltTipoCuenta").css("border","1px solid #d27272");
                            $("#divReqTipo").show();
                            boolCorrecto = false;
                        }
                        <?php
                    }
                    ?>

                    $("#txtNombreCompleto").val( $.trim( $("#txtNombreCompleto").val() ) );
                    if( $("#txtNombreCompleto").val().length == 0 ){
                        $("#divNombre").css("margin-bottom","0");
                        $("#txtNombreCompleto").css("border","1px solid #d27272");
                        $("#divReqNombre").show();
                        boolCorrecto = false;
                    }

                    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

                    $("#txtCorreoElectronico").val( $.trim( $("#txtCorreoElectronico").val() ) );
                    if( $("#txtCorreoElectronico").val().length == 0 ){
                        $("#divCorreo").css("margin-bottom","0");
                        $("#txtCorreoElectronico").css("border","1px solid #d27272");
                        $("#divReqCorreo").show();
                        boolCorrecto = false;
                    }
                    else if( !(expr.test( $("#txtCorreoElectronico").val() )) ){
                        $("#divCorreo").css("margin-bottom","0");
                        $("#txtCorreoElectronico").css("border","1px solid #d27272");
                        $("#divInvCorreo").show();
                        boolCorrecto = false;
                    }

                    if( getDocumentLayer("txtUsuario") ){
                        $("#txtUsuario").val( $.trim( $("#txtUsuario").val() ) );
                        if( $("#txtUsuario").val().length == 0 ){
                            $("#divUsuario").css("margin-bottom","0");
                            $("#txtUsuario").css("border","1px solid #d27272");
                            $("#divReqUsuario").show();
                            boolCorrecto = false;
                        }
                        else if( $("#hdnUsuarioValido").val() == "N" ){
                            $("#divUsuario").css("margin-bottom","0");
                            $("#txtUsuario").css("border","1px solid #d27272");
                            $("#divUsuarioRep").show();
                            boolCorrecto = false;
                        }
                    }

                    if($("#hdnChangePassword").val() == "Y"){
                        if( getDocumentLayer("txtPassword") ){
                            if( $("#txtPassword").val().length == 0 ){
                                $("#divPass").css("margin-bottom","0");
                                $("#txtPassword").css("border","1px solid #d27272");
                                $("#divReqPass").show();
                                boolCorrecto = false;
                            }
                            else if( $("#txtConfirmaPassword").val().length == 0 ){
                                $("#divPass2").css("margin-bottom","0");
                                $("#txtConfirmaPassword").css("border","1px solid #d27272");
                                $("#divReqPass2").show();
                                boolCorrecto = false;
                            }
                            else if( $("#txtPassword").val() != $("#txtConfirmaPassword").val() ){
                                $("#divPass2").css("margin-bottom","0");
                                $("#txtConfirmaPassword").css("border","1px solid #d27272");
                                $("#divNCPass2").show();
                                boolCorrecto = false;
                            }
                        }
                    }

                    strFoto = $("#txtFoto").val();
                    arrFoto = strFoto.split(".");
                    arrFoto = arrFoto.reverse();
                    if( strFoto.length > 0  && ( arrFoto[0].toLowerCase() != "png" && arrFoto[0].toLowerCase() != "jpg" && arrFoto[0].toLowerCase() != "gif" ) ) {
                        $("#divFoto").css("margin-bottom","0");
                        $("#txtFoto").css("border","1px solid #d27272");
                        $("#divReqFoto").show();
                        boolCorrecto = false;
                    }

                    arrPerfilesRepetidos = new Array();
                    $("select[id^='sltPerfil_']").each(function(){
                        arrSplit = $(this).attr("id").split("_");
                        if( $(this).val() != 0 && $("#hdnPerfilDelete_"+arrSplit[1]).val() == "N" ){
                            if( !arrPerfilesRepetidos[$(this).val()] ){
                                arrPerfilesRepetidos[$(this).val()] = $(this);
                            }
                            else{
                                $("#sltPerfil_"+arrSplit[1]).siblings().children().css("border", "1px solid #d27272");
                                $("#spnAlertaRep_"+arrSplit[1]).show();

                                boolCorrecto = false;
                            }
                        }
                        else if( $(this).val() == 0 ){
                            $("#sltPerfil_"+arrSplit[1]).siblings().children().css("border", "1px solid #d27272");
                            $("#sltPerfil_"+arrSplit[1]).siblings().children().css("background", "#fff");
                            $("#spnAlertaReq_"+arrSplit[1]).show();
                            boolCorrecto = false;
                        }
                    });

                    /*
                    arrClientesRepetidos = new Array();
                    $("input[id^='txtCliente_']").each(function(){
                        arrSplit = $(this).attr("id").split("_");
                        if( $(this).val() != 0 && $("#hdnClienteDelete_"+arrSplit[1]).val() == "N" ){
                            if( !arrClientesRepetidos[$(this).val()] ){
                                arrClientesRepetidos[$(this).val()] = $(this);
                            }
                            else{
                                $("#tdCliente_"+arrSplit[1]).css("margin-bottom","0");
                                $("#txtCliente_"+arrSplit[1]).css("border","1px solid #d27272");
                                $("#spnAlertaCRep_"+arrSplit[1]).show();

                                boolCorrecto = false;
                            }
                        }
                        else if( $(this).val() == 0 ){
                            $("#tdCliente_"+arrSplit[1]).css("margin-bottom","0");
                            $("#txtCliente_"+arrSplit[1]).css("border","1px solid #d27272");
                            $("#spnAlertaCReq_"+arrSplit[1]).show();
                            boolCorrecto = false;
                        }
                    });
                    */

                    if( boolCorrecto ){
                        document.frmPersona.submit();
                    }
                }

                function fntEliminar(){
                    PNotify.removeAll();
                    $('#dialogEliminar').modal();
                }

                function fntValidacionNumeros( obj ){
                    if( $(obj).val() < 0 || $(obj).val() == 0 ){
                        $(obj).val("");
                    }
                }

                <?php
                if( array_key_exists("crear",$arrAccesos) || array_key_exists("modificar",$arrAccesos) ){
                    ?>
                    var objForms = new forms();

                    var arrPerfiles = new Array();
                    arrPerfiles[0] = new Array();
                    arrPerfiles[0]["texto"] = "<?php echo $lang[MODULO]["usuarios_seleccione_opcion"]; ?>";
                    arrPerfiles[0]["selected"] = true;
                    <?php
                    reset($arrPerfiles);
                    while( $rTMP = each($arrPerfiles) ){
                        if( $rTMP["value"]["activo"] == "Y" ){
                            ?>
                            arrPerfiles[<?php echo $rTMP["key"]; ?>] = new Array();
                            arrPerfiles[<?php echo $rTMP["key"]; ?>]["texto"] = "<?php core_print($rTMP["value"]["nombre"]); ?>";
                            arrPerfiles[<?php echo $rTMP["key"]; ?>]["selected"] = false;
                            <?php
                        }
                    }
                    ?>
                    function fntNewPerfil(){

                        strHtml = ' <tr>'+
                                        '<td>'+
                                            objForms.add_select("sltPerfil_"+intCorrelativo,arrPerfiles,"","rel='combobox'","<?php echo $lang[MODULO]["usuarios_nombre"]; ?>")+
                                            objForms.add_input_hidden("hdnPerfilOriginal_"+intCorrelativo,"0",true)+
                                            objForms.add_input_hidden("hdnPerfilDelete_"+intCorrelativo,"N",true)+
                                            objForms.add_input_hidden("hdnPerfilUpdate_"+intCorrelativo,"N",true)+
                                            '<span class="ocultar" id="spnAlertaReq_'+intCorrelativo+'" style="color: #d27272; margin-top: 0;">'+
                                                "<?php echo "*".$lang["core"]["campo_requerido_title"]; ?>"+
                                            '</span>'+
                                            '<span class="ocultar" id="spnAlertaRep_'+intCorrelativo+'" style="color: #d27272; margin-top: 0;">'+
                                                "<?php echo "*".$lang[MODULO]["usuarios_perfil_repetido"]; ?>"+
                                            '</span>'+
                                        '</td>'+
                                        '<td id="tdPerfilDescripcion_'+intCorrelativo+'">'+
                                            '&nbsp;'+
                                        '</td>'+
                                        '<td>'+
                                            objForms.draw_icon("imgPerfilDelete_"+intCorrelativo,"fntDeleteNewRow(this,'tblPerfiles',"+intCorrelativo+");","trash","md",true)+
                                        '</td>'+
                                    '</tr>';

                        $("#tblPerfiles > tbody").append(strHtml);
                        fntMakeCombobox("sltPerfil_"+intCorrelativo);
                        $("[rel='tooltip']").tooltip();
                        intCorrelativo++;
                    }

                    function fntDeleteNewRow( obj, strTableName, intCor ){
                        objTable = getDocumentLayer(strTableName);
                        objTr = obj.parentNode.parentNode;
                        objTable.deleteRow(objTr.rowIndex);
                        $("#divAlertaRep_"+intCor).remove();
                        $("#divAlertaReq_"+intCor).remove();
                    }

                    function fntDeleteNewRowC( obj, strTableName, intCor ){
                        objTable = getDocumentLayer(strTableName);
                        objTr = obj.parentNode.parentNode;
                        objTable.deleteRow(objTr.rowIndex);
                        $("#divAlertaCRep_"+intCor).remove();
                        $("#divAlertaCReq_"+intCor).remove();
                    }
                    <?php
                }

                if( array_key_exists("eliminar",$arrAccesos) || array_key_exists("modificar",$arrAccesos) ){
                    ?>
                    function fntPerfilEliminar( intId ){
                        $("#imgPerfilDelete_"+intId).hide();
                        $("#imgPerfilReturn_"+intId).show();
                        $("#hdnPerfilUpdate_"+ intId).val("N");
                        $("#hdnPerfilDelete_"+ intId).val("Y");
                        <?php
                        if( isset($arrAccesos["modificar"]) ){
                            ?>
                            $("#sltPerfil_"+intId).combobox("destroy");
                            $("#sltPerfil_"+intId).hide();
                            $("#divReadModesltPerfil_"+intId).show();
                            <?php
                        }
                        ?>
                        $("#imgPerfilDelete_"+intId).hide();
                        var objTr = $("#imgPerfilDelete_"+intId).parent().parent();
                        $(objTr).children().addClass("rowdelete");
                    }
                    <?php
                }

                if( array_key_exists("modificar",$arrAccesos) || array_key_exists("eliminar",$arrAccesos) ){
                    ?>
                    function fntPerfilCancelar( intId ){
                        $("#imgPerfilDelete_"+intId).show();
                        $("#imgPerfilReturn_"+intId).hide();
                        $("#hdnPerfilUpdate_"+ intId).val("Y");
                        $("#hdnPerfilDelete_"+ intId).val("N");

                        $("#sltPerfil_"+intId).val( $("#hdnPerfilOriginal_"+intId).val() );
                        $("#tdPerfilDescripcion_"+intId).html( $("#hdnPerfilDescripcion_"+intId).val() );

                        <?php
                        if( isset($arrAccesos["modificar"]) ){
                            ?>
                            $("#divReadModesltPerfil_"+intId).hide();
                            if( $("#sltPerfil_"+intId).combobox() ){
                                $("#sltPerfil_"+intId).combobox("destroy");
                            }
                            fntMakeCombobox("sltPerfil_"+intId);
                            <?php
                        }
                        ?>
                        $("#imgPerfilDelete_"+intId).show();
                        var objTr = $("#imgPerfilDelete_"+intId).parent().parent();
                        $(objTr).children().removeClass("rowdelete");
                    }
                    /*
                    function fntClienteCancelar( intId ){
                        $("#imgClienteDeleteC_"+intId).show();
                        $("#imgClienteReturnC_"+intId).hide();
                        $("#hdnClienteUpdate_"+ intId).val("Y");
                        $("#hdnClienteDelete_"+ intId).val("N");

                        $("#hdnCliente_"+intId).val( $("#hdnClienteOriginal_"+intId).val() );
                        $("#imgClienteDeleteC_"+intId).show();
                        var objTr = $("#imgClienteDeleteC_"+intId).parent().parent();
                        $(objTr).children().removeClass("rowdelete");
                    }
                    */
                    <?php
                }

                if( $_SESSION["hml"]["tipo_usuario"] == "admin" ){
                    ?>
                    var strTipoUsuarioPasado = $("#hdnTipoCuentaNormal").val();

                    function fntChangeTipoCuenta(){
                        $("#hdnCuentaNueva").val($("#sltTipoCuenta").val());

                        if( strTipoUsuarioPasado == "normal" && $("#sltTipoCuenta").val() == "admin" ){
                            $("#tblPerfiles > tbody").html("");
                        }
                        if( $("#sltTipoCuenta").val() == "admin" ){
                            $("#divMasterPerfilesAcc").hide();
                        }
                        else if(  $("#sltTipoCuenta").val() == "normal" ){
                            $("#divMasterPerfilesAcc").show();
                        }
                        strTipoUsuarioPasado = $("#sltTipoCuenta").val();
                    }
                    <?php
                }
                ?>
                $(function(){
                    $("[rel=tooltip]").tooltip();
                    <?php
                    if( $strTipoCuenta == "admin" ){
                        ?>
                        $("#divMasterPerfilesAcc").hide();
                        <?php
                    }
                    if($strTipoCuenta !== "admin" ) {
                        ?>
                        $("#divMasterPerfilesAcc").show();
                        <?php
                    }
                    ?>

                    if(!$("#hdnPuedeEliminar").val())
                        $("#btnEliminar").remove();
                });
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
        $objForm->form_closeForm();

    }

}