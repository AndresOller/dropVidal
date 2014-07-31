<?php
require_once CARPETACONTROLADORES . "controlador.php";

class indice extends Controlador {

    function __construct(){
       
    }

    function entrar(){
			require CARPETAVISTAS . "index.php";
    }
}

?>