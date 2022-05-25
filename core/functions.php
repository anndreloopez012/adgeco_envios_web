<?php

function drawDebug($ThisVar, $VariableName = "", $ShowWhat=0) {
    global $arrConfigSite;

    $boolIsAdmin = ( isset($_SESSION["hml"]["class"]) && $_SESSION["hml"]["class"] == "admin" );
    if (!$arrConfigSite["config"]["local"] && !$boolIsAdmin) {
        core_SendScriptInfoToWebmaster("DRAW DEBUG!!!:" . __FILE__ . " línea " . __LINE__);
        return;
    }

    $strType = gettype($ThisVar);
    $strPreOpen = "";
    $strPreClose = "";
    if (!is_string($ThisVar)) {
        $strPreOpen = "<pre>";
        $strPreClose = "</pre>";
    }

    echo "\n<hr>";
    if (!empty($VariableName)) echo "<b><i> $VariableName</b></i> ";
    echo "Var  Type of var = <b>" . $strType . "</b><br><br>\n{$strPreOpen}";
    if ($ShowWhat==0) {
        if (is_bool($ThisVar)) print_r(($ThisVar)?"true":"false");
        else print_r($ThisVar);
    }

    else if ($ShowWhat==1) {
        print_r(array_values($ThisVar));
    }
    else if ($ShowWhat==2) {
        print_r(array_keys($ThisVar));
    }
    print_r("<hr>{$strPreClose}\n");
}

function debugQuery($strQuery, $boolShowQueryString = true, $arrFilter = false, $boolExplain = false, $objConnection = false) {

    $boolFilter = is_array($arrFilter);
    if ($boolExplain)
        $strQuery = "EXPLAIN\n" . $strQuery;
    $qTMP = db_query($strQuery, false, $objConnection);
    ?>
    <div  style="position:relative; z-index:20; background-color:white; color:black;">
    <?php
    if ($boolShowQueryString)
        print_r("<hr>" . nl2br($strQuery) . "<br><br>");
    ?>
        <table border="1" cellspacing="0" cellpadding="2" align="center">
    <?php
    $boolFirstRow = true;
    $listFields = db_get_fields($qTMP);
    if ($rTMP = db_fetch_array($qTMP)) {
        do {
            if ($boolFirstRow) {
                $strRow = "<tr>";
                reset($listFields);
                foreach ($listFields as $key => $entry) {
                    $strRow.="<th>{$key}</th>";
                }
                $strRow.= "</tr>\n";
                echo $strRow;
                $boolFirstRow = false;
                reset($rTMP);
            }
            if ($boolFilter) {
                $boolOK = true;
                while ($arrFItem = each($arrFilter)) {
                    if ($rTMP[$arrFItem["key"]] != $arrFItem["value"])
                        $boolOK = false;
                }
                reset($arrFilter);
                if (!$boolOK)
                    continue;
            }
            $strRow = "<tr>";
            reset($listFields);
            foreach ($listFields as $key => $entry) {
                $strValue = $rTMP[$key];
                if (strlen($rTMP[$key]) == 0) {
                    $strValue = "&nbsp;";
                }
                $strRow.="<td>{$strValue}</td>";
            }
            $strRow.= "</tr>\n";
            echo $strRow;
        } while ($rTMP = db_fetch_array($qTMP));
    }
    ?>
        </table><br><?php print db_num_rows($qTMP); ?> rows<hr>
    </div>
    <?php
    db_free_result($qTMP);
}

function core_get_idioma() {

    $intIdioma = 1;

    if( core_is_login() )
        $intIdioma = $_SESSION["hml"]["idioma"];

    return $intIdioma;

}

function core_load_configuracion($strModulo, $intIdioma = 0) {

    global $cfg;

    if( !$intIdioma ) $intIdioma = core_get_idioma();
    $intIdioma = intval($intIdioma);

    $strQuery = "SELECT modulo.modulo, configuracion.codigo, configuracion.valor,
                        configuracion_idioma.nombre
                 FROM   configuracion_idioma,
                        configuracion,
                        modulo
                 WHERE  modulo.codigo = '{$strModulo}'
                 AND    configuracion_idioma.idioma = {$intIdioma}
                 AND    configuracion_idioma.modulo = configuracion.modulo
                 AND    configuracion_idioma.codigo = configuracion.codigo
                 AND    configuracion.modulo = modulo.modulo
                 ORDER  BY configuracion.codigo, configuracion.valor";

    $qTMP = db_query($strQuery);
    while( $rTMP = db_fetch_assoc($qTMP) ) {
        $cfg[$strModulo][$rTMP["codigo"]]["nombre"] = $rTMP["nombre"];
        $cfg[$strModulo][$rTMP["codigo"]]["valor"] = $rTMP["valor"];
    }
    db_free_result($qTMP);

}

function core_load_lang($strModulo, $intIdioma = 0, $strLangs = "") {

    global $lang;

    if( !$intIdioma ) $intIdioma = core_get_idioma();
    $intIdioma = intval($intIdioma);

    $strFilter = "";
    if( !empty($strLangs) )
        $strFilter = "AND lang.lang IN({$strLangs})";

    $strQuery = "SELECT lang.lang, lang_idioma.valor
                 FROM   lang, lang_idioma, modulo
                 WHERE  modulo.codigo = '{$strModulo}'
                 {$strFilter}
                 AND    lang.lang = lang_idioma.lang
                 AND    lang.modulo = lang_idioma.modulo
                 AND    lang.modulo = modulo.modulo
                 ORDER  BY lang.lang, lang_idioma.valor";

    $qTMP = db_query($strQuery);
    while( $rTMP = db_fetch_assoc($qTMP) ) {
        $lang[$strModulo][$rTMP["lang"]] = $rTMP["valor"];
    }
    db_free_result($qTMP);

}

function sqlGetValueFromKey($strSQL, $boolFalseOnEmpty = false, $boolForceArray = false, $boolLogError = true, $objConnection = false) {
    $return = false;

    $qList = db_query($strSQL . " LIMIT 0,1 ", $boolLogError, $objConnection);

    $listFields = db_get_fields($qList);
    if ($rList = db_fetch_array($qList)) {
        if (db_num_fields($qList) == 1 && !$boolForceArray) {
            $return = $rList[0];
            if ($boolFalseOnEmpty) {
                $strTMP = html_entity_decode($return);
                $strTMP = strip_tags($strTMP);

                $strTMP = str_replace(" ", "", $strTMP);
                $strTMP = trim($strTMP);
                $strTMP = str_replace(" ", "", $strTMP);
                $strTMP = trim($strTMP);

                if (empty($return) || empty($strTMP))
                    $return = false;
            }
        }
        else {
            $return = array();
            foreach ($listFields as $field) {
                $return[$field['name']] = $rList[$field['name']];
            }
        }
    }
    db_free_result($qList);
    return $return;
}

function user_input_delmagic($strInput, $boolUTF8Decode = false) {
    //htmlspecialchars_decode
    //html_entity_decode
    $strInput = trim($strInput);
    if (get_magic_quotes_gpc()) {
        $strInput = stripslashes($strInput);
    }

    // 20090515 AG: Esto arruina los gets... pero sirve con los posts de ajax...
    if ($boolUTF8Decode && mb_detect_encoding($strInput) == "UTF-8") {
        $strInput = utf8_decode($strInput);
    }
    return $strInput;
}

function user_input_delmagic_reference(&$strInput, $intKey = 0) {
    $strInput = trim($strInput);
    if (get_magic_quotes_gpc()) {
        $strInput = stripslashes($strInput);
    }
    /*
      if (mb_detect_encoding($strInput)=="UTF-8") {
      $strInput = utf8_decode($strInput);
      }
     */
    return $strInput;
}

function core_getInfoMenu($boolPrivateInfo = true, $boolPublicInfo = false, $intPersona = 0, $strBusqueda = "", $intAcceso = 0) {

    $arrMenu = array();
    $arrMenuFiltro = array();
    $strBusqueda = trim($strBusqueda);
    $boolBusqueda = !empty($strBusqueda);

    $strFrom = "";
    $strWhere = "";
    $strUnion = "";
    $strWhereFiltro = "";

    $intPersona = intval($intPersona);
    if( core_is_login() && !$intPersona ){
        $intPersona = intval($_SESSION["hml"]["persona"]);
    }

    $intIdioma = core_get_idioma();

    if( core_is_login() ) {

        if( $_SESSION["hml"]["tipo_usuario"] != "admin" ) {
            $strFrom = ", perfil, perfil_acceso, persona_perfil";
            $strWhere = "  AND perfil.activo = 'Y'
                            AND perfil_acceso.tipo_acceso = 1
                            AND persona_perfil.persona = {$intPersona}
                            AND perfil_acceso.perfil = perfil.perfil
                            AND perfil_acceso.acceso = acceso.acceso
                            AND persona_perfil.perfil = perfil.perfil";
        }

        if( $boolBusqueda )
            $strWhereFiltro .= getFilterQuery("acceso_idioma.nombre_menu,acceso_idioma.nombre_menu",$strBusqueda,true,true);

    }
    else {
        $boolPrivateInfo = false;
    }



    $strWhere .= ($boolPrivateInfo) ?  ( " AND acceso.privado = 'Y'". ($boolPublicInfo ? "" : " AND acceso.publico = 'N' ") ) : ( $boolPublicInfo ? "" : " AND acceso.privado = 'N'" );
    $strWhere .= ($boolPublicInfo) ? ( " AND acceso.publico = 'Y'". ( $boolPrivateInfo ? "" : " AND acceso.privado = 'N'" ) ) : ( $boolPrivateInfo ? "" : " AND acceso.publico = 'N'" );

    if( $boolBusqueda ) {

        $strQuery = "SELECT modulo.modulo, modulo_idioma.nombre nombreModulo, modulo.orden orden_modulo,
                            acceso.acceso, acceso.acceso_pertenece, acceso.publico, acceso.privado, acceso.icono,
                            acceso_idioma.nombre_menu nombreAcceso, acceso.path, acceso.orden orden_acceso
                     FROM   acceso_idioma,
                            acceso,
                            modulo_idioma,
                            modulo {$strFrom}
                     WHERE  modulo.activo = 'Y'
                     AND    acceso.activo = 'Y'
                     AND    acceso.menu = 'Y'
                     AND    acceso.acceso_extra = 'N'
                     AND    acceso_idioma.idioma = {$intIdioma}
                     AND    modulo_idioma.idioma = {$intIdioma}
                     {$strWhere}
                     {$strWhereFiltro}
                     AND    modulo_idioma.modulo = modulo.modulo
                     AND    modulo.modulo = acceso.modulo
                     AND    acceso.acceso = acceso_idioma.acceso

                     ORDER  BY orden_modulo, nombreModulo, acceso_pertenece IS NULL DESC, orden_acceso, nombreAcceso";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ) {

            $intIndex = is_null($rTMP["acceso_pertenece"]) ? 0 : intval($rTMP["acceso_pertenece"]);
            if( $intIndex > 0 && !empty($rTMP["path"]) ) {
                $arrMenuFiltro[$rTMP["modulo"]]["contenido"][$rTMP["acceso_pertenece"]]["accesos"][$rTMP["acceso"]]["nombre"] = $rTMP["nombreAcceso"];
            }
            elseif( $intIndex == 0 && !empty($rTMP["path"])) {
                $arrMenuFiltro[$rTMP["modulo"]]["acceso"][$rTMP["acceso"]]["nombre"] = $rTMP["nombreAcceso"];
            }

        }
        db_free_result($qTMP);

    }

    $strQuery = "SELECT modulo.modulo,
                        modulo.orden AS orden_modulo,
                        modulo.icono AS iconoModulo,
                        modulo_idioma.nombre AS nombreModulo,
                        acceso.acceso,
                        acceso.codigo,
                        acceso.acceso_pertenece,
                        acceso.publico,
                        acceso.privado,
                        acceso.icono,
                        acceso.path,
                        acceso.orden orden_acceso,
                        acceso_idioma.nombre_menu nombreAcceso
                 FROM   acceso_idioma,
                        acceso,
                        modulo_idioma,
                        modulo {$strFrom}
                 WHERE  modulo.activo = 'Y'
                 AND    acceso.activo = 'Y'
                 AND    acceso.menu = 'Y'
                 AND    acceso.acceso_extra = 'N'
                 AND    acceso_idioma.idioma = {$intIdioma}
                 AND    modulo_idioma.idioma = {$intIdioma}
                 {$strWhere}
                 AND    modulo_idioma.modulo = modulo.modulo
                 AND    modulo.modulo = acceso.modulo
                 AND    acceso.acceso = acceso_idioma.acceso

                 ORDER  BY orden_modulo, nombreModulo, acceso_pertenece IS NULL DESC, orden_acceso, nombreAcceso";
    $qTMP = db_query($strQuery);

    if( $boolBusqueda ) {
        while( $rTMP = db_fetch_assoc($qTMP) ) {
            if( isset($arrMenuFiltro[$rTMP["modulo"]]) ) {
                $arrMenu[$rTMP["modulo"]]["nombre"] = $rTMP["nombreModulo"];
                $arrMenu[$rTMP["modulo"]]["icono"] = $rTMP["iconoModulo"];
            }
            $intIndex = is_null($rTMP["acceso_pertenece"]) ? 0 : intval($rTMP["acceso_pertenece"]);
            if( $intIndex == 0 && empty($rTMP["path"]) ) {
                if( isset($arrMenuFiltro[$rTMP["modulo"]]["contenido"][$rTMP["acceso"]]) ) {
                    $arrMenu[$rTMP["modulo"]]["contenido"][$rTMP["acceso"]]["nombre"] = $rTMP["nombreAcceso"];
                    $arrMenu[$rTMP["modulo"]]["contenido"][$rTMP["acceso"]]["icono"] = $rTMP["icono"];
                }
            }
            elseif( $intIndex == 0 && !empty($rTMP["path"])) {
                if( isset($arrMenuFiltro[$rTMP["modulo"]]["acceso"][$rTMP["acceso"]]) ) {
                    $arrMenu[$rTMP["modulo"]]["acceso"][$rTMP["acceso"]]["nombre"] = $rTMP["nombreAcceso"];
                    $arrMenu[$rTMP["modulo"]]["acceso"][$rTMP["acceso"]]["path"] = $rTMP["path"];
                    $arrMenu[$rTMP["modulo"]]["acceso"][$rTMP["acceso"]]["icono"] = $rTMP["icono"];
                }
            }
            else {
                if( isset($arrMenuFiltro[$rTMP["modulo"]]["contenido"][$rTMP["acceso_pertenece"]]["accesos"][$rTMP["acceso"]]) ) {
                    $arrMenu[$rTMP["modulo"]]["contenido"][$rTMP["acceso_pertenece"]]["accesos"][$rTMP["acceso"]]["nombre"] = $rTMP["nombreAcceso"];
                    $arrMenu[$rTMP["modulo"]]["contenido"][$rTMP["acceso_pertenece"]]["accesos"][$rTMP["acceso"]]["path"] = $rTMP["path"];
                    $arrMenu[$rTMP["modulo"]]["contenido"][$rTMP["acceso_pertenece"]]["accesos"][$rTMP["acceso"]]["icono"] = $rTMP["icono"];
                }
            }
        }
    }
    else {
        while( $rTMP = db_fetch_assoc($qTMP) ) {
            $arrMenu[$rTMP["modulo"]]["nombre"] = $rTMP["nombreModulo"];
            $arrMenu[$rTMP["modulo"]]["icono"] = $rTMP["iconoModulo"];
            $intIndex = is_null($rTMP["acceso_pertenece"]) ? 0 : intval($rTMP["acceso_pertenece"]);
            if( $intIndex == 0 && empty($rTMP["path"]) ) {
                $arrMenu[$rTMP["modulo"]]["contenido"][$rTMP["acceso"]]["nombre"] = $rTMP["nombreAcceso"];
                $arrMenu[$rTMP["modulo"]]["contenido"][$rTMP["acceso"]]["icono"] = $rTMP["icono"];
            }
            elseif( $intIndex == 0 && !empty($rTMP["path"])) {
                $arrMenu[$rTMP["modulo"]]["acceso"][$rTMP["acceso"]]["nombre"] = $rTMP["nombreAcceso"];
                $arrMenu[$rTMP["modulo"]]["acceso"][$rTMP["acceso"]]["path"] = $rTMP["path"];
                $arrMenu[$rTMP["modulo"]]["acceso"][$rTMP["acceso"]]["icono"] = $rTMP["icono"];
            }
            else {
                $arrMenu[$rTMP["modulo"]]["contenido"][$rTMP["acceso_pertenece"]]["accesos"][$rTMP["acceso"]]["nombre"] = $rTMP["nombreAcceso"];
                $arrMenu[$rTMP["modulo"]]["contenido"][$rTMP["acceso_pertenece"]]["accesos"][$rTMP["acceso"]]["path"] = $rTMP["path"];
                $arrMenu[$rTMP["modulo"]]["contenido"][$rTMP["acceso_pertenece"]]["accesos"][$rTMP["acceso"]]["icono"] = $rTMP["icono"];
            }
        }
    }
    db_free_result($qTMP);

    return $arrMenu;

}

function getIdiomasArreglo($intIdioma = 0) {

    $intIdioma = intval($intIdioma);
    $strFilter = ($intIdioma) ? "WHERE  idioma = {$intIdioma}" : "";

    $arrIdiomas = array();
    $strQuery = "SELECT idioma, codigo, nombre
                 FROM   idioma
                 {$strFilter}
                 ORDER  BY idioma";

    $qTMP = db_query($strQuery);
    while( $rTMP = db_fetch_assoc($qTMP) ) {
        $arrIdiomas[$rTMP["idioma"]]["codigo"] = $rTMP["codigo"];
        $arrIdiomas[$rTMP["idioma"]]["nombre"] = $rTMP["nombre"];
    }
    db_free_result($qTMP);

    return $arrIdiomas;

}

function getModulosArreglo() {

    $arrModulos = array();

    $intIdioma = core_get_idioma();
    $strQuery = "SELECT modulo.modulo, modulo.codigo, modulo_idioma.nombre
                 FROM   modulo, modulo_idioma
                 WHERE  modulo.modulo = modulo_idioma.modulo
                 AND    modulo_idioma.idioma = {$intIdioma}
                 ORDER BY modulo.orden, modulo_idioma.nombre";

    $qTMP = db_query($strQuery);
    while( $rTMP = db_fetch_assoc($qTMP) ) {
        $arrModulos[$rTMP["modulo"]]["codigo"] = $rTMP["codigo"];
        $arrModulos[$rTMP["modulo"]]["nombre"] = $rTMP["nombre"];
    }
    db_free_result($qTMP);

    return $arrModulos;

}

/**
* Función que devuelve el texto para hacer búsquedas en los querys y que este sea flexible para cualquier qu9ery
*
* @param string $strFieldsSearch Se manda los campos en donde se quiere hacer las búsquedas. Si se quiere hacer un OR entre varios campos, se pueden mandar separados por comas.
* @param string $strFilterText Este es el texto que se quiere buscar
* @param boolean $boolAddAnd Indica si se quiere agregar o no la palabra AND antes de la búsqueda
* @param boolean $boolSepararPorEspacios Si se quiere separar por medio de espacios el texto que incluye el usuario al buscar, o bien el texto tal y como viene buscarlo entre los campos enviados.
*/
function getFilterQuery($strFieldsSearch, $strFilterText, $boolAddAnd = true, $boolSepararPorEspacios = true ) {

    $strSearchString = "";
    $strFilterText = upper_tildes(trim($strFilterText));
    $strFilterText = str_replace(array("Á","É","Í","Ó","Ú"),array("A","E","I","O","U"),$strFilterText);
    $mixedFieldsSearch = explode(",",$strFieldsSearch);

    if( count($mixedFieldsSearch) > 1 ) {

        if( $boolSepararPorEspacios )
            $arrFilterText = explode(" ",$strFilterText);
        else
            $arrFilterText[] = $strFilterText;


        while( $arrTMP = each($arrFilterText) ) {

            $strSearchString .= (empty($strSearchString)) ? "" : " AND ";
            $strSearchString .= " ( ";

            $intContador = 0;
            reset($mixedFieldsSearch);
            while( $arrFields = each($mixedFieldsSearch) ) {
                $strWord = db_escape($arrTMP["value"]);
                $intContador++;
                if( $intContador > 1 ) $strSearchString .= " OR ";
                $strSearchString .= " UPPER(replace({$arrFields["value"]}, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU')) LIKE '%{$strWord}%' ";

            }

            $strSearchString .= " ) ";

        }

    }
    else
        $strSearchString .= " UPPER(replace({$strFieldsSearch}, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU')) LIKE '%{$strFilterText}%' ";

    if( $boolAddAnd )$strSearchString = " AND ".$strSearchString;

    return $strSearchString;

}

/**
 * Quita las tildes y las dieresis de un string
 *
 * @param unknown_type $parametro
 * @return unknown
 */
function striptildes($parametro){
    $parametro = str_replace("á","a",$parametro);
    $parametro = str_replace("é","e",$parametro);
    $parametro = str_replace("í","i",$parametro);
    $parametro = str_replace("ó","o",$parametro);
    $parametro = str_replace("ú","u",$parametro);
    $parametro = str_replace("ä","a",$parametro);
    $parametro = str_replace("ë","e",$parametro);
    $parametro = str_replace("ï","i",$parametro);
    $parametro = str_replace("ö","o",$parametro);
    $parametro = str_replace("ü","u",$parametro);


    return $parametro;
}

/**
 * Pasa a upper case un string y se asegura que las letras con tildes y dieresis tambien las pase a upper case.
 *
 * @param string $strString
 * @param bool $boolProper Si se quiere solo la primera letra.
 * @return string
 */
function upper_tildes($strString, $boolProper = false) {
    if ($boolProper) {
        $strString = ucwords($strString);
    }
    else {
        $strString = strtoupper($strString);

        $strString = str_replace("á","Á",$strString);
        $strString = str_replace("é","É",$strString);
        $strString = str_replace("í","Í",$strString);
        $strString = str_replace("ó","Ó",$strString);
        $strString = str_replace("ú","Ú",$strString);
        $strString = str_replace("ä","Ä",$strString);
        $strString = str_replace("ë","Ë",$strString);
        $strString = str_replace("ï","Ï",$strString);
        $strString = str_replace("ö","Ö",$strString);
        $strString = str_replace("ü","Ü",$strString);
        $strString = str_replace("ñ","Ñ",$strString);
    }

    return $strString;
}

/**
 * Lo mismo que upper_tildes pero al revés...
 *
 * @param string $strString
 * @return string
 */
function lower_tildes($strString) {
    $strString = strtolower($strString);

    $strString = str_replace("Á","á",$strString);
    $strString = str_replace("É","é",$strString);
    $strString = str_replace("Í","í",$strString);
    $strString = str_replace("Ó","ó",$strString);
    $strString = str_replace("Ú","ú",$strString);
    $strString = str_replace("Ä","ä",$strString);
    $strString = str_replace("Ë","ë",$strString);
    $strString = str_replace("Ï","ï",$strString);
    $strString = str_replace("Ö","ö",$strString);
    $strString = str_replace("Ü","ü",$strString);
    $strString = str_replace("Ñ","ñ",$strString);

    return $strString;
}

function core_getImagePath($strImage) {

    global $cfg;
    $strReturn = "";

    if( file_exists("templates/{$cfg["core"]["template"]["valor"]}/images/{$strImage}") )
        $strReturn = "templates/{$cfg["core"]["template"]["valor"]}/images/{$strImage}";
    elseif( file_exists("images/{$strImage}") )
        $strReturn = "images/{$strImage}";

    return $strReturn;

}

function core_getPersonaName($strNombreUno, $strNombreDos, $strApellidoUno, $strApellidoDos, $strApellidoCasada){

    $strCompleteName = $strNombreUno." ".(!empty($strNombreDos) ? trim($strNombreDos)." " : "").trim($strApellidoUno).(!empty($strApellidoDos) ? trim($strApellidoDos)." " : "").trim($strApellidoCasada);

    /*if( !empty($strApellidoCasada) ){
        $strApellidoCasada = "de ".$strApellidoCasada;
    }

    if ( $cfg["persona"]["ordenNombre"] == false ){



    }
    else{
        $strCompleteName = $strApellidoUno." ".$strApellidoDos.( (empty($strApellidoCasada) ? "" : " ".$strApellidoCasada ) ).", ".$strNombreUno." ".$strNombreDos;
    }*/

    $strCompleteName = trim($strCompleteName);

    return $strCompleteName;

}

function draw_breadcrum($arrBreadCrum) {

    if( is_array($arrBreadCrum) && count($arrBreadCrum) ) {

        ?>

        <ol class="breadcrumb">
            <?php

            reset($arrBreadCrum);
            while( $arrTMP = each($arrBreadCrum) ) {

                if( isset($arrTMP["value"]["nombre"]) && !empty($arrTMP["value"]["nombre"]) ) {

                    print "<li class=\"breadcrumbElement\">".($arrTMP["value"]["nombre"])."</li>";

                }
            }

            ?>
        </ol>

        <?php

    }

}

/**
* Función que imprime o retorna un texto convirtiendo caracteres html a sus entidades evitando problemas al dibujar un sitio
*
* @param string Texto que se desea convertir
* @param string La codificación en la que se está trabajando, se manda su alias establecido por php
* @param boolean Indica si el texto se imprime(false) o se devuelve(true)
*/
function core_print( $strTexto, $strEncodingBase = "ISO8859-1", $boolReturn = false ){

    $strEncodingBase = empty($strEncodingBase) ? "ISO8859-1" : $strEncodingBase;

    if( !$boolReturn ){
        print htmlentities($strTexto, ENT_QUOTES, $strEncodingBase);
    }
    else{
        return htmlentities($strTexto, ENT_QUOTES, $strEncodingBase);
    }

}

/**
* Función que sirve para enviar correos electrónicos con la función mail de php o a través de un SMTP si se tiene configurado PEAR en el servidor
*
* @param boolean Indica si se enviara a través de SMTP(true) o a través de la función mail(false)
* @param array (obligatorio) Arreglo que contiene la configuración del correo en si. "Keys" esperados: ["from"] string = Quien está enviando el correo (obligatorio). ["from_name"] string = Nombre de quien está enviando el correo (no obligatorio). ["subject"] = El asunto que aparecera en el correo electrónico (obligatorio). ["mail_individual"] boolean = Si es true, se enviara el correo por separado a cada destinatario; si es false, se enviara el correo a todos los destinatarios en uno solo (no obligatorio) (default true). ["reply_to"] string = correo al que se le respondera (no obligatorio). ["cc"] string = Correo o correos separados por coma a los que se les enviará copia oculta.
* @param array o string (obligatorio) Array: Arreglo que contiene los emails de las personas a las que se les desea enviar el correo; deberán de estar en el value cada uno de los emails; el key, si no se declara como integer, se tomara como el Alias para el email de su value. String: El email a quien se le enviará el correo.
* @param array o string (obligatorio) Array: Arreglo que contiene el contenido del email, debe de tener solo una de las siguientes dos posiciones: ["plain"] String texto plano o, ["html"] String con el contenido que se desea enviar en html. String: El contenido que se envie mediante un string en este parametro se traducirá como html.
* @param array o string (no obligatorio) Array: Arreglo que contiene los paths de los archivos que se desan adjuntar. Se puede enviar en un arreglo de un solo nivel, conteniendo en el value el path de cada archivo, o en un arreglo de dos niveles que en el segundo nivel hayan dos keys ["path"] la cual contendrá el path del archivo y ["content_type"] la cual contendrá el tipo de contenido del archivo. String: path del archivo que se desea adjuntar.
* @param array (obligatorio al ser $boolSmtp = true) Arreglo que contiene la configuracion para conectarse al SMTP. Se esperan las siguientes posiciones y todas son obligatorias: ["host"] String indica el host del SMTP; ["username"] String indica el usuario para usar el SMTP; ["password"] String indica el password del usuario para usar el SMTP
*/
function core_mail( $boolSmtp = false, $arrMailConfig = array(), $arrSendTo = array(), $arrMailContent = array(), $arrAttachs = array(), $arrSmtpConfig = array() ){

    $strFrom = isset($arrMailConfig["from"]) ? $arrMailConfig["from"] : "webmaster@webmaster.com";
    $strFromName = isset($arrMailConfig["from_name"]) ? $arrMailConfig["from_name"] : "";
    $strSubject = isset($arrMailConfig["subject"]) ? $arrMailConfig["subject"] : "Subject";
    $boolMailIndividual = isset($arrMailConfig["mail_individual"]) ? $arrMailConfig["mail_individual"] : true;
    $strReplyTo = isset($arrMailConfig["reply_to"]) ? $arrMailConfig["reply_to"] : "";
    $strCc = isset($arrMailConfig["cc"]) ? $arrMailConfig["cc"] : "";
    $arrTo = array();
    if( isset($arrSendTo) ){
        if( is_array($arrSendTo) ){
            reset($arrSendTo);
            while( $rTMP = each($arrSendTo) ){
                if( is_int($rTMP["key"]) ){
                    $arrTo[] = "<{$rTMP["value"]}>";
                }
                else{
                    $arrTo[] = "{$rTMP["key"]} <{$rTMP["value"]}>";
                }

            }

        }
        else{
            $arrTo[] = $arrSendTo;
        }
    }

    if( $boolSmtp ){
        require_once("Mail.php");
        require_once("Mail/mime.php");

        $strHost = isset($arrSmtpConfig["host"]) ? $arrSmtpConfig["host"] : "localhost";
        $strUsername = isset($arrSmtpConfig["username"]) ? $arrSmtpConfig["username"] : "webmaster";
        $strPassword = isset($arrSmtpConfig["password"]) ? $arrSmtpConfig["password"] : "";


        $crlf = "\n";
        $mime = new Mail_mime(array('eol' => $crlf));

        if( is_array($arrAttachs) ){
            reset($arrAttachs);
            while( $sTMP = each($arrAttachs) ){
                if( is_array($sTMP["value"]) && array_key_exists("content_type",$sTMP["value"]) && array_key_exists("path",$sTMP["value"]) ){
                    $strContentType = $sTMP["value"]["content_type"];
                    $strPath = $sTMP["value"]["path"];
                    if( file_exists($strPath) ){
                        $mime->addAttachment($strPath,$strContentType);
                    }
                }
                elseif( is_array($sTMP["value"]) && array_key_exists("path",$sTMP["value"]) ){
                    $strPath = $sTMP["value"]["path"];
                    if( file_exists($strPath) ){
                        $mime->addAttachment($strPath);
                    }
                }
                else{
                    $strPath = $sTMP["value"];
                    if( file_exists($strPath) ){
                        $mime->addAttachment($strPath);
                    }
                }
            }
        }
        elseif( !empty($arrAttachs) && is_string($arrAttachs) ){
            if( file_exists($arrAttachs) ){
                $mime->addAttachment($arrAttachs);
            }
        }

        if( is_array($arrMailContent) && array_key_exists("html",$arrMailContent) ){
            $strBody = $arrMailContent["html"];
            $mime->setHTMLBody($strBody);
        }
        elseif( is_array($arrMailContent) && array_key_exists("plain",$arrMailContent) ){
            $strBody = $arrMailContent["plain"];
            $mime->setTxtBody ($strBody);
        }
        elseif( is_string($arrMailContent) && !empty($arrMailContent) ){
            $strBody = $arrMailContent;
            $mime->setHTMLBody($strBody);
        }



        $smtp = Mail::factory('smtp', array ('host' => $strHost, 'auth' => true, 'username' => $strUsername, 'password' => $strPassword));
        $arrHeaders = array();
        if( empty($strFromName) ){
            $arrHeaders["From"] = $strFrom;
        }
        else{
            $arrHeaders["From"] = $strFromName." <".$strFrom.">";
        }

        $arrHeaders["Subject"] = $strSubject;
        if( !empty($strReplyTo) ){
            $arrHeaders["Reply-To"] = $strReplyTo;
        }
        if( !empty($strCc) ){
            $arrHeaders["Cc"] = $strCc;
        }
        if( $boolMailIndividual ){
            while( $rTMP = each($arrTo) ){
                $arrHeaders["To"] = $rTMP["value"];
                $mailBody = $mime->get();
                $mailHeaders = $mime->headers($arrHeaders);
                $smtp->send($rTMP["value"], $mailHeaders, $mailBody);
            }
        }
        else{
            $arrHeaders["To"] = $arrTo;
            $mailBody = $mime->get();
            $mailHeaders = $mime->headers($arrHeaders);
            $smtp->send($arrTo, $mailHeaders, $mailBody);
        }
    }
    else{

        $strHeader = "";
        $intUniqId = md5(uniqid(time()));
        if( empty($strFromName) ){
            $strHeader = "From: ".$strFrom."\r\n";
        }
        else{
            $strHeader = "From: ".$strFromName." <".$strFrom.">\r\n";
        }

        if( !empty($strCc) ){
            $strHeader .= "Cc: ".$strCc."\r\n";
        }

        if( !empty($strReplyTo) ){
            $strHeader .= "Reply-To: ".$strReplyTo."\r\n";
        }

        $strHeader .= "MIME-Version: 1.0\r\n";
        $strHeader .= "Content-Type: multipart/mixed; boundary=\"".$intUniqId."\"\r\n\r\n";
        $strHeader .= "This is a multi-part message in MIME format.\r\n";
        $strHeader .= "--".$intUniqId."\r\n";

        if( is_array($arrMailContent) && array_key_exists("html",$arrMailContent) ){
            $strBody = $arrMailContent["html"];
            $strHeader .= "Content-type:text/html; charset=iso-8859-1\r\n";
            $strHeader .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $strHeader .= $strBody."\r\n\r\n";
            $strHeader .= "--".$intUniqId."\r\n";
        }
        if( is_array($arrMailContent) && array_key_exists("plain",$arrMailContent) ){
            $strBody = $arrMailContent["plain"];
            $strHeader .= "Content-type:text/plain; charset=iso-8859-1\r\n";
            $strHeader .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $strHeader .= $strBody."\r\n\r\n";
            $strHeader .= "--".$intUniqId."\r\n";
        }
        elseif( is_string($arrMailContent) && !empty($arrMailContent) ){
            $strBody = $arrMailContent;
            $strHeader .= "Content-type:text/html; charset=iso-8859-1\r\n";
            $strHeader .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $strHeader .= $strBody."\r\n\r\n";
            $strHeader .= "--".$intUniqId."\r\n";
        }

        if( is_array($arrAttachs) ){
            reset($arrAttachs);
            while( $sTMP = each($arrAttachs) ){
                if( is_array($sTMP["value"]) && array_key_exists("content_type",$sTMP["value"]) && array_key_exists("path",$sTMP["value"]) ){
                    $strContentType = $sTMP["value"]["content_type"];
                    $strPath = $sTMP["value"]["path"];
                    $strHeader .= core_convert_attach_mail($strPath,$intUniqId,$strContentType);
                }
                elseif( is_array($sTMP["value"]) && array_key_exists("path",$sTMP["value"]) ){
                    $strPath = $sTMP["value"]["path"];
                    $strHeader .= core_convert_attach_mail($strPath,$intUniqId);
                }
                else{
                    $strPath = $sTMP["value"];
                    $strHeader .= core_convert_attach_mail($strPath,$intUniqId);
                }
            }
        }
        elseif( !empty($arrAttachs) && is_string($arrAttachs) ){
            $strHeader .= core_convert_attach_mail($arrAttachs,$intUniqId);
        }

        if( $boolMailIndividual ){
            reset($arrTo);
            while( $rTMP = each($arrTo) ){
                @mail($rTMP["value"], $strSubject, "", $strHeader);
            }
        }
        else{
            $strTo = "";
            reset($arrTo);
            while( $rTMP = each($arrTo) ){
                $strTo .= empty($strTo) ? $rTMP["value"] : ",".$rTMP["value"];
            }

            @mail($strTo, $strSubject, "", $strHeader);
        }

    }

}

/**
* Funcion que convierte un archivo en texto para adjuntarse al contenido de un correo que se enviará a través de la función mail de php
*
* @param String (obligatorio) Path de donde se encuentra el archivo y nombre del archivo con extensión
* @param Integer (obligatorio) UniqId que separa los contenidos adentro del correo
* @param String Tipo de contenido que se indica para el archivo
* @return String Archivo convertido a texto
*/
function core_convert_attach_mail( $strPathFile, $intUniqId, $strContentType = "application/octet-stream" ){
    $strReturn = "";
    if( file_exists($strPathFile)  && !empty($intUniqId) ){
        $strFilename = basename($strPathFile);

        $intFileSize = filesize($strPathFile);
        $objFile = fopen($strPathFile, "r");
        $strContent = fread($objFile, $intFileSize);
        fclose($objFile);
        $strContent = chunk_split(base64_encode($strContent));

        $strReturn .= "Content-Type: {$strContentType}; name=\"".$strFilename."\"\r\n";
        $strReturn .= "Content-Transfer-Encoding: base64\r\n";
        $strReturn .= "Content-Disposition: attachment; filename=\"".$strFilename."\"\r\n\r\n";
        $strReturn .= $strContent."\r\n\r\n";
        $strReturn .= "--".$intUniqId."--";
    }
    return $strReturn;
}

/**
* Función que sirve para guardar archivos en sistema
*
* @param string Nombre del input file
* @param string Por defecto se guardara en la carpeta "attach/", si se desea agregar una carpeta en "attach/", se deberá enviar en este parámetro, se aceptan varios niveles de carpetas separados siempre por slash "/"
* @param string Por defecto es "attach". Palabra que se desea anteponer al nombre del archivo a subir
* @param integer Id que llevara la imagen después de su antelación, por defecto es un uniqid simple
* @param boolean True si se desea borrar el archivo anterior que estaba con el mismo nombre, false si se desea conservar el archivo anterior
*
* @return string Retorna el nombre del archivo creado por la función, si se retorna un string en blanco probablemente no se subio bien el archivo
*/
function core_save_file( $strInputFileName, $strExtraPath = "", $strAntelacionArchivo = "attach", $intImagenId = 0, $boolDeletePreviousFile = true, $boolUTF8Decode = false ){
    $strFileName = isset( $_FILES[$strInputFileName]['name'] ) ? $_FILES[$strInputFileName]['name'] : "" ;
    $strFileName = user_input_delmagic($strFileName, $boolUTF8Decode);
    $strFileName = core_removeSpecialChars($strFileName);

    $intImagenId = $intImagenId == 0 ? uniqid() : $intImagenId;

    $strAntelacionArchivo = empty($strAntelacionArchivo) ? "attach" : $strAntelacionArchivo;

    $strPath = "attach/";

    $strExtraPath = core_removeSpecialChars($strExtraPath);
    if( !empty($strExtraPath) ){
        if( substr($strExtraPath,-1,1) != "/" ){
            $strExtraPath = $strExtraPath."/";
        }
    }

    $strPath = $strPath.$strExtraPath;
    $strPathAndFile = "";
    if( !file_exists($strPath) ){
        mkdir($strPath, 0777, true);
    }


    if( file_exists($strPath) ){
        if( isset( $_FILES[$strInputFileName]['name'] ) && $_FILES[$strInputFileName]['error'] == UPLOAD_ERR_OK ){
            $strPathAndFile = $strPath.$strAntelacionArchivo."_".$intImagenId."_".$strFileName;

            if( $boolDeletePreviousFile ){
                if( strlen($strPathAndFile) > 0 ){
                    if( file_exists($strPathAndFile) ){
                        @chmod($strPathAndFile, 0777);
                        unlink($strPathAndFile);
                    }
                }
            }

            if( !file_exists($strPathAndFile) ){
                @chmod("attach/lib_images/", 0777);
                move_uploaded_file($_FILES[$strInputFileName]["tmp_name"], $strPathAndFile);
                @chmod($strPathAndFile, 0777);
            }
        }
    }

    return $strPathAndFile;
}

/**
* Función que remueve los caracteres especiales de un string
*
* @param string String al que se le desea aplicar este filtro de caracteres especiales
*
* @return string El string que se recibio ya con el filtro aplicado
*/
function core_removeSpecialChars( $strString ){
    $strReturn = "";
    $strReturn = str_replace(array("/"," ","%",";"), "", $strString);
    $strReturn = str_replace(array("á","é","í","ó","ú","ñ","à","è","ì","ò","ù","Á","É","Í","Ó","Ú","Ñ","À","È","Ì","Ò","Ù"),
                           array("a","e","i","o","u","n","a","e","i","o","u","A","E","I","O","U","N","A","E","I","O","U"),
                           $strReturn);
    return $strReturn;
}

/**
* Función que devuelve array con datos para dibujar el breadcrumb
*
* @param string ID de acceso de la pantalla
* @param boolean Indica si debe dibujar link de pagina de inicio default = true
* @param array Links extra que se agregan al final
*/
function core_getInfoBreadCrumb( $strAcceso, $boolHome = true, $arrInfoExtra = array() ) {
    global $lang;
    $arrData = array();
    $intIdioma = core_get_idioma();
    $intCorrelativo = 0;

    if( $boolHome ) {
        $arrData[$intCorrelativo]['nombre'] = $lang['core']['config_inicio'];
        $arrData[$intCorrelativo]['link'] = 'index.php';
    }

    $strQuery ="SELECT  modulo_idioma.nombre AS moduloNombre,
                        acceso_idioma_padre.nombre_menu AS accesoPadreNombre,
                        acceso_padre.path AS accesoPadreLink,
                        acceso_idioma.nombre_menu AS accesoHijoNombre,
                        acceso.path AS accesoHijoLink
                FROM    acceso
                        INNER JOIN acceso_idioma
                            ON  acceso.acceso = acceso_idioma.acceso
                            AND acceso_idioma.idioma = {$intIdioma}
                        INNER JOIN acceso AS acceso_padre
                            ON  acceso.acceso_pertenece = acceso_padre.acceso
                        INNER JOIN acceso_idioma AS acceso_idioma_padre
                            ON  acceso_padre.acceso = acceso_idioma_padre.acceso
                            AND acceso_idioma_padre.idioma = {$intIdioma}
                        INNER JOIN modulo
                            ON  acceso.modulo = modulo.modulo
                        INNER JOIN modulo_idioma
                            ON  modulo.modulo = modulo_idioma.modulo
                WHERE   acceso.codigo = '{$strAcceso}'";
    $arrInfoAccesoPadre = sqlGetValueFromKey($strQuery);

    if( count($arrInfoAccesoPadre) ) {
        ++$intCorrelativo;
        $arrData[$intCorrelativo]['nombre'] = $arrInfoAccesoPadre["moduloNombre"];
        $arrData[$intCorrelativo]['link'] = '';
        ++$intCorrelativo;
        $arrData[$intCorrelativo]['nombre'] = $arrInfoAccesoPadre["accesoPadreNombre"];
        $arrData[$intCorrelativo]['link'] = $arrInfoAccesoPadre["accesoPadreLink"];
        ++$intCorrelativo;
        $arrData[$intCorrelativo]['nombre'] = $arrInfoAccesoPadre["accesoHijoNombre"];
        $arrData[$intCorrelativo]['link'] = $arrInfoAccesoPadre["accesoHijoLink"];
        $arrData[$intCorrelativo]['activo'] = true;
    }

    if( count($arrInfoExtra) > 0 ) {

        while($arrTMP = each($arrInfoExtra) ) {
            ++$intCorrelativo;
            $arrData[$intCorrelativo]['nombre'] = $arrTMP["value"]["nombre"];
            $arrData[$intCorrelativo]['link'] = $arrTMP["value"]["link"];
            if( isset($arrTMP["value"]["activo"]) )
                $arrData[$intCorrelativo]['activo'] = $arrTMP["value"]["activo"];
        }

    }

    return $arrData;

}

/**
* Devuelve el Id de la persona que esta logueada en base a la session activa.
*
*/
function core_getUserId() {
    return isset($_SESSION["wt"]["originalUserToTest"]) ? intval($_SESSION["wt"]["originalUserToTest"]) : intval($_SESSION["hml"]["persona"]);
}

/**
* Función que retorna los ids de los campus a los que la persona que está loggineada tiene acceson, en un arreglo.
*
*/
function core_getUserCampus(){
    $arrData = array();
    $intPersona = core_getUserId();

    $strFrom = ", persona_campus_facultad";
    $strAnd = " WHERE campus.campus = persona_campus_facultad.campus
                AND   persona_campus_facultad.persona = {$intPersona}";
    if( $_SESSION["hml"]["tipo_usuario"] == "admin" ){
        $strFrom = "";
        $strAnd = "WHERE campus.activo = 'Y'";
    }

    $strQuery = "SELECT campus.campus
                FROM    campus
                {$strFrom}
                {$strAnd}";
    $qTMP = db_query($strQuery);
    while( $rTMP = db_fetch_assoc($qTMP) ){
        $arrData[$rTMP["campus"]] = $rTMP["campus"];
    }
    db_free_result($qTMP);
    return $arrData;
}

function core_getUserFacultad(){
    $arrData = array();
    $intPersona = core_getUserId();

    $strFrom = ", carrera";
    $strAnd = " WHERE facultad.facultad = carrera.facultad
                 AND carrera.persona = {$intPersona}";
    if( $_SESSION["hml"]["tipo_usuario"] == "admin" ){
        $strFrom = "";
    }

    $strQuery = "SELECT facultad.facultad
                FROM    facultad
                {$strFrom}";
    $qTMP = db_query($strQuery);
    while( $rTMP = db_fetch_assoc($qTMP) ){
        $arrData[$rTMP["facultad"]] = $rTMP["facultad"];
    }
    db_free_result($qTMP);
    return $arrData;
}

/**
 * @return string
 * @param integer $intNumber El número a convertir, puede tener 2 decimales también
 * @desc Devuelve un numero en letras (estandar para dinero)
*/
function PrecioEnLetras($intNumber, $strMonedaName = "") {
    $arrParts = explode(".",$intNumber);
    $arrParts["int"] = $arrParts[0];
    if (!isset($arrParts[1])) {
        $arrParts["dec"] = "00";
    }
    else {
        $intDecLen = strlen($arrParts[1]);
        if ($intDecLen==1) {
            $arrParts["dec"]=$arrParts[1]."0";
        }
        else {
            $arrParts["dec"]=$arrParts[1];
        }
    }
    unset($arrParts[0]);
    unset($arrParts[1]);

    $strReturn = NumeroEnLetras($intNumber);
    if ($strMonedaName)
        $strReturn.= " ".$strMonedaName." con {$arrParts["dec"]}/100";
    else
        $strReturn.=" con {$arrParts["dec"]}/100";

    return trim($strReturn);
}

/**
 * @return string
 * @param integer $intNumber El número a convertir, puede tener 2 decimales también
 * @param array $arrStrings (NO USAR ESTE PARAMETRO, ES PARA USO DE LA RECURSION EN LA FUNCION)
 * @desc Devuelve un numero en letras (estandar para dinero)
*/
function NumeroEnLetras($intNumber, $arrStrings = false) {
    $boolFirst = false;
    if (!$arrStrings) {
        $boolFirst = true;
        $intNumber = round($intNumber,2)."";
        $arrStrings = array();
        $arrStrings[0][1] = "uno";
        $arrStrings[0][2] = "dos ";
        $arrStrings[0][3] = "tres ";
        $arrStrings[0][4] = "cuatro ";
        $arrStrings[0][5] = "cinco ";
        $arrStrings[0][6] = "seis ";
        $arrStrings[0][7] = "siete ";
        $arrStrings[0][8] = "ocho ";
        $arrStrings[0][9] = "nueve ";

        $arrStrings[1][1] = "dieci";
        $arrStrings[1][2] = "veinti";
        $arrStrings[1][3] = "treinta y ";
        $arrStrings[1][4] = "cuarenta y ";
        $arrStrings[1][5] = "cincuenta y ";
        $arrStrings[1][6] = "sesenta y ";
        $arrStrings[1][7] = "setenta y ";
        $arrStrings[1][8] = "ochenta y ";
        $arrStrings[1][9] = "noventa y ";

        $arrStrings[2][1] = "ciento ";
        $arrStrings[2][2] = "doscientos ";
        $arrStrings[2][3] = "trescientos ";
        $arrStrings[2][4] = "cuatrocientos ";
        $arrStrings[2][5] = "quinientos ";
        $arrStrings[2][6] = "seiscientos ";
        $arrStrings[2][7] = "setecientos ";
        $arrStrings[2][8] = "ochocientos ";
        $arrStrings[2][9] = "novecientos ";

        $arrStrings[3][1] = "mil ";
    }

    if ($intNumber == 0) return "cero";

    $arrParts = explode(".",$intNumber);
    $arrParts["int"] = $arrParts[0];
    if (!isset($arrParts[1])) {
        $arrParts["dec"] = "00";
    }
    else {
        $intDecLen = strlen($arrParts[1]);
        if ($intDecLen==1) {
            $arrParts["dec"]=$arrParts[1]."0";
        }
        else {
            $arrParts["dec"]=$arrParts[1];
        }
    }
    unset($arrParts[0]);
    unset($arrParts[1]);

    $strTMP = $arrParts["int"];
    $arrParts["int"] = array();
    for ($i=strlen($strTMP);$i>0;$i--) {
        $arrParts["int"][$i-1] = substr($strTMP,strlen($strTMP) - $i,1);
    }
    ksort($arrParts["int"]);

    $strReturn = "";
    while (($arrThis = each($arrParts["int"])) && $arrThis["key"] < 3) {
        $strTMP = "";
        if ($arrThis["key"]==1 && $arrThis["value"]==1 && $arrParts["int"][0] < 6) {
            switch ($arrParts["int"][0]) {
                case 0:
                $strReturn = "diez";
                break;
                case 1:
                $strReturn = "once";
                break;
                case 2:
                $strReturn = "doce";
                break;
                case 3:
                $strReturn = "trece";
                break;
                case 4:
                $strReturn = "catorce";
                break;
                case 5:
                $strReturn = "quince";
                break;
            }
        }
        elseif( $arrThis["key"] == 1 && $arrThis["value"] == 2 && $arrParts["int"][0] == 0 ) {
            $strReturn = "veinte";
        }
        elseif ($arrThis["key"]==2 && $arrThis["value"]==1 && $arrParts["int"][1] == 0 && $arrParts["int"][0] == 0) {
            $strReturn = "cien";
        }
        else {
            $strTMP = (isset($arrStrings[$arrThis["key"]][$arrThis["value"]]))?$arrStrings[$arrThis["key"]][$arrThis["value"]]:"";
        }

        if (empty($strReturn)) {
            $strTMP = str_replace(" y ","",$strTMP);
        }
        $strReturn = $strTMP.$strReturn;
    }

    $strMiles = "";
    $strMillones = "";
    for ($i = 3;$i < count($arrParts["int"]);$i++) {
        if ($i<6) {
            $strMiles = $arrParts["int"][$i].$strMiles;
        }
        else {
            $strMillones = $arrParts["int"][$i].$strMillones;
        }
    }

    if (!empty($strMiles)) {
        if ($strMiles == 1) {
            $strReturn = "un mil ".$strReturn;
        }
        else if ($strMiles > 0) {
            $strReturn = NumeroEnLetras($strMiles, $arrStrings)." mil ".$strReturn;
        }
    }

    if (!empty($strMillones)) {
        if ($strMillones == 1) {
            $strReturn = "un millón ".$strReturn;
        }
        else if ($strMillones > 0) {
            $strReturn = NumeroEnLetras($strMillones, $arrStrings)." millones ".$strReturn;
        }
    }

    //Agrego este proceso para sustituir las palabras con tilde(solo son los numeros 16,22,23,26)
    $strReturn = str_replace(array(
            "dieciseis","veintidos","veintitres","veintiseis"
        ),array(
            "dieciséis","veintidós","veintitrés","veintiséis"
        ),$strReturn);
    return trim($strReturn);
}

/**
* Función que dibuja input hidden con nombre hdnToken con un valor unico
*
*/
function core_drawToken() {

    ?>
    <input type="hidden" name="hdnToken" value="<?php print uniqid("", true); ?>" readonly="readonly">
    <?php

}

/**
* Función que valida si un token ha sido procesado o no
*
* @param str ID del acceso de la pantalla
* @return boolean Devuelve true si el token no ha sido procesado y false si ya fue procesado
*/
function core_validarToken($strAcceso) {

    $boolReturn = false;

    if( isset($_POST['hdnToken']) && ( !isset($_SESSION['hml'][$strAcceso]) || ( isset($_SESSION['hml'][$strAcceso]) && $_SESSION['hml'][$strAcceso] != $_POST['hdnToken'] ) ) ) {
        $_SESSION['hml'][$strAcceso] = $_POST['hdnToken'];
        $boolReturn = true;
    }

    return $boolReturn;

}

function core_sesion_expirada(){

    clear_login();
    if(!$_SESSION["hml"]["logged"]) {
        // Notifica que la sesion expiró
        $_SESSION["hml"]["sesion_expirada"] = "true";

        ?>
        <script>
            document.location.href = "index.php"
        </script>
        <?php
        die();

    }
}

function core_getIsPasswordAleatorio() {
    $strIsAleatorio = "N";
    $intPersona = $_SESSION["hml"]["persona"];
    $strQuery = "SELECT isAleatorio
                 FROM   usuario
                 WHERE  persona = {$intPersona}";
    $strIsAleatorio = sqlGetValueFromKey($strQuery);

    return $strIsAleatorio;
}

/**
 * Funcion que sirve para calcular la edad a partir de su fecha de nacimiento
 * @param string $strFechaNacimiento Es la fecha con la que se desea calcular
 * @param boolean $boolReturnLetras colocar true si se desea devolver en numeros y letras
 * @return integer/array
 */
function core_calculate_age($strFechaNacimiento,$boolReturnLetras = false){
    //fecha actual
    $intDiaActual = date("j");
    $intMesActual = date("n");
    $intAnoActual = date("Y");

    //fecha de nacimiento
    $strFecha = date("Y-n-j",strtotime($strFechaNacimiento));
    $arrFecha = explode("-",$strFecha);
    $intDiaNacimiento = $arrFecha[2];
    $intMesNacimiento = $arrFecha[1];
    $intAnoNacimiento = $arrFecha[0];

    //si el mes es el mismo pero el día inferior aun no ha cumplido años, le quitaremos un año al actual
    if (($intMesNacimiento == $intMesActual) && ($intDiaNacimiento > $intDiaActual))$intAnoActual -= 1;

    //si el mes es superior al actual tampoco habrá cumplido años, por eso le quitamos un año al actual
    if ($intMesNacimiento > $intMesActual)$intAnoActual -= 1;

    //ya no habría mas condiciones, ahora simplemente restamos los años y mostramos el resultado como su edad
    $intEdad = ($intAnoActual - $intAnoNacimiento);

    if($boolReturnLetras){
        $arrReturn = array();
        $arrReturn["edad"] = $intEdad;
        $arrReturn["edad_letras"] = NumeroEnLetras($intEdad);
        return $arrReturn;
    }
    return $intEdad;
}

/**
* Función que retorna un arreglo de fechas dentro de un rango establecido de fechas
*
* @param string Formato: "YYYY-mm-dd", fecha inicio del rango
* @param string Formato: "YYYY-mm-dd", fecha fin del rango
* @param string Indica que tipo de recurrencia es: diario, mensual, semanal, anual
* @param int Indica el intervalo de la recurrencia ej. 2 retornara la recurrencia cada 2 intervalos del parametro de strRecurrencia
* @param string Opcional si se establece este parametro se buscara solo el dia ingresado: lunes, martes, miercoles, jueves, viernes, sabado, domingo
* @param string Opciones: diaExacto, diaFinal default diaExacto indica si se toma el dia exacto ej. si se ingresa 31 de cada mes solo los meses que tengan día 31 o el ultimo día ej. si se ingresa 31 de cada mes los meses que tengan solo 30 días tendra como resultado 30
* @return array Retorna un arreglo con las fechas encontradas segun los parametros ingresados
*/
function core_getFechasRecurrencia($strFechaInicio, $strFechaFin, $strRecurrencia, $intIntervalRecurrencia, $strDia = "", $strFinOExacto = ""){
    //Este arreglo contiene las recurrencias y el tipo de intervalo que utilizo para cada uno
    $arrRecurrencias = array();
    $arrRecurrencias["mensual"] = array();
    $arrRecurrencias["mensual"]["cantidad"] = $intIntervalRecurrencia;
    $arrRecurrencias["mensual"]["tipo"] = "MONTH";
    $arrRecurrencias["semanal"] = array();
    $arrRecurrencias["semanal"]["cantidad"] = $intIntervalRecurrencia * 7;
    $arrRecurrencias["semanal"]["tipo"] = "DAY";
    $arrRecurrencias["diario"] = array();
    $arrRecurrencias["diario"]["cantidad"] = $intIntervalRecurrencia;
    $arrRecurrencias["diario"]["tipo"] = "DAY";
    $arrRecurrencias["anual"] = array();
    $arrRecurrencias["anual"]["cantidad"] = $intIntervalRecurrencia;
    $arrRecurrencias["anual"]["tipo"] = "YEAR";

    $strFinOExacto = empty($strFinOExacto) ? "diaExacto" : $strFinOExacto;

    //Si se ingresa otra recurrencia extraña entonces se establece como diario por default
    if (!isset($arrRecurrencias[$strRecurrencia]))
        $strRecurrencia = "diario";
    if ($intIntervalRecurrencia <= 0)
        $intIntervalRecurrencia = 1;


    $arrExplodeFechaInicio = explode("-",$strFechaInicio);

    $intDia = $arrExplodeFechaInicio[2];
    $intMes = $arrExplodeFechaInicio[1];
    $intAnio = $arrExplodeFechaInicio[0];

    $intDiaExacto = $intDia;

    if(!empty($strDia)){
        $strDia = strtolower($strDia);
        $strRecurrencia = $strRecurrencia == "diario" ? "semanal" : $strRecurrencia;
        $strDiaIngles = "";
        switch($strDia){
            case "lunes":
                $strDiaIngles = "Mon";
                break;
            case "martes":
                $strDiaIngles = "Tue";
                break;
            case "miercoles":
                $strDiaIngles = "Wed";
                break;
            case "jueves":
                $strDiaIngles = "Thu";
                break;
            case "viernes":
                $strDiaIngles = "Fri";
                break;
            case "sabado":
                $strDiaIngles = "Sat";
                break;
            case "domingo":
                $strDiaIngles = "Sun";
                break;
        }

        $intIntervalo = new DateInterval('P1D');

        $intBreak = 0;

        $strDate = new DateTime("{$intAnio}-{$intMes}-{$intDia}");

        while($intBreak == 0){
            $arrExplodeDate = explode("-",date_format($strDate, 'Y-m-d'));
            $intMes = $arrExplodeDate[1];
            $intDia = $arrExplodeDate[2];
            $intAnio = $arrExplodeDate[0];
            $boolFechaValida = checkdate($intMes, $intDia, $intAnio);

            if($boolFechaValida){
                $strDay = date("D",mktime(0,0,0,$intMes,$intDia,$intAnio));
            }
            if($strDiaIngles == $strDay){
                $intBreak = 1;
                $strFechaInicio = date_format($strDate, 'Y-m-d');
            }
            else{
                $strDate->add($intIntervalo);
            }

            if(date_format($strDate, 'Y-m-d') > $strFechaFin){
                $intBreak = 1;
            }
        }

    }

    //Inicia siempre con la fecha de inicio, por lo tanto
    //siempre va a devolver por lo menos esta fecha
    $arrFechas = array();
    $arrFechas[$strFechaInicio] = $strFechaInicio;
    $fecha = $strFechaInicio;


    $intContinue = sqlGetValueFromKey("SELECT IF( TO_DAYS('{$fecha}') <= TO_DAYS('{$strFechaFin}'), 1,0 )");
    $intContinue = intval($intContinue);

    $intCountFechas = 2;

    while ($intContinue > 0) {
        $fecha = sqlGetValueFromKey("SELECT DATE_ADD('{$fecha}',
                                     INTERVAL {$arrRecurrencias[$strRecurrencia]["cantidad"]}
                                     {$arrRecurrencias[$strRecurrencia]["tipo"]})");
        //drawDebug($fecha,"fecha");


        $intContinue = sqlGetValueFromKey("SELECT IF( TO_DAYS('{$fecha}') <= TO_DAYS('{$strFechaFin}'), 1,0 )");
        $intContinue = intval($intContinue);

        $boolFechaSi = true;
        $boolFechaGuardar = true;

        if ($intContinue > 0){
            $arrExplodeFecha = explode("-",$fecha);
            $intDia = $arrExplodeFecha[2];
            $intMes = $arrExplodeFecha[1];
            $intAnio = $arrExplodeFecha[0];

            if($strRecurrencia == "mensual" || $strRecurrencia == "anual"){
                if($strFinOExacto == "diaExacto"){
                    //drawDebug($intDiaExacto,"intDiaExacto");
                    $boolFechaValida = checkdate($intMes, $intDiaExacto, $intAnio);
                    //drawDebug($boolFechaValida,"boolFechaValida");
                    if($boolFechaValida){
                        $fecha = "{$intAnio}-{$intMes}-{$intDiaExacto}";
                    }
                    else{
                        $fecha = "{$intAnio}-{$intMes}-{$intDia}";
                        $boolFechaGuardar = false;
                    }
                    //drawDebug($fecha,"fecha");
                }
                else if($strFinOExacto == "diaFinal"){
                    switch(intval($intMes)){
                        case 2:
                            if($intDiaExacto >= 29){
                                $boolFechaValida = checkdate($intMes, $intDiaExacto, $intAnio);
                                if($boolFechaValida){
                                    $fecha = "{$intAnio}-{$intMes}-{$intDiaExacto}";
                                }
                                else{
                                    $fecha = "{$intAnio}-{$intMes}-28";
                                }
                            }
                            break;
                        case 3:
                            if($intDiaExacto > 28 && $intDiaExacto <= 31){
                                $fecha = "{$intAnio}-{$intMes}-{$intDiaExacto}";
                            }
                            break;
                        case 4:
                            if($intDiaExacto >= 31){
                                $fecha = "{$intAnio}-{$intMes}-30";
                            }
                            break;
                        case 5:
                            if($intDiaExacto == 31){
                                $fecha = "{$intAnio}-{$intMes}-31";
                            }
                            break;
                        case 6:
                            if($intDiaExacto >= 31){
                                $fecha = "{$intAnio}-{$intMes}-30";
                            }
                            break;
                        case 7:
                            if($intDiaExacto == 31){
                                $fecha = "{$intAnio}-{$intMes}-31";
                            }
                            break;
                        case 8:
                            if($intDiaExacto == 31){
                                $fecha = "{$intAnio}-{$intMes}-31";
                            }
                            break;
                        case 9:
                            if($intDiaExacto >= 31){
                                $fecha = "{$intAnio}-{$intMes}-30";
                            }
                            break;
                        case 10:
                            if($intDiaExacto == 31){
                                $fecha = "{$intAnio}-{$intMes}-31";
                            }
                            break;
                        case 11:
                            if($intDiaExacto >= 31){
                                $fecha = "{$intAnio}-{$intMes}-30";
                            }
                            break;
                        case 12:
                            if($intDiaExacto == 31){
                                $fecha = "{$intAnio}-{$intMes}-31";
                            }
                            break;
                    }
                }
            }

            if(isset($strDay) && date("D",mktime(0,0,0,$intMes,$intDia,$intAnio)) == $strDay){
                $boolFechaSi = true;
            }
            else{
                $boolFechaSi = false;
            }

            if(!isset($strDay)){
                $boolFechaSi = true;
            }

            if($boolFechaSi && !empty($fecha) && $boolFechaGuardar){
                $arrFechas[$fecha] = $fecha;
            }


        }

        $intCountFechas++;

    }
    return $arrFechas;
}

/**
* Función que verifica si se pueden eliminar los datos de una tabla
*
* @param String Nombre de la tabla que se desea verificar
* @param Int ID de la tabla que se desea verificar
* @param Array Nombre de las tablas que no se desean incluir
*/
function core_boolPuedeEliminarDatos($strTableName,$intIdCampo,$arrTablasDescartadas = array() ){
    global $arrConfigSite;

    $boolReturn = true;
    $strAnd = "";
    $strAnd2 = "";

    $arrTablasColumnasDescartadas = array();
    $tableName = "";
    $tableColumnName = "";

    while( $rTMP = each($arrTablasDescartadas) ){
        $arrExplode = explode(".",$rTMP["key"]);
        if( isset($arrExplode[0]) && isset($arrExplode[1]) ){
            $arrTablasColumnasDescartadas[$arrExplode[0]][$arrExplode[1]] = $arrExplode[1];
        }
        else{
            if( !isset($arrTablasColumnasDescartadas[$arrExplode[0]]) ){
                $arrTablasColumnasDescartadas[$arrExplode[0]] = array();
            }
        }
    }

    while( $rTMP = each($arrTablasColumnasDescartadas) ){
        if( count($rTMP["value"]) == 0 ){
            $tableName .= $rTMP["key"]."','";
        }
        else{
            foreach($rTMP["value"] as $columName){
                $tableColumnName .= !empty($columName) ? $rTMP["key"].".".$columName."','" : "";
            }
        }
    }

    if($tableName != "" ){
        $strAnd .= " AND table_name NOT IN('{$tableName}')";
    }

    if($tableColumnName != "" ){
        $strAnd2 .= " AND CONCAT(table_name,'.',column_name) NOT IN('{$tableColumnName}')";
    }

    $strQuery = "SELECT table_name,
                        column_name
                 FROM   information_schema.key_column_usage
                 WHERE  table_schema = '{$arrConfigSite["db"]["database"]}'
                 AND    referenced_table_name = '{$strTableName}'
                 AND    referenced_table_schema = '{$arrConfigSite["db"]["database"]}' {$strAnd} {$strAnd2}";
    $qTMP = db_query($strQuery);

    $strQuery = "";
    while($rTMP = db_fetch_assoc($qTMP)){
        $strQuery .= empty($strQuery) ? "" : " UNION ";
        $strQuery .= "SELECT  {$rTMP["table_name"]}.{$rTMP["column_name"]}
                     FROM   {$rTMP["table_name"]}
                     WHERE  {$rTMP["column_name"]} = {$intIdCampo}";
    }
    $strQuery .= empty($strQuery) ? "" : " LIMIT 1 ";

    db_free_result($qTMP);
    if( !empty($strQuery) ) {
        $qTMP = db_query($strQuery);
        while($rTMP =  db_fetch_assoc($qTMP)){
            $boolReturn = false;
        }
        db_free_result($qTMP);
    }
    return $boolReturn;
}

function core_getMonthText($intMes) {
    global $lang;
    $intMes = intval($intMes);
    return isset($lang["core"]["core_mes_{$intMes}"]) ? $lang["core"]["core_mes_{$intMes}"] : "";
}