<?php

session_start();
define('CARPETACONTROLADORES', "CONTROLADOR/");
define('CARPETAMODELOS', "MODELO/");
define('CARPETAVISTAS', "VISTA/");

if (count($_GET) == 0) require CARPETAVISTAS . "index.php";
else {
    if(!empty($_GET['controlador'])){
        $controlador = $_GET['controlador'];
        $file = CARPETACONTROLADORES . $controlador . ".php";
        if(is_file($file)){
            require_once $file;
            $objecto = new $controlador;
        }
        else{
              die('El controlador no existe');
        }
    }else{
              die('No ha definido un controlador');
    }

    if(!empty($_GET['accion'])){
        $accion = $_GET['accion'];
        if(!method_exists($controlador, $accion)){
            die('El metodo '.$accion.' no existe en el controlador ' . $controlador);
        }
    }else{
              die('No ha definido una accion');
    }

    $objecto->$accion();
}

?>
