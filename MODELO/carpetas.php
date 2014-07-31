<?php 
      require_once CARPETAMODELOS . "modelo.php";

      /* Clase principal de usuaris */
      class modeloCarpetas extends Modelo{

      		/**
            *Crea la carpeta personal de usuario
            *
            * @access public Array $datosUsuario
            * 
            */
            public function crearCarpetaPersonal($datosUsuario){
                  $dbh = BaseDatos::getInstance(); 
                  $insertaCarpeta = $dbh->prepare('INSERT INTO carpeta(NOM_CARPETA, ID_USUARI, ID_CARPETA_FORANA)
                                                   VALUES (?,?, null)');
                  $insertaCarpeta->execute(array($datosUsuario['MAIL'], $datosUsuario['ID_USUARI']));
            }

            /**
            *Crea una carpeta dando el nombre y id de la carpeta que la contiene
            *
            * @access public Array $idCarpeta $nombreCarpeta $idUsuario
            * 
            */
            public function creaCarpeta($idCarpeta, $nombreCarpeta, $idUsuario){
                  $dbh = BaseDatos::getInstance(); 
                  $insertaCarpeta = $dbh->prepare('INSERT INTO carpeta(NOM_CARPETA, ID_USUARI, ID_CARPETA_FORANA)
                                                   VALUES (:nomCarpeta,:idUsuari, :idCarpeta)');
                  $insertaCarpeta->bindParam(':nomCarpeta', $nombreCarpeta, PDO::PARAM_STR);
                  $insertaCarpeta->bindParam(':idUsuari', $idUsuario, PDO::PARAM_STR);
                  $insertaCarpeta->bindParam(':idCarpeta', $idCarpeta, PDO::PARAM_INT);
                  $insertaCarpeta->execute();
                  $selecciona = $dbh->prepare('SELECT *
                                                   FROM carpeta
                                                   WHERE ID_USUARI = :idUsuario AND NOM_CARPETA = :nomCarpeta');
                  $selecciona->bindParam(':idUsuario', $idUsuario, PDO::PARAM_STR);
                  $selecciona->bindParam(':nomCarpeta', $nombreCarpeta, PDO::PARAM_STR);
                  $selecciona->execute();
                  $carpeta = $selecciona-> fetch(PDO::FETCH_ASSOC);
                  return $carpeta;

            }

            /**
            *Devuelve los datos  de la carpeta introducida
            *
            * @access public Array $idUsuario,$idCarpetaForana
            * 
            */
            public function recogeCarpeta($idUsuario,$idCarpetaForana){
                  $dbh = BaseDatos::getInstance(); 
                  if ($idCarpetaForana == null){
                        $insertaCarpeta = $dbh->prepare('SELECT *
                                                   FROM carpeta
                                                   WHERE ID_USUARI = :idUsuario AND ID_CARPETA_FORANA IS :idCarpeta');
                        $insertaCarpeta->bindParam(':idUsuario', $idUsuario, PDO::PARAM_STR);
                        $insertaCarpeta->bindParam(':idCarpeta', $idCarpetaForana, PDO::PARAM_INT);
                        $insertaCarpeta->execute();
                        $carpeta = $insertaCarpeta-> fetch(PDO::FETCH_ASSOC);
                  }
                  else{
                        $insertaCarpeta = $dbh->prepare('SELECT *
                                                         FROM carpeta
                                                         WHERE ID_USUARI = :idUsuario AND ID_CARPETA_FORANA = :idCarpeta');
                        $insertaCarpeta->bindParam(':idUsuario', $idUsuario, PDO::PARAM_STR);
                        $insertaCarpeta->bindParam(':idCarpeta', $idCarpetaForana, PDO::PARAM_INT);
                        $insertaCarpeta->execute();
                        $carpeta = $insertaCarpeta-> fetchAll(PDO::FETCH_ASSOC);
                  }
                  return $carpeta;
            }

            /**
            *Devuelve las carpetas de la carpeta introducida
            *
            * @access public Array $idCarpeta
            * 
            */
            public function todasCarpetas($idCarpeta){
                  $dbh = BaseDatos::getInstance(); 
                  $insertaCarpeta = $dbh->prepare('SELECT *
                                                   FROM carpeta
                                                   WHERE ID_CARPETA_FORANA = :idCarpeta');
                  $insertaCarpeta->bindParam(':idCarpeta', $idCarpeta, PDO::PARAM_INT);
                  $insertaCarpeta->execute();
                  $carpeta = $insertaCarpeta-> fetchAll(PDO::FETCH_ASSOC);
                  return $carpeta;
            }

            /**
            *Devuelve los datos  de la carpeta por el id
            *
            * @access public Array $idCarpeta
            * 
            */
            public function recogeCarpetaId($idCarpeta){
                  $dbh = BaseDatos::getInstance();
                        $insertaCarpeta = $dbh->prepare('SELECT *
                                                         FROM carpeta
                                                         WHERE ID_CARPETA = :idCarpeta');
                        $insertaCarpeta->bindParam(':idCarpeta', $idCarpeta, PDO::PARAM_INT);
                        $insertaCarpeta->execute();
                        $carpeta = $insertaCarpeta-> fetch(PDO::FETCH_ASSOC);
                  return $carpeta;
            }
            
            /**
            *Elimina las carpetas 
            *
            * @access public Array $idCarpeta
            * 
            */
            public function eliminarCarpeta($idCarpeta){
                  $dbh = BaseDatos::getInstance();
                        $insertaCarpeta = $dbh->prepare('DELETE FROM carpeta
                                                         WHERE ID_CARPETA = :idCarpeta');
                        $insertaCarpeta->bindParam(':idCarpeta', $idCarpeta, PDO::PARAM_INT);
                        $insertaCarpeta->execute();
            }
            /**
            *Devuelve todas las carpetas de un usuari 
            *
            * @access public Array $idUsuari
            * 
            */
            public function todasCarpetasUser($idUsuari){
                  $dbh = BaseDatos::getInstance();
                        $insertaCarpeta = $dbh->prepare('SELECT * FROM carpeta WHERE ID_USUARI = :idUsuario');
                        $insertaCarpeta->bindParam(':idUsuario', $idUsuari, PDO::PARAM_INT);
                        $insertaCarpeta->execute();
                        $carpeta = $insertaCarpeta-> fetchAll(PDO::FETCH_ASSOC);
                  return $carpeta;
            }
      }
?>  