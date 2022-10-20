<?php
class clientes_cliente_model{

    private $intCliente;
    private $intClienteHistorico;

    public function __construct($intCliente){
        $this->intCliente = $intCliente;
    }

    public static function getClienteMD5($strCliente) {
        //22af645d1859cb5ca6da0c484f1f37ea = new
        //cfcd208495d565ef66e7dff9f98764da = 0
        if( $strCliente == "22af645d1859cb5ca6da0c484f1f37ea" || $strCliente == "cfcd208495d565ef66e7dff9f98764da" ) {
            $intReturn = 0;
        }
        else {
            $strQuery ="SELECT  cliente
                        FROM    cliente
                        WHERE   MD5(cliente) = '{$strCliente}'";
            $intReturn = sqlGetValueFromKey($strQuery);
            $intReturn = $intReturn ? $intReturn : -1;
        }
        return $intReturn;
    }

    public function getInfoPais($intPaisEnUso = 0){
        $arrData = array();
        $arrData["0"]["texto"] = "--SELECCIONE UNA OPCIoN--";
        $strQuery ="SELECT  pais,
                            UPPER(nombre) AS nombre,
                            activo,
                            predeterminado
                    FROM    pais
                    WHERE   activo = 'Y'
                    OR      pais = {$intPaisEnUso}
                    ORDER   BY nombre";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ){
            $arrData[$rTMP["pais"]]["texto"] = $rTMP["nombre"];
            if( $rTMP["pais"] == $intPaisEnUso ) {
                $arrData[$rTMP["pais"]]["selected"] = true;
            }else if ($rTMP["predeterminado"] == "Y") {
                if($intPaisEnUso == 0) {
                    $arrData[$rTMP["pais"]]["selected"] = true;
                }
            }
        }
        db_free_result($qTMP);
        return $arrData;
    }

    public function getInfoDepartamento($intPais,$intDepartamentoEnUso = 0){
        $arrData = array();
        $arrData["0"]["texto"] = "---NO APLICA---";
        $strQuery ="SELECT  departamento,
                            UPPER(nombre) AS nombre,
                            activo
                    FROM    departamento
                    WHERE   pais = {$intPais}
                    AND     (activo = 'Y'
                    OR      departamento = {$intDepartamentoEnUso})
                    ORDER   BY nombre";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ){
            $arrData[$rTMP["departamento"]]["texto"] = $rTMP["nombre"];
            if( $rTMP["departamento"] == $intDepartamentoEnUso )
                $arrData[$rTMP["departamento"]]["selected"] = true;
        }
        db_free_result($qTMP);
        return $arrData;
    }

    public function getInfoMunicipio($intDepartamento,$intMunicipioEnUso = 0){
        $arrData = array();
        $arrData["0"]["texto"] = "---NO APLICA---";
        $strQuery ="SELECT  municipio,
                            UPPER(nombre) AS nombre
                    FROM    municipio
                    WHERE   departamento = {$intDepartamento}
                    AND     (activo = 'Y'
                    OR      municipio = {$intMunicipioEnUso})
                    ORDER   BY nombre";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ){
            $arrData[$rTMP["municipio"]]["texto"] = $rTMP["nombre"];
            if( $rTMP["municipio"] == $intMunicipioEnUso )
                $arrData[$rTMP["municipio"]]["selected"] = true;
        }
        db_free_result($qTMP);
        return $arrData;
    }

    public function getInfoLugar($intLugarEnUso = 0){
        $arrData = array();
        $strQuery ="SELECT  lugar,
                            UPPER(nombre) AS nombre,
                            activo
                    FROM    lugar
                    WHERE   activo = 'Y'
                    OR      lugar = {$intLugarEnUso}
                    ORDER   BY nombre";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ){
            $arrData[$rTMP["lugar"]]["texto"] = $rTMP["nombre"];
            if( $rTMP["lugar"] == $intLugarEnUso )
                $arrData[$rTMP["lugar"]]["selected"] = true;
        }
        db_free_result($qTMP);
        if( count($arrData) > 1 ) {
            $arrData[""]["texto"] = "--SELECCIONE UNA OPCIoN--";
            ksort($arrData);
        }
        return $arrData;
    }

    public function getInfoEstados(){
        $arrData = array();
        $arrData["INGRESADO"]["texto"] = "INGRESADO";
        $arrData["ASIGNADO"]["texto"] = "ASIGNADO";
        $arrData["ENTREGADO"]["texto"] = "ENTREGADO";
        $arrData["PENDIENTE"]["texto"] = "PENDIENTE";
        $arrData["FINALIZADO"]["texto"] = "FINALIZADO";
        return $arrData;
    }

    public function getInfoZona(){
        $arrData = array();
        $arrData["1"]["texto"] = "1";
        $arrData["2"]["texto"] = "2";
        $arrData["3"]["texto"] = "3";
        $arrData["4"]["texto"] = "4";
        $arrData["5"]["texto"] = "5";
        $arrData["6"]["texto"] = "6";
        $arrData["7"]["texto"] = "7";
        $arrData["8"]["texto"] = "8";
        $arrData["9"]["texto"] = "9";
        $arrData["10"]["texto"] = "10";
        $arrData["11"]["texto"] = "11";
        $arrData["12"]["texto"] = "12";
        $arrData["13"]["texto"] = "13";
        $arrData["14"]["texto"] = "14";
        $arrData["15"]["texto"] = "15";
        $arrData["16"]["texto"] = "16";
        $arrData["17"]["texto"] = "17";
        $arrData["18"]["texto"] = "18";
        $arrData["19"]["texto"] = "19";
        $arrData["20"]["texto"] = "20";
        $arrData["21"]["texto"] = "21";
        return $arrData;
    }

    public function getInfoFilas(){
        $arrData = array();
        $arrData["100"]["texto"] = "100";
        $arrData["200"]["texto"] = "200";
        $arrData["300"]["texto"] = "300";
        $arrData["400"]["texto"] = "400";
        $arrData["500"]["texto"] = "500";
        $arrData[""]["texto"] = "All";
        return $arrData;
    }

    public function getInfoEstadosExt(){
        return getInfoEstados();
    }

    public function getInfoPiloto($intPiloto = 0){
        $arrData = array();
        $strQuery ="SELECT  p.persona,
                    UPPER(p.nombre_usual) AS nombre
                    FROM persona AS p
                    LEFT JOIN usuario AS u
                    ON p.persona = u.persona
                    WHERE   u.tipo ='mensajero'
                    ORDER BY p.nombre_usual";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ){
            $arrData[$rTMP["persona"]]["texto"] = $rTMP["nombre"];
            if( $rTMP["persona"] == $intPiloto )
                $arrData[$rTMP["persona"]]["selected"] = true;
        }
        db_free_result($qTMP);
        if( count($arrData) > 1 ) {
            $arrData[""]["texto"] = "--SELECCIONE UNA OPCION--";
            ksort($arrData);
        }
        return $arrData;
    }

    public function getInfoRuta($intRuta = 0){
        $arrData = array();
        $strQuery ="SELECT ruta,
                    UPPER(nombre) AS nombre
                    FROM ruta 
                    ORDER BY ruta DESC LIMIT 20";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ){
            $arrData[$rTMP["ruta"]]["texto"] = $rTMP["nombre"];
            if( $rTMP["ruta"] == $intRuta )
                $arrData[$rTMP["ruta"]]["selected"] = true;
        }
        db_free_result($qTMP);
        if( count($arrData) > 1 ) {
            $arrData[""]["texto"] = "--SELECCIONE UNA OPCION--";
            ksort($arrData);
        }
        return $arrData;
    }

    
    public function getInfoClienteConsultaDetalle($intCliente = 0){

        $arrData = array();
        $strQuery ="SELECT  c.cliente,
                            c.longitud,
                            c.nombre,
                            c.latitud,
                            c.mapa_url,
                            a.latitud_mensajero,
                            a.longitud_mensajero,
                            a.comentario_mensajero,
                            a.fecha_entrega_mensajero
                    FROM    cliente AS c
                    LEFT JOIN cliente_asigna_piloto AS a
                    ON c.cliente = a.cliente
                    WHERE c.cliente = {$intCliente}";
            $qTMP = db_query($strQuery);
            
            while( $rTMP = db_fetch_assoc($qTMP) ){
                    $arrData = $rTMP;
            }
            db_free_result($qTMP);
        return $arrData;
    }

    public function getInfoClienteConsultaAdjunto($intCarpeta = 0){
        $intCliente = isset($_POST['cliente']) ? db_escape(user_input_delmagic($_POST['cliente'],true)) : 0;

        $arrData = array();
        $strQuery ="SELECT  adjuntos_clientes,
                            nombre_adjunto,
                            documento_identificacion,
                            path_adjunto
                    FROM    adjuntos_clientes
                    WHERE persona = {$intCliente}
                    AND documento_identificacion = {$intCarpeta}
                    ORDER BY adjuntos_clientes DESC";
                                //print($strQuery);

            $qTMP = db_query($strQuery);
            while( $rTMP = db_fetch_assoc($qTMP) ){
                //$arrData[$rTMP["estado"]][$rTMP["cliente"]] = $rTMP;
                $arrData[$rTMP["adjuntos_clientes"]] = $rTMP;
            }
            db_free_result($qTMP);
            
        return $arrData;
    }

    public function getInfoClienteConsultaAdjuntoPdf($intCarpeta = 0, $intCliente = 0){
        $arrData = array();
        $strQuery ="SELECT  adjuntos_clientes,
                            nombre_adjunto,
                            documento_identificacion,
                            path_adjunto
                    FROM    adjuntos_clientes
                    WHERE persona = {$intCliente}
                    AND documento_identificacion = {$intCarpeta}
                    ORDER BY adjuntos_clientes DESC
                    LIMIT 2";
            $qTMP = db_query($strQuery);
            //print($strQuery);
            while( $rTMP = db_fetch_assoc($qTMP) ){
                //$arrData[$rTMP["estado"]][$rTMP["cliente"]] = $rTMP;
                $arrData[$rTMP["adjuntos_clientes"]] = $rTMP;
            }
            db_free_result($qTMP);
            
        return $arrData;
    }

    public function getInfoClienteConsulta($strCodigo,$strMunicipio,$strDireccion,$strEstado,$strPiloto,$strDepartamento,$strZona,$strFilas,$strRuta,$strFechaDe,$strFechaHasta,$strRecibo,$strCarga){
        $arrData = array();
        $strWhereFilter = "";
        if( !empty($strCodigo) ) {
            if( intval($strCodigo) > 0 ) {
                $strWhereFilter .= getFilterQuery("c.cliente", intval($strCodigo), false);
                $strWhereFilter .= " OR ";
                $strWhereFilter .= getFilterQuery("c.cif", $strCodigo, false);
                $strWhereFilter .= " OR ";
                $strWhereFilter .= getFilterQuery("c.crm", $strCodigo, false);
                $strWhereFilter .= " OR ";
            }
            $strWhereFilter .= getFilterQuery("c.nombre", $strCodigo, false);
        }

        if( !empty($strMunicipio) ) {
            $strWhereFilter .= empty($strWhereFilter) ? "" : " AND ";
            $strWhereFilter .= getFilterQuery("c.zona_municipio", $strMunicipio, false);
        }

        if( !empty($strDireccion) ) {
            $strWhereFilter .= empty($strWhereFilter) ? "" : " AND ";
            $strWhereFilter .= getFilterQuery("c.direccion", $strDireccion, false);
        }

        if( !empty($strEstado) ) {
            $strWhereFilter .= empty($strWhereFilter) ? "" : " AND ";
            //$strWhereFilter .= getFilterQuery("c.estado", $strEstado, false);
            $strWhereFilter .= "c.estado IN({$strEstado})";

        }

        if( !empty($strPiloto) ) {
            $strWhereFilter .= empty($strWhereFilter) ? "" : " AND ";
            $strWhereFilter .= "p.persona IN({$strPiloto})";
        }

        if( !empty($strDepartamento) ) {
            $strWhereFilter .= empty($strWhereFilter) ? "" : " AND ";
            $strWhereFilter .= getFilterQuery("c.departamento", $strDepartamento, false);
        }

        if( !empty($strRuta) ) {
            $strWhereFilter .= empty($strWhereFilter) ? "" : " AND ";
            $strWhereFilter .= getFilterQuery("a.ruta", $strRuta, false);
        }

        if( !empty($strZona) ) {
            $strZona = trim($strZona);
            $strWhereFilter .= empty($strWhereFilter) ? "" : " AND ";
            $strWhereFilter .= "c.zona_municipio IN({$strZona})";
        }

        if( !empty($strFechaDe) && !empty($strFechaHasta) ) {
            $strFechaDe = trim($strFechaDe);
            $strFechaHasta = trim($strFechaHasta);
            $strWhereFilter .= empty($strWhereFilter) ? "" : " AND ";
            $strWhereFilter .= "c.fecha_entrega_cliente BETWEEN'$strFechaDe' AND '$strFechaHasta'";
        }

        if( !empty($strCarga) ) {
            $strZona = trim($strCarga);
            $strWhereFilter .= empty($strWhereFilter) ? "" : " AND ";
            $strWhereFilter .= "c.codigo_carga_update IN({$strCarga})";
        }

        if( !empty($strRecibo) ) {
            $strZona = trim($strRecibo);
            $strWhereFilter .= empty($strWhereFilter) ? "" : " AND ";
            $strWhereFilter .= "c.recibo IN({$strRecibo})";
        }

        if( !empty($strWhereFilter) ) {
            $strWhereFilter = "WHERE {$strWhereFilter}";
        }

        
        if( $strFilas ) {
            $strLimit = "LIMIT {$strFilas}";
        }else{
            $strLimit = "";
        }
        
        $strOrder = "ORDER BY c.nombre, c.direccion DESC";
        
        $strQuery ="SELECT  c.cliente,
                            c.cif,
                            c.crm,
                            c.nombre,
                            c.direccion,
                            c.horario,
                            c.telefono,
                            c.zona_municipio,
                            c.departamento,
                            c.tc,
                            c.fecha_entrega_cliente,
                            c.ejecutivo_ventas,
                            CASE c.estado
                                WHEN 'INGRESADO' THEN 1
                                WHEN 'ASIGNADO' THEN 2
                                WHEN 'ENTREGADO' THEN 3
                                END AS estado,
                            c.estado AS estado_entrega,
                            IF(c.mod_fecha IS NULL,c.add_fecha,c.mod_fecha) AS fecha,
                            a.ruta,
                            p.nombre_usual AS piloto
                    FROM    cliente AS c
                    LEFT JOIN cliente_asigna_piloto AS a
                    on a.cliente = c.cliente
                    LEFT JOIN persona AS p
                    on p.persona = a.persona
                    {$strWhereFilter}
                    {$strOrder}
                    {$strLimit}";
            $qTMP = db_query($strQuery);
            //print($strQuery);
            while( $rTMP = db_fetch_assoc($qTMP) ){
                //$arrData[$rTMP["estado"]][$rTMP["cliente"]] = $rTMP;
                $arrData[$rTMP["cliente"]] = $rTMP;
            }
            db_free_result($qTMP);
            
        return $arrData;
    }
    
    
}