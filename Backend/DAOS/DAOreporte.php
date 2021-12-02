<?php
//importa la clase conexión y el modelo para usarlos
require_once '../../Util/Conexion.php'; 
require_once '../../Backend/Modelo/Reporte.php'; 
class DAOreporte{
    
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
    public function ventasPorFecha($fi,$ff)
	{
        $clave=0;
		try 
		{
            
            $this->conectar();
             
			$lista = array();
            /*Se arma la sentencia sql para seleccionar todos los registros de la base de datos*/
			$sentenciaSQL = $this->conexion->prepare("call ventasPorFecha(?,?)");
			
            //Se ejecuta la sentencia sql, retorna un cursor con todos los elementos
			$sentenciaSQL->execute(array($fi,$ff));
        
           /*Obtiene los datos*/
           foreach($sentenciaSQL->fetchAll(PDO::FETCH_OBJ) as $fila)
           {
            $registro = new Reporte();
            $registro->ID = $fila->ID;
            $registro->Fecha = $fila->Fecha;
            $registro->IDProducto = $fila->IDProducto;
            $registro->CodigoBarras = $fila->CodigoBarras;
            $registro->Descripcion = $fila->Descripcion;
            $registro->Cantidad = $fila->Cantidad;
            $registro->Precio = $fila->Precio;
            $registro->Descuento = $fila->descuento;
            $registro->Importe = $fila->Importe;
            $registro->Empleado = $fila->Empleado;
            $registro->Cliente = $fila->Cliente;
           
            $lista[] = $registro;
			}
            return $lista;
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