<?php
require_once("modules/clientes/clases/clientes_cliente_model.php");
require_once("modules/clientes/clases/clientes_cliente_view.php");

class clientes_cliente_controller{

    private $objModel;
    private $objView;
    private $intCliente;
    public function __construct($strCliente){

        $this->intCliente = clientes_cliente_model::getClienteMD5($strCliente);
        $this->objModel = new clientes_cliente_model($this->intCliente);
        $this->objView = new clientes_cliente_view($this->intCliente);
        $this->intUser = $_SESSION["hml"]["persona"];;
    }

    public function runAjax() {
        $strTipoValidacion = isset($_REQUEST["validaciones"]) ? $_REQUEST["validaciones"] : '';
        if( isset($_POST["metodo"]) ) {
            header("Content-Type: text/html; charset=iso-8859-1");

            $strMetodo = isset($_POST["metodo"]) ? $_POST["metodo"] : "";

            if( method_exists($this->objView,$strMetodo) ) {

                $this->objView->{$strMetodo}();

            }
            else if( method_exists($this,$strMetodo) ) {

                $this->{$strMetodo}();

            }
            else {

                print "Defina el metodo";

            }
            db_close();
            die();
        }
        else if( $strTipoValidacion == "drawClienteCargaMasiva" ){

            header("Content-Type: text/html; charset=iso-8859-1");

            //set_time_limit(3600);
            //ini_set('memory_limit', '-1');

            $fileContacts = $_FILES['fileContacts'];
            $fileContacts = file_get_contents($fileContacts['tmp_name']);
            $fileContacts = str_replace(';;', ";NULL;", $fileContacts);
            $fileContacts = str_replace(',', '', $fileContacts);
            //$fileContacts = str_replace('/', '.', $fileContacts);
            $fileContacts = explode(";", $fileContacts);
            //$fileContacts = array_filter($fileContacts);
            $intKey = 0;
            $intControl = 1;
            //print $intContadorLimite;         
            foreach ($fileContacts as $contact) {

                if ($intControl == 12) {
                    $keyRow = 0;
                    $arrCtrl = explode(PHP_EOL, $contact);
                    if (count($arrCtrl) == 2) {
                        //print_r($arrCtrl);
                        $contact = $arrCtrl[0];
                        $keyRow = $arrCtrl[1];
                    } else {
                        $contact = '';
                        for ($i = 0; $i < count($arrCtrl); $i++) {
                            $contact .= $arrCtrl[$i];
                        }
                        $keyRow = $arrCtrl[(count($arrCtrl) - 1)];
                    }
                }

                $strTexto = trim(preg_replace("/\r|\n/", "", $contact));
                $strTexto = str_replace("'", "", $strTexto);
                //Reemplazamos la A y a
                $strTexto = str_replace(
                array('a', 'À', 'Â', 'Ä', 'a', 'à', 'ä', 'â', 'ª'),
                array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
                $strTexto);
        
                //Reemplazamos la E y e
                $strTexto = str_replace(
                array('e', 'È', 'Ê', 'Ë', 'e', 'è', 'ë', 'ê'),
                array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
                $strTexto);
        
                //Reemplazamos la I y i
                $strTexto = str_replace(
                array('i', 'Ì', 'Ï', 'Î', 'i', 'ì', 'ï', 'î'),
                array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
                $strTexto);
        
                //Reemplazamos la O y o
                $strTexto = str_replace(
                array('o', 'Ò', 'Ö', 'Ô', 'o', 'ò', 'ö', 'ô'),
                array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
                $strTexto);
        
                //Reemplazamos la U y u
                $strTexto = str_replace(
                array('u', 'Ù', 'Û', 'Ü', 'u', 'ù', 'ü', 'û'),
                array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
                $strTexto );
                $contactList[$intKey][$intControl] = $strTexto;
                $intControl++;

                if ($intControl == (12 + 1)) {
                    $intKey++;
                    if ($keyRow != 0) {
                        $contactList[$intKey][1] = $keyRow;
                    }
                    $intControl = 2;
                    //break;
                }
            }
            //print 'contactList      '.$contactList;
            $usuario = $_SESSION["hml"]["persona"];
            foreach ($contactList as $key => $contactData) {
                //print $contactData[0].'<br>';           
                $contactData[1] = trim($contactData[1]);
                $contactData[1] = str_replace(" ", "", "$contactData[1]");
                $contactData[11] = date('Y-m-d', strtotime($contactData[11]));

                $strQuery = "INSERT INTO cliente ( cif, crm, ejecutivo_ventas, nombre, telefono, direccion, zona_municipio, departamento, horario , tc, fecha_entrega_cliente, empresa, add_fecha, add_user) VALUES('{$contactData[1]}','{$contactData[2]}', '{$contactData[3]}','{$contactData[4]}',{$contactData[5]},'{$contactData[6]}','{$contactData[7]}','{$contactData[8]}','{$contactData[9]}', '{$contactData[10]}', '{$contactData[11]}', '{$contactData[12]}', NOW() ,$usuario);";
                //print 'strQuery   ' . $strQuery . '<br>   <br>    ';
                db_query($strQuery);

            }
            die();
        }

    }

    public function drawButtons() {
        $this->objView->drawButtonsClienteConsulta();
    }

    public function drawContent() {
        $this->objView->drawContentClienteConsulta();
    }

    public function processModificarPilotoCliente(){
        $arrCliente = json_decode(stripslashes($_POST['cliente']));
        $intPiloto = isset($_POST['piloto']) ? db_escape(user_input_delmagic($_POST['piloto'],true)) : "";
        $intRuta = isset($_POST['ruta']) ? db_escape(user_input_delmagic($_POST['ruta'],true)) : "";
        $status = isset($_POST['status']) ? db_escape(user_input_delmagic($_POST['status'],true)) : "";

        if($status == 1){
            foreach ($arrCliente as $cliente) {
                $strQuery = "INSERT INTO cliente_asigna_piloto(cliente, persona, fecha_asignada, ruta, add_user, add_fecha)
                VALUES ({$cliente}, {$intPiloto}, now(), $intRuta, $this->intUser, now());";
                db_query($strQuery);
            }
        }
        else if($status == 2){
            foreach ($arrCliente as $cliente) {
                $strQuery = "UPDATE cliente_asigna_piloto 
                                SET persona = {$intPiloto},
                                    ruta = {$intRuta},
                                    fecha_asignada = now(),
                                    mod_user = $this->intUser,
                                    mod_fecha = now()
                                WHERE cliente = {$cliente}";
                db_query($strQuery); 
            }
        }

        else if($status == 3){
            foreach ($arrCliente as $cliente) {
                $strQuery = "DELETE FROM cliente_asigna_piloto
                            WHERE cliente = {$cliente}";
                db_query($strQuery);
            }
        }
            
    }

}