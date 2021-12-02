<?php
require_once '../../Util/Conexion.php';
require_once '../../Backend/Modelo/Cliente.php';

class DAOcliente{

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

    public function obtenerTodos()
	{
		try
		{
            $this->conectar();
            
			$lista = array();
            /*Se arma la sentencia sql para seleccionar todos los registros de la base de datos*/
			$sentenciaSQL = $this->conexion->prepare("SELECT idCliente, nombre, apellidos, direccion, telefono FROM Cliente");
			
            //Se ejecuta la sentencia sql, retorna un cursor con todos los elementos
			$sentenciaSQL->execute();
            
            /*Se recorre el cursor para obtener los datos*/
			foreach($sentenciaSQL->fetchAll(PDO::FETCH_OBJ) as $fila)
			{
				$obj = new Cliente();
                $obj->idCliente = $fila->idCliente;
	            $obj->nombre = $fila->nombre;
	            $obj->apellidos = $fila->apellidos;
	            $obj->direccion = $fila->direccion;
                $obj->telefono=$fila->telefono;
	            
				$lista[] = $obj;
			}
            
			return $lista;
		}
		catch(PDOException $e){
			return null;
		}finally{
            Conexion::cerrarConexion();
        }
	}


	public function obtenerIDUltimo()
	{
		try
		{ 
            $this->conectar();
            
            //Almacenará el registro obtenido de la BD
			$resultado = null; 
            
			$sentenciaSQL = $this->conexion->prepare
            ("SELECT MAX(idCliente)as idCliente FROM cliente"); 
			//Se ejecuta la sentencia sql con los parametros dentro del arreglo 
            $sentenciaSQL->execute();
            
            /*Obtiene los datos*/
			$fila=$sentenciaSQL->fetch(PDO::FETCH_OBJ);
			
            $resultado = new Producto();
            $resultado->idCliente = $fila->idCliente;
          
            $salida=$resultado->idCliente;
            return $salida;
		}
		catch(Exception $e){
            echo $e->getMessage();
            return null;
		}finally{
            Conexion::cerrarConexion();
        }
	}

    public function obtenerUno($id)
	{
		try
		{ 
            $this->conectar();
            
            //Almacenará el registro obtenido de la BD
			$registro = null; 
            
			$sentenciaSQL = $this->conexion->prepare("SELECT *FROM cliente WHERE idCliente=?"); 
			//Se ejecuta la sentencia sql con los parametros dentro del arreglo 
            $sentenciaSQL->execute([$id]);
            
            /*Obtiene los datos*/
			$fila=$sentenciaSQL->fetch(PDO::FETCH_OBJ);
			if($fila){
				$registro = new Cliente();
				$registro->idCliente = $fila->idCliente;
				$registro->nombre = $fila->nombre;
				$registro->apellidos = $fila->apellidos;
				$registro->direccion = $fila->direccion;
				$registro->telefono = $fila->telefono;
           
            	return $registro;
			}else{
				return false;
			}
            
		}
		catch(Exception $e){
            return null;
		}finally{
            Conexion::cerrarConexion();
        }
	}
    
    /**
     * Elimina el usuario con el id indicado como parámetro
     */
	public function eliminar($id)
	{
		try 
		{
			$this->conectar();
            
            $sentenciaSQL = $this->conexion->prepare("DELETE FROM cliente WHERE idCliente = ?");			          
			$resultado=$sentenciaSQL->execute(array($id));
			return $resultado;
		} catch (PDOException $e) 
		{
			//Si quieres acceder expecíficamente al numero de error
			//se puede consultar la propiedad errorInfo
			return false;	
		}finally{
            Conexion::cerrarConexion();
        }

		
        
	}

	/**
     * Función para editar al empleado de acuerdo al objeto recibido como parámetro
     */
	public function editar(cliente $obj)
	{
		try 
		{
			$sql = "UPDATE cliente SET 
                    nombre = ?,
                    apellidos = ?,
                    direccion = ?,
                    telefono = ?
				    WHERE idCliente = ?";

            $this->conectar();
            
            $sentenciaSQL = $this->conexion->prepare($sql);			          
			$sentenciaSQL->execute(
				array(	$obj->nombre,
						$obj->apellidos,
						$obj->direccion,
                        $obj->telefono,
						$obj->idCliente )
					);
            return true;
		} catch (PDOException $e){
			//Si quieres acceder expecíficamente al numero de error
			//se puede consultar la propiedad errorInfo
			
			return false;
		}finally{
            Conexion::cerrarConexion();
        }
	}

	
	/**
     * Agrega un nuevo usuario de acuerdo al objeto recibido como parámetro
     */
    public function agregar(Cliente $obj)
	{
        $clave=0;
		try 	
		{
            $sql = "INSERT INTO cliente (nombre, apellidos, direccion, telefono) values(?, ?, ?, ?)";
            
            $this->conectar();
            $this->conexion->prepare($sql)
                 ->execute(
                array($obj->nombre,
						$obj->apellidos,
                        $obj->direccion,
						$obj->telefono));
            $clave=$this->conexion->lastInsertId();
            return $clave;
		} catch (Exception $e){
			return $clave;
		}finally{
            
            /*En caso de que se necesite manejar transacciones, 
			no deberá desconectarse mientras la transacción deba 
			persistir*/
            
            Conexion::cerrarConexion();
        }
	}

}