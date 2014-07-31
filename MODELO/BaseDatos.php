<?php  
require_once CARPETAMODELOS . "conexion.php";  
  
final class BaseDatos{  
  private static $dns = DNS;  
  private static $username  = USERNAME;  
  private static $password  = PASSWORD;
  private static $instance;
      
  private function __construct() { }  
      
  /** 
   * Crea una instancia de la clase PDO 
   *  
   * @access public static 
   * @return object de la clase PDO 
   */  
  public static function getInstance()  
  {  
    if (!isset(self::$instance))  
    {  
      self::$instance = new PDO(self::$dns, self::$username, self::$password);  
      self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
    }  
    return self::$instance;  
  }  
      
      
 /** 
  * Impide que la clase sea clonada 
  *  
  * @access public 
  * @return string trigger_error 
  */  
  public function __clone()  
  {  
    trigger_error('Clone is not allowed.', E_USER_ERROR);  
  }  
}  
?>  