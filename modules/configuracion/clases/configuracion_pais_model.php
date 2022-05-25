<?php
class configuracion_pais_model{

    public function __construct(){

    }

    public function getInfo($intId = 0){
        $arrData = array();
        $strWhere = "";

        if( $intId > 0 ) {
            $strWhere .= "WHERE pais = {$intId}";
        }
        $strQuery ="SELECT  pais,
                            nombre,
                            nacionalidad,
                            activo,
                            predeterminado
                    FROM    pais
                    {$strWhere}
                    ORDER BY nombre";
        $qTMP = db_query($strQuery);
        while( $rTMP = db_fetch_assoc($qTMP) ) {
            $arrData[$rTMP["pais"]] = $rTMP;
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

    public function removePaisPredeterminado($intUser){
        $intUser =  intval($intUser);
        if($intUser > 0){
            $strQuery = "UPDATE pais SET predeterminado = 'N'";
            db_query($strQuery);
        }
    }
}