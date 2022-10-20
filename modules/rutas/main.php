<?php

if( strstr($_SERVER["PHP_SELF"], "/clientes/") ) die("You can't access this file directly...");

require_once("functions.php");
core_load_lang("clientes");
core_load_configuracion("clientes");