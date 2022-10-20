<?php

class cpanel_model {
    
    
    public function getIdFromMD5($strTableName, $strKeyField, $strIdentificador){
        
        $intReturn = 0;
        $strTableName = trim($strTableName);
        $strKeyField = trim($strKeyField);
        $strIdentificador = trim($strIdentificador);
        
        if( !empty($strTableName) && !empty($strKeyField) && !empty($strIdentificador) ){
            $strQuery = "SELECT {$strKeyField} FROM {$strTableName} WHERE MD5({$strKeyField}) = '{$strIdentificador}'";
            $intReturn = sqlGetValueFromKey($strQuery);
        }
        
        return $intReturn;
        
    }
    
    
    public function getModulos($intModulo = 0) {
        
        $arrModulos = array();
        
        $intIdioma = core_get_idioma();
        
        $intModulo = intval($intModulo);
        $strFilter = "";
        if( $intModulo )
            $strFilter = "AND    modulo.modulo NOT IN({$intModulo})
                          AND    modulo.activo = 'Y'";
        
        $strQuery = "SELECT modulo.modulo, modulo.codigo, modulo.publico, modulo.privado, modulo.activo,
                            modulo_idioma.nombre
                     FROM   modulo_idioma, modulo
                     WHERE  modulo_idioma.idioma = {$intIdioma}
                     AND    modulo_idioma.modulo = modulo.modulo
                     {$strFilter}
                     ORDER BY activo, orden, nombre";
                     
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ) {
            $arrModulos[$rTMP["modulo"]]["codigo"] = $rTMP["codigo"];
            $arrModulos[$rTMP["modulo"]]["publico"] = $rTMP["publico"];
            $arrModulos[$rTMP["modulo"]]["privado"] = $rTMP["privado"];
            $arrModulos[$rTMP["modulo"]]["activo"] = $rTMP["activo"];
            $arrModulos[$rTMP["modulo"]]["nombre"] = $rTMP["nombre"];
            $arrModulos[$rTMP["modulo"]]["modulo"] = $rTMP["modulo"];
        }
        db_free_result($qTMP);
        
        return $arrModulos;
    }
    
    public function getInfoModulo($intModulo) {
        
        $arrModulo = array();
        
        $intModulo = intval($intModulo);
        
        $strQuery = "SELECT modulo.modulo, modulo.codigo, modulo.orden, modulo.publico, modulo.privado, modulo.activo,
                            modulo_idioma.idioma, modulo_idioma.nombre, modulo_dependencia.dependencia
                     FROM   modulo_idioma,
                            modulo
                                LEFT JOIN modulo_dependencia
                                    ON  modulo.modulo = modulo_dependencia.modulo
                     WHERE  modulo.modulo = {$intModulo}
                     AND    modulo_idioma.modulo = modulo.modulo
                     ORDER BY orden";
        
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ) {
            
            $arrModulo["codigo"] = $rTMP["codigo"];
            $arrModulo["orden"] = $rTMP["orden"];
            $arrModulo["publico"] = $rTMP["publico"];
            $arrModulo["privado"] = $rTMP["privado"];
            $arrModulo["activo"] = $rTMP["activo"];
            $arrModulo["modulo"] = $rTMP["modulo"];
            $arrModulo["idiomas"][$rTMP["idioma"]]["nombre"] = $rTMP["nombre"];
            
            if( !isset($arrModulo["modulo_dependencia"]) )
                $arrModulo["modulo_dependencia"] = array();
            
            if( intval($rTMP["dependencia"]) > 0 )
                $arrModulo["modulo_dependencia"][$rTMP["dependencia"]] = $rTMP["dependencia"];
            
        }
        db_free_result($qTMP);
        
        return $arrModulo;
    }
    
    
    
    public function getEtiquetas($intModulo){
        
        $arrEtiquetas = array();
        $intModulo = intval($intModulo);
        
        $intIdioma = core_get_idioma();
        
        $strQuery = "SELECT lang.lang, lang_idioma.valor
                     FROM   lang, lang_idioma
                     WHERE  lang_idioma.idioma = {$intIdioma}
                     AND    lang_idioma.lang = lang.lang
                     AND    lang_idioma.modulo = lang.modulo
                     AND    lang.modulo = {$intModulo}
                     ORDER BY lang.lang";
                     
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ) {
            $arrEtiquetas[$rTMP["lang"]]["codigo"] = $rTMP["lang"];
            $arrEtiquetas[$rTMP["lang"]]["valor"] = $rTMP["valor"];
        }
        db_free_result($qTMP);
        
        return $arrEtiquetas;
        
    }
    
    public function getInfoEtiqueta($intModulo, $strLang){
        
        $arrEtiqueta = array();
        
        $intModulo = intval($intModulo);
        
        if( $intModulo && !empty($strLang) ){
            
            $strQuery = "SELECT lang.lang, lang.modulo, lang_idioma.idioma, lang_idioma.valor
                         FROM   lang_idioma, lang
                         WHERE  lang.modulo = {$intModulo}
                         AND    lang.lang = '{$strLang}'
                         AND    lang_idioma.lang = lang.lang
                         AND    lang.modulo = lang_idioma.modulo";
            
            $qTMP = db_query($strQuery);
            while( $rTMP = db_fetch_assoc($qTMP) ) {
                
                $arrEtiqueta["codigo"] = $rTMP["lang"];
                $arrEtiqueta["modulo"] = $rTMP["modulo"];
                $arrEtiqueta["idiomas"][$rTMP["idioma"]]["nombre"] = $rTMP["valor"];
                
            }
            db_free_result($qTMP);
            
        }
        
        return $arrEtiqueta;
        
    }
    
    
    
    public function getTiposDato(){
        
        $arrInfo = array();
        
        $arrInfo["texto"] = "Texto";
        $arrInfo["fecha"] = "Fecha";
        $arrInfo["descripcion"] = "Descripcion";
        $arrInfo["lista"] = "Lista";
        $arrInfo["checkbox"] = "Checkbox";
        
        return $arrInfo;
        
    }
    
    public function getVariablesConfig($intModulo){
        
        $arrVarConfig = array();
        $intModulo = intval($intModulo);
        
        $intIdioma = core_get_idioma();
        
        $strQuery = "SELECT configuracion.modulo, configuracion.codigo, configuracion.tipo_dato, configuracion.valores, configuracion.valor,
                            configuracion_idioma.nombre, configuracion_idioma.descripcion
                     FROM   configuracion, configuracion_idioma
                     WHERE  configuracion_idioma.idioma = {$intIdioma}
                     AND    configuracion_idioma.codigo = configuracion.codigo
                     AND    configuracion_idioma.modulo = configuracion.modulo
                     AND    configuracion.modulo = {$intModulo}
                     ORDER BY configuracion.codigo";
                     
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ) {
            
            $arrVarConfig[$rTMP["codigo"]]["codigo"] = $rTMP["codigo"];
            $arrVarConfig[$rTMP["codigo"]]["nombre"] = $rTMP["nombre"];
            $arrVarConfig[$rTMP["codigo"]]["tipo_dato"] = $rTMP["tipo_dato"];
            $arrVarConfig[$rTMP["codigo"]]["valor"] = $rTMP["valor"];
            $arrVarConfig[$rTMP["codigo"]]["valores"] = $rTMP["valores"];
            $arrVarConfig[$rTMP["codigo"]]["descripcion"] = $rTMP["descripcion"];
            $arrVarConfig[$rTMP["codigo"]]["modulo"] = $rTMP["modulo"];
        }
        db_free_result($qTMP);
        
        return $arrVarConfig;
        
    }
    
    public function getInfoVariablesConfig($intModulo, $strCodigo){
        
        $arrConfig = array();
        
        $intModulo = intval($intModulo);
        
        if( $intModulo && !empty($strCodigo) ){
            
            $strQuery = "SELECT configuracion.codigo, configuracion.modulo, configuracion.tipo_dato, configuracion.valores, configuracion.valor,
                                configuracion_idioma.idioma, configuracion_idioma.nombre, configuracion_idioma.descripcion
                         FROM   configuracion_idioma, configuracion
                         WHERE  configuracion.modulo = {$intModulo}
                         AND    configuracion.codigo = '{$strCodigo}'
                         AND    configuracion_idioma.codigo = configuracion.codigo
                         AND    configuracion.modulo = configuracion_idioma.modulo";
            
            $qTMP = db_query($strQuery);
            while( $rTMP = db_fetch_assoc($qTMP) ) {
                
                $arrConfig["codigo"] = $rTMP["codigo"];
                $arrConfig["modulo"] = $rTMP["modulo"];
                $arrConfig["tipo_dato"] = $rTMP["tipo_dato"];
                $arrConfig["valores"] = $rTMP["valores"];
                $arrConfig["valor"] = $rTMP["valor"];
                $arrConfig["idiomas"][$rTMP["idioma"]]["nombre"] = $rTMP["nombre"];
                $arrConfig["idiomas"][$rTMP["idioma"]]["descripcion"] = $rTMP["descripcion"];
                
            }
            db_free_result($qTMP);
            
        }
        
        return $arrConfig;
        
    }
    
    
    
    public function getTiposAcceso(){
        
        $arrTiposAcceso = array();
        
        $intIdioma = core_get_idioma();
        
        $strQuery = "SELECT tipo_acceso.tipo_acceso, tipo_acceso.codigo, tipo_acceso.orden, tipo_acceso.activo,
                            tipo_acceso_idioma.nombre
                     FROM   tipo_acceso_idioma, tipo_acceso
                     WHERE  tipo_acceso_idioma.idioma = {$intIdioma}
                     AND    tipo_acceso_idioma.tipo_acceso = tipo_acceso.tipo_acceso
                     ORDER BY tipo_acceso.orden, tipo_acceso_idioma.nombre";
                     
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ) {
            $arrTiposAcceso[$rTMP["tipo_acceso"]]["tipo_acceso"] = $rTMP["tipo_acceso"];
            $arrTiposAcceso[$rTMP["tipo_acceso"]]["codigo"] = $rTMP["codigo"];
            $arrTiposAcceso[$rTMP["tipo_acceso"]]["orden"] = $rTMP["orden"];
            $arrTiposAcceso[$rTMP["tipo_acceso"]]["activo"] = $rTMP["activo"];
            $arrTiposAcceso[$rTMP["tipo_acceso"]]["nombre"] = $rTMP["nombre"];
        }
        db_free_result($qTMP);
        
        return $arrTiposAcceso;
        
    }
    
    public function getInfoTipoAcceso($intTipoAcceso) {
        
        $arrTipoAcceso = array();
        
        $intTipoAcceso = intval($intTipoAcceso);
        
        $strQuery = "SELECT tipo_acceso.tipo_acceso, tipo_acceso.codigo, tipo_acceso.orden, tipo_acceso.activo,
                            tipo_acceso_idioma.idioma, tipo_acceso_idioma.nombre
                     FROM   tipo_acceso_idioma, tipo_acceso
                     WHERE  tipo_acceso.tipo_acceso = {$intTipoAcceso}
                     AND    tipo_acceso_idioma.tipo_acceso = tipo_acceso.tipo_acceso";
        
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ) {
            
            $arrTipoAcceso["tipo_acceso"] = $rTMP["tipo_acceso"];
            $arrTipoAcceso["codigo"] = $rTMP["codigo"];
            $arrTipoAcceso["orden"] = $rTMP["orden"];
            $arrTipoAcceso["activo"] = $rTMP["activo"];
            $arrTipoAcceso["idiomas"][$rTMP["idioma"]]["nombre"] = $rTMP["nombre"];
            
        }
        db_free_result($qTMP);
        
        return $arrTipoAcceso;
    }
    
    
    
    public function getAccesos($intModulo,$intAcceso) {
        
        $arrAccesos = array();
        
        $intModulo = intval($intModulo);
        $intAcceso = intval($intAcceso);
        
        if( $intModulo ){
            
            $intIdioma = core_get_idioma();
            
            $strFilter = "AND    acceso.acceso_pertenece IS NULL";
            if( $intAcceso )
                $strFilter = "AND    acceso.acceso_pertenece = {$intAcceso}";
            
            $strQuery = "SELECT acceso.acceso, acceso.modulo, acceso.codigo, acceso.orden, acceso.acceso_pertenece, acceso.path, acceso.publico, acceso.privado, acceso.activo,
                                acceso_idioma.nombre_menu
                         FROM   acceso, acceso_idioma
                         WHERE  acceso_idioma.idioma = {$intIdioma}
                         AND    acceso_idioma.acceso = acceso.acceso
                         AND    acceso.modulo = {$intModulo}
                         {$strFilter}
                         ORDER BY acceso.orden, acceso_idioma.nombre_menu";
                         
            $qTMP = db_query($strQuery);
            while( $rTMP = db_fetch_assoc($qTMP) ) {
                $arrAccesos[$rTMP["acceso"]]["acceso"] = $rTMP["acceso"];
                $arrAccesos[$rTMP["acceso"]]["modulo"] = $rTMP["modulo"];
                $arrAccesos[$rTMP["acceso"]]["codigo"] = $rTMP["codigo"];
                $arrAccesos[$rTMP["acceso"]]["orden"] = $rTMP["orden"];
                $arrAccesos[$rTMP["acceso"]]["acceso_pertenece"] = $rTMP["acceso_pertenece"];
                $arrAccesos[$rTMP["acceso"]]["path"] = $rTMP["path"];
                $arrAccesos[$rTMP["acceso"]]["publico"] = $rTMP["publico"];
                $arrAccesos[$rTMP["acceso"]]["privado"] = $rTMP["privado"];
                $arrAccesos[$rTMP["acceso"]]["activo"] = $rTMP["activo"];
                $arrAccesos[$rTMP["acceso"]]["nombre"] = $rTMP["nombre_menu"];
            }
            db_free_result($qTMP);
            
        }
        
        return $arrAccesos;
    }
    
    public function getAccesoPertenece($intAcceso){
        
        $intAcceso = intval($intAcceso);
        $intAccesoPertenece = 0;
        
        if( $intAcceso ){
            
            $strQuery = "SELECT acceso_pertenece FROM acceso WHERE acceso = {$intAcceso}";
            $intAccesoPertenece = sqlGetValueFromKey($strQuery);
        }
        $intAccesoPertenece = intval($intAccesoPertenece);
        
        return $intAccesoPertenece;
        
    }
    
    public function getInfoAccesos($intModulo, $intAcceso){
        
        $arrAcceso = array();
        
        $intModulo = intval($intModulo);
        $intAcceso = intval($intAcceso);
        
        if( $intModulo && $intAcceso ){
            
            $intIdioma = core_get_idioma();
            
            $strQuery = "SELECT acceso.acceso, acceso.modulo, acceso.codigo, acceso.orden, acceso.path, acceso.publico, acceso.privado, acceso.activo,
                                acceso_idioma.idioma, acceso_idioma.nombre_menu, acceso_idioma.nombre_pantalla, tipo_permitido.tipo_acceso
                         FROM   acceso
                                    LEFT JOIN acceso_tipo_permitido tipo_permitido
                                        ON acceso.acceso = tipo_permitido.acceso,
                                acceso_idioma
                         WHERE  acceso.modulo = {$intModulo}
                         AND    acceso.acceso = {$intAcceso}
                         AND    acceso_idioma.acceso = acceso.acceso";
            
            $qTMP = db_query($strQuery);
            while( $rTMP = db_fetch_assoc($qTMP) ) {
                
                $arrAcceso["acceso"] = $rTMP["acceso"];
                $arrAcceso["modulo"] = $rTMP["modulo"];
                $arrAcceso["codigo"] = $rTMP["codigo"];
                $arrAcceso["orden"] = $rTMP["orden"];
                $arrAcceso["path"] = $rTMP["path"];
                $arrAcceso["publico"] = $rTMP["publico"];
                $arrAcceso["privado"] = $rTMP["privado"];
                $arrAcceso["activo"] = $rTMP["activo"];
                
                if( !isset($arrAcceso["idiomas"]) ) $arrAcceso["idiomas"] = array();
                
                if( intval($rTMP["idioma"]) > 0 ){
                    $arrAcceso["idiomas"][$rTMP["idioma"]]["nombre_menu"] = $rTMP["nombre_menu"];
                    $arrAcceso["idiomas"][$rTMP["idioma"]]["nombre_pantalla"] = $rTMP["nombre_pantalla"];
                }
                
                if( !isset($arrAcceso["acceso_permitido"]) ) $arrAcceso["acceso_permitido"] = array();
                
                if( intval($rTMP["tipo_acceso"]) ){
                    $arrAcceso["acceso_permitido"][$rTMP["tipo_acceso"]] = $rTMP["tipo_acceso"];
                }
                
            }
            db_free_result($qTMP);
            
        }
        
        return $arrAcceso;
        
    }
    
    
    
    
    
    public function insertModulo($strCodigo,$intOrden,$strPrivado,$strPublico,$strActivo){
        
        global $strQuerysPrint;
        
        $intModulo = 0;
        
        $strCodigo = substr($strCodigo, 0, 15);
        $intOrden = intval($intOrden);
        $strPrivado = ( $strPrivado == "Y" ) ? "Y" : "N";
        $strPublico = ( $strPublico == "Y" ) ? "Y" : "N";
        $strActivo = ( $strActivo == "Y" ) ? "Y" : "N";
        
        if( !empty($strCodigo) && $intOrden ){
            
            $intAddUser = isset($_SESSION["wt"]["originalUserToTest"]) ? intval($_SESSION["wt"]["originalUserToTest"]) : intval($_SESSION["hml"]["persona"]);
            
            $strQuery = "INSERT INTO modulo (codigo,orden,publico,privado,activo,add_user,add_fecha) VALUES ('{$strCodigo}',{$intOrden},'{$strPublico}','{$strPrivado}','{$strActivo}',{$intAddUser},NOW());";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
            $intModulo = db_insert_id();
            
        }
        
        return $intModulo;
        
    }
    
    public function updateModulo($intModulo,$strCodigo,$intOrden,$strPrivado,$strPublico,$strActivo){
        
        global $strQuerysPrint;
        
        $intModulo = intval($intModulo);
        $strCodigo = substr($strCodigo, 0, 15);
        $intOrden = intval($intOrden);
        $strPrivado = ( $strPrivado == "Y" ) ? "Y" : "N";
        $strPublico = ( $strPublico == "Y" ) ? "Y" : "N";
        $strActivo = ( $strActivo == "Y" ) ? "Y" : "N";
        
        if( $intModulo && !empty($strCodigo) && $intOrden ){
            
            $intAddUser = isset($_SESSION["wt"]["originalUserToTest"]) ? intval($_SESSION["wt"]["originalUserToTest"]) : intval($_SESSION["hml"]["persona"]);
            
            $strQuery = "UPDATE modulo SET codigo ='{$strCodigo}', orden = {$intOrden}, publico = '{$strPublico}', privado = '{$strPrivado}', activo = '{$strActivo}', mod_user = {$intAddUser}, mod_fecha = NOW() WHERE  modulo = {$intModulo};";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
        }
        
    }
    
    public function deleteModuloIdioma($intModulo){
        
        global $strQuerysPrint;
        
        $intModulo = intval($intModulo);
        
        if( $intModulo ){
            
            $strQuery = "DELETE FROM modulo_idioma WHERE modulo = {$intModulo};";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
        }
        
    }
    
    public function deleteModuloDependencia($intModulo){
        
        global $strQuerysPrint;
        
        $intModulo = intval($intModulo);
        
        if( $intModulo ){
            
            $strQuery = "DELETE FROM modulo_dependencia WHERE modulo = {$intModulo};";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
        }
        
    }
    
    public function insertModuloIdioma($intModulo, $intIdioma, $strNombre){
        
        global $strQuerysPrint;

        $intModulo = intval($intModulo);
        $intIdioma = intval($intIdioma);
        $strNombre = substr($strNombre,0,40);
        
        if( $intModulo && $intIdioma && !empty($strNombre) ){
            
            $intAddUser = isset($_SESSION["wt"]["originalUserToTest"]) ? intval($_SESSION["wt"]["originalUserToTest"]) : intval($_SESSION["hml"]["persona"]);
            
            $strQuery = "INSERT INTO modulo_idioma (modulo,idioma,nombre) VALUES ({$intModulo},{$intIdioma},'{$strNombre}');";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
        }
        
    }
    
    public function insertModuloDependencia($intModulo, $intModuloDependencia){
        
        global $strQuerysPrint;

        $intModulo = intval($intModulo);
        $intModuloDependencia = intval($intModuloDependencia);
        
        if( $intModulo && $intModuloDependencia ){
            
            $strQuery = "INSERT INTO modulo_dependencia (modulo,dependencia) VALUES ({$intModulo},{$intModuloDependencia});";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
        }
        
    }
    
    public function insertIdioma($strCodigo,$strNombre){
        
        global $strQuerysPrint;

        $intIdioma = 0;
        
        $strCodigo = substr($strCodigo, 0, 5);
        $strNombre = substr($strNombre, 0, 15);
        
        if( !empty($strCodigo) && !empty($strNombre) ){
            
            $intAddUser = isset($_SESSION["wt"]["originalUserToTest"]) ? intval($_SESSION["wt"]["originalUserToTest"]) : intval($_SESSION["hml"]["persona"]);
            
            $strQuery = "INSERT INTO idioma (codigo,nombre,add_user,add_fecha) VALUES ('{$strCodigo}','{$strNombre}',{$intAddUser},NOW());";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
            $intIdioma = db_insert_id();
            
        }
        
        return $intIdioma;
        
    }
    
    public function updateIdioma($intIdioma,$strCodigo,$strNombre){
        
        global $strQuerysPrint;

        $intIdioma = intval($intIdioma);
        $strCodigo = substr($strCodigo, 0, 5);
        $strNombre = substr($strNombre, 0, 15);
        
        if( $intIdioma && !empty($strCodigo) && !empty($strNombre) ){
            
            $intAddUser = isset($_SESSION["wt"]["originalUserToTest"]) ? intval($_SESSION["wt"]["originalUserToTest"]) : intval($_SESSION["hml"]["persona"]);
            
            $strQuery = "UPDATE idioma SET codigo = '{$strCodigo}', nombre = '{$strNombre}', mod_user = {$intAddUser}, mod_fecha = NOW() WHERE idioma = {$intIdioma};";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
        }
        
    }
    
    public function insertLang($intModulo,$strCodigo){
        
        global $strQuerysPrint;

        $strLang = "";
        
        $intModulo = intval($intModulo);
        $strCodigo = substr($strCodigo, 0, 50);
        
        if( $intModulo && !empty($strCodigo) ){
            
            $intAddUser = isset($_SESSION["wt"]["originalUserToTest"]) ? intval($_SESSION["wt"]["originalUserToTest"]) : intval($_SESSION["hml"]["persona"]);
            
            $strQuery = "INSERT INTO lang (lang,modulo,add_user,add_fecha) VALUES ('{$strCodigo}',{$intModulo},{$intAddUser},NOW());";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
            $strLang = $strCodigo;
            
        }
        
        return $strLang;
    }
    
    public function updateLang($intModulo,$strLang,$strCodigo){
        
        global $strQuerysPrint;

        $intModulo = intval($intModulo);
        $strCodigo = substr($strCodigo, 0, 50);
        
        if( $intModulo && !empty($strLang) && !empty($strCodigo) ){
            
            $intAddUser = isset($_SESSION["wt"]["originalUserToTest"]) ? intval($_SESSION["wt"]["originalUserToTest"]) : intval($_SESSION["hml"]["persona"]);
            
            $strQuery = "UPDATE lang SET lang = '{$strCodigo}', mod_user = {$intAddUser}, mod_fecha = NOW() WHERE  lang = '{$strLang}' AND    modulo = {$intModulo};";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
        }
        
    }
    
    public function deleteLangIdioma($intModulo, $strLang){
        
        global $strQuerysPrint;

        $intModulo = intval($intModulo);
        
        if( $intModulo && !empty($strLang) ){
            
            $strQuery = "DELETE FROM lang_idioma WHERE modulo = {$intModulo} AND lang = '{$strLang}';";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
        }
        
    }
    
    public function insertLangIdioma($intModulo, $strLang, $intIdioma, $strValor){
        
        global $strQuerysPrint;

        $intModulo = intval($intModulo);
        $intIdioma = intval($intIdioma);
        
        if( $intModulo && !empty($strLang) && $intIdioma && !empty($strValor) ){
            
            $intAddUser = isset($_SESSION["wt"]["originalUserToTest"]) ? intval($_SESSION["wt"]["originalUserToTest"]) : intval($_SESSION["hml"]["persona"]);
            
            $strQuery = "INSERT INTO lang_idioma (lang,modulo,idioma,valor) VALUES ('{$strLang}',{$intModulo},{$intIdioma},'{$strValor}');";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
        }
        
    }
    
    public function insertTipoAcceso($intOrden,$strCodigo,$strActivo){
        
        global $strQuerysPrint;

        $intTipoAcceso = 0;
        
        $intOrden = intval($intOrden);
        $strCodigo = substr($strCodigo, 0, 15);
        $strActivo = ( $strActivo == "Y" ) ? "Y" : "N";
        
        if( $intOrden && !empty($strCodigo) ){
            
            $intAddUser = isset($_SESSION["wt"]["originalUserToTest"]) ? intval($_SESSION["wt"]["originalUserToTest"]) : intval($_SESSION["hml"]["persona"]);
            
            $strQuery = "INSERT INTO tipo_acceso (codigo,orden,activo,add_user,add_fecha) VALUES ('{$strCodigo}',{$intOrden},'{$strActivo}',{$intAddUser},NOW());";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
            $intTipoAcceso = db_insert_id();
            
        }
        
        return $intTipoAcceso;
        
    }
    
    public function updateTipoAcceso($intTipoAcceso,$intOrden,$strCodigo,$strActivo){
        
        global $strQuerysPrint;

        $intTipoAcceso = intval($intTipoAcceso);
        $strCodigo = substr($strCodigo, 0, 15);
        $intOrden = intval($intOrden);
        $strActivo = ( $strActivo == "Y" ) ? "Y" : "N";
        
        if( $intTipoAcceso && !empty($strCodigo) && $intOrden ){
            
            $intAddUser = isset($_SESSION["wt"]["originalUserToTest"]) ? intval($_SESSION["wt"]["originalUserToTest"]) : intval($_SESSION["hml"]["persona"]);
            
            $strQuery = "UPDATE tipo_acceso SET codigo ='{$strCodigo}', orden = {$intOrden}, activo = '{$strActivo}', mod_user = {$intAddUser}, mod_fecha = NOW() WHERE  tipo_acceso = {$intTipoAcceso};";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
        }
        
    }
    
    public function deleteTipoAccesoIdioma($intTipoAcceso){
        
        global $strQuerysPrint;

        $intTipoAcceso = intval($intTipoAcceso);
        
        if( $intTipoAcceso ){
            
            $strQuery = "DELETE FROM tipo_acceso_idioma WHERE tipo_acceso = {$intTipoAcceso};";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
        }
        
    }
    
    public function insertTipoAccesoIdioma($intTipoAcceso, $intIdioma, $strNombre){
        
        global $strQuerysPrint;

        $intTipoAcceso = intval($intTipoAcceso);
        $intIdioma = intval($intIdioma);
        $strNombre = substr($strNombre,0,40);
        
        if( $intTipoAcceso && $intIdioma && !empty($strNombre) ){
            
            $intAddUser = isset($_SESSION["wt"]["originalUserToTest"]) ? intval($_SESSION["wt"]["originalUserToTest"]) : intval($_SESSION["hml"]["persona"]);
            
            $strQuery = "INSERT INTO tipo_acceso_idioma (tipo_acceso,idioma,nombre) VALUES ({$intTipoAcceso},{$intIdioma},'{$strNombre}');";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
        }
        
    }
    
    
    
    public function insertAcceso($intModulo, $intAccesoPertenece,$intOrden,$strCodigo,$strPath,$strPrivado,$strPublico,$strActivo){
        
        global $strQuerysPrint;

        $intAcceso = 0;
        
        $intModulo = intval($intModulo);
        $intAccesoPertenece = intval($intAccesoPertenece);
        $intOrden = intval($intOrden);
        $strCodigo = substr($strCodigo, 0, 25);
        $strPath = substr($strPath,0,150);
        $strPrivado = ( $strPrivado == "Y" ) ? "Y" : "N";
        $strPublico = ( $strPublico == "Y" ) ? "Y" : "N";
        $strActivo = ( $strActivo == "Y" ) ? "Y" : "N";
        
        if( !$intAccesoPertenece ) $intAccesoPertenece = "NULL";
        
        if( $intModulo && $intOrden && !empty($strCodigo) ){
            
            $intAddUser = isset($_SESSION["wt"]["originalUserToTest"]) ? intval($_SESSION["wt"]["originalUserToTest"]) : intval($_SESSION["hml"]["persona"]);
            
            $strQuery = "INSERT INTO acceso (modulo,codigo,orden,acceso_pertenece,path,publico,privado,activo,add_user,add_fecha) VALUES ({$intModulo},'{$strCodigo}',{$intOrden},{$intAccesoPertenece},'{$strPath}','{$strPublico}','{$strPrivado}','{$strActivo}',{$intAddUser},NOW());";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
            $intAcceso = db_insert_id();
            
        }
        
        return $intAcceso;
        
    }
    
    public function updateAcceso($intAcceso,$intOrden,$strCodigo,$strPath,$strPrivado,$strPublico,$strActivo){
        
        global $strQuerysPrint;

        $intAcceso = intval($intAcceso);
        $intOrden = intval($intOrden);
        $strCodigo = substr($strCodigo, 0, 25);
        $strPath = substr($strPath,0,150);
        $strPrivado = ( $strPrivado == "Y" ) ? "Y" : "N";
        $strPublico = ( $strPublico == "Y" ) ? "Y" : "N";
        $strActivo = ( $strActivo == "Y" ) ? "Y" : "N";
        
        if( $intAcceso && $intOrden && !empty($strCodigo) ){
            
            $intAddUser = isset($_SESSION["wt"]["originalUserToTest"]) ? intval($_SESSION["wt"]["originalUserToTest"]) : intval($_SESSION["hml"]["persona"]);
            
            $strQuery = "UPDATE acceso SET codigo ='{$strCodigo}', orden = {$intOrden}, path = '{$strPath}', publico = '{$strPublico}', privado = '{$strPrivado}', activo = '{$strActivo}', mod_user = {$intAddUser}, mod_fecha = NOW() WHERE  acceso = {$intAcceso};";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
        }
        
    }
    
    public function deleteAccesoIdioma($intAcceso){
        
        global $strQuerysPrint;

        $intAcceso = intval($intAcceso);
        
        if( $intAcceso ){
            
            $strQuery = "DELETE FROM acceso_idioma WHERE acceso = {$intAcceso};";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
        }
        
    }
    
    public function deleteAccesoPermitido($intAcceso){
        
        global $strQuerysPrint;

        $intAcceso = intval($intAcceso);
        
        if( $intAcceso ){
            
            $strQuery = "DELETE FROM acceso_tipo_permitido WHERE acceso = {$intAcceso};";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
        }
        
    }
    
    public function insertAccesoIdioma($intAcceso, $intIdioma, $strNombreMenu, $strNombrePantalla){
        
        global $strQuerysPrint;

        $intAcceso = intval($intAcceso);
        $intIdioma = intval($intIdioma);
        $strNombreMenu = substr($strNombreMenu,0,75);
        $strNombrePantalla = substr($strNombrePantalla,0,75);
        
        if( $intAcceso && $intIdioma && !empty($strNombreMenu) && !empty($strNombrePantalla) ){
            
            $intAddUser = isset($_SESSION["wt"]["originalUserToTest"]) ? intval($_SESSION["wt"]["originalUserToTest"]) : intval($_SESSION["hml"]["persona"]);
            
            $strQuery = "INSERT INTO acceso_idioma (acceso,idioma,nombre_menu,nombre_pantalla) VALUES ({$intAcceso},{$intIdioma},'{$strNombreMenu}','{$strNombrePantalla}');";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
        }
        
    }
    
    public function insertAccesoTipoAcceso($intAcceso, $intTipoAcceso){
        
        global $strQuerysPrint;

        $intAcceso = intval($intAcceso);
        $intTipoAcceso = intval($intTipoAcceso);
        
        if( $intAcceso && $intTipoAcceso ){
            
            $intAddUser = isset($_SESSION["wt"]["originalUserToTest"]) ? intval($_SESSION["wt"]["originalUserToTest"]) : intval($_SESSION["hml"]["persona"]);
            
            $strQuery = "INSERT INTO acceso_tipo_permitido (acceso,tipo_acceso) VALUES ({$intAcceso},{$intTipoAcceso});";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
        }
        
    }
    
    
    public function insertConfig($intModulo,$strCodigo,$strTipoDato,$strValor,$strValores){
        
        global $strQuerysPrint;

        $strConfig = "";
        
        $intModulo = intval($intModulo);
        $strCodigo = substr($strCodigo, 0, 20);
        
        if( $intModulo && !empty($strCodigo) ){
            
            $intAddUser = isset($_SESSION["wt"]["originalUserToTest"]) ? intval($_SESSION["wt"]["originalUserToTest"]) : intval($_SESSION["hml"]["persona"]);
            
            $strQuery = "INSERT INTO configuracion (modulo,codigo,tipo_dato,valores,valor,add_user,add_fecha) VALUES ({$intModulo},'{$strCodigo}','{$strTipoDato}','{$strValores}','{$strValor}',{$intAddUser},NOW());";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
            $strConfig = $strCodigo;
            
        }
        
        return $strConfig;
    }
    
    public function updateConfig($intModulo,$strConfig,$strCodigo,$strTipoDato,$strValor,$strValores){
        
        global $strQuerysPrint;

        $intModulo = intval($intModulo);
        $strCodigo = substr($strCodigo, 0, 50);
        
        $strValores = empty($strValores) ? "NULL" : "'{$strValores}'";
        
        if( $intModulo && !empty($strConfig) && !empty($strCodigo) ){
            
            $intAddUser = isset($_SESSION["wt"]["originalUserToTest"]) ? intval($_SESSION["wt"]["originalUserToTest"]) : intval($_SESSION["hml"]["persona"]);
            
            $strQuery = "UPDATE configuracion SET codigo = '{$strCodigo}', tipo_dato = '{$strTipoDato}', valores = {$strValores},  valor = '{$strValor}',  mod_user = {$intAddUser}, mod_fecha = NOW() WHERE codigo = '{$strConfig}' AND modulo = {$intModulo};";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
        }
        
    }
    
    public function deleteConfigIdioma($intModulo, $strConfig){
        
        global $strQuerysPrint;

        $intModulo = intval($intModulo);
        
        if( $intModulo && !empty($strConfig) ){
            
            $strQuery = "DELETE FROM configuracion_idioma WHERE modulo = {$intModulo} AND codigo = '{$strConfig}';";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
        }
        
    }
    
    public function insertConfigIdioma($intModulo, $strConfig, $intIdioma, $strNombre, $strDescripcion){
        
        global $strQuerysPrint;

        $intModulo = intval($intModulo);
        $intIdioma = intval($intIdioma);
        
        if( $intModulo && !empty($strConfig) && $intIdioma && !empty($strNombre) && !empty($strDescripcion) ){
            
            $intAddUser = isset($_SESSION["wt"]["originalUserToTest"]) ? intval($_SESSION["wt"]["originalUserToTest"]) : intval($_SESSION["hml"]["persona"]);
            
            $strQuery = "INSERT INTO configuracion_idioma (modulo,codigo,idioma,nombre,descripcion) VALUES ({$intModulo},'{$strConfig}',{$intIdioma},'{$strNombre}','{$strDescripcion}');";
            db_query($strQuery);
            
            $strQuerysPrint .= (empty($strQuerysPrint) ? "" : "<br><br>").$strQuery;
            
        }
        
    }
    
    
}