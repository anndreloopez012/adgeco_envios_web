<?php

if (strstr($_SERVER["PHP_SELF"], "/core/")) die ("You can't access this file directly...");

function db_connect($strHost, $strDatabase, $strUser, $strPassword, $boolNewLink = false) {
    
    global $arrConfigSite;

    $intReportingError = error_reporting();
    error_reporting(0);
    
    //DETERMINAR SI TIENE CONEXIÓN CON MYSQL
    $objConexion = mysql_connect($strHost, $strUser, $strPassword, $boolNewLink);
    
    //SI NO TIENE CONEXION ENTONCES MANDO UN CORREO AL ADMINISTRADOR QUE EVALUE LA CONEXIÓN
    if( $objConexion ) {
        
        if (db_select_database($strDatabase, $objConexion)) {
            $arrConfigSite["db"]["database_conexion"] = true;
        }
        
    }
    else {
        
        $today = getdate();
        
        $month = $today["month"];
        $year = $today["year"];
        $weekday = $today["weekday"];
        $day = $today["mday"];

        $horas = $today["hours"];
        $minutos = $today["minutes"];
        $segundos = $today["seconds"];
        
        $strError = "Servidor: {$_SERVER["SERVER_NAME"]}\n";
        $strError .= "Error: ". mysql_error() ."\n";
        $strError .= "No. Error: ". mysql_errno() ."\n";
        $strError .= "Hora: {$weekday} {$year}-{$month}-{$day} {$horas}:{$minutos}:{$segundos} \n";
        
        //ENVIO UN CORREO CON EL ERROR
        error_log($strError,1,$arrConfigSite["config"]["infomail"]);
        
        die();
        
    }
    
    error_reporting($intReportingError);
    $arrConfigSite["db"]["database_resource"] = $objConexion;
    
    return $arrConfigSite["db"]["database_conexion"];
}

function db_select_database($strDataBase, $objConnection = false) {
    
    global $arrConfigSite;
    if (!is_resource($objConnection))
        $objConnection = $arrConfigSite["db"]["database_resource"];

    return mysql_select_db($strDataBase, $objConnection);
}

function db_close($objConnection = false) {
    
    global $arrConfigSite;
    if (!is_resource($objConnection))
        $objConnection = $arrConfigSite["db"]["database_resource"];
    
    return mysql_close($objConnection);
    
}

function db_escape($strString, $objConnection = false) {
    
    global $arrConfigSite;
    if (!is_resource($objConnection)) 
        $objConnection = $arrConfigSite["db"]["database_resource"];

    $strString = mysql_real_escape_string($strString, $objConnection);

    return $strString;
}

function db_escape_reference(&$strString, $objConnection = false) {
    
    global $arrConfigSite;
    if (!is_resource($objConnection)) 
        $objConnection = $arrConfigSite["db"]["database_resource"];

    $strString = mysql_real_escape_string($strString, $objConnection);

    return $strString;
}

function db_query_localDev($argQry, $boolLogError = true, $objConnection = false) {
    
    global $arrConfigSite, $cfg, $arrGlobalNotFreedQueries;
    if (!is_resource($objConnection)) 
        $objConnection = $arrConfigSite["db"]["database_resource"];

    $arrBackTrace = false;
    $sinQueryStart = 0;
    $sinQueryEnd = 0;
    $boolQueryPerformanceLog = (isset($cfg["core"]["query_performance_log"]))?$cfg["core"]["query_performance_log"]:false;

    if ($boolQueryPerformanceLog) $sinQueryStart = getmicrotime();
    $qTMP = mysql_query($argQry, $objConnection);
    if ($boolQueryPerformanceLog) $sinQueryEnd = getmicrotime();

    $strError = mysql_error($objConnection);
    if (strlen($strError)>0) {
        if ($boolLogError) {
            print_r("<hr>{$strError}<br><pre>");
            print_r(debug_backtrace());
            print_r("</pre><hr>");
        }
        $varReturn = false;
    }
    else {
        $varReturn = $qTMP;
    }

    if (substr(strtolower($argQry), 0, 6) == "select" && false) {
        $strTMP = strval($varReturn);
        $arrTMP = explode("#", $strTMP);

        if (isset($arrTMP[1])) {
            $intQNumber = intval($arrTMP[1]);

            if ($arrBackTrace === false) $arrBackTrace = debug_backtrace();

            //drawDebug($arrBackTrace);
            $strTMP = var_export($arrBackTrace, true);
            $arrInfo = array();
            $arrInfo["query"] = $argQry;
            $arrInfo["backtrace"] = $strTMP;

            $arrGlobalNotFreedQueries[$intQNumber] = $arrInfo;
        }
    }

    if ($boolQueryPerformanceLog) {
        if ($arrBackTrace === false) $arrBackTrace = debug_backtrace();
        $strTMP = db_escape(var_export($arrBackTrace, true));

        $strPhpSessID = session_id();

        $sinTime = $sinQueryEnd - $sinQueryStart;

        $strQuery = db_escape($argQry);
        $strQueryLog = "INSERT INTO wt_queries_log
                        (uid, sessid, clickCounter, fecha, strQuery, strBackTrace, processed)
                        VALUES
                        ({$_SESSION["wt"]["uid"]}, '{$strPhpSessID}', {$_SESSION["wt"]["clickCount"]}, NOW(), '{$strQuery}', '{$strTMP}', '{$sinTime}')";
        mysql_query($strQueryLog);
    }

    return $varReturn;
}

function db_query_online($argQry, $boolLogError = true, $objConnection = false) {
    global $arrConfigSite, $cfg, $arrGlobalNotFreedQueries;
    if (!is_resource($objConnection)) 
        $objConnection = $arrConfigSite["db"]["database_resource"];

    // Esto desabilita el reporte de errores para el usuario en internet
    $ErrRep = error_reporting();
    error_reporting(0);

    $arrBackTrace = false;

    $boolQueryPerformanceLog = (isset($cfg["core"]["query_performance_log"]) && check_user_class("admin"))?$cfg["core"]["query_performance_log"]:false;

    if ($boolQueryPerformanceLog) $sinQueryStart = getmicrotime();

    $qTMP = mysql_query($argQry, $objConnection);
    if ($boolQueryPerformanceLog) $sinQueryEnd = getmicrotime();

    $strError = mysql_error($objConnection);
    if (strlen($strError)>0) {
        if ($boolLogError) {
            $strEmails = $arrConfigSite["config"]["infomail"];
            $strHeaders  = "MIME-Version: 1.0" . "\r\n";
            $strHeaders .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
            $strHeaders .= "From: servidor@{$_SERVER["SERVER_NAME"]}\r\n";

            $strErrMsg = $argQry."<hr><br>{$strError} in file <b>".basename($_SERVER["PHP_SELF"])."</b><hr>UID: {$_SESSION["wt"]["uid"]}";

            if ($arrBackTrace === false) $arrBackTrace = debug_backtrace();
            $strTMP = var_export($arrBackTrace, true);
            $strErrMsg .= "<hr>Backtrace:<br><pre>{$strTMP}</pre>";

            @error_log($strErrMsg, 1, $strEmails, $strHeaders);
        }
        $varReturn = false;
    }
    else {
        $varReturn = $qTMP;
    }

    if ($boolQueryPerformanceLog) {
        if ($arrBackTrace === false) $arrBackTrace = debug_backtrace();
        $strTMP = db_escape(var_export($arrBackTrace, true));
        $strTMP = "";

        $strPhpSessID = session_id();

        $sinTime = $sinQueryEnd - $sinQueryStart;

        $strQuery = db_escape($argQry);
        $strQueryLog = "INSERT INTO wt_queries_log
                        (uid, sessid, clickCounter, fecha, strQuery, strBackTrace, processed)
                        VALUES
                        ({$_SESSION["wt"]["uid"]}, '{$strPhpSessID}', {$_SESSION["wt"]["clickCount"]}, NOW(), '{$strQuery}', '{$strTMP}', '{$sinTime}')";
        mysql_query($strQueryLog);
    }

    // Restauro el error reporting
    error_reporting($ErrRep);

    return $varReturn;
}

function db_query($argQry, $boolLogError = true, $objConnection = false) {
    
    global $arrConfigSite;
    if (!is_resource($objConnection)) 
        $objConnection = $arrConfigSite["db"]["database_resource"];

    if ($arrConfigSite["config"]["local"]) {
        return db_query_localDev($argQry, $boolLogError, $objConnection);
    }
    else {
        return db_query_online($argQry, $boolLogError, $objConnection);
    }
}

function db_insert_id($objConnection = false) {
    global $arrConfigSite;
    if (!is_resource($objConnection)) 
        $objConnection = $arrConfigSite["db"]["database_resource"];

    return mysql_insert_id($objConnection);
}

function db_affected_rows($objConnection = false) {
    global $arrConfigSite;

    if (!is_resource($objConnection)) 
        $objConnection = $arrConfigSite["db"]["database_resource"];

    return mysql_affected_rows($objConnection);
}

function db_result($argIndex, $argRow=0, $argField=0) {
    return mysql_result($argIndex, $argRow, $argField);
}

function db_fetch_row($argIndex) {
    return mysql_fetch_row($argIndex);
}

function db_fetch_array($argIndex) {
    return mysql_fetch_array($argIndex);
}

function db_fetch_assoc($argIndex) {
    return mysql_fetch_assoc($argIndex);
}

function db_fetch_object($argIndex) {
    return mysql_fetch_object($argIndex);
}

function db_free_result($argIndex) {
    global $arrGlobalNotFreedQueries, $boolGlobalIsLocalDev;

    mysql_free_result($argIndex);

    if ($boolGlobalIsLocalDev) {
        $strTMP = strval($argIndex);
        $arrTMP = explode("#", $strTMP);
        $intQNumber = intval($arrTMP[1]);

        if (isset($arrGlobalNotFreedQueries[$intQNumber])) unset($arrGlobalNotFreedQueries[$intQNumber]);
    }

    return;
}

function db_num_rows($argIndex) {
    return mysql_num_rows($argIndex);
}

function db_num_fields($argIndex) {
    return mysql_num_fields($argIndex);
}

function db_error($objConnection = false) {
    global $arrConfigSite;
    if ($objConnection === false) $objConnection = $arrConfigSite["db"]["database_resource"];

    return mysql_error($objConnection);
}

function db_seek($argIndex, $intRow) {
    return mysql_data_seek ($argIndex, $intRow);
}

function db_get_fields($argIndex){
    if ($field = mysql_fetch_field($argIndex)){
        do {
            $fields[$field->name]['name'] = $field->name;
            $fields[$field->name]['table'] = $field->table;
            $fields[$field->name]['max_length'] = $field->max_length;
            $fields[$field->name]['not_null'] = $field->not_null;

        }while ($field = mysql_fetch_field($argIndex));
    }
    return $fields;
}
