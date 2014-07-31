<?php
require_once CARPETACONTROLADORES . "controlador.php";

class Archivos extends Controlador {

    function __construct(){
        require_once CARPETAMODELOS . "archivos.php";
        $this->modelo = new modeloArchivos;
    }

	/**
    *MEtodo que inserta archivos en la carpeta
    */
    function subirArchivos(){
        require_once CARPETAMODELOS . "carpetas.php";
        $this->modeloCarpetas= new modeloCarpetas;
        $idCarpeta = $_SESSION['carpeta'];
        $ruta = $_SESSION['ruta'];
	    //Recogemos los datos de la carpeta
	    $datosCarpeta = $this->modeloCarpetas->recogeCarpetaId($idCarpeta);
	    //Ahora subimos los archivos
	    for($i=0;$i<count($_FILES['imagen']['size']);$i++){
	    	$nombreNuevo = time().'-'.$_FILES['imagen']['name'][$i];
			$file = 'CARPETAS/'.$ruta. '/'. $nombreNuevo;
      $directorio = "log/" . $_SESSION['mail'] . ".txt";
      $fp = fopen($directorio,"a");
      fwrite($fp, date("d-m-Y H:i:s"). " - Sube el archivo " . $file . "." . PHP_EOL);
      fclose($fp);
			$nombre = $_FILES['imagen']['name'][$i];
			$tipo = $_FILES['imagen']['type'][$i];
			//Introducimos el archivo en la base de datos
			$this->modelo->subirArchivo($nombre, $nombreNuevo, $tipo, $idCarpeta);
			move_uploaded_file($_FILES['imagen']['tmp_name'][$i],$file);
		}
	    //Recogemos todos los archivos de la carpeta y las carpetas
	    $archivosCarpeta = $this->modelo->archivosCarpeta($datosCarpeta['ID_CARPETA']);
	    $carpetasCarpeta = $this->modeloCarpetas->todasCarpetas($datosCarpeta['ID_CARPETA']);
      	$todasCarpetas = $this->modeloCarpetas->todasCarpetasUser($_SESSION['idUser']);
	    require_once CARPETAVISTAS . "usuari.php";
  }
  	/**
    *Elimina el archivo en cuestion
    */
    function eliminarArchivo(){
      $nomBaseArchiu = $_GET['nomBaseArchiu'];
      //Recogemos el id de la carpeta actual 
      $idCarpeta = $_SESSION["carpeta"];
      //Recogemos la ruta
      $ruta = $_SESSION['ruta'];
      //eliminamos el archivo de la base de datos
      $this->modelo->eliminarArchivo($nomBaseArchiu);
      //Eliminamos de la carpeta fisica
      $source = "CARPETAS/" . $ruta . "/" . $nomBaseArchiu;
      unlink($source);
      //Ahora recogemos todos los archivos de la carpeta
      require_once CARPETAMODELOS . "carpetas.php";
      $this->modeloCarpetas = new modeloCarpetas;
	    //Recogemos los datos de la carpeta
	    $datosCarpeta = $this->modeloCarpetas->recogeCarpetaId($idCarpeta);
      $archivosCarpeta = $this->modelo->archivosCarpeta($datosCarpeta['ID_CARPETA']);
      $carpetasCarpeta = $this->modeloCarpetas->todasCarpetas($datosCarpeta['ID_CARPETA']);
      $todasCarpetas = $this->modeloCarpetas->todasCarpetasUser($_SESSION['idUser']);

      $directorio = "log/" . $_SESSION['mail'] . ".txt";
      $fp = fopen($directorio,"a");
      fwrite($fp, date("d-m-Y H:i:s"). " - Elimina el archivo " . $source . "." . PHP_EOL);
      fclose($fp);

      require_once CARPETAVISTAS . "usuari.php";
  }

  	/**
    *Envia el nombre del archivo que se va a mover
    */
    function cogerMover(){
      $nomBaseArchiu = $_GET['archiu'];
      //Recogemos el id de la carpeta actual 
      $idCarpeta = $_SESSION["carpeta"];
      //Ahora recogemos todos los archivos de la carpeta
      require_once CARPETAMODELOS . "carpetas.php";
      $this->modeloCarpetas = new modeloCarpetas;
	    //Recogemos los datos de la carpeta
	    $datosCarpeta = $this->modeloCarpetas->recogeCarpetaId($idCarpeta);
      $archivosCarpeta = $this->modelo->archivosCarpeta($datosCarpeta['ID_CARPETA']);
      $carpetasCarpeta = $this->modeloCarpetas->todasCarpetas($datosCarpeta['ID_CARPETA']);
      $todasCarpetas = $this->modeloCarpetas->todasCarpetasUser($_SESSION['idUser']);
      $mover =true;
      require_once CARPETAVISTAS . "usuari.php";
  }

    /**
    *Mueve un archivo de carpeta.
    */
    function mover(){
      require_once CARPETAMODELOS . "carpetas.php";
      $this->modeloCarpetas = new modeloCarpetas;
      $nomBaseArchiu =$_POST['nomBase'];
      $idCarpetaDestino = $_POST['carpeta'];
      //Recogemos el id de la carpeta actual 
      $idCarpeta = $_SESSION["carpeta"];
      //Ahora recogemos todos los archivos de la carpeta
      $this->modelo->mover($nomBaseArchiu, $idCarpetaDestino);
      $datosCarpeta = $this->modeloCarpetas->recogeCarpetaId($idCarpetaDestino);
      $ruta = $datosCarpeta['NOM_CARPETA']. "/";
      while($this->modelo->mirarForanea($idCarpetaDestino)!=null){
        $idCarpetaDestino = $this->modelo->mirarForanea($idCarpetaDestino);
        $datosCarpeta = $this->modeloCarpetas->recogeCarpetaId($idCarpetaDestino);
        $ruta = $datosCarpeta['NOM_CARPETA'] . "/" . $ruta ;
      }

      $ruta = "CARPETAS/" . $ruta;
      $rutaAntigua = $_SESSION['ruta'];
      $rutaAntigua = "CARPETAS/" . $rutaAntigua . "/";
      rename($rutaAntigua . $nomBaseArchiu, $ruta . $nomBaseArchiu);
      //Recogemos los datos de la carpeta
      $datosCarpeta = $this->modeloCarpetas->recogeCarpetaId($idCarpeta);
      $archivosCarpeta = $this->modelo->archivosCarpeta($datosCarpeta['ID_CARPETA']);
      $carpetasCarpeta = $this->modeloCarpetas->todasCarpetas($datosCarpeta['ID_CARPETA']);
      $todasCarpetas = $this->modeloCarpetas->todasCarpetasUser($_SESSION['idUser']);


      $directorio = "log/" . $_SESSION['mail'] . ".txt";
      $fp = fopen($directorio,"a");
      fwrite($fp, date("d-m-Y H:i:s"). " - Mueve el archivo " . $rutaAntigua . $nomBaseArchiu .  " a " . $ruta .  "." . PHP_EOL);
      fclose($fp);

      require_once CARPETAVISTAS . "usuari.php";
  }
}

?>