<?php
//importa la clase conexión y el modelo para usarlos
require_once '../../Util/Conexion.php'; 
require_once '../../Backend/Modelo/Venta.php'; 
class DAOventa{
    
	private $conexion; 
    
    /**
     * Permite obtener la conexión a la BD
     */
    private function conectar(){
        try{
			$this->conexion = Conexion::abrirConexion(); 
		}
		catch(Exception $e)
		{
			die($e->getMessage()); /*Si la conexion no se establece se cortara el flujo enviando un mensaje con el error*/
		}
    }
    
	/**
     * Agrega un nuevo usuario de acuerdo al objeto recibido como parámetro
     */
    public function insertarVenta($idEmpleado,$idCliente)
	{
        $clave=0;
		try 
		{
            $sql = "call insertVenta(?,?)";
            
            $this->conectar();
            $this->conexion->prepare($sql)
                 ->execute(
                    array($idEmpleado,$idCliente));
            return true;
		} catch (Exception $e){
			echo $e->getMessage();
			return $clave;
		}finally{
            /*
            En caso de que se necesite manejar transacciones, no deberá desconectarse mientras la transacción deba persistir
            */
            Conexion::cerrarConexion();
        }
	}

    public function obtenerUltimaVenta()
	{
		try
		{ 
            $this->conectar();
            
            //Almacenará el registro obtenido de la BD
			$resultado = null; 
            
			$sentenciaSQL = $this->conexion->prepare
            ("SELECT MAX(idVenta)as idVenta FROM venta"); 
			//Se ejecuta la sentencia sql con los parametros dentro del arreglo 
            $sentenciaSQL->execute();
            
            /*Obtiene los datos*/
			$fila=$sentenciaSQL->fetch(PDO::FETCH_OBJ);
			
            $resultado = new Venta();
            $resultado->idVenta = $fila->idVenta;
            return $resultado->idVenta;
		}
		catch(Exception $e){
            echo $e->getMessage();
            return null;
		}finally{
            Conexion::cerrarConexion();
        }
	}

}