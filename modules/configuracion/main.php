<?php

if( strstr($_SERVER["PHP_SELF"], "/configuracion/") ) die("You can't access this file directly...");

require_once("functions.php");
core_load_lang("configuracion");
core_load_configuracion("configuracion");