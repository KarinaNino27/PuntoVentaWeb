<?php
//importa la clase conexión y el modelo para usarlos
require_once '../../Util/Conexion.php'; 
require_once '../../Backend/Modelo/Empleado.php'; 
class DAOempleado{
    
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
			$sentenciaSQL = $this->conexion->prepare("SELECT idEmpleado, user, nombre, apellido, correo, telefono, direccion, rol FROM Empleado");
			
            //Se ejecuta la sentencia sql, retorna un cursor con todos los elementos
			$sentenciaSQL->execute();
            
            /*Se recorre el cursor para obtener los datos*/
			foreach($sentenciaSQL->fetchAll(PDO::FETCH_OBJ) as $fila)
			{
				$obj = new Empleado();
                $obj->idEmpleado = $fila->idEmpleado;
                $obj->user = $fila->user;
                $obj->nombre = $fila->nombre;
                $obj->apellido = $fila->apellido;
                $obj->correo = $fila->correo;
                $obj->telefono = $fila->telefono;
                $obj->direccion = $fila->direccion;
                $obj->rol = $fila->rol;
	            
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
            
			$sentenciaSQL = $this->conexion->prepare("SELECT idEmpleado, user, nombre, apellido, correo, telefono, direccion, rol FROM Empleado WHERE idEmpleado=?"); 
			//Se ejecuta la sentencia sql con los parametros dentro del arreglo 
            $sentenciaSQL->execute([$id]);
            
            /*Obtiene los datos*/
			$fila=$sentenciaSQL->fetch(PDO::FETCH_OBJ);
			
            $registro = new Empleado();
            $registro->idEmpleado = $fila->idEmpleado;
            $registro->user = $fila->user;
            $registro->nombre = $fila->nombre;
            $registro->apellido = $fila->apellido;
            $registro->correo = $fila->correo;
            $registro->telefono = $fila->telefono;
            $registro->direccion = $fila->direccion;
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

    public function eliminar($id)
	{
		try 
		{
			$this->conectar();
            
            $sentenciaSQL = $this->conexion->prepare("DELETE FROM Empleado WHERE idEmpleado = ?");			          
            //echo'arma la sentencia';
			$resultado=$sentenciaSQL->execute(array($id));
			//var_dump($resultado);
            return $resultado;
		} catch (Exception $e) 
		{	
			//echo $e->getMessage();
            return false;
		}finally{
            Conexion::cerrarConexion();
        }
        
	}
    

    public function editar(Empleado $objEmpleado)
	{
		try 
		{
			$sql = "UPDATE Empleado SET 
                    user = ?,
                    password=password,
                    nombre = ?,
                    apellido = ?,
                    correo = ?,
                    telefono = ?,
                    direccion = ?,
                    rol = ?
				    WHERE idEmpleado = ?";

            $this->conectar();
            
            $sentenciaSQL = $this->conexion->prepare($sql);			          
			$sentenciaSQL->execute(
				array(	$objEmpleado->user,
						$objEmpleado->nombre,
						$objEmpleado->apellido,
						$objEmpleado->correo,
                        $objEmpleado->telefono,
                        $objEmpleado->direccion,
                        $objEmpleado->rol,
                        $objEmpleado->idEmpleado)
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
    public function agregar(Empleado $objEmpleado)
	{
        $clave=0;
		try 
		{
            $sql = "insert into Empleado (idEmpleado, 
                                        user, 
                                        password, 
                                        nombre, 
                                        apellido, 
                                        correo, 
                                        telefono,
                                        direccion,
                                        rol)  
                    values(null, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $this->conectar();
            $this->conexion->prepare($sql)
                 ->execute(
                    array(	$objEmpleado->user,
                        $objEmpleado->password,
                        $objEmpleado->nombre,
						$objEmpleado->apellido,
						$objEmpleado->correo,
                        $objEmpleado->telefono,
                        $objEmpleado->direccion,
                        $objEmpleado->rol));
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