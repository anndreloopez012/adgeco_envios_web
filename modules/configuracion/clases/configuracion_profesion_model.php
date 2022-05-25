<?php
class configuracion_profesion_model{

    public function __construct(){

    }

    public function getInfo($intId = 0){
        $arrData = array();
        $strWhere = "";

        if( $intId > 0 ) {
            $strWhere .= "WHERE profesion = {$intId}";
        }
        $strQuery ="SELECT  profesion,
                            nombre,
                            activo
                    FROM    profesion
                    {$strWhere}
                    ORDER BY nombre";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ) {
            $arrData[$rTMP["profesion"]] = $rTMP;
        }
        db_free_result($qTMP);
        unset($arrData[34]);
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