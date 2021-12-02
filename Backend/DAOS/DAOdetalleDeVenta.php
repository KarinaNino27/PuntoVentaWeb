<?php
//importa la clase conexión y el modelo para usarlos
require_once '../../Util/Conexion.php'; 
require_once '../../Backend/Modelo/DetalleDeVenta.php'; 
class DAOdetalleDeVenta{
    
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
    public function insertDetalleVenta($idVenta,$carrito)
	{
        $clave=0;
		try 
		{
            $sql = "call insertDetalleVenta(?,?,?,?,?)";
            
            foreach($carrito as $elemento){
                $this->conectar();
                $this->conexion->prepare($sql)
                    ->execute(array($elemento->idProducto,
                                    $idVenta,
                                    $elemento->cantidad,
                                    $elemento->precio,
                                    $elemento->descuento));
            }

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
}