<?php

class form {

    private $arrFormInfo;
    private $arrFormFields;

    function __construct( $strId = "", $strName = "", $strMethod = "POST", $strAction = "", $strOnSubmit = "", $strClass = "" ) {

        $this->form_setFormInfo($strId, $strName, $strMethod, $strAction, $strOnSubmit, $strClass);

    }

    /* AQUI VA TODO LO QUE ES DE LOS FORMULARIO COMO TAL*/
    /* INFORMACIÓN DE LOS FORMULARIOS */

    public function form_setFormInfo($strId = "", $strName = "", $strMethod = "POST", $strAction = "", $strOnSubmit = "", $strClass = "") {

        $this->form_setFormId($strId);
        $this->form_setFormName($strName);
        $this->form_setFormMethod($strMethod);
        $this->form_setFormAction($strAction);
        $this->form_setFormOnSubmit($strOnSubmit);
        $this->form_setFormClass($strClass);

    }

    public function form_setFormId($strId) {
        $this->arrFormInfo["id"] = $strId;
    }

    public function form_setFormName($strName) {
        $this->arrFormInfo["name"] = $strName;
    }

    public function form_setFormMethod($strMethod) {
        $this->arrFormInfo["method"] = $strMethod;
    }

    public function form_setFormAction($strAction) {
        $this->arrFormInfo["action"] = $strAction;
    }

    public function form_setFormOnSubmit($strOnSubmit) {
        $this->arrFormInfo["onsubmit"] = $strOnSubmit;
    }

    public function form_setFormClass($strClass) {
        $this->arrFormInfo["class"] = $strClass;
    }

    public function form_setExtraTag($strTagName,$strTagValue = "") {
        if( empty($strTagValue) ) $strTagValue = $strTagName;
        $this->arrFormInfo["tags"][$strTagName] = $strTagValue;
    }

    public function form_getFormId() {
        return $this->arrFormInfo["id"];
    }

    public function form_getFormName() {
        return $this->arrFormInfo["name"];
    }

    public function form_getFormMethod() {
        return $this->arrFormInfo["method"];
    }

    public function form_getFormAction() {
        return $this->arrFormInfo["action"];
    }

    public function form_getFormOnSubmit() {
        return $this->arrFormInfo["onsubmit"];
    }

    public function form_getFormClass() {
        return $this->arrFormInfo["class"];
    }

    public function form_openForm() {

        $strExtraTags = "";
        if( isset($this->arrFormInfo["tags"]) ) {

            while( $arrTMP = each($this->arrFormInfo["tags"]) ) {
                $strExtraTags .= " {$arrTMP["key"]}=\"{$arrTMP["value"]}\"";
            }

        }

        ?>
        <form id="<?php print $this->form_getFormId(); ?>" name="<?php print $this->form_getFormName(); ?>" method="<?php print $this->form_getFormMethod(); ?>" action="<?php print $this->form_getFormAction(); ?>"
              onsubmit="<?php print $this->form_getFormOnSubmit(); ?>" class="<?php print $this->form_getFormClass(); ?>" <?php print $strExtraTags; ?> >
        <?php

    }

    public function form_closeForm() {


        ?>
        </form>
        <?php

    }
    /* TERMINAR DE VER EL FORMULARIO */

    /* AQUÍ EMPIEZO CON LOS CAMPOS */
    public function add_input_extraTag($strFieldName, $strExtraTagName, $strExtraTagValue = "") {

        if( empty($strExtraTagValue) ) $strExtraTagValue = $strExtraTagName;
        $this->arrFormFields[$strFieldName]["tags"][$strExtraTagName] = $strExtraTagValue;

    }

    private function getExtraTagsText($strFieldName) {

        $strExtraTags = "";
        if( isset($this->arrFormFields[$strFieldName]["tags"]) ) {
            while( $arrExtraTags = each($this->arrFormFields[$strFieldName]["tags"]) ) {
                if( trim($arrExtraTags["key"]) == "style" && $this->arrFormFields[$strFieldName]["readMode"] ) $arrExtraTags["value"] .= " display: none;";
                $strExtraTags .= " {$arrExtraTags["key"]} = \"{$arrExtraTags["value"]}\"";
            }
        }

        return $strExtraTags;

    }

    public function add_input_text($strFieldNameId, $strValue, $strClass = "", $boolDraw = false, $strPlaceHolder = "", $strToolTip = "", $boolIncludeDiv = false, $boolReadMode = false) {

        $this->arrFormFields[$strFieldNameId]["value"] = $strValue;
        $this->arrFormFields[$strFieldNameId]["placeholder"] = $strPlaceHolder;
        $this->arrFormFields[$strFieldNameId]["includeDiv"] = $boolIncludeDiv;
        $this->arrFormFields[$strFieldNameId]["readMode"] = $boolReadMode;
        $this->arrFormFields[$strFieldNameId]["class"] = $strClass;
        $this->arrFormFields[$strFieldNameId]["toolTip"] = $strToolTip;

        if( $boolDraw ) $this->draw_input_text($strFieldNameId);

    }
    public function add_input_checkbox( $strFieldNameId, $boolChecked = false, $strClass = "", $boolDraw = false, $strToolTip = "", $boolIncludeDiv = false, $boolReadMode = false ) {
        global $lang;

        $this->arrFormFields[$strFieldNameId]["checked"] = $boolChecked;
        $this->arrFormFields[$strFieldNameId]["includeDiv"] = $boolIncludeDiv;
        $this->arrFormFields[$strFieldNameId]["readMode"] = $boolReadMode;
        $this->arrFormFields[$strFieldNameId]["class"] = $strClass;
        $this->arrFormFields[$strFieldNameId]["toolTip"] = $strToolTip;
        $this->arrFormFields[$strFieldNameId]["value"] = $boolChecked ? $lang["core"]["yes"] : $lang["core"]["no"];

        if( $boolDraw ) $this->draw_input_checkbox($strFieldNameId);

    }
    public function add_input_password($strFieldNameId, $strClass = "", $boolDraw = false, $strPlaceHolder = "", $strToolTip = "", $boolIncludeDiv = false, $boolReadMode = false) {

        $this->arrFormFields[$strFieldNameId]["includeDiv"] = $boolIncludeDiv;
        $this->arrFormFields[$strFieldNameId]["placeholder"] = $strPlaceHolder;
        $this->arrFormFields[$strFieldNameId]["readMode"] = $boolReadMode;
        $this->arrFormFields[$strFieldNameId]["class"] = $strClass;
        $this->arrFormFields[$strFieldNameId]["toolTip"] = $strToolTip;

        if( $boolDraw ) $this->draw_input_password($strFieldNameId);

    }
    public function add_input_hidden($strFieldNameId, $strValue, $boolDraw = false) {

        $this->arrFormFields[$strFieldNameId]["value"] = $strValue;

        if( $boolDraw ) $this->draw_input_hidden($strFieldNameId);

    }
    public function add_textarea($strFieldNameId, $strValue, $strClass = "", $boolDraw = false, $strPlaceHolder = "", $strToolTip = "", $boolIncludeDiv = false, $boolReadMode = false) {

        $this->arrFormFields[$strFieldNameId]["value"] = $strValue;
        $this->arrFormFields[$strFieldNameId]["placeholder"] = $strPlaceHolder;
        $this->arrFormFields[$strFieldNameId]["includeDiv"] = $boolIncludeDiv;
        $this->arrFormFields[$strFieldNameId]["readMode"] = $boolReadMode;
        $this->arrFormFields[$strFieldNameId]["class"] = $strClass;
        $this->arrFormFields[$strFieldNameId]["toolTip"] = $strToolTip;

        if( $boolDraw ) $this->draw_textarea($strFieldNameId);

    }
    public function add_select($strFieldNameId, $arrContenido, $strClass = "", $boolDraw = false, $strToolTip = "", $boolIncludeDiv = false, $boolReadMode = false, $boolMultiselect = false) {

        $this->arrFormFields[$strFieldNameId]["contenido"] = $arrContenido;
        $this->arrFormFields[$strFieldNameId]["includeDiv"] = $boolIncludeDiv;
        $this->arrFormFields[$strFieldNameId]["readMode"] = $boolReadMode;
        $this->arrFormFields[$strFieldNameId]["class"] = $strClass;
        $this->arrFormFields[$strFieldNameId]["toolTip"] = $strToolTip;
        $this->arrFormFields[$strFieldNameId]["multiple"] = $boolMultiselect;

        if( $boolDraw ) $this->draw_select($strFieldNameId);

    }
    public function add_multi_select($strFieldNameId, $arrContenido, $strClass = "", $boolDraw = false, $strToolTip = "", $boolIncludeDiv = false, $boolReadMode = false, $boolValidarTodos = false) {

        $this->arrFormFields[$strFieldNameId]["contenido"] = $arrContenido;
        $this->arrFormFields[$strFieldNameId]["includeDiv"] = $boolIncludeDiv;
        $this->arrFormFields[$strFieldNameId]["readMode"] = $boolReadMode;
        $this->arrFormFields[$strFieldNameId]["class"] = $strClass;
        $this->arrFormFields[$strFieldNameId]["toolTip"] = $strToolTip;
        $this->arrFormFields[$strFieldNameId]["validarTodos"] = $boolValidarTodos;

        if( $boolDraw ) $this->draw_multi_select($strFieldNameId);

    }
    public function add_file($strFieldNameId, $strValue, $strText = "", $boolDraw = false, $boolFileTypeOnclick = false, $strFileTypeOnclick = "", $boolIncludeDiv = false, $boolReadMode = false){

        $this->arrFormFields[$strFieldNameId]["value"] = $strValue;
        $this->arrFormFields[$strFieldNameId]["text"] = $strText;
        $this->arrFormFields[$strFieldNameId]["includeDiv"] = $boolIncludeDiv;
        $this->arrFormFields[$strFieldNameId]["readMode"] = $boolReadMode;
        $this->arrFormFields[$strFieldNameId]["FileTypeOnclik"] = $boolFileTypeOnclick;
        $this->arrFormFields[$strFieldNameId]["IdFileTypeOnclick"] = $strFileTypeOnclick;

        if( $boolDraw ) $this->draw_file($strFieldNameId);

    }
    public function add_date_picker($strFieldNameId, $strValue = "", $boolDraw = false, $boolReadMode = false, $boolShowDia = true, $boolShowMes = true, $boolShowHora = false, $boolPrint = true, $strLenguaje = "esp", $strOnSelect = "",$strlanghora = "hour") {

        $this->arrFormFields[$strFieldNameId]["value"] = $strValue;
        $this->arrFormFields[$strFieldNameId]["readMode"] = $boolReadMode;
        $this->arrFormFields[$strFieldNameId]["showDia"] = $boolShowDia;
        $this->arrFormFields[$strFieldNameId]["showMes"] = $boolShowMes;
        $this->arrFormFields[$strFieldNameId]["showHora"] = $boolShowHora;
        $this->arrFormFields[$strFieldNameId]["print"] = $boolPrint;
        $this->arrFormFields[$strFieldNameId]["lenguaje"] = $strLenguaje;
        $this->arrFormFields[$strFieldNameId]["onSelect"] = $strOnSelect;
        $this->arrFormFields[$strFieldNameId]["langHora"] = $strlanghora;

        if( $boolDraw ) $this->draw_date_picker($strFieldNameId);

    }
    public function add_input_radio( $strFieldName, $strFieldId, $strValue, $strText = "", $boolChecked = false, $strClass = "", $strToolTip = "", $boolDraw = false, $boolIncludeDiv = false, $boolReadMode = false ) {

        $this->arrFormFields[$strFieldName]["id"] = $strFieldId;
        $this->arrFormFields[$strFieldName]["value"] = $strValue;
        $this->arrFormFields[$strFieldName]["text"] = $strText;
        $this->arrFormFields[$strFieldName]["checked"] = $boolChecked;
        $this->arrFormFields[$strFieldName]["class"] = $strClass;
        $this->arrFormFields[$strFieldName]["toolTip"] = $strToolTip;
        $this->arrFormFields[$strFieldName]["includeDiv"] = $boolIncludeDiv;
        $this->arrFormFields[$strFieldName]["readMode"] = $boolReadMode;

        if( $boolDraw ) $this->draw_input_radio($strFieldName);

    }


    public function draw_input_text($strFieldNameId) {

        if( isset($this->arrFormFields[$strFieldNameId]) ) {
            $strExtraTags = $this->getExtraTagsText($strFieldNameId);
            $strReadMode = ($this->arrFormFields[$strFieldNameId]["readMode"]) ? " ocultar " : "";
            $strToolTip = (empty($this->arrFormFields[$strFieldNameId]["toolTip"])) ? "" : "rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"{$this->arrFormFields[$strFieldNameId]["toolTip"]}\"";
            ?>
            <input type="text" name="<?php print $strFieldNameId; ?>" id="<?php print $strFieldNameId; ?>" value="<?php print $this->arrFormFields[$strFieldNameId]["value"]; ?>"
                   placeholder="<?php print $this->arrFormFields[$strFieldNameId]["placeholder"]; ?>" <?php print $strExtraTags; ?>  data-content=""
                   class="form-control <?php print $strReadMode.$this->arrFormFields[$strFieldNameId]["class"]; ?>" <?php print $strToolTip; ?> />
            <?php
            if( $this->arrFormFields[$strFieldNameId]["includeDiv"] ) {
                $strReadMode = ($this->arrFormFields[$strFieldNameId]["readMode"]) ? "" : " ocultar";
                ?>
                <div id="divReadMode<?php print $strFieldNameId; ?>" class="form-control-static break-text <?php print $strReadMode; ?>"><?php print $this->arrFormFields[$strFieldNameId]["value"]; ?></div>
                <?php
            }
        }

    }
    public function draw_input_checkbox($strFieldNameId) {

        if( isset($this->arrFormFields[$strFieldNameId]) ) {
            $strExtraTags = $this->getExtraTagsText($strFieldNameId);
            $strReadMode = ($this->arrFormFields[$strFieldNameId]["readMode"]) ? "ocultar " : "";
            $strToolTip = (empty($this->arrFormFields[$strFieldNameId]["toolTip"])) ? "" : "rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"{$this->arrFormFields[$strFieldNameId]["toolTip"]}\"";
            $strChecked = ($this->arrFormFields[$strFieldNameId]["checked"]) ? " checked=\"checked\"" : "";
            $strIcon = ($this->arrFormFields[$strFieldNameId]["checked"]) ? " fa fa-check idc-blue" : " fa fa-close idc-red";
            ?>
            <input type="checkbox" name="<?php print $strFieldNameId; ?>" id="<?php print $strFieldNameId; ?>" <?php print $strChecked. " ". $strExtraTags; ?>
                   class="<?php print $strReadMode.$this->arrFormFields[$strFieldNameId]["class"]; ?>" <?php print $strToolTip; ?> data-content="" />
            <?php
            if( $this->arrFormFields[$strFieldNameId]["includeDiv"] ) {
                $strReadMode = ($this->arrFormFields[$strFieldNameId]["readMode"] ) ? "" : " ocultar";
                ?>
                <div id="divReadMode<?php print $strFieldNameId; ?>" class="<?php print $strReadMode.$strIcon; ?> "></div>
                <?php
            }
        }

    }
    public function draw_input_password($strFieldNameId) {

        if( isset($this->arrFormFields[$strFieldNameId]) ) {
            $strExtraTags = $this->getExtraTagsText($strFieldNameId);
            $strReadMode = ($this->arrFormFields[$strFieldNameId]["readMode"]) ? "ocultar " : "";
            $strToolTip = (empty($this->arrFormFields[$strFieldNameId]["toolTip"])) ? "" : "rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"{$this->arrFormFields[$strFieldNameId]["toolTip"]}\"";
            ?>
            <input type="password" name="<?php print $strFieldNameId; ?>" id="<?php print $strFieldNameId; ?>" <?php print $strExtraTags. " ". $strToolTip; ?>
                   placeholder="<?php print $this->arrFormFields[$strFieldNameId]["placeholder"]; ?>" data-content=""
                   class="form-control <?php print $strReadMode.$this->arrFormFields[$strFieldNameId]["class"]; ?>" />
            <?php
            if( $this->arrFormFields[$strFieldNameId]["includeDiv"] ) {
                $strReadMode = ($this->arrFormFields[$strFieldNameId]["readMode"] ) ? "" : " ocultar";
                ?>
                <div id="divReadMode<?php print $strFieldNameId; ?>" class="form-control-static <?php print $strReadMode; ?>"></div>
                <?php
            }
        }

    }
    public function draw_input_hidden($strFieldNameId) {

        if( isset($this->arrFormFields[$strFieldNameId]) ) {

            $strExtraTags = $this->getExtraTagsText($strFieldNameId);

            ?>
            <input type="hidden" name="<?php print $strFieldNameId; ?>" id="<?php print $strFieldNameId; ?>" value="<?php print $this->arrFormFields[$strFieldNameId]["value"]; ?>" <?php print $strExtraTags; ?>/>
            <?php

        }

    }
    public function draw_textarea($strFieldNameId) {

        if( isset($this->arrFormFields[$strFieldNameId]) ) {
            $strExtraTags = $this->getExtraTagsText($strFieldNameId);
            $strReadMode = ($this->arrFormFields[$strFieldNameId]["readMode"]) ? "ocultar " : "";
            $strToolTip = (empty($this->arrFormFields[$strFieldNameId]["toolTip"])) ? "" : "rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"{$this->arrFormFields[$strFieldNameId]["toolTip"]}\"";
            ?>
            <textarea name="<?php print $strFieldNameId; ?>" id="<?php print $strFieldNameId; ?>" placeholder="<?php print $this->arrFormFields[$strFieldNameId]["placeholder"]; ?>"
                      <?php print $strExtraTags; ?>  data-content="" class="form-control <?php print $strReadMode.$this->arrFormFields[$strFieldNameId]["class"]; ?>"
                      <?php print $strToolTip; ?>><?php print $this->arrFormFields[$strFieldNameId]["value"]; ?></textarea>
            <?php
            if( $this->arrFormFields[$strFieldNameId]["includeDiv"] ) {
                $strReadMode = ($this->arrFormFields[$strFieldNameId]["readMode"] ) ? "" : " ocultar";
                ?>
                <div id="divReadMode<?php print $strFieldNameId; ?>" class="form-control-static break-text <?php print $strReadMode; ?>"><?php print $this->arrFormFields[$strFieldNameId]["value"]; ?></div>
                <?php
            }
        }

    }
    public function draw_select($strFieldNameId) {

        if( isset($this->arrFormFields[$strFieldNameId]) ) {
            $strExtraTags = $this->getExtraTagsText($strFieldNameId);
            $strReadMode = ($this->arrFormFields[$strFieldNameId]["readMode"]) ? "ocultar " : "";
            $strToolTip = (empty($this->arrFormFields[$strFieldNameId]["toolTip"])) ? "" : "rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"{$this->arrFormFields[$strFieldNameId]["toolTip"]}\"";
            $strMultiple = ($this->arrFormFields[$strFieldNameId]["multiple"] == true) ? "multiple=\"multiple\"" : "";
            $strTextoDiv = "";
            ?>
            <select name="<?php print $strFieldNameId; ?>" id="<?php print $strFieldNameId; ?>" data-content="" class="form-control <?php print $strReadMode.$this->arrFormFields[$strFieldNameId]["class"]; ?>" <?php print $strExtraTags. " ". $strToolTip." ". $strMultiple; ?> >
                <?php
                while( $arrGrupo = each($this->arrFormFields[$strFieldNameId]["contenido"]) ) {
                    if( isset($arrGrupo["value"]["options"]) ) {
                        ?>
                        <optgroup label="<?php print $arrGrupo["value"]["nombre"]; ?>">
                            <?php
                            while( $arrTMP = each($arrGrupo["value"]["options"]) ) {
                                $strSelected = (isset($arrTMP["value"]["selected"]) && $arrTMP["value"]["selected"]) ? "selected" : "";
                                $strTexto = isset($arrTMP["value"]["texto"]) ? $arrTMP["value"]["texto"] : $arrTMP["value"];
                                $strTextoDiv = (empty($strSelected)) ? $strTextoDiv : $strTexto;
                                ?>
                                <option value="<?php print $arrTMP["key"]; ?>" <?php print $strSelected; ?>>
                                    <?php print $strTexto; ?>
                                </option>
                                <?php
                            }
                            ?>
                        </optgroup>
                        <?php
                    }
                    else {
                        $strSelected = (isset($arrGrupo["value"]["selected"]) && $arrGrupo["value"]["selected"]) ? "selected" : "";
                        $strTexto = isset($arrGrupo["value"]["texto"]) ? $arrGrupo["value"]["texto"] : $arrGrupo["value"];
                        $strTextoDiv = (empty($strSelected)) ? $strTextoDiv : ( empty($arrGrupo["key"]) ? "" : $strTexto );
                        ?>
                        <option value="<?php print $arrGrupo["key"]; ?>" <?php print $strSelected; ?>><?php print $strTexto; ?></option>
                        <?php
                    }
                }
                ?>
            </select>
            <?php
            if( $this->arrFormFields[$strFieldNameId]["includeDiv"] ) {
                $strReadMode = ($this->arrFormFields[$strFieldNameId]["readMode"] ) ? "" : " ocultar";
                ?>
                <div id="divReadMode<?php print $strFieldNameId; ?>" class="form-control-static break-text <?php print $strReadMode; ?>"><?php print $strTextoDiv; ?></div>
                <?php
            }

        }


    }
    public function draw_multi_select($strFieldNameId) {

        if( isset($this->arrFormFields[$strFieldNameId]) ) {
            $strExtraTags = $this->getExtraTagsText($strFieldNameId);
            $strReadMode = ($this->arrFormFields[$strFieldNameId]["readMode"]) ? "ocultar" : "";
            $strToolTip = (empty($this->arrFormFields[$strFieldNameId]["toolTip"])) ? "" : "rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"{$this->arrFormFields[$strFieldNameId]["toolTip"]}\"" ;
            $strTextoDiv = "" ;
            ?>
            <div id="DivContent<?php print $strFieldNameId;?>" class="<?php print $strReadMode; ?>" >
                <select id="<?php print $strFieldNameId;?>" name="<?php print $strFieldNameId; ?>[]" data-content="" class="<?php print $this->arrFormFields[$strFieldNameId]["class"]; ?>" <?php print $strExtraTags. " ". $strToolTip; ?> multiple="multiple" >
                    <?php
                    while( $arrGrupo = each($this->arrFormFields[$strFieldNameId]["contenido"]) ) {
                        if( isset($arrGrupo["value"]["options"]) ) {
                            ?>
                            <optgroup label="<?php print $arrGrupo["value"]["nombre"]; ?>">
                                <?php
                                while( $arrTMP = each($arrGrupo["value"]["options"]) ) {
                                    $strSelected = (isset($arrTMP["value"]["selected"]) && $arrTMP["value"]["selected"]) ? "selected" : "";
                                    $strTexto = isset($arrTMP["value"]["texto"]) ? $arrTMP["value"]["texto"] : $arrTMP["value"];
                                    $strTextoDiv .= ( $strSelected == "selected" ) ? ( ( empty($strTextoDiv) ? "" : "<br>" ).(empty($strSelected)) ? $strTextoDiv : $strTexto ) : "";
                                    ?>
                                    <option value="<?php print $arrTMP["key"]; ?>" <?php print $strSelected; ?>>
                                        <?php print $strTexto; ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </optgroup>
                            <?php
                        }
                        else {
                            $strSelected = (isset($arrGrupo["value"]["selected"]) && $arrGrupo["value"]["selected"]) ? "selected" : "";
                            $strTexto = isset($arrGrupo["value"]["texto"]) ? $arrGrupo["value"]["texto"] : $arrGrupo["value"];
                            $strTextoDiv .= ( isset($arrGrupo["value"]["selected"]) && $arrGrupo["value"]["selected"] ) ? ( (empty($strSelected)) ? $strTextoDiv : $strTexto )."<br>" : "";
                            ?>
                            <option value="<?php print $arrGrupo["key"]; ?>" <?php print $strSelected; ?>>
                                <?php print $strTexto; ?>
                            </option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <?php
            if( $this->arrFormFields[$strFieldNameId]["includeDiv"] ) {
                $strReadMode = ($this->arrFormFields[$strFieldNameId]["readMode"]) ? "" : "ocultar";
                if( $this->arrFormFields[$strFieldNameId]["validarTodos"] && empty($strTextoDiv) ){
                    $strTextoDiv = "Todos";
                }
                elseif( strlen($strTextoDiv) > 4 ){
                    $strTextoDiv = substr($strTextoDiv,0,-4);
                }
                ?>
                <div id="divReadMode<?php print $strFieldNameId; ?>" class="form-control-static break-text <?php print $strReadMode; ?>"><?php print $strTextoDiv; ?></div>
                <?php
            }
        }

    }
    public function draw_file($strFieldNameId){

        if( isset($this->arrFormFields[$strFieldNameId]) ) {
            $strReadMode = ($this->arrFormFields[$strFieldNameId]["readMode"]) ? "ocultar" : "";
            ?>
            <div id="Content<?php print $strFieldNameId;?>" class="<?php print $strReadMode; ?>">
                <?php
                if( !$this->arrFormFields[$strFieldNameId]["FileTypeOnclik"] ){
                    ?>
                    <table>
                        <tr>
                            <td width="80%" style="width: 80%; height: 32px; border: 1px solid black; text-align: right;position: relative;">
                                <div style="position:absolute; top:0px; left: 0px; width: 100%; z-index:1">
                                    <input type="file" id="<?php print $strFieldNameId;?>" class="form-control" name="<?php print $strFieldNameId;?>" value="<?php print $this->arrFormFields[$strFieldNameId]["value"];?>"  style="width: 1px; height: 10px; visibility: hidden;">
                                </div>
                                <div style="position:absolute; top:0px; left: 0px; width: 100%; z-index:2;">
                                    <input type="" id="Divtext<?php print $strFieldNameId;?>" class="form-control" readonly="readonly" value="<?php print $this->arrFormFields[$strFieldNameId]["text"];?>" style="width: 99%; background-color: white; border-radius: none; border: none;height: 29px; margin: 0 0 0 0; padding: 0 0 0 0;">
                                </div>
                            </td>
                            <td id="tdNombre<?php print $strFieldNameId;?>" width="20%" style=" cursor: pointer; background: #E0E0E0; color: #808080; font-size: 14px;  padding: 1px 25px 1px 5px; border: 1px solid #E0E0E0;" >Examinar...</td>
                        </tr>
                    </table>
                    <script>
                        $(function(){
                            <?php
                            if ( !empty($this->arrFormFields[$strFieldNameId]["IdFileTypeOnclick"]) ){
                                ?>
                                $("#<?php print $this->arrFormFields[$strFieldNameId]["IdFileTypeOnclick"];?>").click(function (){ $("#<?php print $strFieldNameId;?>").click();});
                                <?php
                            }
                            ?>
                            $("#tdNombre<?php print $strFieldNameId;?>").on("click",function (){ $("#<?php print $strFieldNameId;?>").trigger("click");});
                            $("#<?php print $strFieldNameId;?>").change(function (){
                                if( getDocumentLayer("Divtext<?php print $strFieldNameId;?>") )
                                    getDocumentLayer("Divtext<?php print $strFieldNameId;?>").value = $("#<?php print $strFieldNameId;?>").val();
                            });
                        });
                    </script>
                    <?php
                }
                else {

                    ?>
                    <input type="file" id="<?php print $strFieldNameId;?>" name="<?php print $strFieldNameId;?>" style="display: none;" value="<?php print $this->arrFormFields[$strFieldNameId]["value"];?>">
                    <?php
                    if ( !empty($this->arrFormFields[$strFieldNameId]["IdFileTypeOnclick"]) ){
                        ?>
                        <script>
                            $("#<?php print $this->arrFormFields[$strFieldNameId]["IdFileTypeOnclick"];?>").click(function (){ $("#<?php print $strFieldNameId;?>").click();});
                        </script>
                        <?php
                    }

                }
                ?>
            </div>
            <?php
            if( $this->arrFormFields[$strFieldNameId]["includeDiv"] ) {
                $strReadMode = ($this->arrFormFields[$strFieldNameId]["readMode"] ) ? "" : "ocultar";
                ?>
                <div id="divReadMode<?php print $strFieldNameId; ?>" class="form-control-static <?php print $strReadMode; ?>">
                    <?php
                    if( !empty($this->arrFormFields[$strFieldNameId]["value"]) ){
                        ?>
                        <a class="fa fa-download cursor" href="<?php print $this->arrFormFields[$strFieldNameId]["value"]; ?>" download="<?php print $this->arrFormFields[$strFieldNameId]["text"];?>" target="_blank"></a>
                        <?php
                    }
                    ?>

                </div>
                <?php
            }

        }

    }
    public function draw_date_picker($strFieldNameId) {
        global $lang;

        $strValue = $this->arrFormFields[$strFieldNameId]["value"];
        $boolReadMode = $this->arrFormFields[$strFieldNameId]["readMode"];
        $boolShowDia = $this->arrFormFields[$strFieldNameId]["showDia"];
        $boolShowMes = $this->arrFormFields[$strFieldNameId]["showMes"];
        $boolShowHora = $this->arrFormFields[$strFieldNameId]["showHora"];
        $boolPrint = $this->arrFormFields[$strFieldNameId]["print"];
        $strLenguaje = $this->arrFormFields[$strFieldNameId]["lenguaje"];
        $strOnSelect = $this->arrFormFields[$strFieldNameId]["onSelect"];
        $strlanghora = $this->arrFormFields[$strFieldNameId]["langHora"];

        $strReturn = '';
        $strDia = $boolShowDia ? '' : '01';
        $strMes = $boolShowMes ? '' : '01';
        $strAnio = '';
        $strHora = $boolShowHora ? '' : '00';
        $strMinuto = $boolShowHora ? '' : '00';

        $strDisplayEdit = $boolReadMode ? 'hide' : '';
        $strDisplayEtiqueta = $boolReadMode ? '' : 'hide';
        $strValueOriginal = $strValue;
        $strValueOriginal = str_replace('-', '/', trim($strValueOriginal));

        if( !empty($strValue) ) {
            /*if( $strValue == "today" && $boolShowHora ) $strValueOriginal = date("d-m-Y");
            if( $strValue == "today" && !$boolShowHora ) $strValueOriginal = date("d-m-Y:i");
            if( $strValue == "today" ) $strValue = date("d-m-Y:i");      */

           /* if( $strValue == "today" && $boolShowHora ) $strValueOriginal = date("d-m-Y");
            if( $strValue == "today" && !$boolShowHora ) $strValueOriginal = date("d-m-Y:i");
            if( $strValue == "today" ) $strValue = date("d-m-Y:i");*/

            if( $strValue == "today" && $boolShowHora ) $strValueOriginal = date("d/m/Y ");
            if( $strValue == "today" && !$boolShowHora ) $strValueOriginal = date("d/m/Y H:i");
            if( $strValue == "today" ) $strValue = date("d-m-Y H:i");

            $strValue = str_replace('/', '-', trim($strValue));
            $arrFechaHora = explode(' ', $strValue);

            if( isset($arrFechaHora[0]) ) $arrFecha = explode('-', $arrFechaHora[0]);
            if( isset($arrFecha[0]) ) $strDia = $arrFecha[0];
            if( isset($arrFecha[1]) ) $strMes = $arrFecha[1];
            if( isset($arrFecha[2]) ) $strAnio = $arrFecha[2];

            if( isset($arrFechaHora[1]) ) $arrHora = explode(':', $arrFechaHora[1]);
            if( isset($arrHora[0]) ) $strHora = $arrHora[0];
            if( isset($arrHora[1]) ) $strMinuto = $arrHora[1];

            $arrFecha = array_reverse($arrFecha);

            $strValue = isset($arrFecha) ? implode("/", $arrFecha) : '';
            $strValue .= isset($arrHora) ? ' '.implode(":", $arrHora) : '';
        }

        if( $strLenguaje == "esp" ) {
            $strConfiguracionIdioma = ' dayNames: ["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado"],
                                        dayNamesMin: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"],
                                        dayNamesShort: ["Dom","Lun","Mar","Mie","Jue","Vie","Sab"],
                                        monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
                                        monthNamesShort: ["Ene","Feb","Mar","Abr", "May","Jun","Jul","Ago", "Sep","Oct","Nov","Dic"],';
        }
        else if( $strLenguaje == "en" ) {
            $strConfiguracionIdioma = ' dayNames: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                                        dayNamesMin: ["Su","Mo","Tu","We","Th","Fr","Sa"],
                                        dayNamesShort: ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],
                                        monthNames: ["January","February","March","April","May","June","July","August","September","October","November","December"],
                                        monthNamesShort: ["Jan","Feb","Mar","Apr", "May","Jun","Jul","Aug", "Sep","Oct","Nov","Dec"],';
        }
        if( $boolShowMes) {
            $strChangeMonth = 'changeMonth: true,';
        }


        $strImagen = core_getImagePath("calendar.gif");
        $strReturn .= '<div id="divEnable_'.$strFieldNameId.'" class="'.$strDisplayEdit.' inputSizeComplete">';
        $strReturn .= $boolShowDia ? '<input type="text" name="'.$strFieldNameId.'_dia" id="'.$strFieldNameId.'_dia" value="'.$strDia.'" size="2" style="width: 30%;max-width: 40px;" class="text-center" maxlength="2" placeholder="dd">&nbsp;' : '<input type="hidden" name="'.$strFieldNameId.'_dia" id="'.$strFieldNameId.'_dia" value="'.$strDia.'" size="2" style="width: 40px;" maxlength="2">';
        $strReturn .= $boolShowMes ? '<input type="text" name="'.$strFieldNameId.'_mes" id="'.$strFieldNameId.'_mes" value="'.$strMes.'" size="2" style="width: 30%;max-width: 40px;" class="text-center" maxlength="2" placeholder="mm">&nbsp;' : '<input type="hidden" name="'.$strFieldNameId.'_mes" id="'.$strFieldNameId.'_mes" value="'.$strMes.'" size="2" style="width: 40px;" maxlength="2">';
        $strReturn .= '<input type="text" name="'.$strFieldNameId.'_anio" id="'.$strFieldNameId.'_anio" value="'.$strAnio.'" size="4" maxlength="4" placeholder="aaaa" style="width: 30%;max-width: 45px;" class="text-center">';
        $strReturn .= $boolShowHora ? '&nbsp;&nbsp;<input type="text" name="'.$strFieldNameId.'_hora" id="'.$strFieldNameId.'_hora" value="'.$strHora.'" size="2" style="width: 40px;" class="text-center" maxlength="2" placeholder="hh">' : '<input type="hidden" name="'.$strFieldNameId.'_hora" id="'.$strFieldNameId.'_hora" value="'.$strHora.'" size="2" style="width: 40px;" maxlength="2">';
        $strReturn .= $boolShowHora ? '&nbsp;<input type="text" name="'.$strFieldNameId.'_minuto" id="'.$strFieldNameId.'_minuto" value="'.$strMinuto.'" size="2" style="width: 40px;" class="text-center" maxlength="2" placeholder="mm">' : '<input type="hidden" name="'.$strFieldNameId.'_minuto" id="'.$strFieldNameId.'_minuto" value="'.$strMinuto.'" size="2" style="width: 40px;" maxlength="2">';
        $strReturn .= '<input type="hidden" name="'.$strFieldNameId.'" id="'.$strFieldNameId.'" value="'.$strValue.'" readonly="readonly">';
        $strReturn .= '</div>';
        $strReturn .= '<div id="divDisable_'.$strFieldNameId.'" class="'.$strDisplayEtiqueta.'">';
        $strReturn .= $strValueOriginal;
        $strReturn .= '</div>';
        $strReturn .= '<script>';
        $strReturn .= '$(function() {';
        $strReturn .= '$( "#'.$strFieldNameId.'_dia" ).change(function(){';
        $strReturn .= 'if( $( "#'.$strFieldNameId.'_dia" ).val().length > 0 && $( "#'.$strFieldNameId.'_mes" ).val().length > 0 && $( "#'.$strFieldNameId.'_anio" ).val().length > 0 && $( "#'.$strFieldNameId.'_hora" ).val().length > 0 && $( "#'.$strFieldNameId.'_minuto" ).val().length > 0 ) {';
        $strReturn .= '$( "#'.$strFieldNameId.'" ).val($( "#'.$strFieldNameId.'_anio" ).val()+"-"+$( "#'.$strFieldNameId.'_mes" ).val()+"-"+$( "#'.$strFieldNameId.'_dia" ).val()+" "+$( "#'.$strFieldNameId.'_hora" ).val()+":"+$( "#'.$strFieldNameId.'_minuto" ).val())';
        $strReturn .= '}';
        $strReturn .= $strOnSelect;
        $strReturn .= '});';
        $strReturn .= '$( "#'.$strFieldNameId.'_mes" ).change(function(){';
        $strReturn .= 'if( $( "#'.$strFieldNameId.'_dia" ).val().length > 0 && $( "#'.$strFieldNameId.'_mes" ).val().length > 0 && $( "#'.$strFieldNameId.'_anio" ).val().length > 0 && $( "#'.$strFieldNameId.'_hora" ).val().length > 0 && $( "#'.$strFieldNameId.'_minuto" ).val().length > 0 ) {';
        $strReturn .= '$( "#'.$strFieldNameId.'" ).val($( "#'.$strFieldNameId.'_anio" ).val()+"-"+$( "#'.$strFieldNameId.'_mes" ).val()+"-"+$( "#'.$strFieldNameId.'_dia" ).val()+" "+$( "#'.$strFieldNameId.'_hora" ).val()+":"+$( "#'.$strFieldNameId.'_minuto" ).val())';
        $strReturn .= '}';
        $strReturn .= $strOnSelect;
        $strReturn .= '});';
        $strReturn .= '$( "#'.$strFieldNameId.'_anio" ).change(function(){';
        $strReturn .= 'if( $( "#'.$strFieldNameId.'_dia" ).val().length > 0 && $( "#'.$strFieldNameId.'_mes" ).val().length > 0 && $( "#'.$strFieldNameId.'_anio" ).val().length > 0 && $( "#'.$strFieldNameId.'_hora" ).val().length > 0 && $( "#'.$strFieldNameId.'_minuto" ).val().length > 0 ) {';
        $strReturn .= '$( "#'.$strFieldNameId.'" ).val($( "#'.$strFieldNameId.'_anio" ).val()+"-"+$( "#'.$strFieldNameId.'_mes" ).val()+"-"+$( "#'.$strFieldNameId.'_dia" ).val()+" "+$( "#'.$strFieldNameId.'_hora" ).val()+":"+$( "#'.$strFieldNameId.'_minuto" ).val())';
        $strReturn .= '}';
        $strReturn .= $strOnSelect;
        $strReturn .= '});';
        $strReturn .= '$( "#'.$strFieldNameId.'_hora" ).change(function(){';
        $strReturn .= 'if( $( "#'.$strFieldNameId.'_dia" ).val().length > 0 && $( "#'.$strFieldNameId.'_mes" ).val().length > 0 && $( "#'.$strFieldNameId.'_anio" ).val().length > 0 && $( "#'.$strFieldNameId.'_hora" ).val().length > 0 && $( "#'.$strFieldNameId.'_minuto" ).val().length > 0 ) {';
        $strReturn .= '$( "#'.$strFieldNameId.'" ).val($( "#'.$strFieldNameId.'_anio" ).val()+"-"+$( "#'.$strFieldNameId.'_mes" ).val()+"-"+$( "#'.$strFieldNameId.'_dia" ).val()+" "+$( "#'.$strFieldNameId.'_hora" ).val()+":"+$( "#'.$strFieldNameId.'_minuto" ).val())';
        $strReturn .= '}';
        $strReturn .= $strOnSelect;
        $strReturn .= '});';
        $strReturn .= '$( "#'.$strFieldNameId.'_minuto" ).change(function(){';
        $strReturn .= 'if( $( "#'.$strFieldNameId.'_dia" ).val().length > 0 && $( "#'.$strFieldNameId.'_mes" ).val().length > 0 && $( "#'.$strFieldNameId.'_anio" ).val().length > 0 && $( "#'.$strFieldNameId.'_hora" ).val().length > 0 && $( "#'.$strFieldNameId.'_minuto" ).val().length > 0 ) {';
        $strReturn .= '$( "#'.$strFieldNameId.'" ).val($( "#'.$strFieldNameId.'_anio" ).val()+"-"+$( "#'.$strFieldNameId.'_mes" ).val()+"-"+$( "#'.$strFieldNameId.'_dia" ).val()+" "+$( "#'.$strFieldNameId.'_hora" ).val()+":"+$( "#'.$strFieldNameId.'_minuto" ).val())';
        $strReturn .= '}';
        $strReturn .= $strOnSelect;
        $strReturn .= '});';

        if($boolShowHora) {
            $strReturn .= '$( "#'.$strFieldNameId.'" ).datetimepicker({';
            $strReturn .= 'showOn: "button",';
            $strReturn .= 'buttonImage: "'.$strImagen.'",';
            $strReturn .= 'buttonImageOnly: true,';
            $strReturn .= 'dateFormat: "yy-mm-dd",';
            $strReturn .= 'timeFormat: "HH:mm",';
            $strReturn .= $strConfiguracionIdioma;
            $strReturn .= $strChangeMonth;
            $strReturn .= 'timeText: "'. $lang["core"]["tiempo"].'",';
            $strReturn .= 'hourText: "'. $lang["core"]["hora"].'",';
            $strReturn .= 'minuteText:"'. $lang["core"]["minutos"].'",';
            $strReturn .= 'currentText:"'. $lang["core"]["hoy"].'",';
            $strReturn .= 'closeText:"'. $lang["core"]["aceptar"].'",';
            $strReturn .= 'changeYear: true,';
            $strReturn .= 'onSelect: function(dateText,inst) {';
            $strReturn .= 'var arrSplit = dateText.split("-");';
            $strReturn .= 'var arrSplit2 = arrSplit[2].split(" ");';
            $strReturn .= 'var arrSplit3 = arrSplit2[1].split(":");';
            $strReturn .= '$("#'.$strFieldNameId.'_anio").val(arrSplit[0]);';
            $strReturn .= '$("#'.$strFieldNameId.'_mes").val(arrSplit[1]);';
            $strReturn .= '$("#'.$strFieldNameId.'_dia").val(arrSplit2[0]);';
            $strReturn .= '$("#'.$strFieldNameId.'_hora").val(arrSplit3[0]);';
            $strReturn .= '$("#'.$strFieldNameId.'_minuto").val(arrSplit3[1]);';
            $strReturn .= $strOnSelect;
            $strReturn .= '}';
            $strReturn .= '});';
            $strReturn .= '});';
            $strReturn .= '</script>';
        }
        else {
            $strReturn .= '$( "#'.$strFieldNameId.'" ).datepicker({';
            $strReturn .= 'showOn: "button",';
            $strReturn .= 'buttonImage: "'.$strImagen.'",';
            $strReturn .= 'buttonImageOnly: true,';
            $strReturn .= 'dateFormat: "yy-mm-dd",';
            $strReturn .= $strConfiguracionIdioma;
            $strReturn .= $strChangeMonth;
            $strReturn .= 'changeYear: true,';
            $strReturn .= 'onSelect: function(dateText,inst) {';
            $strReturn .= 'var arrSplit = dateText.split("-");';
            $strReturn .= '$("#'.$strFieldNameId.'_anio").val(arrSplit[0]);';
            $strReturn .= '$("#'.$strFieldNameId.'_mes").val(arrSplit[1]);';
            $strReturn .= '$("#'.$strFieldNameId.'_dia").val(arrSplit[2]);';
            $strReturn .= $strOnSelect;
            $strReturn .= '}';
            $strReturn .= '});';
            $strReturn .= '});';
            $strReturn .= '</script>';
        }
        if( $boolPrint ) {
            print $strReturn;
        }
        else {
            return $strReturn;
        }

    }
    public function draw_input_radio($strFieldName) {

        if( isset($this->arrFormFields[$strFieldName]) ) {

            $strExtraTags = $this->getExtraTagsText($strFieldName);
            $strReadMode = ($this->arrFormFields[$strFieldName]["readMode"]) ? "ocultar " : "";
            $strToolTip = (empty($this->arrFormFields[$strFieldName]["toolTip"])) ? "" : "rel=\"tooltip\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"{$this->arrFormFields[$strFieldName]["toolTip"]}\"" ;
            $strChecked = ($this->arrFormFields[$strFieldName]["checked"]) ? " checked=\"checked\"" : "";

            ?>
            <span id="divRadio<?php print $this->arrFormFields[$strFieldName]["id"]; ?>" class="<?php echo $strReadMode; ?>">
                <input type="radio" name="<?php print $strFieldName; ?>" id="<?php print $this->arrFormFields[$strFieldName]["id"]; ?>" value="<?php print $this->arrFormFields[$strFieldName]["value"]; ?>" <?php print $strChecked. " ". $strExtraTags; ?>
                       class="<?php print $this->arrFormFields[$strFieldName]["class"]; ?>" <?php print $strToolTip; ?> data-content="" />
                <?php print $this->arrFormFields[$strFieldName]["text"]; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </span>
            <?php

            if( $this->arrFormFields[$strFieldName]["includeDiv"] ) {

                $strReadMode = ($this->arrFormFields[$strFieldName]["readMode"] ) ? "" : "ocultar";

                ?>
                <div id="divReadMode<?php print $this->arrFormFields[$strFieldName]["id"]; ?>" class="<?php print $strReadMode; ?>">
                    <?php
                    if($this->arrFormFields[$strFieldName]["checked"]){
                        print $this->arrFormFields[$strFieldName]["text"];
                    }
                    ?>
                </div>
                <?php

            }

        }

    }
    /* AQUI VAN LOS TIPOS INPUT */

    public function form_addField($strFieldName, $strType, $strValue = "", $strPlaceHolder = "", $boolReadMode = false, $strToolTip = "", $strClass = "", $boolIncludeDiv = false, $boolDraw = false, $strId = "", $boolRequired = false, $boolDrawGroup = false, $strLabelGroup = "" ) {

        $this->createField($strFieldName,$strType);
        $this->form_field_setValue($strFieldName,$strValue);
        $this->form_field_setPlaceHolder($strFieldName,$strPlaceHolder);
        $this->form_field_setReadMode($strFieldName,$boolReadMode);
        $this->form_field_setToolTip($strFieldName,$strToolTip);
        $this->form_field_setClass($strFieldName,$strClass);
        $this->form_field_setId($strFieldName,$strId);
        $this->form_field_includeDiv($strFieldName,$boolIncludeDiv);

        if( $boolDraw )
            $this->form_drawField($strFieldName, $boolRequired, $boolDrawGroup, $strLabelGroup);

    }

    private function createField($strFieldName, $strType) {
        $this->arrFormFields[$strFieldName] = array();
        $this->arrFormFields[$strFieldName]["type"] = $strType;
    }

    public function form_field_setValue($strFieldName, $strValue) {

        if( empty($strValue) )
            unset($this->arrFormFields[$strFieldName]["value"]);
        else
            $this->arrFormFields[$strFieldName]["value"] = $strValue;
    }

    public function form_field_setPlaceHolder($strFieldName, $strPlaceHolder) {

        if( empty($strPlaceHolder) )
            unset($this->arrFormFields[$strFieldName]["placeholder"]);
        else
            $this->arrFormFields[$strFieldName]["placeholder"] = $strPlaceHolder;
    }

    public function form_field_setReadMode($strFieldName, $boolReadMode) {
        $this->arrFormFields[$strFieldName]["readMode"] = $boolReadMode;
    }

    public function form_field_setToolTip($strFieldName, $strToolTip) {

        if( empty($strToolTip) )
            unset($this->arrFormFields[$strFieldName]["tooltip"]);
        else
            $this->arrFormFields[$strFieldName]["tooltip"] = $strToolTip;
    }

    public function form_field_setClass($strFieldName, $strClass) {

        if( empty($strClass) )
            unset($this->arrFormFields[$strFieldName]["class"]);
        else
            $this->arrFormFields[$strFieldName]["class"] = $strClass;
    }

    public function form_field_setExtraTag($strFieldName, $strTagName, $strTagValue = "") {
        if( empty($strTagValue) ) $strTagValue = $strTagName;
        $this->arrFormFields[$strFieldName]["tags"][$strTagName] = $strTagValue;
    }

    public function form_field_setId($strFieldName, $strId) {

        if( empty($strId) )
            unset($this->arrFormFields[$strFieldName]["id"]);
        else
            $this->arrFormFields[$strFieldName]["id"] = $strId;
    }

    /*
    * Formato
    * $arrOptionSelected[groupKey]["nombreGrupo"] = "Nombre"
    * $arrOptionSelected[groupKey]["options"][keyOption] = "valueOption"
    *
    * ó
    *
    * $arrOptionSelected[groupKey]["nombreGrupo"] = "Nombre"
    * $arrOptionSelected[groupKey]["options"][keyOption]["valor"] = "valueOption"
    * $arrOptionSelected[groupKey]["options"][keyOption]["selected"] = true/false
    *
    * ó
    *
    * $arrOptionSelected[keyOption]["valor"] = "valueOption"
    *
    * ó
    *
    * $arrOptionSelected[keyOption]["valor"] = "valueOption"
    * $arrOptionSelected[keyOption]["selected"] = true/false
    *
    */

    public function form_field_setOptions($strFieldName, $arrOptions, $strOptionSelected = "") {

        $this->arrFormFields[$strFieldName]["select"] = array();

        while( $arrGrupo = each($arrOptions) ) {

            if( isset($arrGrupo["value"]["options"]) ) {

                $this->arrFormFields[$strFieldName]["select"]["groups"][$arrGrupo["key"]]["nombre"] = $arrGrupo["value"]["nombreGrupo"];

                while( $arrTMP = each($arrGrupo["value"]["options"]) ) {

                    $strValue = isset($arrTMP["value"]["valor"]) ? $arrTMP["value"]["valor"] : $arrTMP["value"];
                    $boolSelected = isset($arrTMP["value"]["selected"]) ? $arrTMP["value"]["selected"] : ( $strValue == $strOptionSelected );

                    $this->arrFormFields[$strFieldName]["select"]["groups"][$arrGrupo["key"]]["options"][$arrTMP["key"]]["valor"] = $strValue;
                    $this->arrFormFields[$strFieldName]["select"]["groups"][$arrGrupo["key"]]["options"][$arrTMP["key"]]["selected"] = $boolSelected;

                }

            }
            else {

                $strValue = isset($arrGrupo["value"]["valor"]) ? $arrGrupo["value"]["valor"] : $arrGrupo["value"];
                $boolSelected = isset($arrGrupo["value"]["selected"]) ? $arrGrupo["value"]["selected"] : ( $strValue == $strOptionSelected );

                $this->arrFormFields[$strFieldName]["select"]["options"][$arrGrupo["key"]]["valor"] = $strValue;
                $this->arrFormFields[$strFieldName]["select"]["options"][$arrGrupo["key"]]["selected"] = $boolSelected;

            }

        }


        if( empty($strId) )
            unset($this->arrFormFields[$strFieldName]["id"]);
        else
            $this->arrFormFields[$strFieldName]["id"] = $strId;

    }

    public function form_field_addGrupo($strFieldName, $strKey, $strName) {

        $this->arrFormFields[$strFieldName]["select"]["groups"][$strKey]["nombre"] = $strName;

    }

    public function form_field_addOption($strFieldName, $strKey, $strValue, $boolSelected = false, $mixGroupKey = "") {

        if( empty($mixGroupKey) ) {

            $this->arrFormFields[$strFieldName]["select"]["options"][$strKey]["valor"] = $strValue;
            $this->arrFormFields[$strFieldName]["select"]["options"][$strKey]["selected"] = $boolSelected;

        }
        else {

            $this->arrFormFields[$strFieldName]["select"]["groups"][$mixGroupKey]["options"][$strKey]["valor"] = $strValue;
            $this->arrFormFields[$strFieldName]["select"]["groups"][$mixGroupKey]["options"][$strKey]["selected"] = $boolSelected;

        }

    }

    public function form_field_includeDiv($strFieldName, $boolIncludeDiv) {
        $this->arrFormFields[$strFieldName]["includeDiv"] = $boolIncludeDiv;
    }

    public function form_field_boolDrawSearchButton($strFieldName, $boolDrawSearchButton, $strOnClick = "") {

        $this->arrFormFields[$strFieldName]["boolDrawSearchButton"] = $boolDrawSearchButton;
        $this->arrFormFields[$strFieldName]["boolOnClickSearchButton"] = $strOnClick;

    }

    public function form_field_boolDrawClearButton($strFieldName, $boolDrawClearButton, $strOnClick = "") {

        $this->arrFormFields[$strFieldName]["boolDrawClearButton"] = $boolDrawClearButton;
        $this->arrFormFields[$strFieldName]["boolOnClickClearButton"] = $strOnClick;

    }

    public function form_field_getType($strFieldName) {
        return $this->arrFormFields[$strFieldName]["type"];
    }

    public function form_field_getValue($strFieldName) {
        return (isset($this->arrFormFields[$strFieldName]["value"])) ? $this->arrFormFields[$strFieldName]["value"] : "";
    }

    public function form_field_getPlaceHolder($strFieldName) {
        return (isset($this->arrFormFields[$strFieldName]["placeholder"])) ? $this->arrFormFields[$strFieldName]["placeholder"] : "";
    }

    public function form_field_getReadMode($strFieldName) {
        return (isset($this->arrFormFields[$strFieldName]["readMode"])) ? $this->arrFormFields[$strFieldName]["readMode"] : false;
    }

    public function form_field_getToolTip($strFieldName) {
        return (isset($this->arrFormFields[$strFieldName]["tooltip"])) ? $this->arrFormFields[$strFieldName]["tooltip"] : "";
    }

    public function form_field_getClass($strFieldName) {
        return (isset($this->arrFormFields[$strFieldName]["class"])) ? $this->arrFormFields[$strFieldName]["class"] : "";
    }

    public function form_field_getId($strFieldName) {
        return (isset($this->arrFormFields[$strFieldName]["id"])) ? $this->arrFormFields[$strFieldName]["id"] : "";
    }

    public function form_drawYesNoElement($strFieldName, $strYesValue, $strNoValue, $strYesText, $strNoText, $strValueSelected, $strTitulo = "") {

        $strYesChecked = ($strYesValue == $strValueSelected) ? "checked=\"checked\"": "";
        $strNoChecked = ($strNoValue == $strValueSelected) ? "checked=\"checked\"": "";

        if( !empty($strTitulo) ) print $strTitulo;

        ?>
        <fieldset class="radio btn-group btn-group-yesno">
            <input id="<?php print "{$strFieldName}Yes"; ?>" type="radio" value="<?php print $strYesValue; ?>" name="<?php print $strFieldName; ?>" <?php print $strYesChecked; ?> class="hide"
                   onclick="getDocumentLayer('<?php print "label{$strFieldName}Yes"; ?>').className = 'btn btn-success active'; getDocumentLayer('<?php print "label{$strFieldName}No"; ?>').className = 'btn btn-default'; ">
            <label class="<?php print (empty($strYesChecked)) ? "btn btn-default" : "btn btn-success active"; ?>" for="<?php print "{$strFieldName}Yes"; ?>" id="<?php print "label{$strFieldName}Yes"; ?>">
                <?php print $strYesText; ?>
            </label>
            <input id="<?php print "{$strFieldName}No"; ?>" type="radio"  value="<?php print $strNoValue; ?>" name="<?php print $strFieldName; ?>" <?php print $strNoChecked; ?> class="hide"
                   onclick="getDocumentLayer('<?php print "label{$strFieldName}No"; ?>').className = 'btn btn-danger active'; getDocumentLayer('<?php print "label{$strFieldName}Yes"; ?>').className = 'btn btn-default'; ">
            <label class="<?php print (empty($strNoChecked)) ? "btn btn-default" : "btn btn-danger active"; ?>" for="<?php print "{$strFieldName}No"; ?>" id="<?php print "label{$strFieldName}No"; ?>">
                <?php print $strNoText; ?>
            </label>
        </fieldset>
        <?php

    }

    public function form_drawButton($strIdButton, $strButtonText, $strButtonType = "", $strButtonIcon = "", $strOnClick = "") {

        //Button: btn btn-small btn-success
        //Span: icon-new icon-white

        ?>
        <div id="<?php print $strIdButton; ?>" class="btn-wrapper">
            <button class="btn btn-small <?php print $strButtonType; ?>" onclick="<?php print $strOnClick; ?>">
                <span class="<?php print $strButtonIcon; ?>"></span>
                <?php print $strButtonText; ?>
            </button>
        </div>
        <?php

    }

    public function form_drawField($strFieldName, $boolRequired = false, $boolDrawGroup = false, $strLabelGroup = "") {

        $strExtraTags = "";
        if( isset($this->arrFormFields[$strFieldName]["tags"]) ) {

            while( $arrTMP = each($this->arrFormFields[$strFieldName]["tags"]) ) {
                $strExtraTags .= " {$arrTMP["key"]}=\"{$arrTMP["value"]}\"";
            }

        }


        if( $this->arrFormFields[$strFieldName]["includeDiv"] ) {

            $strValue = $this->form_field_getValue($strFieldName);

            ?>
            <div id="div<?php print $this->form_field_getType($strFieldName); ?>" style="<?php print $this->form_field_getReadMode($strFieldName) ? "" : "display: none"; ?>">
                <?php print empty($strValue) ? "" : $strValue; ?>
            </div>
            <?php
        }


        $strType = $this->form_field_getType($strFieldName);

        $strValue = $this->form_field_getValue($strFieldName);
        $strPlaceHolder = $this->form_field_getPlaceHolder($strFieldName);
        $strToolTip = $this->form_field_getToolTip($strFieldName);
        $strClass = $this->form_field_getClass($strFieldName);
        $strId = $this->form_field_getId($strFieldName);

        if( $strType == "select" ) {

            ?>

            <select name="<?php print $strFieldName; ?>" <?php print $this->form_field_getReadMode($strFieldName) ? "style=\"display: none;\"" : ""; ?>
                    <?php print (empty($strToolTip) ? "" : " data-toggle=\"tooltip\" data-placement=\"left\" title=\"{$strToolTip}\"" ); ?>
                    <?php print (empty($strClass) ? "" : " class=\"{$strClass}\"" ); ?>
                    <?php print (empty($strId) ? "" : " id=\"{$strId}\"" ); ?> >

                    <?php

                    if( isset($this->arrFormFields[$strFieldName]["select"]) ) {

                        while( $arrSelect = each($this->arrFormFields[$strFieldName]["select"]) ) {

                            if( isset($arrSelect["value"]["options"]) ) {

                                while( $arrOptions = each($arrSelect["value"]["options"]) ) {

                                    ?>
                                    <option value="<?php print $arrOptions["key"]; ?>" <?php print ($arrOptions["value"]["selected"]) ? "selected=\"selected\"" : ""; ?>>
                                        <?php print $arrOptions["value"]["valor"]; ?>
                                    </option>

                                    <?php

                                }

                            }

                            if( isset($arrSelect["value"]["groups"]) ) {

                                while( $arrGrupos = each($arrSelect["value"]["groups"]) ) {

                                    ?>

                                    <optgroup label="<?php print $arrGrupos["value"]["nombre"]; ?>">

                                        <?php

                                        while( $arrOptions = each($arrGrupos["value"]["options"]) ) {

                                            ?>
                                            <option value="<?php print $arrOptions["key"]; ?>" <?php print ($arrOptions["value"]["selected"]) ? "selected=\"selected\"" : ""; ?>>
                                                <?php print $arrOptions["value"]["valor"]; ?>
                                            </option>
                                            <?php

                                        }

                                        ?>

                                    </optgroup>

                                    <?php

                                }

                            }

                        }

                    }

                    ?>

            </select>

            <?php

        }
        elseif( $strType == "search" ) {

            ?>

            <div class="btn-wrapper input-append">
                <input type="text" name="<?php print $strFieldName; ?>"
                       <?php print (empty($strValue) ? "" : "value=\"{$strValue}\"" ); ?>
                       <?php print (empty($strPlaceHolder) ? "" : "placeholder=\"{$strPlaceHolder}\"" ); ?>
                       <?php print (empty($strToolTip) ? "" : " data-toggle=\"tooltip\" data-placement=\"left\" title=\"{$strToolTip}\"" ); ?>
                       <?php print (empty($strClass) ? "" : " class=\"{$strClass}\"" ); ?>
                       <?php print (empty($strId) ? "" : " id=\"{$strId}\"" ); ?> />
                <button class="btn hasTooltip" title="" type="submit" <?php print (empty($strToolTip) ? "" : " data-original-titles=\"tooltip\" data-placement=\"left\" title=\"{$strToolTip}\"" ); ?> >
            </div>

            <?php

        }
        else {

            if( $boolDrawGroup ) {

                $strClass = "form-control {$strClass}";

                ?>
                <div class="form-group">
                    <label for="<?php print $strFieldName; ?>" class="col-sm-1 text-right">
                        <?php
                        print $strLabelGroup;
                        if( $boolRequired )  {
                            ?>
                            <span class="text-danger">*</span>
                            <?php
                        }
                        ?>
                    </label>
                    <div class="col-sm-6">


                <?php
            }

            $strRequired = $boolRequired ? "required=\"required\"" : "";


            ?>

            <input type="<?php print $this->form_field_getType($strFieldName); ?>" name="<?php print $strFieldName; ?>"
                    <?php print (empty($strValue) ? "" : "value=\"{$strValue}\"" ); ?>
                    <?php print (empty($strPlaceHolder) ? "" : "placeholder=\"{$strPlaceHolder}\"" ); ?>
                    <?php print $this->form_field_getReadMode($strFieldName) ? "style=\"display: none;\"" : ""; ?>
                    <?php print (empty($strToolTip) ? "" : " data-toggle=\"tooltip\" data-placement=\"left\" title=\"{$strToolTip}\"" ); ?>
                    <?php print (empty($strClass) ? "" : " class=\"{$strClass}\"" ); ?>
                    <?php print (empty($strId) ? "" : " id=\"{$strId}\"" ); ?>
                    <?php print $strExtraTags. " ". $strRequired; ?>  />

            <?php

            if( $boolDrawGroup ) {

                ?>

                    </div>
                    <div class="col-sm-5"></div>
                </div>

                <?php

            }


        }

    }

    /* FIN DE LOS TIPOS INPUT */

}