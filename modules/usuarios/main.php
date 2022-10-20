<?php

if( strstr($_SERVER["PHP_SELF"], "/usuarios/") ) die("You can't access this file directly...");

require_once("functions.php");
core_load_lang("usuarios");
core_load_configuracion("usuarios");