<?php 
      require_once CARPETAMODELOS . "modelo.php";

      /* Clase principal de usuaris */
      class modeloArchivos extends Modelo{

      	/**
        *Devuelve los archivos de una carpeta
        *
        * @access public Array $idCarpeta
        * @return Array
        */
        public function archivosCarpeta($idCarpeta){
	        $dbh = BaseDatos::getInstance(); 
	        $cogeArchivos = $dbh->prepare('SELECT *
	                                       FROM archiu
	                                       WHERE ID_CARPETA = :idCarpeta');

          $cogeArchivos->bindParam(':idCarpeta', $idCarpeta, PDO::PARAM_INT);
	        $cogeArchivos->execute();
	        $archivos = $cogeArchivos-> fetchAll(PDO::FETCH_ASSOC);
	        return $archivos;
        }
        /**
        *Sube un arhivo a la base de datos
        *
        * @access public Array $nombre $nuevoNombre $tipo $idCarpeta
        */
        public function subirArchivo($nombre, $nombreNuevo, $tipo, $idCarpeta){
          $dbh = BaseDatos::getInstance(); 
          $insertaArchiu = $dbh->prepare('INSERT INTO archiu(NOM_ARCHIU, NOM_BASE_ARCHIU, TIPUS_ARCHIU, ID_CARPETA)
                                           VALUES (:nomArchiu,:nomBaseArchiu, :tipusArchiu, :idCarpeta)');
          $insertaArchiu->bindParam(':nomArchiu', $nombre, PDO::PARAM_STR);
          $insertaArchiu->bindParam(':nomBaseArchiu', $nombreNuevo, PDO::PARAM_STR);
          $insertaArchiu->bindParam(':tipusArchiu', $tipo, PDO::PARAM_STR);
          $insertaArchiu->bindParam(':idCarpeta', $idCarpeta, PDO::PARAM_INT);
          $insertaArchiu->execute();
        }

        /**
        *Elimina un archivo de la base de datos
        *
        * @access public Array $nomBaseArchiu
        */
        public function eliminarArchivo($nomBaseArchiu){
          $dbh = BaseDatos::getInstance(); 
          $borraArchiu = $dbh->prepare('DELETE FROM archiu
                                              WHERE NOM_BASE_ARCHIU = :nomArchiu');
          $borraArchiu->bindParam(':nomArchiu', $nomBaseArchiu, PDO::PARAM_STR);
          $borraArchiu->execute();
        }

        /**
        *Mueve un archivo
        *
        * @access public Array $nomBaseArchiu, $idCarpetaDestino
        */
        public function mover($nomBaseArchiu, $idCarpetaDestino){
          $dbh = BaseDatos::getInstance(); 
          $borraArchiu = $dbh->prepare('UPDATE archiu
                                        SET ID_CARPETA = :idCarpetaDestino
                                        WHERE NOM_BASE_ARCHIU = :nomBase');
          $borraArchiu->bindParam(':idCarpetaDestino', $idCarpetaDestino, PDO::PARAM_INT);
          $borraArchiu->bindParam(':nomBase', $nomBaseArchiu, PDO::PARAM_STR);
          $borraArchiu->execute();
        }
      
        /**
        *Devuelve la carpeta foranea
        *
        * @access public Array $idCarpetaDestino
        */
        public function mirarForanea($idCarpetaDestino){
          $dbh = BaseDatos::getInstance(); 
          $mirarArchiu = $dbh->prepare('SELECT *
                                        FROM carpeta
                                        WHERE ID_CARPETA = :idCarpetaDestino');
          $mirarArchiu->bindParam(':idCarpetaDestino', $idCarpetaDestino, PDO::PARAM_INT);
          $mirarArchiu->execute();
          $foranea = $mirarArchiu-> fetch(PDO::FETCH_ASSOC);
          return $foranea['ID_CARPETA_FORANA'];
        }

      }
?>  