<?php
require_once("modules/rutas/clases/clientes_ruta_model.php");
$yBeneficiario=0;
$beneficiarioGlobal;
class clientes_ruta_view{

    private $objModel;
    private $intCliente;
   
    public function __construct($intCliente){
        $this->objModel = new clientes_ruta_model($intCliente);
        $this->intCliente = $intCliente;
    }

    /**
    * INICIO DE METODOS PARA CONSULTA DE CLIENTE
    */

    public function drawButtonsClienteConsulta(){
        global $lang,$strAction,$objTemplate;
        $objTemplate->draw_button("btnModificarRuta","Nueva Ruta","fntAsignarRuta();","book","sm");
        $objTemplate->draw_button("btnModificarRuta","Configurar Ruta","fntModificarPiloto();","book","sm");
        $objTemplate->draw_button("btnRefrescar","Refrescar","document.location.href='{$strAction}'","refresh","sm");
    }

    public function drawContentClienteRuta() {
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
                <div class="row" id="divRadioRuta">
                    <div class="col-lg-4 col-lg-offset-2" >
                        <?php $objTemplate->drawTitleLeft("Que es lo que desea hacer?",false); ?>
                    </div>
                    <div class="col-lg-4">
                    <?php
                        $objForm->add_input_radio("modificar_piloto_check","modificar_piloto_check","modificar_piloto","Modificar", false, "piloto_check","",true);
                    ?>
                    </div>
                
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
                            <?php $objTemplate->drawTitleLeft("Modificar piloto",true); ?>
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
                <div id="divAsignarRuta">
                    <hr>
                    <div class="row">
                        <div class="col-lg-4 col-lg-offset-2">
                            <?php $objTemplate->drawTitleLeft("Ingrese Nombre de Ruta",false); ?>
                        </div>
                        <div class="col-lg-4" id="divtxtAsignarClienteRuta">
                            <?php
                                $objForm->add_input_text("asigRuta","","text-uppercase",true,""); 
                            ?>
                            <span id="spantxtModificarClienteRuta" class="help-block ocultar">
                                <span class="fa fa-warning"></span>&nbsp;Por favor ingrese nombre de la ruta.
                            </span>
                        </div>
                        <div class="col-lg-4 col-lg-offset-2">
                            <?php $objTemplate->drawTitleLeft("Ingrese Descripcion de Ruta",false); ?>
                        </div>
                        <div class="col-lg-4" id="divtxtAsignarClienteRuta">
                            <?php
                                $objForm->add_input_text("descrip","","text-uppercase",true,""); 
                            ?>
                        </div>
                    </div>
                </div>
            <?php 
        $objTemplate->draw_modal_close_content();
        $objTemplate->draw_modal_open_footer();
        ?>
            <div id="btnFntAgregar">
        <?php 
            $objTemplate->draw_button("btnModificarPilotoAceptar","Aceptar","fntAgregarRuta();","ok","sm");
            $objTemplate->draw_button("btnModificarPilotoCancelar","Cerrar","fntModificarPilotoCancelar();","remove","sm");
            ?>
            </div>
            <div id="btnFntModificar">
        <?php 
            $objTemplate->draw_button("btnModificarPilotoAceptar","Aceptar","fntModificarPilotoAceptar();","ok","sm");
            $objTemplate->draw_button("btnModificarPilotoCancelar","Cerrar","fntModificarPilotoCancelar();","remove","sm");
            ?>
            </div>
        <?php 
            
        $objTemplate->draw_modal_close_footer();
        $objTemplate->draw_modal_close();

        $objTemplate->draw_modal_open("divModalGoogleMaps", "","lg");
        $objTemplate->draw_modal_draw_header("Piloto", "", true,"");
        $objTemplate->draw_modal_open_content("divModalGoogleMapsContent");
            ?>
                <iframe src="https://embed.waze.com/es/iframe?
                zoom=6&lat=14.64750187835853&lon=-90.47747507986521&pin=1"
                width="100%" height="700"></iframe>
            <?php 
        $objTemplate->draw_modal_close_content();
        $objTemplate->draw_modal_open_footer();
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
                                <?php $objTemplate->drawTitleLeft("ruta/ Nombre de ruta "); ?>
                            </label>
                            <?php
                            $objForm->add_input_text("codigo","","",true);
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
                     data: "metodo=drawContentClienteRutaListado&"+$("#frmClienteConsulta").serialize(),
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
                    $("#spantxtAsignarClientePiloto").hide();
                    $("#divModalModificarPiloto").modal("show");
                    //$("#divModalModificarPilotoContent").modal("show");
                    $("#divRadioRuta").show();
                    $("#divAsignarRuta").hide();
                    $("#btnFntModificar").show();
                    $("#btnFntAgregar").hide();

                }else{
                    draw_Alert("danger","","Por favor selecciona el (los) ruta(s) que desees modificar.",true);
                }
            }

            function fntAsignarRuta(){
                
                $("#divModalModificarPiloto").modal("show");
                $("#divAsignarRuta").show();
                $("#divRadioRuta").hide();
                $("#divModificarPiloto").hide();
                $("#btnFntModificar").hide();
                $("#btnFntAgregar").show();
            }

            function fntModificarPilotoAceptar(){
                var cliente = [];
                var boolError = false;
                var modPiloto = $( "#modPiloto" ).val();
                var modRuta = $( "#modRuta" ).val();
                var asigPiloto = $( "#asigPiloto" ).val();
                var asigRuta = $( "#asigRuta" ).val();
                var descrip = $( "#descrip" ).val();
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
                                data: "metodo=processModificarRuta&cliente="+JSON.stringify(cliente)+"&piloto="+modPiloto+"&ruta="+modRuta+"&status="+status,
                                beforeSend: function(){
                                    showImgCoreLoading();
                                },
                                success: function( data ){
                                    hideImgCoreLoading();
                                    $( "#modRuta" ).val("");
                                    $( "#modPiloto" ).val("");
                                    $( "#asigPiloto" ).val("");
                                    $( "#asigRuta" ).val("");
                                    $( "#descrip" ).val("");
                                    $("#divModalModificarPiloto").modal("hide");
                                    fntClienteBusqueda();
                                    draw_Alert("succes","","Modificacion Exitosa.",true);
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
                                data: "metodo=processModificarRuta&cliente="+JSON.stringify(cliente)+"&piloto="+modPiloto+"&status="+status+"&strRuta="+asigRuta+"&descrip="+descrip,
                                beforeSend: function(){
                                    showImgCoreLoading();
                                },
                                success: function( data ){
                                    hideImgCoreLoading();
                                    $( "#modRuta" ).val("");
                                    $( "#modPiloto" ).val("");
                                    $( "#asigPiloto" ).val("");
                                    $( "#asigRuta" ).val("");
                                    $( "#descrip" ).val("");
                                    $("#divModalModificarPiloto").modal("hide");
                                    fntClienteBusqueda();
                                    draw_Alert("succses","","Eliminacion Exitosa.",true);
                                }
                            });
                        }
                    } 
                }else{
                    draw_Alert("danger","","Por favor selecciona la (las) ruta(s) que desees modificar.",true);
                    $("#divModalModificarPiloto").modal("hide");
                }
                
            }

            function fntAgregarRuta(){
                var boolError = false;
                var asigRuta = $( "#asigRuta" ).val();
                var descrip = $( "#descrip" ).val();
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
                        data: "metodo=processModificarRuta&cliente="+"&strRuta="+asigRuta+"&descrip="+descrip+"&status="+status,
                        beforeSend: function(){
                            showImgCoreLoading();
                        },
                        success: function( data ){
                            hideImgCoreLoading();
                            $( "#modPiloto" ).val("");
                            $( "#modRuta" ).val("");
                            $( "#asigPiloto" ).val("");
                            $( "#asigRuta" ).val("");
                            $( "#descrip" ).val("");
                            $("#divModalModificarPiloto").modal("hide");
                            fntClienteBusqueda();
                            draw_Alert("succes","","Ingreso Exitoso.",true);
                        }
                    });
                }
                    
            }
            function fntModificarPilotoCancelar(){
                $("#divModalModificarPiloto").modal("hide");
            }

            function fntGoogleMapsCancelar(){
                $("#divModalGoogleMaps").modal("hide");
            }
            
            function fntMostrarModificarPiloto(){
                if ($("input[name='modificar_piloto_check']:checked").val() == "modificar_piloto") {
                    $("#divModificarPiloto").show();
                    $("#divAsignarRuta").hide();
                }else if($("input[name='modificar_piloto_check']:checked").val() == "asignar_piloto") {
                    $("#divModificarPiloto").hide();
                    $("#divAsignarRuta").show();
                }else{
                    $("#divModificarPiloto").hide();
                    $("#divAsignarRuta").hide();
                }
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
                $("#divAsignarRuta").hide();
                $("input[name='modificar_piloto_check']").change(function (){
                    fntMostrarModificarPiloto();
                });
            });
        </script>
        <?php
    }

    public function drawContentClienteRutaListado() {
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
                <table class="table table-hover" id="tblRutaConsultaListado">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>Ruta</th>
                            <th>Nombre</th>
                            <th class="text-center">Descripcion</th>
                            <th class="text-center">Url</th>
                            <th class="text-center">Piloto</th>
                            <th class="text-center">Fecha Ruta</th>
                            <th class="text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $arrInfoClienteConsulta = $this->objModel->getInfoClienteRuta($strCodigo,$strEstado,$strPiloto,$strFilas);
                        if( !empty($arrInfoClienteConsulta) ) {
                            reset($arrInfoClienteConsulta);
                            while( $arrC = each($arrInfoClienteConsulta) ) {
                                $strMd5 = md5($arrC["value"]["ruta"]);
                                ?>
                                <tr>
                                    <th>&nbsp;</th>
                                    <td style="text-align: center;">
                                        <input type="checkbox" name="check_modificar_cliente" id="check_modificar_cliente" data-content="" value="<?php print $arrC["value"]["ruta"] ?>">
                                    </td>
                                    <td><b><?php print ($arrC["value"]["ruta"]); ?></b></td>
                                    <td><?php print $arrC["value"]["nombre"]; ?></td>
                                    <td><?php print $arrC["value"]["descrip"]; ?></td>
                                    <td style="text-align: center;"><?php print $arrC["value"]["ruta_url"]; ?></td>
                                    <td style="text-align: center;"><?php print $arrC["value"]["piloto"]; ?></td>
                                    <td style="text-align: center;"><?php print $arrC["value"]["ruta_fecha"]; ?></td>
                                    <td style="text-align: center;"><?php print $arrC["value"]["estado_entrega"]; ?></td>
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
                $("#tblRutaConsultaListado").DataTable({
                    "dom": 'Bfrtip',
                    "buttons": [
                        {
                            extend: 'excelHtml5',
                            exportOptions: {
                                columns: [ 2, 3, 4, 5, 6, 7, 8 ]
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
                        { "bSearchable": false, "aTargets": [ 2,3,4,5,6,7,8 ] },
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
                        { "sWidth": "10%"}
                       
                        //{ "sWidth": "5%"}
                    ]
                    
                });
            });
        </script>
        <?php
    }
    
}