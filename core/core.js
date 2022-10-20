
function getDocumentLayer(strName, objDoc) {
    var p,i,x=false;

    if(!objDoc) objDoc=document;

    if(objDoc[strName]) {
        x=objDoc[strName];
        if (!x.tagName) x = false;
    }

    if (!x && objDoc.all) x=objDoc.all[strName];
    for (i=0;!x && i<objDoc.forms.length; i++) x=objDoc.forms[i][strName];
    if (!x && objDoc.getElementById) x=objDoc.getElementById(strName);
    for (i=0;!x && objDoc.layers && i<objDoc.layers.length; i++) x=getDocumentLayer(strName,objDoc.layers[i].document);
    //for(i=0;!x && i<objDoc.all.length; i++) if (objDoc.all(i).id == strName || objDoc.all(i).name == strName) x = objDoc.all(i);

    return x;
}

function draw_Alert(strType, strTitle, strAlert, boolAddClose){

    PNotify.prototype.options.styling = "bootstrap3";
    PNotify.removeAll();
    var stack_center = {"dir1": "down", "dir2": "right", "firstpos1": 25, "firstpos2": ($(window).width() / 2) - (Number(PNotify.prototype.options.width.replace(/\D/g, '')) / 2)};
    var options = {
        text: strAlert,
        type: strType,
        buttons: {
            closer: true,
            closer_hover : false,
            sticker: false
        },
        stack: stack_center
    };
    if( strTitle.length > 0 )
        options["title"] = strTitle;
    new PNotify(options);

    /*
    boolAddClose = ( boolAddClose != undefined ) ? boolAddClose : true;

    if( !strType ) strType = "info";
    if( !strTitle ) strTitle = "";
    if( !strAlert ) strAlert = "";
    if( strAlert == "" ) strAlert = "&nbsp;";

    var strContent = "";
    strContent = "<div class=\"alert alert-"+strType+" fade in\">";

    if( boolAddClose ){
        strContent += "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
    }

    if( strTitle != "" ){
        strContent += "<strong>"+strTitle+"</strong>";
    }

    strContent += strAlert;
    strContent += "</div>";

    getDocumentLayer("divAlert").innerHTML = "";
    getDocumentLayer("divAlert").innerHTML = strContent;
    */

}

function removeAlertOnFooter(){

    getDocumentLayer("divAlert").innerHTML = "";

}

function controlDisplayLoadingImage(strAction){

    var strClass = ( strAction == "hide" ) ? "hide" : "";

    getDocumentLayer("imgCoreLoading").className = strClass;

}


function forms(){

    // private constructor
    var __construct = function(that) {}(this)

    //public property
    this.add_textarea = function(strFieldNameId, strValue, strClass, strExtraTags, strPlaceHolder, strToolTip){

        var strReturn = "";
        if( !strValue ) strValue = "";
        if( !strClass ) strClass = "";
        if( !strExtraTags ) strExtraTags = "";
        if( !strPlaceHolder ) strPlaceHolder = "";
        if( !strToolTip ) strToolTip = "";

        if( strToolTip != "" ){
            strToolTip = "rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\""+strToolTip+"\"";
        }
        if( strFieldNameId != "" )
            strReturn = "<textarea name=\""+strFieldNameId+"\" id=\""+strFieldNameId+"\" placeholder=\""+strPlaceHolder+"\" "+strExtraTags+" data-content=\"\" class=\"form-control "+strClass+"\" "+strToolTip+" >"+strValue+"</textarea>";

        return strReturn;

    }

    this.add_input_hidden = function(strFieldNameId, strValue, strExtraTags){

        var strReturn = "";
        if( !strValue ) strValue = "";
        if( !strExtraTags ) strExtraTags = "";

        if( strFieldNameId != "" )
            strReturn = "<input type=\"hidden\" name=\""+strFieldNameId+"\" id=\""+strFieldNameId+"\" value=\""+strValue+"\" "+strExtraTags+"/>";

        return strReturn;

    }

    this.add_input_password = function(strFieldNameId, strClass, strExtraTags, strPlaceHolder, strToolTip){

        var strReturn = "";
        if( !strClass ) strClass = "";
        if( !strExtraTags ) strExtraTags = "";
        if( !strPlaceHolder ) strPlaceHolder = "";
        if( !strToolTip ) strToolTip = "";

        if( strToolTip != "" ){
            strToolTip = "rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\""+strToolTip+"\"";
        }
        if( strFieldNameId != "" )
            strReturn = "<input type=\"password\" name=\""+strFieldNameId+"\" id=\""+strFieldNameId+"\" "+strExtraTags+" "+strToolTip+" placeholder=\""+strPlaceHolder+"\" data-content=\"\" class=\"form-control "+strClass+"\" />";

        return strReturn;

    }

    this.add_input_checkbox = function(strFieldNameId, boolChecked, strClass, strExtraTags, strToolTip){

        var strReturn = "";
        boolChecked = ( boolChecked != undefined ) ? boolChecked : false;
        if( !strClass ) strClass = "";
        if( !strExtraTags ) strExtraTags = "";
        if( !strToolTip ) strToolTip = "";

        strChecked = boolChecked ? "checked=\"checked\"" : "";

        if( strToolTip != "" ){
            strToolTip = "rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\""+strToolTip+"\"";
        }
        if( strFieldNameId != "" )
            strReturn = "<input type=\"checkbox\" name=\""+strFieldNameId+"\" id=\""+strFieldNameId+"\" "+strChecked+" "+strExtraTags+" class=\""+strClass+"\" "+strToolTip+" data-content=\"\" />";

        return strReturn;

    }

    this.add_input_text = function(strFieldNameId, strValue, strClass, strExtraTags, strPlaceHolder, strToolTip){

        var strReturn = "";
        if( !strValue ) strValue = "";
        if( !strClass ) strClass = "";
        if( !strExtraTags ) strExtraTags = "";
        if( !strPlaceHolder ) strPlaceHolder = "";
        if( !strToolTip ) strToolTip = "";

        if( strToolTip != "" ){
            strToolTip = "rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\""+strToolTip+"\"";
        }
        if( strFieldNameId != "" )
            strReturn = "<input type=\"text\" name=\""+strFieldNameId+"\" id=\""+strFieldNameId+"\" value=\""+strValue+"\" placeholder=\""+strPlaceHolder+"\" "+strExtraTags+"  data-content=\"\" class=\"form-control "+strClass+"\" "+strToolTip+" />";

        return strReturn;

    }

    this.add_select = function(strFieldNameId, arrContenido, strClass, strExtraTags, strToolTip){

        var strReturn = "";
        if( !strClass ) strClass = "";
        if( !strExtraTags ) strExtraTags = "";
        if( !strToolTip ) strToolTip = "";

        if( strToolTip != "" ){
            strToolTip = "rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\""+strToolTip+"\"";
        }

        if( strFieldNameId != "" ){
            strReturn = "<select name=\""+strFieldNameId+"\" id=\""+strFieldNameId+"\" data-content=\"\" class=\"form-control "+strClass+"\" "+strExtraTags+" "+strToolTip+" >";

            for( i in arrContenido ){

                if( arrContenido[i]["options"] ){

                    strReturn += "<optgroup label=\""+arrContenido[i]["nombre"]+"\">";

                    for( j in arrContenido[i]["options"] ){

                        boolSelected = ( arrContenido[i]["options"][j]["selected"] ) ? arrContenido[i]["options"][j]["selected"] : false;
                        strTexto = ( arrContenido[i]["options"][j]["texto"] ) ? arrContenido[i]["options"][j]["texto"] : arrContenido[i]["options"][j];
                        strSelected = boolSelected ? "selected" : "";

                        strReturn += "<option value=\""+j+"\" "+strSelected+">"+strTexto+"</option>";

                    }

                    strReturn += "</optgroup>";

                }
                else{

                    boolSelected = ( arrContenido[i]["selected"] ) ? arrContenido[i]["selected"] : false;
                    strTexto = ( arrContenido[i]["texto"] ) ? arrContenido[i]["texto"] : arrContenido[i];
                    strSelected = boolSelected ? "selected" : "";

                    strReturn += "<option value=\""+i+"\" "+strSelected+">"+strTexto+"</option>";

                }
            }

            strReturn += "</select>";
        }

        return strReturn;

    }

    this.draw_icon = function(strId, strOnClick, strIcon, strTamanio, boolPointer, strExtraClass){

        var strReturn = "";
        if( !strId ) strId = "";
        if( !strOnClick ) strOnClick = "";
        if( !strIcon ) strIcon = "th-list";
        if( !strTamanio ) strTamanio = "sm";
        if( !strExtraClass ) strExtraClass = "glyphicon-color";
        boolPointer = ( boolPointer != undefined ) ? boolPointer : false;
        strExtraClass += boolPointer ? " cursor" : "";

        strReturn += "<span id=\""+strId+"\" onclick=\""+strOnClick+"\" class=\"glyphicon glyphicon-"+strIcon+" btn-"+strTamanio+" "+strExtraClass+"\"></span>";

        return strReturn;

    }

    this.draw_icon_fa = function(strId, strIcon, strOnClick, boolPointer, strTamano, boolFixedWidth, boolList, strBorderedPulled, boolSpinning, strRotatedFlipped, strExtraClass){

        var strReturn = "";
        if( !strId ) strId = "";
        if( !strIcon ) strIcon = "";
        if( !strOnClick ) strOnClick = "";
        boolPointer = ( boolPointer != undefined ) ? boolPointer : false;
        if( !strTamano ) strTamano = "";
        boolFixedWidth = ( boolFixedWidth != undefined ) ? boolFixedWidth : false;
        boolList = ( boolList != undefined ) ? boolList : false;
        if( !strBorderedPulled ) strBorderedPulled = "";
        boolSpinning = ( boolSpinning != undefined ) ? boolSpinning : false;
        if( !strRotatedFlipped ) strRotatedFlipped = "";
        if( !strExtraClass ) strExtraClass = "fa-color";

        strTamano = (strTamano.length == 0) ? "" : " fa-"+strTamano;
        strFixedWidth = boolFixedWidth ? " fa-fw" : "";
        strList = boolList ? "fa-li " : "";
        strBorderedPulled = (strBorderedPulled.length == 0) ? "" : " "+strBorderedPulled;
        strSpinning = boolSpinning ? " fa-spin" : "";
        strRotatedFlipped = (strRotatedFlipped.length == 0) ? "" : " fa-"+strRotatedFlipped;
        strPointer = boolPointer ? " cursor" : "";

        strReturn += "<i id='"+strId+"' class='"+strList+"fa fa-"+strIcon+strTamano+strFixedWidth+strBorderedPulled+strSpinning+strRotatedFlipped.strPointer+" "+strExtraClass+"' onclick='"+strOnClick+"'></i>";

        return strReturn;

    }

    this.draw_image = function(strId, strImagePath, strOnClick, strStyles, strExtraTags, strTooltip){

        var strReturn = "";
        if( !strId ) strId = "";
        if( !strImagePath ) strImagePath = "";
        if( !strOnClick ) strOnClick = "";
        if( !strStyles ) strStyles = "";
        if( !strExtraTags ) strExtraTags = "";
        if( !strTooltip ) strTooltip = "";

        if( strTooltip != "" ){
            strTooltip = "rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\""+strTooltip+"\"";
        }

        if( strStyles != "" ){
            strStyles = "style=\""+strStyles+"\"";
        }

        strReturn = "<img id=\""+strId+"\" src=\""+strImagePath+"\" "+strTooltip+"  "+strExtraTags+"  "+strStyles+">";

        return strReturn;

    }

    this.add_input_radio = function(strFieldName, strFieldId, strValue, strText, boolChecked, strClass, strExtraTags, strToolTip){

        var strReturn = "";
        boolChecked = ( boolChecked != undefined ) ? boolChecked : false;
        if( !strValue ) strValue = "";
        if( !strText ) strText = "";
        if( !strClass ) strClass = "";
        if( !strExtraTags ) strExtraTags = "";
        if( !strToolTip ) strToolTip = "";

        strChecked = boolChecked ? "checked=\"checked\"" : "";

        if( strToolTip != "" ){
            strToolTip = "rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\""+strToolTip+"\"";
        }
        if( strFieldName != "" )
            strReturn = "<span id=\"divRadio"+strFieldId+"\"><input type=\"radio\" name=\""+strFieldName+"\" id=\""+strFieldId+"\" value=\""+strValue+"\" "+strChecked+" "+strExtraTags+" class=\""+strClass+"\" "+strToolTip+" data-content=\"\" />&nbsp;"+strText+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";

        return strReturn;

    }

    this.add_file = function(strFieldNameId, strClass, boolFileTypeOnclick, strFileTypeOnclick, strPlaceHolder, strToolTip){

        var strReturn = "";
        if( !strClass ) strClass = "";
        if( !strToolTip ) strToolTip = "";
        if ( !boolFileTypeOnclick ) boolFileTypeOnclick = false;
        if ( !strFileTypeOnclick ) strFileTypeOnclick = "";


        if( strToolTip != "" ){
            strToolTip = "rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\""+strToolTip+"\"";
        }

        if( strFieldNameId != "" ){

            if ( boolFileTypeOnclick ){

                strReturn += "  <div id=\"Content"+strFieldNameId+"\">"+
                                    "<table class=\"table-layout\">"+
                                        "<tr>"+
                                            "<td width=\"80%\" style=\"border: 1px solid black; text-align: right;position: relative;\">"+
                                                "<div style=\"position:absolute; top:0px; left: 0px; width: 100%; z-index:1\">"+
                                                    "<input type=\"file\" id=\""+strFieldNameId+"\" class=\"form-control\" name=\""+strFieldNameId+"\" value=\"\"  style=\"width: 1px; height: 10px;\">"+
                                                "</div>"+
                                                "<div style=\"position:absolute; top:0px; left: 0px; width: 100%; z-index:2;\">"+
                                                    "<input type=\"\" id=\"Divtext"+strFieldNameId+"\" readonly=\"readonly\" class=\"form-control\" value=\"\" style=\"background-color: white; border-radius: none; border: none;height: auto;\">"+
                                                "</div>"+
                                            "</td>"+
                                            "<td id=\"tdNombre"+strFieldNameId+"\" width=\"20%\" style=\" cursor: pointer; background: #E0E0E0; color: #808080; font-size: 14px;  padding: 1px 25px 1px 5px; border: 1px solid #E0E0E0;\" >Examinar...</td>"+
                                        "</tr>"+
                                    "</table>"+
                                "</div>";
                              if ( strFileTypeOnclick != "" ){
                                  $("#"+strFileTypeOnclick+"").click(function (){
                                      $("#"+strFieldNameId+"").click();
                                  });
                              }

                              $("#tdNombre"+strFieldNameId+"").click(function (){
                                  $("#"+strFieldNameId+"").click();
                              });

            }
            else{

                strReturn += "<input type=\"file\" id=\""+strFieldNameId+"\" name=\""+strFieldNameId+"\" value=\"\">";
                if ( strFileTypeOnclick != "" ) $("#"+strFileTypeOnclick+"").click(function (){ $("#"+strFieldNameId+"").click();});

            }

        }

        return strReturn;


    }

}

function addDynamicRow(strTableId, arrElements, arrOptions) {

    if( !arrOptions ) arrOptions = new Array();
    if( !arrElements ) arrElements = new Array();

    if( arrElements ){

        var strHtml = "";
        strRowId = ( arrOptions["row_id"] ) ? arrOptions["row_id"] : "";

        strHtml = "<tr>";

        for( i in arrElements ){

            var intColspan = ( arrElements[i]["colspan"] ) ? arrElements[i]["colspan"] : "";
            var intRowspan = ( arrElements[i]["rowspan"] ) ? arrElements[i]["rowspan"] : "";
            var strClass = ( arrElements[i]["class"] ) ? arrElements[i]["class"] : "";
            var strCellId = ( arrElements[i]["cell_id"] ) ? arrElements[i]["cell_id"] : "";

            strHtml += "<td id=\""+strCellId+"\" class=\""+strClass+"\" colspan=\""+intColspan+"\" rowspan=\""+intRowspan+"\">";
            strHtml += ( arrElements[i]["contenido"] && arrElements[i]["contenido"] != "" ) ? arrElements[i]["contenido"] : "&nbsp;";
            strHtml += "</td>";

        }

        strHtml += "</tr>";

        $("#"+strTableId+" > tbody").append(strHtml);

    }

}

function drawPreviewImage( strInputFileNameOrId, strImgTagetNameOrId ){

    if( !getDocumentLayer(strInputFileNameOrId) ){
        $("input[name='"+strInputFileNameOrId+"']").attr("id",strInputFileNameOrId);
    }

    if ( getDocumentLayer(strInputFileNameOrId).files && getDocumentLayer(strInputFileNameOrId).files[0]) {

        var reader = new FileReader();

        reader.onload = function (e) {
            if( $("img[id='"+strImgTagetNameOrId+"']") ){
                $("img[id='"+strImgTagetNameOrId+"']").attr("src", e.target.result);
            }
            else if( $("img[name='"+strImgTagetNameOrId+"']") ){
                $("img[name='"+strImgTagetNameOrId+"']").attr("src", e.target.result);
            }
        }

        reader.readAsDataURL( getDocumentLayer(strInputFileNameOrId).files[0] );
    }
}

function getFileSize( strInputFileNameOrId ){
    var intSize = 0;

    if( !getDocumentLayer(strInputFileNameOrId) ){
        $("input[name='"+strInputFileNameOrId+"']").attr("id",strInputFileNameOrId);
    }

    if ( getDocumentLayer(strInputFileNameOrId).files && getDocumentLayer(strInputFileNameOrId).files[0]) {
        intSize = getDocumentLayer(strInputFileNameOrId).files[0].size;
    }
    return intSize;
}

function fntVerificarExtension( strInputFileNameOrId, arrExtensiones ){
    // enviar las extensiones deseadas a verificar en el key

    var boolValido = false;

    var expresion = /(?:\.([^.]+))?$/;
    var extension = "";

    if( !getDocumentLayer(strInputFileNameOrId) ){
        $("input[name='"+strInputFileNameOrId+"']").attr("id",strInputFileNameOrId);
    }

    if( getDocumentLayer(strInputFileNameOrId).value.length > 0 ){
        var extension = expresion.exec(getDocumentLayer(strInputFileNameOrId).value)[1];
        if( arrExtensiones[extension] ){
            boolValido = true;
        }
    }

    return boolValido;
}

function fntCentrarLoading() {
    $('#imgCoreLoading').css({
        position: 'fixed',
        left: ($(window).width() - $('#imgCoreLoading').outerWidth())/2,
        top: (($(window).height() - $('#imgCoreLoading').outerHeight())/2)
    });
}

function showImgCoreLoading(){
    //fntCentrarLoading();
    $("#imgCoreLoading").removeClass("ocultar");
}

function hideImgCoreLoading(){
    $("#imgCoreLoading").addClass("ocultar");
    /*if( navigator.userAgent.indexOf('Chrome') == -1 ) {
        setTimeout(function(){ $("#imgCoreLoading").addClass("ocultar"); }, 250);
    }
    else {
        setTimeout(function(){ $("#imgCoreLoading").addClass("ocultar"); }, 1000);
    }*/
}

function drawMenu(strAction,strBusqueda) {
    $.ajax({
        url: strAction,
        async: false,
        data:{
            drawMenu : true,
            busqueda : strBusqueda
        },
        type:'post',
        dataType:'html',
        beforeSend:function(){
            showImgCoreLoading();
        },
        success:function(data){
            hideImgCoreLoading();
            $('#divMenu').html("");
            $('#divMenu').html(data);
            //$('#side-menu').metisMenu({toggle:true}).removeClass("hide");
            $.AdminLTE.tree('.sidebar');
        }
    });
}

function core_FormatMonto(monto,intDec) {
    var comas  =/,/ig;
    var strTotal = $.trim(monto) + '';

    if (!intDec) intDec = 2;
    strTotal = strTotal.replace(comas,'');
    strTotal = strTotal * 1;

    var intTenExp =    Math.pow(10,intDec);
    strTotal = Math.round(strTotal * intTenExp)/intTenExp;
    var addMinus = false;
    if (strTotal < 0 ){
        strTotal = Math.abs(strTotal);
        addMinus = true;
    }
    return ((addMinus ? '-':'')+(core_OutInts(Math.floor(strTotal-0) + '', true) + core_OutCents(strTotal - 0,intDec)));
}

function core_FormatMontoSincomas(monto, intDec) {
    var strTotal = "";
    var comas  =/,/ig;
    if (!intDec) intDec = 2;
    var intTenExp =    Math.pow(10,intDec);

    monto = $.trim(monto);
    monto = monto.replace(comas,'');
    monto = Math.round(monto * intTenExp)/intTenExp;

    strTotal = monto + "";
    strTotal = strTotal.replace(comas,'');

    return core_OutInts(Math.floor(strTotal-0) + '', false) + core_OutCents(strTotal - 0, intDec);
}

function core_OutInts(number, boolAddComma) {
    if (number.length <= 3)
        return (number == '' ? '0' : number);
    else {
        var mod = number.length%3;
        var output = (mod == 0 ? '' : (number.substring(0,mod)));
        for (i=0 ; i < Math.floor(number.length/3) ; i++) {
            if (((mod ==0) && (i ==0)) || !boolAddComma)
            output+= number.substring(mod+3*i,mod+3*i+3);
            else
            output+= ',' + number.substring(mod+3*i,mod+3*i+3);
        }
        return (output);
    }
}

function core_OutCents(amount,intDec) {
    if (!intDec)
        intDec = 2;
    var intTenExp =    Math.pow(10,intDec);
    amount = Math.round( ( (amount) - Math.floor(amount) ) *intTenExp);
    var strZeros = "";
    for (i=1;i<=intDec;i++){
        if (amount < Math.pow(10,i-1))
            strZeros+="0";
    }
    if (amount==0)
        return "."+strZeros;
    else
        return "."+strZeros+amount;
}

function core_VerificarFechas( strNombreInputFechaInicio, strNombreInputFechaFin ){
    var boolCorrecto = true;

    intFechaInicioAnio = parseInt($("input[name='"+strNombreInputFechaInicio+"_anio']").val());
    strFechaInicioAnio = intFechaInicioAnio.toString();
    intFechaInicioMes = parseInt($("input[name='"+strNombreInputFechaInicio+"_mes']").val());
    strFechaInicioMes = intFechaInicioMes.toString();
    if( strFechaInicioMes.length == 1 ){
        strFechaInicioMes = "0"+strFechaInicioMes;
    }
    intFechaInicioDia = parseInt($("input[name='"+strNombreInputFechaInicio+"_dia']").val());
    strFechaInicioDia = intFechaInicioDia.toString();
    if( strFechaInicioDia.length == 1 ){
        strFechaInicioDia = "0"+strFechaInicioDia;
    }

    intFechaFinAnio = parseInt($("input[name='"+strNombreInputFechaFin+"_anio']").val());
    strFechaFinAnio = intFechaFinAnio.toString();
    intFechaFinMes = parseInt($("input[name='"+strNombreInputFechaFin+"_mes']").val());
    strFechaFinMes = intFechaFinMes.toString();
    if( strFechaFinMes.length == 1 ){
        strFechaFinMes = "0"+strFechaFinMes;
    }
    intFechaFinDia = parseInt($("input[name='"+strNombreInputFechaFin+"_dia']").val());
    strFechaFinDia = intFechaFinDia.toString();
    if( strFechaFinDia.length == 1 ){
        strFechaFinDia = "0"+strFechaFinDia;
    }

    strFechaInicio = strFechaInicioAnio+strFechaInicioMes+strFechaInicioDia;
    intFechaInicio = parseInt(strFechaInicio);
    strFechaFin = strFechaFinAnio+strFechaFinMes+strFechaFinDia;
    intFechaFin = parseInt(strFechaFin);

    if( intFechaInicio > intFechaFin ){
        boolCorrecto = false;
    }

    return boolCorrecto;
}

function core_FechaValida( strNombreInputFecha ) {
    d = getDocumentLayer(strNombreInputFecha+"_dia") ? parseInt($("#"+strNombreInputFecha+"_dia").val()) : 0;
    m = getDocumentLayer(strNombreInputFecha+"_mes") ? parseInt($("#"+strNombreInputFecha+"_mes").val()) : 0;
    y = getDocumentLayer(strNombreInputFecha+"_anio") ? parseInt($("#"+strNombreInputFecha+"_anio").val()) : 0;
    return m > 0 && m < 13 && y > 0 && y < 32768 && d > 0 && d <= (new Date(y, m, 0)).getDate();
}


function core_nit_valido(nit) {
    if (!nit) {
        return true;
    }

    var nitRegExp = new RegExp('^[0-9]+(-?[0-9kK])?$');

    if (!nitRegExp.test(nit)) {
        return false;
    }

    nit = nit.replace(/-/, '');
    var lastChar = nit.length - 1;
    var number = nit.substring(0, lastChar);
    var expectedCheker = nit.substring(lastChar, lastChar + 1).toLowerCase();

    var factor = number.length + 1;
    var total = 0;

    for (var i = 0; i < number.length; i++) {
        var character = number.substring(i, i + 1);
        var digit = parseInt(character, 10);

        total += (digit * factor);
        factor = factor - 1;
    }

    var modulus = (11 - (total % 11)) % 11;
    var computedChecker = (modulus == 10 ? "k" : modulus.toString());

    return expectedCheker === computedChecker;
}