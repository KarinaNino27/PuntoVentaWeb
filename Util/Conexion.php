<?php
/**
 * Clase que manejará la conexión a la BD
 */
class Conexion
{
    private static $servidor = 'localhost' ;
    private static $db = 'puntoventaweb' ;
    private static $usuario = 'root';
    private static $password = 'root';
    
    //Referencia de la conexión a la BD
    private static $conexion  = null;

    /**
     * No se permite realizar instancias de la clase
     */
    public function __construct() {
        exit('Instancia no permitida');
    }
    
    /**
     * Funcion que permite abrir una nueva conexion a la base de datos 
     */
    public static function abrirConexion()
    {
        //self permite hacer una referencia al elemento estático
        //Se verifica si ya hay una conexión abierta
        if (self::$conexion==null)
        {     
            try
            {
                self::$conexion =  new PDO( "mysql:host=".self::$servidor.";"."dbname=".self::$db, self::$usuario, self::$password); 
                self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
            }
            catch(PDOException $e)
            {
                exit($e->getMessage()); 
            }
        }
        return self::$conexion;
    }
    
    /**
     * Funcion que permite cerrar la conexion a la base de datos 
     */
    public static function cerrarConexion()
    {
        self::$conexion = null;
    }
}
?>