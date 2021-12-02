<?php
//importa la clase conexión y el modelo para usarlos
require_once '../../Util/Conexion.php'; 
require_once '../../Backend/Modelo/Proveedor.php'; 
class DAOproveedor{
    
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
    
   
	public function login($user,$pass){
		try{ 
            $this->conectar();
            
            //Almacenará el registro obtenido de la BD
			$registro = null; 
            
			$sentenciaSQL = $this->conexion->prepare("SELECT idEmpleado, user, nombre, apellido, rol FROM Empleado WHERE user=? AND password=sha1(?)"); 
			//Se ejecuta la sentencia sql con los parametros dentro del arreglo
            $sentenciaSQL->execute(array($user,$pass));
            
            /*Obtiene los datos*/
			$fila=$sentenciaSQL->fetch(PDO::FETCH_OBJ);
			
            $registro = new Empleado();
            $registro->idEmpleado = $fila->idEmpleado;
            $registro->user = $fila->user;
            $registro->nombre = $fila->nombre;
            $registro->apellido = $fila->apellido;
            $registro->rol = $fila->rol;
		
            return $registro;
		}
		catch(Exception $e){
            echo $e->getMessage();
            return null;
		}finally{
            Conexion::cerrarConexion();
        }
	}

    public function obtenerTodos()
	{
		try
		{
            $this->conectar();
             
			$lista = array();
            /*Se arma la sentencia sql para seleccionar todos los registros de la base de datos*/
			$sentenciaSQL = $this->conexion->prepare("SELECT idProveedor, nombre, numCuenta,correo,telefono from proveedor");
			
            //Se ejecuta la sentencia sql, retorna un cursor con todos los elementos
			$sentenciaSQL->execute();
            
            /*Se recorre el cursor para obtener los datos*/
			foreach($sentenciaSQL->fetchAll(PDO::FETCH_OBJ) as $fila)
			{
				$obj = new Proveedor();

                $obj->idProveedor = $fila->idProveedor;
                $obj->nombre = $fila->nombre;
                $obj->numCuenta = $fila->numCuenta;
                $obj->correo = $fila->correo;
                $obj->telefono = $fila->telefono;
               
	            
				$lista[] = $obj;
			}
            
			return $lista;
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
            
			$sentenciaSQL = $this->conexion->prepare("SELECT idProveedor, nombre, numCuenta,correo,telefono from proveedor where idProveedor = ?");
			//Se ejecuta la sentencia sql con los parametros dentro del arreglo 
            $sentenciaSQL->execute([$id]);
            
            /*Obtiene los datos*/
			$fila=$sentenciaSQL->fetch(PDO::FETCH_OBJ);
			
            $registro = new Proveedor();
            $registro->idProveedor = $fila->idProveedor;
            $registro->nombre = $fila->nombre;
            $registro->numCuenta = $fila->numCuenta;
            $registro->correo = $fila->correo;
            $registro->telefono = $fila->telefono;
           
            return $registro;
		}
		catch(Exception $e){
            echo $e->getMessage();
            return null;
		}finally{
            Conexion::cerrarConexion();
        }
	}






    public function eliminar($id)
	{
		try 
		{
			$this->conectar();
            
            $sentenciaSQL = $this->conexion->prepare("DELETE FROM Proveedor WHERE idProveedor = ?");			          
			$resultado=$sentenciaSQL->execute(array($id));
            return $resultado;
		} catch (Exception $e) 
		{	
            return false;
		}finally{
            Conexion::cerrarConexion();
        }
        
	}
    

    public function editar(Proveedor $objProveedor)
	{
		try 
		{
			$sql = "UPDATE proveedor set nombre = ?, numCuenta = ?, correo = ?, telefono = ? WHERE idProveedor = ?";

            $this->conectar();
            
            $sentenciaSQL = $this->conexion->prepare($sql);			          
			$sentenciaSQL->execute(
				array(  $objProveedor->nombre,
						$objProveedor->numCuenta,
						$objProveedor->correo,
                        $objProveedor->telefono,
                        $objProveedor->idProveedor)
					);
            return true;
		} catch (Exception $e){
			echo $e->getMessage();
			return false;
		}finally{
            Conexion::cerrarConexion();
        }
	}





	
	/**
     * Agrega un nuevo usuario de acuerdo al objeto recibido como parámetro
     */
    public function agregar(Proveedor $objProveedor)
	{
        $clave=0;
		try 
		{
           // $sql = "insert into provedor (idProveedor, nombre, numCuenta, correo, telefono,) values (null, ?, ?, ?, ?)";
            $sql = "INSERT INTO proveedor (nombre, numCuenta, correo, telefono) values(?, ?, ?, ?)";
            
            $this->conectar();
            $this->conexion->prepare($sql)
                 ->execute(
                    array($objProveedor->nombre,
						$objProveedor->numCuenta,
						$objProveedor->correo,
                        $objProveedor->telefono));

            $clave=$this->conexion->lastInsertId();
            return $clave;
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