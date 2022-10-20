<?php

function getInfoEstados() {
    $arrData = array();
    $arrData["PROCESO_LLENADO"]["texto"] = "En proceso de llenado";
    $arrData["PROCESO_LLENADO"]["bg"] = "#000000";
    $arrData["PROCESO_LLENADO"]["icono"] = "icon-idc-edit";
    $arrData["DEPARTAMENTO_COMERCIAL"]["texto"] = "Departamento comercial";
    $arrData["DEPARTAMENTO_COMERCIAL"]["bg"] = "#E26B0A";
    $arrData["DEPARTAMENTO_COMERCIAL"]["icono"] = "icon-idc-list";
    $arrData["REVISION_DOCUMENTOS"]["texto"] = "Revisión legal de documentos";
    $arrData["REVISION_DOCUMENTOS"]["bg"] = "#44AF19";
    $arrData["REVISION_DOCUMENTOS"]["icono"] = "icon-idc-file";
    $arrData["UNIDAD_CUMPLIMIENTO"]["texto"] = "Unidad de cumplimiento";
    $arrData["UNIDAD_CUMPLIMIENTO"]["bg"] = "#F39C12";
    $arrData["UNIDAD_CUMPLIMIENTO"]["icono"] = "icon-idc-checklist-on-clipboard";
    $arrData["PENDIENTE_FIRMAS"]["texto"] = "Pendiente de firmas";
    $arrData["PENDIENTE_FIRMAS"]["bg"] = "#02536E";
    $arrData["PENDIENTE_FIRMAS"]["icono"] = "icon-idc-signing-the-contract";
    $arrData["PENDIENTE_AUTORIZAR"]["texto"] = "Pendientes de autorizar";
    $arrData["PENDIENTE_AUTORIZAR"]["bg"] = "#8064A2";
    $arrData["PENDIENTE_AUTORIZAR"]["icono"] = "icon-idc-check";
    $arrData["AUTORIZADO"]["texto"] = "Autorizado";
    $arrData["AUTORIZADO"]["bg"] = "#4BACC6";
    $arrData["AUTORIZADO"]["icono"] = "icon-idc-user1";
    $arrData["POR_VENCER"]["texto"] = "Por vencer";
    $arrData["POR_VENCER"]["bg"] = "#808080";
    $arrData["POR_VENCER"]["icono"] = "icon-idc-warning";
    $arrData["VENCIDO"]["texto"] = "Vencido";
    $arrData["VENCIDO"]["bg"] = "#C00000";
    $arrData["VENCIDO"]["icono"] = "icon-idc-file2";
    return $arrData;
}

function clientes_dashboard() {
    //contenido

}
