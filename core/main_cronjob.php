<?php
date_default_timezone_set("America/Guatemala");

//if ((array_key_exists("CLIENTNAME", $_SERVER) || array_key_exists("SHELL", $_SERVER) || (isset($_SERVER["COMPUTERNAME"]) && ($_SERVER["COMPUTERNAME"] == "HML-CD" || $_SERVER["COMPUTERNAME"] == "HML-JD3"))) && !array_key_exists("HTTP_USER_AGENT", $_SERVER) || true) {
if ( true ) {
    $strTMP = "config.php";
    if (!file_exists($strTMP)) {
        $strTMP = pathinfo(__FILE__);
        $strTMP = $strTMP["dirname"];
        $strTMP = "{$strTMP}/config.php";
    }
    include_once($strTMP);
    $strTMP = "functions.php";
    if (!file_exists($strTMP)) {
        $strTMP = pathinfo(__FILE__);
        $strTMP = $strTMP["dirname"];
        $strTMP = "{$strTMP}/functions.php";
    }
    include_once($strTMP);

    if (!$globalConnection = mysql_connect($arrConfigSite["db"]["host"], $arrConfigSite["db"]["user"], $arrConfigSite["db"]["password"])) {

        $today = getdate();
        $month = $today["month"];
        $year = $today["year"];
        $weekday = $today["weekday"];
        $day = $today["mday"];

        $horas = $today["hours"];
        $minutos = $today["minutes"];
        $segundos = $today["seconds"];

        $strError = "Server: " . $_SERVER["SERVER_NAME"] . "\n File: " . __FILE__ . "\n Error: " . mysql_error() . "\n Time: {$weekday} {$year}-{$month}-{$day} {$horas}:{$minutos}:{$segundos}";

        $strEmails = "asanchez@homeland.com.gt";
        $strEmails = "lmarroquin@homeland.com.gt";
        error_log($strError, 1, $strEmails, "From: servidor@{$_SERVER["SERVER_NAME"]}\r\n");

    }
    else {

        $strDate = date("Y-m-d H:i:s");
        $strCommand = "whoami";
        $strReturn = exec($strCommand, $arrOutput, $arrReturn);

        // Busca archivos modulo_cron.php en los directorios de los modulos y los ejecuta, luego busca updates en el servidor de updates y los copia con FTP.
        print "Cronjob principal: Busca updates para los modulos y ejecuta cronjobs de cada modulo.\n Usuario: {$strReturn}\n Hora: {$strDate}";

        // 20100611 AG: Me aseguro que el directirio var/tmp tenga acceso de escritura... en este directorio vamos a ir agregando los caches que sean necesarios
        @mkdir("../var/tmp");
        $strReturn = @exec("chmod 777 ../var/tmp");

        mysql_select_db($arrConfigSite["db"]["database"]);
        $arrModule = array();
        $arrConfigModule = array();
        $strQuery ='SELECT  configuracion.modulo,
                            configuracion.codigo,
                            configuracion.valor,
                            modulo.codigo modulonombre
                    FROM    configuracion
                            INNER JOIN modulo
                                ON  configuracion.modulo = modulo.modulo
                    ORDER   BY configuracion.modulo,
                            configuracion.codigo';
        $qTMP = mysql_query($strQuery);
        while ($rTMP = mysql_fetch_array($qTMP)) {
            $arrModule[$rTMP["modulo"]] = $rTMP["modulonombre"];
            $arrConfigModule[$rTMP["modulo"]][$rTMP["codigo"]]["valor"] = $rTMP["valor"];
        }
        mysql_free_result($qTMP);

        print "\n\n***** CRON JOB DE MODULOS *****";
        $strPath = "../modules";
        if (!file_exists($strPath)) {
            $strPath = pathinfo(__FILE__);
            $strPath = $strPath["dirname"];
            $strPath = "{$strPath}/../modules";
        }
        $strPath .= "/";

        $strPath = str_replace("/modules/", "/", $strPath);

        $objConexion = mysql_connect($arrConfigSite["db"]["host"], $arrConfigSite["db"]["user"], $arrConfigSite["db"]["password"], true);
        mysql_select_db($arrConfigSite["db"]["database"], $objConexion);

        reset($arrConfigModule);
        while( $arrTMP = each($arrConfigModule) ) {
            print "\n\n". $arrModule[$arrTMP['key']];
            if( isset($arrTMP['value']["globalCronJob"]['valor']) && $arrTMP['value']["globalCronJob"]['valor'] ) {
                $strFile = "{$strPath}modules/{$arrModule[$arrTMP['key']]}/cronjob.php";
                if (file_exists($strFile)) {
                    print "\n\nCronjob: {$arrTMP["key"]}";
                    include_once($strFile);
                }
            }
        }

        print "\n\nProceso terminado a las:" . date("Y-m-d H:i:s");
    }
}
else {
    print "Acceso denegado\n";
}
die();
?>