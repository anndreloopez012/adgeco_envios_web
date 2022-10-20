<?php
require_once("modules/usuarios/clases/usuarios_mi_cuenta_model.php");

class usuarios_mi_cuenta_view{

    private $objModel;

    public function __construct(){
        $this->objModel = new usuarios_mi_cuenta_model();
    }

    public function drawButtonsPersona( $strPersona , $strAleatorio ){
        global $lang, $strAction, $objTemplate, $arrAccesos;

        $intPersona = $this->objModel->getPersonaIdFromMd5( $strPersona );
        $strClassGuardar = "";
        $strClassEditar = "";
        if( $intPersona > 0 ){
            $strClassGuardar = !empty($strAleatorio) && $strAleatorio == "Y" ? "ocultar" : "";
            $strClassEditar = !empty($strAleatorio) && $strAleatorio == "Y" ? "" : "ocultar";
            if( isset($arrAccesos["modificar"])){
                $objTemplate->draw_button("btnEditar",$lang[MODULO]["usuarios_editar"],"fntEditar();","pencil","sm {$strClassGuardar}");
                $objTemplate->draw_button("btnGuardar",$lang[MODULO]["usuarios_guardar"],"fntGuardar();","lock","sm {$strClassEditar}");
            }
        }
        else{
            if( isset($arrAccesos["crear"])){
                $objTemplate->draw_button("btnGuardar",$lang[MODULO]["usuarios_guardar"],"fntGuardar();","lock","sm");
            }
        }
        $objTemplate->draw_button("btnCancel",$lang[MODULO]["usuarios_cerrar"],"fntCancelar();","remove","sm");

    }

    public function drawContentPersona( $strPersona , $strAleatorio){
        global $lang, $strAction, $objTemplate, $arrAccesos, $cfg;
        $boolEdicion = false;

        $arrInfoPersona = array();
        $arrInfoPersona = $this->objModel->getInfoPersona($strPersona);

        $intPersona = isset($arrInfoPersona["persona"]) ? intval($arrInfoPersona["persona"]) : 0;
        $strNombre = isset($arrInfoPersona["nombre_usual"]) ? $arrInfoPersona["nombre_usual"] : "";
        $strTipoCuenta = isset($arrInfoPersona["tipo"]) ? $arrInfoPersona["tipo"] : "";
        $intCorrelativo = 1;

        $arrInfoExtra[0]["nombre"] = $lang[MODULO]["usuarios_mi_cuenta"];
        $arrInfoExtra[0]["link"] = "{$strAction}";
        $arrInfoExtra[0]["activo"] = true;

        $arrInfoExtra[1]["nombre"] = empty($strNombre) ? $lang[MODULO]["usuarios_nuevo"] : $strNombre;
        $arrInfoExtra[1]["link"] = "{$strAction}";
        $arrInfoExtra[1]["activo"] = true;
        $arrBreadCrumb = core_getInfoBreadCrumb("",true,$arrInfoExtra);
        $objTemplate->draw_breadcrumb($arrBreadCrumb);

        $strClass = $boolEdicion ? "" : "no-visible";
        $boolModoEdicion = ($intPersona > 0) && ($strAleatorio == "N" || $strAleatorio == "") ? true : false;

        $objForm = new form("frmPersona","frmPersona","POST",$strAction,"return false;","form-horizontal");
        $objForm->form_setExtraTag("enctype","multipart/form-data");
        $objForm->form_setExtraTag("role","form");
        $objForm->form_openForm();

        $objForm->add_input_hidden("hdnPersona",$intPersona,true);
        $objForm->add_input_hidden("hdnAccion",1,true);
        $objForm->add_input_hidden("hdnIsAleatorio",!empty($strAleatorio) && $strAleatorio == "Y" ? $strAleatorio : "N" ,true);
        $objForm->add_input_hidden("hdnUpdate","N",true);

        if( ( isset($arrAccesos["crear"]) && count($arrInfoPersona) == 0) || (count($arrInfoPersona) > 0) ){
            ?>
            <div class="row">
                <div class="col-xs-12" id="divColXs12">
                    <?php
                    if( isset($_GET["aleatorio"]) && $_GET["aleatorio"] == "Y" && isset($_GET["strAlert"]) && $_GET["strAlert"] == "ale" ) {
                        ?>
                        <div class="alert alert-warning alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <strong>Atencion!</strong> Tu contraseña de acceso al sistema fue generada aleatoriamente, por favor cambiar de contraseña por una de tu preferencia.
                        </div>
                        <?php
                    }
                    $objTemplate->draw_panel_open("divContent");
                        $objTemplate->draw_panel_open_header();
                            ?>
                            &nbsp;
                            <?php
                        $objTemplate->draw_panel_close_header();
                        $objTemplate->draw_panel_open_body();
                        ?>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-6">
                                <div id="divNombreCompleto" class="form-group">
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
                                <div id="divCorreoElectronico" class="form-group">
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
                                <div class="form-group">
                                    <label for="txtUsuario" class="col-md-6 control-label">
                                        <?php $objTemplate->drawTitleLeft($lang[MODULO]["usuarios_cuenta"]); ?>
                                    </label>
                                    <div class="col-md-6 form-control-static">
                                        <?php
                                        $strTexto = isset($arrInfoPersona["usuario"]) && !empty($arrInfoPersona["usuario"]) ? $arrInfoPersona["usuario"] : "";
                                        core_print($strTexto);
                                        ?>
                                    </div>
                                </div>
                                <div id="divPass" class="form-group">
                                    <label for="txtPassword" class="col-md-6 control-label">
                                        <?php $objTemplate->drawTitleLeft($lang[MODULO]["usuarios_contrasenia"],true); ?>
                                    </label>
                                    <div class="col-md-6">
                                        <?php
                                        $objForm->add_input_hidden("hdnChangePassword","N",true);
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
                                        $objForm->add_input_password("txtPassword"," ocultar",false,$lang[MODULO]["usuarios_contrasenia"],$lang[MODULO]["usuarios_contrasenia"],false,true);
                                        $objForm->add_input_extraTag("txtPassword","maxlength","40");
                                        $objForm->add_input_extraTag("txtPassword","autocomplete","off");
                                        $objForm->draw_input_password("txtPassword");
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
                                <div id="divPass2" class="form-group" style="display:none;">
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
                            </div>
                        </div>
                        <?php
                       $objTemplate->draw_panel_close_body();

                    $objTemplate->draw_panel_close();
                    ?>
                </div>
            </div>
            <script>
                var boolEditar = false;
                var intCorrelativo = parseInt("<?php echo $intCorrelativo; ?>");
                var arrAjaxDescripcionPerfil = new Array();
                var objAjaxValidarUsuario;
                var strBase64 = "";
                var rtime = new Date(1, 1, 2000, 12,00,00);
                var timeout = false;
                var delta = 200;
                var intSize = 0;

                function fntEditar(){
                    boolEditar = true;
                    $("#btnGuardar").show();
                    $("#btnEditar").hide();
                    $("#divPassword").hide();
                    $("#lnkPassword").show();

                    <?php
                    if( array_key_exists("modificar",$arrAccesos) ){
                        ?>
                        $("div[id^='divReadMode']").each(function(){
                            arrSplit = $(this).attr("id").split("divReadMode");
                            if( arrSplit[1] != 'sltTipoPersona' ){
                                $(this).hide();
                                $("#"+arrSplit[1]).show();
                            }
                        });
                        <?php
                    }
                    ?>
                }

                function destroyAlertas(){
                    $("#divNombreCompleto").css("margin-bottom","10px");
                    $("#txtNombreCompleto").css("border","1px solid #ccc");
                    $("#divReqNombre").hide();

                    $("#divCorreoElectronico").css("margin-bottom","10px");
                    $("#txtCorreoElectronico").css("border","1px solid #ccc");
                    $("#divReqCorreo").hide();
                    $("#divInvCorreo").hide();

                    $("#divPass").css("margin-bottom","10px");
                    $("#txtPassword").css("border","1px solid #ccc");
                    $("#divReqPass").hide();

                    $("#divPass2").css("margin-bottom","10px");
                    $("#txtConfirmaPassword").css("border","1px solid #ccc");
                    $("#divReqPass2").hide();
                    $("#divNCPass2").hide();
                }

                function fntCancelar(){
                    if( boolEditar ){
                        document.location = '<?php echo $strAction; ?>?persona=<?php echo $strPersona; ?>';
                    }
                    else{
                        document.location = 'index.php?login=true';
                    }
                }

                function fntGuardar(){
                    boolCorrecto = true;
                    destroyAlertas();

                    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

                    $("#txtCorreoElectronico").val( $.trim( $("#txtCorreoElectronico").val() ) );
                    if( $("#txtCorreoElectronico").val().length == 0 ){
                        $("#divCorreoElectronico").css("margin-bottom","0");
                        $("#txtCorreoElectronico").css("border","1px solid #d27272");
                        $("#divReqCorreo").show();
                        boolCorrecto = false;
                    }
                    else if( !(expr.test( $("#txtCorreoElectronico").val() )) ){
                        $("#divCorreoElectronico").css("margin-bottom","0");
                        $("#txtCorreoElectronico").css("border","1px solid #d27272");
                        $("#divInvCorreo").show();
                        boolCorrecto = false;
                    }

                    if( $("#hdnChangePassword").val() == "Y" ){
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

                    if( boolCorrecto ){
                        document.frmPersona.submit();
                    }
                }

                function fntEliminar(){
                    $('#dialogEliminar').modal();
                }

                function fntChangePassword(){
                    $("#lnkPassword").hide();
                    $("input[name='txtPassword']").show();
                    $("#divPass2").show();
                    $("input[name='hdnChangePassword']").val("Y");
                }

                $(document).ready(function(){
                    $("[rel=tooltip]").tooltip();
                    <?php
                    if(!empty($strAleatorio) && $strAleatorio == "Y"){
                        ?>
                        $("#lnkPassword").show();
                        $("#divPassword").hide();
                        $("#lnkPassword").click();
                        fntChangePassword();
                        <?php
                    }
                    ?>
                });
            </script>
            <?php
        }
        else{
            ?>
            <script>
                document.location = "<?php echo $strAction; ?>";
            </script>
            <?php
        }
        $objForm->form_closeForm();

    }

}
