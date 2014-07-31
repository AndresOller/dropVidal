<?php
require_once CARPETACONTROLADORES . "controlador.php";

class Carpetas extends Controlador {

    function __construct(){
        require_once CARPETAMODELOS . "carpetas.php";
        $this->modelo = new modeloCarpetas;
    }

    /**
    *Inserta una nueva carpeta al usuario en la carpeta donde este situado en ese momento.
    */
    function nuevaCarpeta(){
    	//Recogemos el id de la carpeta actual y el nombre que le pondremos y la enviamos a el metodo del modelo
    	$idCarpeta = $_SESSION["carpeta"];
    	$nom = $_POST['nomCarpeta'];
    	$idUsuario = $_SESSION["idUser"];
    	$datosCarpeta = $this->modelo->creaCarpeta($idCarpeta, $nom, $idUsuario);
        $_SESSION["carpeta"] = $datosCarpeta['ID_CARPETA'];
       	$ruta = $_SESSION["ruta"];
       	$_SESSION["ruta"]=$ruta . "/" . $nom;
       	//Creamos la carpeta
		  mkdir("CARPETAS/".$_SESSION["ruta"], 0777);
      $directorio = "log/" . $_SESSION['mail'] . ".txt";
      $fp = fopen($directorio,"a");
      fwrite($fp, date("d-m-Y H:i:s"). " - Crea la carpeta " . $_SESSION['ruta'] . "." . PHP_EOL);
      fclose($fp);
    	require_once CARPETAVISTAS . "usuari.php";
	}

  /**
  *Funcion que elimina la carpeta que le digamos
  */
  function DelTodo($source){
      $source = "CARPETAS/" . $source;
      if (!is_dir($source) && !is_file($source) ){
          echo "No es un directorio o fichero";
      }else {
          $Orden = "rm -R $source" ;
          exec ($Orden);
      }
  } 

  /**
    *Elimina la carpeta de donde nos encontramos
    */
    function eliminarCarpeta(){
      //Recogemos el id de la carpeta actual 
      $idCarpeta = $_SESSION["carpeta"];
      //Recogemos la ruta
      $ruta = $_SESSION['ruta'];
      //Recogemos los datos de la carpeta para eliminarla fisicamente del sistema
      $datosCarpeta = $this->modelo->recogeCarpetaId($idCarpeta);
      //eliminamos las carpetas que pertenecen a esta de la base de datos y los archivos.
      $this->modelo->eliminarCarpeta($idCarpeta);
      //eliminamos las carpetas reales
      $this->DelTodo($ruta);
      $directorio = "log/" . $_SESSION['mail'] . ".txt";
      $fp = fopen($directorio,"a");
      fwrite($fp, date("d-m-Y H:i:s"). " - Elimina la carpeta " . $_SESSION['ruta'] . " y todo lo que contiene." . PHP_EOL);
      fclose($fp);
      //Eliminaremos de la ruta el nombre de la carpeta
      $numLetras = strlen($datosCarpeta['NOM_CARPETA']);
      $numLetras++;//Añadimos 1 para eliminar '/'
      $numLetras = $numLetras - ($numLetras*2);//la hacemos negativa
      $_SESSION['ruta'] = substr($ruta, 0, $numLetras);
      //Recogemos la carpeta anterior 
      $datosCarpeta = $this->modelo->recogeCarpetaId($datosCarpeta['ID_CARPETA_FORANA']);
      //ponermos la sesion carpeta con el nombre de la carpeta donde iremos.
      $_SESSION['carpeta']= $datosCarpeta['ID_CARPETA'];
      //Ahora recogemos todos los archivos de la carpeta
      require_once CARPETAMODELOS . "archivos.php";
      $this->modeloArchivo = new modeloArchivos;
      $archivosCarpeta = $this->modeloArchivo->archivosCarpeta($datosCarpeta['ID_CARPETA']);
      $carpetasCarpeta = $this->modelo->todasCarpetas($datosCarpeta['ID_CARPETA']);
      $todasCarpetas = $this->modelo->todasCarpetasUser($_SESSION['idUser']);
      require_once CARPETAVISTAS . "usuari.php";
  }

  /**
    *Accede a la carpeta dada
    */
    function accederCarpeta(){
      //REcogemos el id de la carpeta que queremos entrar
      $idCarpeta = $_GET['idCarpeta'];
      //Recogemos el id de la carpeta actual 
      $_SESSION["carpeta"] = $idCarpeta;
      //Recogemos la ruta
      $ruta = $_SESSION['ruta'];
      //Recogemos los datos de la carpeta 
      $datosCarpeta = $this->modelo->recogeCarpetaId($idCarpeta);
      //ponermos la sesion ruta con la nueva carpeta.
      $_SESSION['ruta']= $ruta ."/". $datosCarpeta['NOM_CARPETA'];
      //Ahora recogemos todos los archivos de la carpeta
      require_once CARPETAMODELOS . "archivos.php";
      $this->modeloArchivo = new modeloArchivos;
      $archivosCarpeta = $this->modeloArchivo->archivosCarpeta($datosCarpeta['ID_CARPETA']);
      $carpetasCarpeta = $this->modelo->todasCarpetas($datosCarpeta['ID_CARPETA']);
      $todasCarpetas = $this->modelo->todasCarpetasUser($_SESSION['idUser']);
      require_once CARPETAVISTAS . "usuari.php";
  }

    /**
    *ir a la primera carpeta del usuario
    */
    function entrarInicio(){
      //Encontramos el id de la carpeta
      $datosCarpeta = $this->modelo->recogeCarpeta($_SESSION['idUser'], null);
      $_SESSION["carpeta"] = $datosCarpeta['ID_CARPETA'];
      $_SESSION["ruta"] = $datosCarpeta['NOM_CARPETA'];
      //Ahora recogemos todos los archivos de la carpeta
      require_once CARPETAMODELOS . "archivos.php";
      $this->modeloArchivo = new modeloArchivos;
      $archivosCarpeta = $this->modeloArchivo->archivosCarpeta($datosCarpeta['ID_CARPETA']);
      $carpetasCarpeta = $this->modelo->todasCarpetas($datosCarpeta['ID_CARPETA']);
      $todasCarpetas = $this->modelo->todasCarpetasUser($_SESSION['idUser']);
      require_once CARPETAVISTAS . "usuari.php";
  }

    /**
    *ir a la carpeta anterior del usuario
    */
    function entrarAtras(){
      //Recogemos el id de la carpeta actual 
      $idCarpeta = $_SESSION["carpeta"];
      //Recogemos la ruta
      $ruta = $_SESSION['ruta'];
      //Recogemos los datos de la carpeta 
      $datosCarpeta = $this->modelo->recogeCarpetaId($idCarpeta);
      //Eliminaremos de la ruta el nombre de la carpeta
      $numLetras = strlen($datosCarpeta['NOM_CARPETA']);
      $numLetras++;//Añadimos 1 para eliminar '/'
      $numLetras = $numLetras - ($numLetras*2);//la hacemos negativa
      $_SESSION['ruta'] = substr($ruta, 0, $numLetras);
      //Recogemos la carpeta anterior 
      $datosCarpeta = $this->modelo->recogeCarpetaId($datosCarpeta['ID_CARPETA_FORANA']);
      //ponermos la sesion carpeta con el nombre de la carpeta donde iremos.
      $_SESSION['carpeta']= $datosCarpeta['ID_CARPETA'];
      //Ahora recogemos todos los archivos de la carpeta
      require_once CARPETAMODELOS . "archivos.php";
      $this->modeloArchivo = new modeloArchivos;
      $archivosCarpeta = $this->modeloArchivo->archivosCarpeta($datosCarpeta['ID_CARPETA']);
      $carpetasCarpeta = $this->modelo->todasCarpetas($datosCarpeta['ID_CARPETA']);
      $todasCarpetas = $this->modelo->todasCarpetasUser($_SESSION['idUser']);

      require_once CARPETAVISTAS . "usuari.php";
  }

}

?>