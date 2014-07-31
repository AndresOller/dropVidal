<?php 
      require_once CARPETAMODELOS . "modelo.php";

      /* Clase principal de usuaris */
      class modeloUsuaris extends Modelo{

            /**
            *Recoge los datos de un usuario dando el id
            *
            * @access public $id
            * @return Array
            */
            public function recogerUsuario($correu){
                  $dbh = BaseDatos::getInstance(); 
                  $consulta = $dbh->prepare("SET NAMES 'utf8'");
                  $consulta->execute();
                  $seleccioUsuari = $dbh->prepare('SELECT MAIL, IMAGEN, NOM, COGNOMS, ID_USUARI
                                                   FROM usuari
                                                   WHERE MAIL = ?');
                  $seleccioUsuari->execute(array($correu));
                  $usuari = $seleccioUsuari-> fetch(PDO::FETCH_ASSOC);
                  return $usuari;
            }

            /**
            *Comprueba que exista un correo en la base de datos
            *
            * @access public $correo
            * @return boolean
            */
            public function existeUsuari($correo){
                  $dbh = BaseDatos::getInstance(); 
                  $mirarCorreo = $dbh->prepare('SELECT MAIL
                                                   FROM usuari
                                                   WHERE MAIL = ?');
                  $mirarCorreo->execute(array($correo));
                  $usuari = $mirarCorreo->fetch(PDO::FETCH_ASSOC);
                  if ($usuari['MAIL']!="") return true;
                  else return false;
            }

            /**
            *Mira si un usuario y la contraseña son correctos
            *
            * @access public  $mail $contrasenya
            * @return boolean
            */
            public function entrar($mail, $contrasenya){
                  $dbh = BaseDatos::getInstance(); 
                  $seleccioUsuari = $dbh->prepare('SELECT MAIL, CONTRASENYA
                                                   FROM usuari 
                                                   WHERE MAIL = ? AND CONTRASENYA = ?');
                  $seleccioUsuari->execute(array($mail, $contrasenya));
                  $usuari = $seleccioUsuari-> fetch(PDO::FETCH_ASSOC);
                  if ($usuari!="") return true;
                  else return false;
            }

            /**
            *Registra a un usuario
            *
            * @access public array $datosUser 
            * 
            */
            public function registrarse($datosUser){
                  $dbh = BaseDatos::getInstance(); 
                  $consulta = $dbh->prepare("SET NAMES 'utf8'");
                  $consulta->execute();
                  $insertaUsuari = $dbh->prepare('INSERT INTO  `dropVidal`.`usuari` (
                                                                                       `MAIL` ,
                                                                                       `NOM` ,
                                                                                       `COGNOMS` ,
                                                                                       `IMAGEN` ,
                                                                                       `CONTRASENYA`
                                                                                     )
                                                   VALUES (
                                                            ?, 
                                                            ?,
                                                            ?,
                                                            ?,
                                                            ?
                                                      )');
                  $insertaUsuari->execute(array($datosUser['correo'], $datosUser['nombre'], $datosUser['apellidos'], $datosUser['imagen'], $datosUser['contrasenya']));
                    
            }

            /**
            *elimina un usuario
            *
            * @access public  $idUsuario
            */
            public function eliminarUsuario($idUsuario){
                  $dbh = BaseDatos::getInstance(); 
                  $seleccioUsuari = $dbh->prepare('DELETE FROM usuari WHERE ID_USUARI = :idUsuario');
                  $seleccioUsuari->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
                  $seleccioUsuari->execute();
            }

      }
?>  