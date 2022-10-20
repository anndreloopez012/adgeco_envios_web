<?php
class configuracion_agencia_model{

    public function __construct(){

    }

    public function getInfo($intId = 0){
        $arrData = array();
        $strWhere = "";

        if( $intId > 0 ) {
            $strWhere .= "WHERE a.agencia = {$intId}";
        }
        $strQuery ="SELECT  a.agencia,
                            a.empresa,
                            a.nombre,
                            a.codigo,
                            a.direccion,
                            a.correo_electronico,
                            a.activo,
                            b.nombre AS nombreEmpresa
                    FROM    agencia AS a
                            INNER JOIN empresa AS b
                                ON a.empresa = b.empresa
                    {$strWhere}";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ) {
            $arrData[$rTMP["agencia"]] = $rTMP;
        }
        db_free_result($qTMP);
        return $arrData;
    }

    public function getInfoEmpresa($intEmpresaEnUso = 0){
        $arrData = array();
        $strQuery ="SELECT  empresa,
                            UPPER(nombre) AS nombre,
                            activo
                    FROM    empresa
                    WHERE   activo = 'Y'
                    OR      empresa = {$intEmpresaEnUso}
                    ORDER   BY nombre";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ){
            $arrData[$rTMP["empresa"]]["texto"] = $rTMP["nombre"];
            if( $rTMP["empresa"] == $intEmpresaEnUso )
                $arrData[$rTMP["empresa"]]["selected"] = true;
        }
        db_free_result($qTMP);
        if( count($arrData) > 1 ) {
            $arrData[""]["texto"] = "--SELECCIONE UNA OPCIoN--";
            ksort($arrData);
        }
        return $arrData;
    }

    public function getDescribe($strTable) {
        $arrData = array();

        $strQuery = "DESCRIBE {$strTable}";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ) {
            $arrData[$rTMP["Field"]] = $rTMP["Field"];
        }
        db_free_result($qTMP);

        return $arrData;

    }
}