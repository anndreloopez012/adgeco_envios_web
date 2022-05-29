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
        global $lang,$strAction,$objTemplate;
        $objTemplate->draw_button("btnModificarPiloto","Ruta y Piloto","fntModificarPiloto();","user","sm");
        $objTemplate->draw_button("btnCarga","Carga CSV","fntCargaModal();","list","sm");
        $objTemplate->draw_button("btnRefrescar","Refrescar","document.location.href='{$strAction}'","refresh","sm");
    }

    public function drawContentClienteConsulta() {
        global $objTemplate,$strAction,$objForm;
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
                        <div class="col-lg-1">
                            <label for="Filas">
                                <?php $objTemplate->drawTitleLeft("No. Filas"); ?>
                            </label>
                            <?php
                            $objForm->add_select("filas",$arrInfoFilas,"text-uppercase", true, "");
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
            
            function fntCarga() {

                var strCarga = new FormData($('#frmCargaCsv')[0]);

                $.ajax({
                     url: "<?php print $strAction; ?>",
                     async: true,
                     type: "post",
                     data: "metodo=processCargaMasiva&" + strCarga,
                     processData: false,
                     contentType: false,
                     beforeSend: function(){
                         showImgCoreLoading();
                     },
                     success: function( data ){
                         hideImgCoreLoading();
                         $('#frmCargaCsv')[0].reset();
                         $("#divModalCargaMasiva").modal("hide");
                         fntClienteBusqueda()
                         draw_Alert("succes","","Carga realizada exitosamente.",true);
                         
                     },
                     error: function() {
                         hideImgCoreLoading();
                         draw_Alert("danger","","Carga no pudo ser realizada.",true);
                     }
                });
            }
            
           //function fntCarga() {
           //    var strCarga = new FormData($('#frmCargaCsv')[0]);

           //    $.ajax({

           //        url: "<?php //print $strAction; ?>?validaciones=drawClienteCargaMasiva",
           //        type: "post",
           //        data: strCarga,
           //        processData: false,
           //        contentType: false,
           //        beforeSend: function(){
           //             showImgCoreLoading();
           //         },
           //         success: function( data ){
           //             hideImgCoreLoading();
           //             $('#frmCargaCsv')[0].reset();
           //             draw_Alert("danger","","Carga realizada exitosamente.",true);
           //             //fntClienteBusqueda()
           //         },
           //         error: function() {
           //             hideImgCoreLoading();
           //             draw_Alert("danger","","Carga no pudo ser realizada.",true);
           //         }
           //    });

           //    return false;
           //}
            
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

            function fntCargaMasivaCancelar(){
                $("#divModalCargaMasiva").modal("hide");
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

    public function drawContentClienteConsultaListado() {
        global $strAction,$objTemplate,$objForm;
        
        $strCodigo = isset($_POST["codigo"]) ? db_escape(user_input_delmagic($_POST["codigo"],true)) : "";
        $strMunicipio = isset($_POST["municipio"]) ? db_escape(user_input_delmagic($_POST["municipio"],true)) : "";
        $strDireccion = isset($_POST["direccion"]) ? db_escape(user_input_delmagic($_POST["direccion"],true)) : "";
        $strDepartamento = isset($_POST["departamento"]) ? db_escape(user_input_delmagic($_POST["departamento"],true)) : "";
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
                            <th>&nbsp;</th>
                            <th>Cliente</th>
                            <th>Nombre</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Cif</th>
                            <th class="text-center">Caso/Crm</th>
                            <th class="text-center">Direccion</th>
                            <th class="text-center">Horario</th>
                            <th class="text-center">Piloto</th>
                            <th class="text-center">Fecha/Entrega</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $arrInfoClienteConsulta = $this->objModel->getInfoClienteConsulta($strCodigo,$strMunicipio,$strDireccion,$strEstado,$strPiloto,$strDepartamento,$strZona,$strFilas);
                        if( !empty($arrInfoClienteConsulta) ) {
                            reset($arrInfoClienteConsulta);
                            while( $arrC = each($arrInfoClienteConsulta) ) {
                                $strMd5 = md5($arrC["value"]["cliente"]);
                                ?>
                                <tr>
                                    <th>&nbsp;</th>
                                    <td style="text-align: center;">
                                        <input type="checkbox" name="check_modificar_cliente" id="check_modificar_cliente" data-content="" value="<?php print $arrC["value"]["cliente"] ?>">
                                    </td>
                                    <td><a href="<?php print $strAction; ?>?cliente=<?php print $strMd5; ?>"><?php print str_pad($arrC["value"]["cliente"],10,"0",STR_PAD_LEFT); ?></a></td>
                                    <td><?php print $arrC["value"]["nombre"]; ?></td>
                                    <td><?php print $arrC["value"]["estado_entrega"]; ?></td>
                                    <td style="text-align: center;"><?php print $arrC["value"]["cif"]; ?></td>
                                    <td style="text-align: center;"><?php print $arrC["value"]["crm"]; ?></td>
                                    <td style="text-align: center;"><?php print $arrC["value"]["direccion"]; ?></td>
                                    <td style="text-align: center;"><?php print $arrC["value"]["horario"]; ?></td>
                                    <td style="text-align: center;"><?php print $arrC["value"]["piloto"]; ?></td>
                                    <td style="text-align: center;"><?php print $arrC["value"]["fecha_entrega_cliente"]; ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script>
            $(function() {
                $("#tblClienteConsultaListado").DataTable({
                    "dom": 'Bfrtip',
                    "buttons": [
                        {
                            extend: 'excelHtml5',
                            exportOptions: {
                                columns: [ 2, 3, 4, 5, 6, 7, 8, 9 ]
                            }
                        },
                    ],
                    "order": [[ 1, "asc" ]],
                    "bPaginate": true,
                    "sLengthMenu": "Mostrar MENU registros",
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
                    "drawCallback": function ( settings ) {
                        $(".dataTables_scrollBody").scrollTop(0);

                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;

                    },
                    "aoColumnDefs": [
                        { "visible": false, "targets": [ 0 ] },
                        { "bSearchable": false, "aTargets": [ 2,3,4,5,6,7,8,9 ] },
                        { "bSortable": false, "aTargets": [ 0,1 ] }
                    ],
                    "aoColumns" : [
                        { "sWidth": "0%"},
                        { "sWidth": "5%"},
                        { "sWidth": "10%"},
                        { "sWidth": "10%"},
                        { "sWidth": "10%"},
                        { "sWidth": "10%"},
                        { "sWidth": "10%"},
                        { "sWidth": "10%"},
                        { "sWidth": "10%"},
                        { "sWidth": "10%"},
                        { "sWidth": "5%"},
                        //{ "sWidth": "5%"}
                    ]
                    
                });
            });
        </script>
        <?php
    }
    
}