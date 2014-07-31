<?php
require_once CARPETACONTROLADORES . "controlador.php";

class Usuaris extends Controlador {

    function __construct(){
        require_once CARPETAMODELOS . "usuaris.php";
        $this->modelo = new modeloUsuaris;
    }


    /**
    *Controla la entrar de un usuario
    */
    function entrar(){
		    //Recogemos los datos en 2 variables
			$correo = $_POST['correo'];
			$contrasenya = $_POST['contrasenya'];

			//recogemos para saber si esta bien entrado el registro
	        $usuarios = $this->modelo->entrar($correo, $contrasenya);
			if ($usuarios==false) {
				$error="Mail o Contraseña incorrecta";
				require_once CARPETAVISTAS . "index.php";
			}
			else if ($usuarios==true){
				//Si el usuario y contraseña son correctos creamos las sesiones para el usuario
				$datosUsuario = $this->modelo->recogerUsuario($correo);
				$_SESSION["mail"] = $datosUsuario['MAIL'];
				$_SESSION["imagen"] = $datosUsuario['IMAGEN'];
				$_SESSION["nom"] = $datosUsuario['NOM'];
				$_SESSION["cognoms"] = $datosUsuario['COGNOMS'];
				$_SESSION["idUser"] = $datosUsuario['ID_USUARI'];
				//Creamos un objeto para recoger los archivos de una carpeta de la BD
		        require_once CARPETAMODELOS . "carpetas.php";
        		$this->modeloCarpetas = new modeloCarpetas;
        		//Encontramos el id de la carpeta
        		$datosCarpeta = $this->modeloCarpetas->recogeCarpeta($datosUsuario['ID_USUARI'], null);
        		$_SESSION["carpeta"] = $datosCarpeta['ID_CARPETA'];
        		$_SESSION["ruta"] = $datosCarpeta['NOM_CARPETA'];
        		//Ahora recogemos todos los archivos de la carpeta
        		require_once CARPETAMODELOS . "archivos.php";
        		$this->modeloArchivo = new modeloArchivos;
        		$archivosCarpeta = $this->modeloArchivo->archivosCarpeta($datosCarpeta['ID_CARPETA']);
        		$carpetasCarpeta = $this->modeloCarpetas->todasCarpetas($datosCarpeta['ID_CARPETA']);
        		$todasCarpetas = $this->modeloCarpetas->todasCarpetasUser($_SESSION['idUser']);
        		$directorio = "log/" . $_SESSION['mail'] . ".txt";
				$fp = fopen($directorio,"a");
				fwrite($fp, date("d-m-Y H:i:s"). " - Accedido al sistema." . PHP_EOL);
				fclose($fp);
				require_once CARPETAVISTAS . "usuari.php";
			}
    }

    /**
    *Controla el registro de usuario
    */
    function registrarse(){
		    //Recogemos los datos 
			$correo = $_POST['correo'];
			$nombre = $_POST['nombre'];
			$apellidos = $_POST['apellidos'];
			$contrasenya = $_POST['contrasenya'];
			//Miramos si el correo coincide con alguno de la base de datos:
        	$usuario = $this->modelo->existeUsuari($correo);
        	if($usuario==true){
        		$error="Correo ya existente!";
				require_once CARPETAVISTAS . "index.php";
        	}
        	else {
				//Cogemos la imagen y la subimos
				if (is_uploaded_file ($_FILES['imagen']['tmp_name'])){
		        	$nombre = $_FILES['imagen']['name'];
		        	//movemos archivo a la carpeta
		        	$directorio = "IMAGENES/USUARIS/";
	            	$idUnico = time();
	            	$nombreFichero = $idUnico . "-" . $nombre;
	            	move_uploaded_file ($_FILES['imagen']['tmp_name'], $directorio . $nombreFichero);
	            	$nombreCompleto = $directorio . $nombreFichero;
	        	}
	        	else $nombreCompleto = "IMAGENES/USUARIS/no_avatar.jpeg";//COgemos la de por defecto
	        	
				$nombre = $_POST['nombre'];
		        //Creamos una array para pasar todos los datos al metodo para subir el usuario a la base de datos
		        $datosUsuari = array(
		        		"correo"=>$correo,
		        		"nombre"=>$nombre,
		        		"apellidos"=>$apellidos,
		        		"contrasenya"=>$contrasenya,
		        		"imagen"=>$nombreCompleto,
		        	);
		        $this->modelo->registrarse($datosUsuari);
		        $error="Usuario creado correctamente!";
		        //Creamos la carpeta de usuario
		        mkdir("CARPETAS/".$correo, 0777);
		        //Recogemos los datos de usuario por que necesitamos el id para crear la carpeta en la BD
		        $datosUsuario = $this->modelo->recogerUsuario($correo);
		        //Creamos un objeto para insertar nueva carpeta en BD
		        require_once CARPETAMODELOS . "carpetas.php";
        		$this->modeloCarpetas = new modeloCarpetas;
        		$this->modeloCarpetas->crearCarpetaPersonal($datosUsuario);
				require_once CARPETAVISTAS . "index.php";
        	}
			
    }

     /**
    *Darse de baja de un usuario y borrar todos sus archivos y carpetas
    */
    function bajaUser(){

		//Creamos un objeto para insertar nueva carpeta en BD
		require_once CARPETAMODELOS . "carpetas.php";
        $this->modeloCarpetas = new modeloCarpetas;
		//Recogemos el id de usuario
		$idUser = $_SESSION['idUser'];
		//seleccionamos la primera carpeta del usuario
		$datosCarpeta = $this->modeloCarpetas->recogeCarpeta($idUser, null);
		//borramos la carpeta y lo que hay dentro de la base de datos
		$this->modeloCarpetas->eliminarCarpeta($datosCarpeta['ID_CARPETA']);
		//Borramos la carpeta y lo que hay dentro de la fisica:
		$source = "CARPETAS/" . $datosCarpeta['NOM_CARPETA'];
		$Orden = "rm -R $source";
        exec ($Orden);
        //Borramos el usuario 
        $this->modelo->eliminarUsuario($idUser);
        $directorio = "log/" . $_SESSION['mail'] . ".txt";
		$fp = fopen($directorio,"a");
		fwrite($fp, date("d-m-Y H:i:s"). " - Usuario eliminado" . PHP_EOL);
		fclose($fp);
		require_once CARPETAVISTAS . "index.php";
       
			
    }

	/**
    *Controla la salida de un usuario
    */
    function salir(){
    	$directorio = "log/" . $_SESSION['mail'] . ".txt";
		$fp = fopen($directorio,"a");
		fwrite($fp, date("d-m-Y H:i:s"). " - Salida del sistema." . PHP_EOL);
		fclose($fp);
	   	session_destroy();
		require CARPETAVISTAS . "index.php";
	}


}

?>