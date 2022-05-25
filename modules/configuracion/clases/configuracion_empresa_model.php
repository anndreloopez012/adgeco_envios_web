<?php
class configuracion_empresa_model{

    public function __construct(){

    }

    public function getInfo($intId = 0){
        $arrData = array();
        $strWhere = "";

        if( $intId > 0 ) {
            $strWhere .= "WHERE empresa = {$intId}";
        }
        $strQuery ="SELECT  empresa,
                            nombre,
                            giin,
                            por_defecto,
                            activo
                    FROM    empresa
                    {$strWhere}";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ) {
            $arrData[$rTMP["empresa"]] = $rTMP;
        }
        db_free_result($qTMP);
        return $arrData;
    }

    public function getInfoDefault($intEmpresa){
        $arrData = array();
        $strQuery ="SELECT  empresa
                    FROM    empresa
                    WHERE   empresa != {$intEmpresa}
                    AND     por_defecto = 'Y'";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ){
            $arrData[$rTMP["empresa"]] = $rTMP;
        }
        db_free_result($qTMP);
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