<?php
require_once("modules/clientes/clases/clientes_cliente_model.php");
$yBeneficiario=0;
$beneficiarioGlobal;
class clientes_cliente_view{

    private $objModel;
    private $intCliente;
   
    public function __construct($intCliente){
        $this->objModel = new clientes_cliente_model($intCliente);
        $this->intCliente = $intCliente;
    }

    /**
    * INICIO DE METODOS PARA CONSULTA DE CLIENTE
    */

    public function drawButtonsClienteConsulta(){
        global $lang,$strAction,$objTemplate,$arrAccesos;
        $objTemplate->draw_button("btnPrintDocs","PDF DOCS","fntPrintDocsVal();","book","sm");
        $objTemplate->draw_button("btnModificarPiloto","Ruta y Piloto","fntModificarPiloto();","user","sm");
        $objTemplate->draw_button("btnCargaUpdate","Carga CSV Recibo","fntCargaModalUpdate();","list","sm");
        $objTemplate->draw_button("btnCarga","Carga CSV","fntCargaModal();","list","sm");
        $objTemplate->draw_button("btnRefrescar","Refrescar","document.location.href='{$strAction}'","refresh","sm");
    }

    public function drawContentClienteConsulta() {
        global $objTemplate,$strAction,$objForm,$arrAccesos;
        $arrInfoEstados = $this->objModel->getInfoEstados();
        $boolEstado = !empty($_GET["estado"]);
        if( $boolEstado )
            $arrInfoEstados[$_GET["estado"]]["selected"] = true;
        $objForm = new form("frmClienteConsulta","frmClienteConsulta","post");
        $arrInfoPiloto = $this->objModel->getInfoPiloto();
        $arrInfoRuta = $this->objModel->getInfoRuta();
        $arrInfoZona = $this->objModel->getInfoZona();
        $arrInfoFilas = $this->objModel->getInfoFilas();
        
        $objTemplate->draw_modal_open("divModalModificarPiloto", "","lg");
        $objTemplate->draw_modal_draw_header("Ruta y Piloto", "", true,"");
        $objTemplate->draw_modal_open_content("divModalModificarPilotoContent");
            ?>
                <div class="row">
                    <div class="col-lg-4 col-lg-offset-2" id="divModalModificarPilotoContent">
                        <?php $objTemplate->drawTitleLeft("Que es lo que desea hacer?",false); ?>
                    </div>
                    <div class="col-lg-4">
                    <?php
                        $objForm->add_input_radio("modificar_piloto_check","modificar_piloto_check","modificar_piloto","Modificar", false, "piloto_check","",true);
                    ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-lg-offset-6">
                    <?php
                        $objForm->add_input_radio("modificar_piloto_check","asignar_piloto_check","asignar_piloto","Asignar", false, "piloto_check","",true);
                    ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-lg-offset-6">
                    <?php
                        $objForm->add_input_radio("modificar_piloto_check","eliminar_piloto_check","eliminar_piloto","Eliminar", false, "piloto_check","",true);
                    ?>
                    </div>
                </div>
                <div id="divModificarPiloto">
                    <hr>
                    <div class="row">
                        <div class="col-lg-4 col-lg-offset-2">
                            <?php $objTemplate->drawTitleLeft("Seleccione nueva Ruta",true); ?>
                        </div>
                        <div class="col-lg-4" id="divtxtModificarClienteRuta">
                            <?php
                                $objForm->add_select("modRuta",$arrInfoRuta,"text-uppercase",true,""); 
                            ?>
                            <span id="spantxtModificarClienteRuta" class="help-block ocultar">
                                <span class="fa fa-warning"></span>&nbsp;Por favor asigne una ruta.
                            </span>
                        </div>
                        <div class="col-lg-4 col-lg-offset-2">
                            <?php $objTemplate->drawTitleLeft("Seleccione nuevo Piloto",true); ?>
                        </div>
                        <div class="col-lg-4" id="divtxtModificarClientePiloto">
                            <?php
                                $objForm->add_select("modPiloto",$arrInfoPiloto,"text-uppercase",true,""); 
                            ?>
                            <span id="spantxtModificarClientePiloto" class="help-block ocultar">
                                <span class="fa fa-warning"></span>&nbsp;Por favor asigne un Piloto.
                            </span>
                        </div>
                    </div>
                </div>
                <div id="divAsignarPiloto">
                    <hr>
                    <div class="row">
                        <div class="col-lg-4 col-lg-offset-2">
                            <?php $objTemplate->drawTitleLeft("Seleccione Ruta",false); ?>
                        </div>
                        <div class="col-lg-4" id="divtxtAsignarClienteRuta">
                            <?php
                                $objForm->add_select("asigRuta",$arrInfoRuta,"text-uppercase",true,""); 
                            ?>
                            <span id="spantxtAsignarClienteRuta" class="help-block ocultar">
                                <span class="fa fa-warning"></span>&nbsp;Por favor asigne una ruta.
                            </span>
                        </div>
                        <div class="col-lg-4 col-lg-offset-2">
                            <?php $objTemplate->drawTitleLeft("Seleccione Piloto",false); ?>
                        </div>
                        <div class="col-lg-4" id="divtxtAsignarClientePiloto">
                            <?php
                                $objForm->add_select("asigPiloto",$arrInfoPiloto,"text-uppercase",true,""); 
                            ?>
                            <span id="spantxtAsignarClientePiloto" class="help-block ocultar">
                                <span class="fa fa-warning"></span>&nbsp;Por favor asigne un Piloto.
                            </span>
                        </div>
                    </div>
                </div>
            <?php 
        $objTemplate->draw_modal_close_content();
        $objTemplate->draw_modal_open_footer();
            $objTemplate->draw_button("btnModificarPilotoAceptar","Aceptar","fntModificarPilotoAceptar();","ok","sm");
            $objTemplate->draw_button("btnModificarPilotoCancelar","Cerrar","fntModificarPilotoCancelar();","remove","sm");
        $objTemplate->draw_modal_close_footer();
        $objTemplate->draw_modal_close();

        $objTemplate->draw_modal_open("divModalCargaMasiva", "","lg");
        $objTemplate->draw_modal_draw_header("Piloto", "", true,"");
        $objTemplate->draw_modal_open_content("divModalCargaMasivaContent");
            ?>
                <form method="post" enctype="multipart/form-data" id="frmCargaCsv">
                    <div class="row">
                        <div class="col-10">
                            <input class="form-control" type="file" name="fileContacts">
                        </div>
                        <div class="col-12">
                            </br>
                            <div class="col-md-12" style="text-align: center;"><b>CIF</br>CASO CRM</br>EJECUTIVO VENTAS</br>NOMBRE DE CLIENTE</br>TELEFONO</br>DIRECCION ENTREGA</br>ZONA O MUNICIPIO</br>DEPARTAMENTO</br>HORARIO</br>TC NUEVA O ADICIONAL</br>DIA DE ENTREGA SOLICITADA POR CLIENTE</br>EMPRESA</br></div>
                        </div>
                    </div>
                </form>
            <?php 
        $objTemplate->draw_modal_close_content();
        $objTemplate->draw_modal_open_footer();
            $objTemplate->draw_button("btnCargaMasiva","Insertar Registros CSV","fntCarga();","ok","sm");
            $objTemplate->draw_button("btnCargaMasivaCancelar","Cerrar","fntCargaMasivaCancelar();","remove","sm");
        $objTemplate->draw_modal_close_footer();
        $objTemplate->draw_modal_close();

        $objTemplate->draw_modal_open("divModalCargaMasivaUpdate", "","lg");
        $objTemplate->draw_modal_draw_header("Piloto", "", true,"");
        $objTemplate->draw_modal_open_content("divModalModalCargaMasivaUpdateContent");
            ?>
                <form method="post" enctype="multipart/form-data" id="frmCargaCsvUpdate">
                    <div class="row">
                        <div class="col-10">
                            <input class="form-control" type="file" name="fileContacts">
                        </div>
                        <div class="col-12">
                            </br>
                            <div class="col-md-12" style="text-align: center;"><b></br>CASO CRM</br>No.RECIBO</br>CODIGO CARGA</br></div>
                        </div>
                    </div>
                </form>
            <?php 
        $objTemplate->draw_modal_close_content();
        $objTemplate->draw_modal_open_footer();
            $objTemplate->draw_button("btnModalCargaMasivaUpdate","Insertar Registros CSV","fntCargaUpdate();","ok","sm");
            $objTemplate->draw_button("btnModalCargaMasivaUpdateCancelar","Cerrar","fntModalCargaMasivaUpdateCancelar();","remove","sm");
        $objTemplate->draw_modal_close_footer();
        $objTemplate->draw_modal_close();

        $objTemplate->draw_modal_open("divModalDetalleCliente", "","lg");
        $objTemplate->draw_modal_draw_header("Detalle", "", true,"");
        $objTemplate->draw_modal_open_content("divModalDetalleClienteContent");
            ?>
                <div id="detalleCliente"> </div>
            <?php 
        $objTemplate->draw_modal_close_content();
        $objTemplate->draw_modal_open_footer();
            $objTemplate->draw_button("btnDetalle","Revisado","fntAutorizaRevision();","ok","sm");
            $objTemplate->draw_button("btnDetalleCancelar","Cerrar","fntDetalleClienteCancelar();","remove","sm");
        $objTemplate->draw_modal_close_footer();
        $objTemplate->draw_modal_close();

        $objTemplate->draw_modal_open("divModalGoogleMaps", "","lg");
        $objTemplate->draw_modal_draw_header("Mapa", "", true,"");
        $objTemplate->draw_modal_open_content("divModalGoogleMapsContent");
            ?>
                <div id="detalleMapaCliente"></div>
            <?php 
        $objTemplate->draw_modal_close_content();
        $objTemplate->draw_modal_open_footer();
        $objTemplate->draw_button("btnGoogleMapsAceptar","Aceptar","fntModMapa();","ok","sm");
        $objTemplate->draw_button("btnGoogleMapsCancelar","Cerrar","fntGoogleMapsCancelar();","remove","sm");
        $objTemplate->draw_modal_close_footer();
        $objTemplate->draw_modal_close();

        ?>
        <div class="box box-solid">
            <div class="box-header">
                <?php
                    $objForm->form_openForm();
                    ?>
                    <div class="row">
                        <div class="col-lg-3 col-lg-offset-1">
                            <label for="codigo">
                                <?php $objTemplate->drawTitleLeft("Cliente/ CIF/ CRM/ Nombre de cliente "); ?>
                            </label>
                            <?php
                            $objForm->add_input_text("codigo","","",true);
                            ?>
                        </div>
                        <div class="col-lg-2">
                            <label for="Departamento">
                                <?php $objTemplate->drawTitleLeft("Departamento"); ?>
                            </label>
                            <?php
                            $objForm->add_input_text("departamento","","",true);
                            ?>
                        </div>
                        <div class="col-lg-2">
                            <label for="zona">
                                <?php $objTemplate->drawTitleLeft("Municipio"); ?>
                            </label>
                            <?php
                            $objForm->add_input_text("municipio","","",true);
                            ?>
                        </div>

                        <div class="col-lg-3">
                            <label for="Direccion">
                                <?php $objTemplate->drawTitleLeft("Direccion"); ?>
                            </label>
                            <?php
                            $objForm->add_input_text("direccion","","",true);
                            ?>
                        </div>
                        <div class="col-lg-2 col-lg-offset-1">
                            <label for="zona">
                                <?php $objTemplate->drawTitleLeft("Numero Ruta"); ?>
                            </label>
                            <?php
                            $objForm->add_input_text("ruta","","",true);
                            ?>
                        </div>
                        <div class="col-lg-2 ">
                            <label for="estado">
                                <?php $objTemplate->drawTitleLeft("Estado"); ?>
                            </label>
                            <?php
                            $objForm->add_multi_select("estado",$arrInfoEstados,"",true);
                            ?>
                        </div>
                        <div class="col-lg-2">
                            <label for="piloto">
                                <?php $objTemplate->drawTitleLeft("Pilotos"); ?>
                            </label>
                            <?php
                            $objForm->add_multi_select("piloto",$arrInfoPiloto,"text-uppercase", true, "");
                            ?>
                        </div>
                        
                        <div class="col-lg-2  ">
                            <label for="Zona">
                                <?php $objTemplate->drawTitleLeft("Zona"); ?>
                            </label>
                            <?php
                            $objForm->add_multi_select("zona",$arrInfoZona,"text-uppercase", true, "");
                            ?>
                        </div>
                        <div class="col-lg-2">
                            <label for="Filas">
                                <?php $objTemplate->drawTitleLeft("No. Filas"); ?>
                            </label>
                            <?php
                            $objForm->add_select("filas",$arrInfoFilas,"text-uppercase", true, "");
                            ?>
                        </div>

                        <div class="col-lg-offset-1 col-lg-2">
                            <label for="fecha_de">
                                <?php $objTemplate->drawTitleLeft("Fecha de"); ?>
                            </label>
                            <?php
                            $objForm->add_input_text("fecha_de","","",true,"YYYY-MM-DD");
                            ?>
                        </div>
                        <div class="col-lg-2">
                            <label for="fecha_hasta">
                                <?php $objTemplate->drawTitleLeft("fecha hasta"); ?>
                            </label>
                            <?php
                            $objForm->add_input_text("fecha_hasta","","",true,"YYYY-MM-DD");
                            ?>
                        </div>
                        <div class="col-lg-2">
                            <label for="recibo">
                                <?php $objTemplate->drawTitleLeft("No. Recibo"); ?>
                            </label>
                            <?php
                            $objForm->add_input_text("recibo","","",true);
                            ?>
                        </div>
                        <div class="col-lg-2">
                            <label for="carga">
                                <?php $objTemplate->drawTitleLeft("No.Carga"); ?>
                            </label>
                            <?php
                            $objForm->add_input_text("carga","","",true);
                            ?>
                        </div>
                        
                        
                        <div class="col-lg-1">
                            <br>
                            <?php
                            $objTemplate->draw_button("btnBuscar","Buscar","fntClienteBusqueda()","search","sm");
                            ?>
                        </div>
                    </div>
                    
                    <?php
                $objForm->form_closeForm();
                ?>
            </div>
            <div class="box-body" id="displaysomething">
            </div>
            <div class="box-footer">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" id="divClienteConsultaListado">
            </div>
        </div>
        
        <script>
            var strClienteGlobal = "";
            var strEstadoGlobal = "";
            function fntClienteBusqueda() {
                $.ajax({
                     url: "<?php print $strAction; ?>",
                     async: true,
                     type: "post",
                     dataType: "html",
                     data: "metodo=drawContentClienteConsultaListado&"+$("#frmClienteConsulta").serialize(),
                     beforeSend: function(){
                         showImgCoreLoading();
                     },
                     success: function( data ){
                         hideImgCoreLoading();
                         $("#divClienteConsultaListado").html("");
                         $("#divClienteConsultaListado").html(data);
                     },
                     error: function() {
                         hideImgCoreLoading();
                     }
                });
            }

            function fntClienteDetalleBusqueda(intCliente) {
                $.ajax({
                     url: "<?php print $strAction; ?>",
                     async: true,
                     type: "post",
                     dataType: "html",
                     data: "metodo=drawContentClienteConsultaListadoDetalle&"+"&cliente="+intCliente,
                     beforeSend: function(){
                         showImgCoreLoading();
                     },
                     success: function( data ){
                         hideImgCoreLoading();
                         $("#detalleCliente").html("");
                         $("#detalleCliente").html(data);
                         $("#cliente_detalle").val(intCliente);
                         $("#divModalDetalleCliente").modal("show");
                     },
                     error: function() {
                         hideImgCoreLoading();
                     }
                });
            }

            function fntDrawMapa(intCliente) {
                $.ajax({
                     url: "<?php print $strAction; ?>",
                     async: true,
                     type: "post",
                     dataType: "html",
                     data: "metodo=drawContentClienteConsultaMapa&"+"&cliente="+intCliente,
                     beforeSend: function(){
                         showImgCoreLoading();
                     },
                     success: function( data ){
                         hideImgCoreLoading();
                         $("#detalleMapaCliente").html("");
                         $("#detalleMapaCliente").html(data);
                         $( "#cliente_mapa" ).val(intCliente);
                         $("#divModalGoogleMaps").modal("show");
                     },
                     error: function() {
                         hideImgCoreLoading();
                     }
                });
            }
            
            
           function fntCargaUpdate() {
               var strCarga = new FormData($('#frmCargaCsvUpdate')[0]);

               $.ajax({

                   url: "<?php print $strAction; ?>?validaciones=drawClienteCargaMasivaUpdate",
                   type: "post",
                   data: strCarga,
                   processData: false,
                   contentType: false,
                   beforeSend: function(){
                        showImgCoreLoading();
                    },
                    success: function( data ){
                        hideImgCoreLoading();
                        $('#frmCargaCsvUpdate')[0].reset();
                        draw_Alert("danger","","Carga realizada exitosamente.",true);
                        fntClienteBusqueda()
                    },
                    error: function() {
                        hideImgCoreLoading();
                        draw_Alert("danger","","Carga no pudo ser realizada.",true);
                    }
               });

               return false;
           }

           function fntCarga() {
               var strCarga = new FormData($('#frmCargaCsv')[0]);

               $.ajax({

                   url: "<?php print $strAction; ?>?validaciones=drawClienteCargaMasiva",
                   type: "post",
                   data: strCarga,
                   processData: false,
                   contentType: false,
                   beforeSend: function(){
                        showImgCoreLoading();
                    },
                    success: function( data ){
                        hideImgCoreLoading();
                        $('#frmCargaCsv')[0].reset();
                        draw_Alert("danger","","Carga realizada exitosamente.",true);
                        fntClienteBusqueda()
                    },
                    error: function() {
                        hideImgCoreLoading();
                        draw_Alert("danger","","Carga no pudo ser realizada.",true);
                    }
               });

               return false;
           }
            
            function fntModificarPiloto(){
                var cliente = [];
                $.each($("input[name='check_modificar_cliente']:checked"), function(){
                    cliente.push($(this).val());
                });
                if(cliente.length>0){
                    $("#divtxtModificarClientePiloto").removeClass("has-error");
                    $("#spantxtModificarClientePiloto").hide();
                    $("#divtxtAsignarClientePiloto").removeClass("has-error");
                    $("#spantxtAsignarClientePiloto").hide();
                    $("#divModalModificarPiloto").modal("show");
                }else{
                    draw_Alert("danger","","Por favor selecciona el (los) cliente(s) que desees modificar.",true);
                }
            }

            function fntPrintDocsVal(){
                document.frmClienteImprimir.submit();
            }


            function fntPrintPdfBanco() {
                var cliente = [];
                $.each($("input[name='check_modificar_cliente']:checked"), function(){
                    cliente.push($(this).val());
                });

               $.ajax({

                   url: "<?php print $strAction; ?>?validaciones=drawPdfBanco",
                   type: "post",
                   processData: false,
                   data: cliente,
                   contentType: false,
                   beforeSend: function(){
                        showImgCoreLoading();
                    },
                    success: function( data ){
                        hideImgCoreLoading();
                        draw_Alert("danger","","Carga realizada exitosamente.",true);
                        fntClienteBusqueda()
                    },
                    error: function() {
                        hideImgCoreLoading();
                        draw_Alert("danger","","Carga no pudo ser realizada.",true);
                    }
               });

               return false;
           }

            function fntModMapa(){
                var boolError = false;
                var longitud_cliente = $( "#longitud_cliente" ).val();
                var latitud_cliente = $( "#latitud_cliente" ).val();
                var cliente_mapa = $( "#cliente_mapa" ).val();
                var map_cliente = $( "#map_cliente" ).val();

                if(!boolError){
                    status = 1;
                    $.ajax({
                        url: "<?php print $strAction; ?>",
                        async: false,
                        type: "post",
                        dataType: "html",
                        data: "metodo=processModificarClienteMapa"+"&longitud_cliente="+longitud_cliente+"&latitud_cliente="+latitud_cliente+"&cliente_mapa="+cliente_mapa+"&map_cliente="+map_cliente,
                        beforeSend: function(){
                            showImgCoreLoading();
                        },
                        success: function( data ){
                            hideImgCoreLoading();
                            $( "#latitud_cliente" ).val("");
                            $( "#longitud_cliente" ).val("");
                            $( "#map_cliente" ).val("");
                            $("#divModalGoogleMaps").modal("hide");
                            fntClienteBusqueda();
                            draw_Alert("succes","","Actualizacion de mapa Exitoso.",true);
                        }
                    });
                }
                    
            }

            function fntAutorizaRevision(){
                var boolError = false;
                var strEstado = 'FINALIZADO';
                var cliente_detalle = $( "#cliente_detalle" ).val();
                if(!boolError){
                    status = 1;
                    $.ajax({
                        url: "<?php print $strAction; ?>",
                        async: false,
                        type: "post",
                        dataType: "html",
                        data: "metodo=processModificarClienteEstado"+"&estado="+strEstado+"&cliente_detalle="+cliente_detalle,
                        beforeSend: function(){
                            showImgCoreLoading();
                        },
                        success: function( data ){
                            hideImgCoreLoading();
                            $("#divModalDetalleCliente").modal("hide");
                            fntClienteBusqueda();
                            draw_Alert("primary","","Actualizacion de estado Exitoso.",true);
                        }
                    });
                }
                    
            }

            function fntModificarPilotoAceptar(){
                var cliente = [];
                var boolError = false;
                var modPiloto = $( "#modPiloto" ).val();
                var modRuta = $( "#modRuta" ).val();
                var asigPiloto = $( "#asigPiloto" ).val();
                var asigRuta = $( "#asigRuta" ).val();
                $.each($("input[name='check_modificar_cliente']:checked"), function(){
                    cliente.push($(this).val());
                });
                if(cliente.length>0){
                    if ($("input[name='modificar_piloto_check']:checked").val() == "modificar_piloto") {
                        if($( "#modPiloto" ).val()==''){
                            $("#divtxtModificarClientePiloto").addClass("has-error");
                            $("#spantxtModificarClientePiloto").show();
                            
                            boolError = true;
                        }
                        if($( "#modRuta" ).val()==''){
                            $("#divtxtModificarClienteRuta").addClass("has-error");
                            $("#spantxtModificarClienteRuta").show();
                            
                            boolError = true;
                        }
                        if(!boolError){
                            status = 2;
                            $.ajax({
                                url: "<?php print $strAction; ?>",
                                async: false,
                                type: "post",
                                dataType: "html",
                                data: "metodo=processModificarPilotoCliente&cliente="+JSON.stringify(cliente)+"&piloto="+modPiloto+"&ruta="+modRuta+"&status="+status,
                                beforeSend: function(){
                                    showImgCoreLoading();
                                },
                                success: function( data ){
                                    hideImgCoreLoading();
                                    $( "#modRuta" ).val("");
                                    $( "#modPiloto" ).val("");
                                    $( "#asigPiloto" ).val("");
                                    $( "#asigRuta" ).val("");
                                    $("#divModalModificarPiloto").modal("hide");
                                    fntClienteBusqueda();
                                    draw_Alert("succes","","Modificacion Exitosa.",true);
                                }
                            });
                        }
                    }else if($("input[name='modificar_piloto_check']:checked").val() == "asignar_piloto"){
                        if($( "#asigPiloto" ).val()==''){
                            $("#divtxtAsignarClientePiloto").addClass("has-error");
                            $("#spantxtAsignarClientePiloto").show();
                            boolError = true;
                        }
                        if($( "#asigRuta" ).val()==''){
                            $("#divtxtAsignarClienteRuta").addClass("has-error");
                            $("#spantxtAsignarClienteRuta").show();
                            boolError = true;
                        }
                        if(!boolError){
                            status = 1;
                            $.ajax({
                                url: "<?php print $strAction; ?>",
                                async: false,
                                type: "post",
                                dataType: "html",
                                data: "metodo=processModificarPilotoCliente&cliente="+JSON.stringify(cliente)+"&piloto="+asigPiloto+"&ruta="+asigRuta+"&status="+status,
                                beforeSend: function(){
                                    showImgCoreLoading();
                                },
                                success: function( data ){
                                    hideImgCoreLoading();
                                    $( "#modPiloto" ).val("");
                                    $( "#modRuta" ).val("");
                                    $( "#asigPiloto" ).val("");
                                    $( "#asigRuta" ).val("");
                                    $("#divModalModificarPiloto").modal("hide");
                                    fntClienteBusqueda();
                                    draw_Alert("succes","","Asignacion Exitosa.",true);
                                }
                            });
                        }
                    }else if($("input[name='modificar_piloto_check']:checked").val() == "eliminar_piloto"){
                        if(!boolError){
                            modPiloto = $( "#modPiloto" ).val("");
                            status = 3;
                            $.ajax({
                                url: "<?php print $strAction; ?>",
                                async: false,
                                type: "post",
                                dataType: "html",
                                data: "metodo=processModificarPilotoCliente&cliente="+JSON.stringify(cliente)+"&piloto="+modPiloto+"&status="+status,
                                beforeSend: function(){
                                    showImgCoreLoading();
                                },
                                success: function( data ){
                                    hideImgCoreLoading();
                                    $( "#modRuta" ).val("");
                                    $( "#modPiloto" ).val("");
                                    $( "#asigPiloto" ).val("");
                                    $( "#asigRuta" ).val("");
                                    $("#divModalModificarPiloto").modal("hide");
                                    fntClienteBusqueda();
                                    draw_Alert("succses","","Eliminacion Exitosa.",true);
                                }
                            });
                        }
                    }
                }else{
                    draw_Alert("danger","","Por favor selecciona el (los) cliente(s) que desees modificar.",true);
                    $("#divModalModificarPiloto").modal("hide");
                }
            }
            function fntModificarPilotoCancelar(){
                $("#divModalModificarPiloto").modal("hide");
            }
            
            function fntMostrarModificarPiloto(){
                if ($("input[name='modificar_piloto_check']:checked").val() == "modificar_piloto") {
                    $("#divModificarPiloto").show();
                    $("#divAsignarPiloto").hide();
                }else if($("input[name='modificar_piloto_check']:checked").val() == "asignar_piloto") {
                    $("#divModificarPiloto").hide();
                    $("#divAsignarPiloto").show();
                }else{
                    $("#divModificarPiloto").hide();
                    $("#divAsignarPiloto").hide();
                }
            }

            function fntCargaModal(){
                $("#divModalCargaMasiva").modal("show");
            }
            function fntCargaModalUpdate(){
                $("#divModalCargaMasivaUpdate").modal("show");
            }

            function fntCargaMasivaCancelar(){
                $("#divModalCargaMasiva").modal("hide");
            }
            function fntModalCargaMasivaUpdateCancelar(){
                $("#divModalCargaMasivaUpdate").modal("hide");
            }

            function fntDetalleClienteCancelar(){
                $("#divModalDetalleCliente").modal("hide");
            }

            function fntGoogleMapsCancelar(){
                $("#divModalGoogleMaps").modal("hide");
            }
            
            $(function(){
                $("#codigo").keyup(function(e){
                    var code = e.which;
                    if( code==13 ) e.preventDefault();
                    if( code==13 ){
                        fntClienteBusqueda();
                    }
                });
                $("#estado").multiselect({
                    noneSelectedText:"Seleccione estado(s)",
                    selectedText:"# estado(s) seleccionado(s)",
                    checkAllText:"Todos",
                    uncheckAllText:"Ninguno"
                });
                $("#piloto").multiselect({
                    noneSelectedText:"Seleccione piloto(s)",
                    selectedText:"# piloto(s) seleccionado(s)",
                    checkAllText:"Todos",
                    uncheckAllText:"Ninguno"
                });


                $("#zona").multiselect({
                    noneSelectedText:"Seleccione zona(s)",
                    selectedText:"# zona(s) seleccionado(s)",
                    checkAllText:"Todos",
                    uncheckAllText:"Ninguno"
                });

                <?php
                if( $boolEstado ) {
                    ?>
                    fntClienteBusqueda();
                    <?php
                }
                ?>
                
                $("#divModificarPiloto").hide();
                $("#divAsignarPiloto").hide();
                $("input[name='modificar_piloto_check']").change(function (){
                    fntMostrarModificarPiloto();
                });
            });
        </script>
        <?php
    }

    public function drawContentClienteConsultaListadoDetalle() {
        global $objTemplate,$strAction,$objForm,$arrAccesos;
        $intCliente = isset($_POST["cliente"]) ? db_escape(user_input_delmagic($_POST["cliente"],true)) : "";
        $arrClienteDetalle = $this->objModel->getInfoClienteConsultaDetalle($intCliente);
        $strNombre = isset($arrClienteDetalle["nombre"]) ? $arrClienteDetalle["nombre"] : '';
        $comentario = isset($arrClienteDetalle["descrip_entrega"]) ? ($arrClienteDetalle["descrip_entrega"]) : '';
        $urlEntregaLat = isset($arrClienteDetalle["latitud_mensajero"]) ? ($arrClienteDetalle["latitud_mensajero"]) : '';
        $urlEntregaLong = isset($arrClienteDetalle["longitud_mensajero"]) ? ($arrClienteDetalle["longitud_mensajero"]) : '';
        
        ?>
        <input type="hidden" id="cliente_detalle" value="<?php print $intCliente; ?>">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <label for="nombre">
                    <?php $objTemplate->drawTitleLeft("NOMBRE : "); ?>
                </label>
                <b><?php print $strNombre; ?></b>
            </div>
            <div class="col-lg-10 col-lg-offset-1">
                <label for="descripcion">
                    <?php $objTemplate->drawTitleLeft("DESCRIPCION : "); ?>
                </label>
                <b><?php print $comentario; ?></b>
            </div>
            <div class="col-lg-10 col-lg-offset-1">
                <label for="codigo">
                    <?php $objTemplate->drawTitleLeft("UBICACION MENSAJERO ENTREGA - LATITUD/LONGITUD"); ?>
                </label>
                <?php if($urlEntregaLat && $urlEntregaLong){ ?>
                </br><b><?php print 'LATITUD-'.$urlEntregaLat; ?> <?php print 'LONGITUD-'.$urlEntregaLong; ?></b></br>
                <iframe src="https://embed.waze.com/es/iframe?
                zoom=6&lat=<?php print $urlEntregaLat; ?>&lon=<?php print $urlEntregaLong; ?>&pin=1"
                width="100%" height="700"></iframe>
                <?php }?>
            </div>
            <div class="col-lg-10 col-lg-offset-1">
                <label for="DPI">
                    <?php $objTemplate->drawTitleLeft("DPI"); ?>
                </label>
            </div>
            <?php
            $arrInfoClienteConsultaDpi = $this->objModel->getInfoClienteConsultaAdjunto(1);
                if( !empty($arrInfoClienteConsultaDpi) ) {
                    reset($arrInfoClienteConsultaDpi);
                    while( $arrA = each($arrInfoClienteConsultaDpi) ) {
                        ?>
                        <div class="col-lg-10 col-lg-offset-1">
                            <div class="col-lg-6 col-lg-offset-1">
                                <img src="../<?php print $arrA["value"]["path_adjunto"]; ?>" width="400px" height="200px" class="img-fluid" alt="<?php print $arrA["value"]["nombre_adjunto"]; ?>"></br>
                            </div>
                        </div>
            <?php
                    }
                }
            ?>
            <div class="col-lg-10 col-lg-offset-1">
                <label for="RECIBO">
                    <?php $objTemplate->drawTitleLeft("RECIBO"); ?>
                </label>
            </div>
            <?php
            $arrInfoClienteConsultaRecibo = $this->objModel->getInfoClienteConsultaAdjunto(2);
            if( !empty($arrInfoClienteConsultaRecibo) ) {
                reset($arrInfoClienteConsultaRecibo);
                while( $arrB = each($arrInfoClienteConsultaRecibo) ) {
                    ?>
                    <div class="col-lg-10 col-lg-offset-1">
                        <div class="col-lg-6 col-lg-offset-1">
                            <img src="../<?php print $arrB["value"]["path_adjunto"]; ?>" width="300px" height="400px" class="img-fluid" alt="<?php print $arrB["value"]["nombre_adjunto"]; ?>"></br>
                        </div>
                    </div>
            <?php
                    }
                }
            ?>
        </div>
        <?php
    }

    public function drawContentClienteConsultaMapa() {
        global $objTemplate,$strAction,$objForm;
        $intCliente = isset($_POST["cliente"]) ? db_escape(user_input_delmagic($_POST["cliente"],true)) : "";
        $arrClienteDetalle = $this->objModel->getInfoClienteConsultaDetalle($intCliente);
        $urlEntregaLat = isset($arrClienteDetalle["latitud"]) ? $arrClienteDetalle["latitud"] : '';

        $urlEntregaLat = isset($arrClienteDetalle["latitud"]) ? $arrClienteDetalle["latitud"] : '';
        $urlEntregaLong = isset($arrClienteDetalle["longitud"]) ? $arrClienteDetalle["longitud"] : '';
        $intClienteCliente = isset($arrClienteDetalle["cliente"]) ? $arrClienteDetalle["cliente"] : '';
        $mapa_url = isset($arrClienteDetalle["mapa_url"]) ? $arrClienteDetalle["mapa_url"] : '';

        ?>
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <?php
                if($urlEntregaLat &&  $urlEntregaLong){
                ?>
                    <label for="mapa">
                        <?php $objTemplate->drawTitleLeft("MAPA DE INGRESO CLIENTE"); ?>
                    </label>
                    <iframe src="https://embed.waze.com/es/iframe?
                    zoom=6&lat=<?php print $urlEntregaLat; ?>&lon=<?php print $urlEntregaLong; ?>&pin=1"
                    width="100%" height="700"></iframe>
                    <br>
                    <br>
                <?php
                }
                ?>
                <input type="hidden" id="cliente_mapa" value="<?php print $intClienteCliente; ?>">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">Latitud</span>
                    <input type="text" id="latitud_cliente" class="form-control" aria-describedby="basic-addon1" value="<?php print $urlEntregaLat; ?>">
                </div>
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">Longitud</span>
                    <input type="text" id="longitud_cliente" class="form-control" aria-describedby="basic-addon1" value="<?php print $urlEntregaLong; ?>">
                </div>
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">Google Maps</span>
                    <input type="text" id="map_cliente" class="form-control" aria-describedby="basic-addon1" value="<?php print $mapa_url; ?>">
                </div>
            </div>
            
        </div>
        <?php
    }

    public function drawContentClienteConsultaListado() {
        global $strAction,$objTemplate,$objForm;

        $objForm = new form("frmClienteImprimir","frmClienteImprimir","post","{$strAction}");
        $objForm->form_setExtraTag("target","_blank");
        $objForm->form_openForm();
        
        $objForm->add_input_hidden("metodo","drawContentDetalleClienteListado",true);
        $strCodigo = isset($_POST["codigo"]) ? db_escape(user_input_delmagic($_POST["codigo"],true)) : "";
        $strMunicipio = isset($_POST["municipio"]) ? db_escape(user_input_delmagic($_POST["municipio"],true)) : "";
        $strDireccion = isset($_POST["direccion"]) ? db_escape(user_input_delmagic($_POST["direccion"],true)) : "";
        $strDepartamento = isset($_POST["departamento"]) ? db_escape(user_input_delmagic($_POST["departamento"],true)) : "";
        $strRuta = isset($_POST["ruta"]) ? db_escape(user_input_delmagic($_POST["ruta"],true)) : "";

        $strFechaDe = isset($_POST["fecha_de"]) ? db_escape(user_input_delmagic($_POST["fecha_de"],true)) : "";
        $strFechaHasta = isset($_POST["fecha_hasta"]) ? db_escape(user_input_delmagic($_POST["fecha_hasta"],true)) : "";
        $strRecibo = isset($_POST["recibo"]) ? db_escape(user_input_delmagic($_POST["recibo"],true)) : "";
        $strCarga = isset($_POST["carga"]) ? db_escape(user_input_delmagic($_POST["carga"],true)) : "";

        $strEstado = "";
        if( !empty($_POST["estado"]) ) {
            $strEstado .= "'".implode("','",$_POST["estado"]);
            $strEstado .= "'";
        }
        $strPiloto = "";
        if( !empty($_POST["piloto"]) ) {
            $strPiloto .= "'".implode("','",$_POST["piloto"]);
            $strPiloto .= "'";
        }
        $strZona = "";
        if( !empty($_POST["zona"]) ) {
            $strZona .= "'".implode("','",$_POST["zona"]);
            $strZona .= "'";
        }
        $strFilas = isset($_POST["filas"]) ? db_escape(user_input_delmagic($_POST["filas"],true)) : "";
        ?>
        <div class="box box-solid">
            <div class="box-body">
                <table class="table table-hover" id="tblClienteConsultaListado">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th><input type="checkbox" id="MarcarTodos"></th>
                            <th>Cliente</th>
                            <th>Nombre</th>
                            <th class="text-center">Mapa</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Cif</th>
                            <th class="text-center">Caso/Crm</th>
                            <th class="text-center">Direccion</th>
                            <th class="text-center">Comentario</th>
                            <th class="text-center">Ejecutivo en Ventas</th>
                            <th class="text-center">Piloto</th>
                            <th class="text-center">Fecha/Entrega</th>
                            <th class="text-center">Telefono</th>
                            <th class="text-center">Zona/Municipio</th>
                            <th class="text-center">Departamento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $arrInfoClienteConsulta = $this->objModel->getInfoClienteConsulta($strCodigo,$strMunicipio,$strDireccion,$strEstado,$strPiloto,$strDepartamento,$strZona,$strFilas,$strRuta,$strFechaDe,$strFechaHasta,$strRecibo,$strCarga);
                        if( !empty($arrInfoClienteConsulta) ) {
                            $intContadorCliente = 1;
                            reset($arrInfoClienteConsulta);
                            while( $arrC = each($arrInfoClienteConsulta) ) {
                                ?>
                                <tr>
                                    <th>&nbsp;</th>
                                    <td style="text-align: center;">
                                        <input type="hidden" name="cliente_<?php print $intContadorCliente ?>" id="cliente_<?php print $intContadorCliente ?>" value="<?php print $arrC["value"]["cliente"] ?>">
                                        <input type="checkbox" name="check_modificar_cliente" id="check_modificar_cliente" data-content="" value="<?php print $arrC["value"]["cliente"] ?>">
                                    </td>
                                    <td><a onclick="fntClienteDetalleBusqueda(<?php print $arrC["value"]["cliente"] ?>)"><?php print str_pad($arrC["value"]["cliente"],10,"0",STR_PAD_LEFT); ?></a></td>
                                    <td><?php print $arrC["value"]["nombre"]; ?></td>
                                    <td><a onclick="fntDrawMapa(<?php print $arrC["value"]["cliente"] ?>)"><span class="glyphicon glyphicon-eye-open"></span></a></td>
                                    <td><?php print $arrC["value"]["estado_entrega"]; ?></td>
                                    <td style="text-align: center;"><?php print $arrC["value"]["cif"]; ?></td>
                                    <td style="text-align: center;"><?php print $arrC["value"]["crm"]; ?></td>
                                    <td style="text-align: center;"><?php print $arrC["value"]["direccion"]; ?></td>
                                    <td style="text-align: center;"><?php print $arrC["value"]["horario"]; ?></td>
                                    <td style="text-align: center;"><?php print $arrC["value"]["ejecutivo_ventas"]; ?></td>
                                    <td style="text-align: center;"><?php print $arrC["value"]["piloto"]; ?></td>
                                    <td style="text-align: center;"><?php print $arrC["value"]["fecha_entrega_cliente"]; ?></td>
                                    <td style="text-align: center;"><?php print $arrC["value"]["telefono"]; ?></td>
                                    <td style="text-align: center;"><?php print $arrC["value"]["zona_municipio"]; ?></td>
                                    <td style="text-align: center;"><?php print $arrC["value"]["departamento"]; ?></td>
                                </tr>
                                <?php
                                $intContadorCliente ++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
        $objForm->form_closeForm();
        ?>

        <script>
            $(function() {

                $("#tblClienteConsultaListado").DataTable({
                    "dom": 'B<"clear">lfrtip',
                    //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                    buttons: {
                        buttons: [
                            'copy',
                            { extend: 'excelHtml5',
                                text: 'Excel',
                                exportOptions: {
                                                columns: [ 2, 12, 6, 7, 10, 3, 13, 8, 14, 15, 9, 11, 5]
                                                            }, 
                            },
                            { extend: 'pdfHtml5', 
                                text: 'PDF',
                                exportOptions: {
                                                columns: [ 2, 12, 6, 7, 10, 3, 13, 8 ]
                                                            }, 
                            },
                            { extend: 'print', 
                                text: 'Imprimir',
                                exportOptions: {
                                                columns: [ 2, 12, 6, 7, 10, 3, 13, 8, 14, 15, 9, 11, 5 ]
                                                            }, 
                            },
                            { extend: 'colvis', 
                                text: 'Ocultar',
                                columns: [ 2, 3, 5, 6, 7, 8, 9, 10, 11, 12 ],
                            }
                        ]
                    },
                    
                    "oPaginate": true,
                    "aLengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
                    "oLanguage": {
                        "oPaginate": {
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior",
                            "sFirst": "Primero",
                            "sLast": "ultimo"
                        },
                        "sLengthMenu": " Registros a mostar: _MENU_",
                        "sSearch": "Buscar:",
                        "sInfo": "Mostrando desde _START_ hasta _END_ de _TOTAL_ registros",
                        "sZeroRecords": "<div class='text-center' style='color:red'>No se encontraron registros sobre la busqueda</div>",
                        "sInfoEmpty": "Ningun registro por mostrar",
                        "sInfoFiltered": " - filtrado de  _MAX_ registros"
                        
                    },
                    //guarda configuracion de tabla
                    "stateSave": true,
                    "aoColumnDefs": [
                        //ocultar columna columna tabla
                        { "visible": false, "targets": [ 0,13,14,15 ] },
                        //filtro no busqueda columna tabla
                        { "bSearchable": false, "aTargets": [ 0,1 ] },
                        //filtro no columna tabla
                        { "bSortable": false, "aTargets": [ 0,1 ] }
                    ]
                });

            });

            $('document').ready(function () {
                $("#MarcarTodos").change(function () {
                    $("input:checkbox").prop('checked', $(this).prop("checked"));
                });
            });
        </script>
        <?php
    }

    public function drawContentDetalleClienteListado(){
        require_once("libraries/tcpdf/tcpdf.php");

        $pdf = new TCPDF("P", "mm", array(216,279), false, 'ISO-8859-1', false);
        $pdf->SetTitle('Impresion');
        // Quitando encabezado y pie de página
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // set margins
        $pdf->SetMargins(10, 10, 10);
        // set auto page breaks
        $pdf->SetAutoPageBreak(false, 10);
        $pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');
        $font = $pdf->addTTFfont('libraries/tcpdf/fonts/arialbd.ttf', 'TrueType', '', 32);

        reset($_POST);
        while( $rTMP = each($_POST) ){
            $arrExplode = explode("_",$rTMP["key"]);
            if( isset($arrExplode[1]) ) {
                if ($arrExplode[0] == "cliente") {
            $intCliente = isset($_POST["cliente_{$arrExplode[1]}"]) ? intval($_POST["cliente_{$arrExplode[1]}"]) : 0;
            $arrClienteDetalle = $this->objModel->getInfoClienteConsultaDetalle($intCliente);
            $strNombre = isset($arrClienteDetalle["nombre"]) ? $arrClienteDetalle["nombre"] : '';
            $pdf->AddPage();
$html = <<<EOF
<br /><span>NOMBRE: {$strNombre}</span>
<br /><br /><span>DPI</span><br />
EOF;

        $left = 10;
        $arrInfoClienteConsultaDpi = $this->objModel->getInfoClienteConsultaAdjuntoPdf(1,$intCliente);
        if( !empty($arrInfoClienteConsultaDpi) ) {
            reset($arrInfoClienteConsultaDpi);
            while( $arrA = each($arrInfoClienteConsultaDpi) ) {
                $ruta = "../". $arrA["value"]["path_adjunto"]; 
                $pdf->Image($ruta, $left, 27, 90, 50, 'JPG', '', 'left', true, 500, '', false, false, 1, false, false, false);
                $left = 110;
            }
        }
        
        $pdf->writeHTML($html, true, false, true, false, '');
$html = <<<EOF
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><span>RECIBO</span><br />
EOF;
        $left = 10;
        $arrInfoClienteConsultaDpi = $this->objModel->getInfoClienteConsultaAdjuntoPdf(2,$intCliente);
        if( !empty($arrInfoClienteConsultaDpi) ) {
            reset($arrInfoClienteConsultaDpi);
            while( $arrA = each($arrInfoClienteConsultaDpi) ) {
                $ruta = "../". $arrA["value"]["path_adjunto"]; 
                $pdf->Image($ruta, $left, 86, 60, 90, 'JPG', '', 'left', true, 500, '', false, false, 1, false, false, false);
                $left = 110;
            }
        }
        
        $pdf->writeHTML($html, true, false, true, false, '');
                }
            }
        }

        $pdf->Output('DetalleCliente.pdf', 'I');
    }
}