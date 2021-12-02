<?php
//importa la clase conexión y el modelo para usarlos
require_once '../../Util/Conexion.php'; 
require_once '../../Backend/Modelo/Producto.php'; 
class DAOproducto{
    
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
             
			$listaProd = array();
            /*Se arma la sentencia sql para seleccionar todos los registros de la base de datos*/
			$sentenciaSQL = $this->conexion->prepare
            ("SELECT * FROM producto");
			
            //Se ejecuta la sentencia sql, retorna un cursor con todos los elementos
			$sentenciaSQL->execute();
            
            /*Se recorre el cursor para obtener los datos*/
			foreach($sentenciaSQL->fetchAll(PDO::FETCH_OBJ) as $fila)
			{
				$obj = new Producto();
                $obj->idProducto = $fila->idProducto;
                $obj->codigoBarras = $fila->codigoBarras;
                $obj->descripcion = $fila->descripcion;
                $obj->stock = $fila->stock;
                $obj->precio = $fila->precio;
                $obj->marca = $fila->marca;
                $obj->categoria = $fila->categoria;
                $obj->idProveedor = $fila->idProveedor;
	            
				$listaProd[] = $obj;
			}
            
			return $listaProd;
		}
		catch(Exception $e){
			echo $e->getMessage();
			return null;
		}finally{
            Conexion::cerrarConexion();
        }
	}

    public function obtenerUnoCodigoBarras($cb)
	{
		try
		{ 
            $this->conectar();
            
            //Almacenará el registro obtenido de la BD
			$resultado = null; 
            
			$sentenciaSQL = $this->conexion->prepare
            ("SELECT * FROM producto WHERE codigoBarras=?"); 
			//Se ejecuta la sentencia sql con los parametros dentro del arreglo 
            $sentenciaSQL->execute([$cb]);
            
            /*Obtiene los datos*/
			$fila=$sentenciaSQL->fetch(PDO::FETCH_OBJ);
			if($fila){
                $resultado = new Producto();
                $resultado->idProducto = $fila->idProducto;
                $resultado->codigoBarras = $fila->codigoBarras;
                $resultado->descripcion = $fila->descripcion;
                $resultado->stock = $fila->stock;
                $resultado->precio = $fila->precio;
                $resultado->marca = $fila->marca;
                $resultado->categoria = $fila->categoria;
                $resultado->idProveedor = $fila->idProveedor;
            
                return $resultado;
            }else{
                return false;
            }
            
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
			$resultado = null; 
            
			$sentenciaSQL = $this->conexion->prepare
            ("SELECT * FROM producto WHERE idProducto=?"); 
			//Se ejecuta la sentencia sql con los parametros dentro del arreglo 
            $sentenciaSQL->execute([$id]);
            
            /*Obtiene los datos*/
			$fila=$sentenciaSQL->fetch(PDO::FETCH_OBJ);
			if($fila){
                $resultado = new Producto();
                $resultado->idProducto = $fila->idProducto;
                $resultado->codigoBarras = $fila->codigoBarras;
                $resultado->descripcion = $fila->descripcion;
                $resultado->stock = $fila->stock;
                $resultado->precio = $fila->precio;
                $resultado->marca = $fila->marca;
                $resultado->categoria = $fila->categoria;
                $resultado->idProveedor = $fila->idProveedor;
            
                return $resultado;
            }else{
                return false;
            }
            
		}
		catch(Exception $e){
            echo $e->getMessage();
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
            ("SELECT MAX(idProducto)as idProducto FROM producto"); 
			//Se ejecuta la sentencia sql con los parametros dentro del arreglo 
            $sentenciaSQL->execute();
            
            /*Obtiene los datos*/
			$fila=$sentenciaSQL->fetch(PDO::FETCH_OBJ);
			
            $resultado = new Producto();
            $resultado->idProducto = $fila->idProducto;
          
            $salida=$resultado->idProducto;
            return $salida;
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
            
            $sentenciaSQL = $this->conexion->prepare
            ("DELETE FROM Producto WHERE idProducto = ?");			          
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
    

    public function editar(Producto $objProducto)
	{
		try 
		{
			$sql = "UPDATE producto SET 
                    codigoBarras = ?,
                    descripcion = ?,
                    stock = ?,
                    precio = ?,
                    marca = ?,
                    categoria = ?,
                    idProveedor = ?
				    WHERE idProducto = ?";

            $this->conectar();
            
            $sentenciaSQL = $this->conexion->prepare($sql);			          
			$sentenciaSQL->execute(
                
				array(	$objProducto->codigoBarras,
                        $objProducto->descripcion,
						$objProducto->stock,
						$objProducto->precio,
						$objProducto->marca,
                        $objProducto->categoria,
                        $objProducto->idProveedor,
                        $objProducto->idProducto)
					);
            return true;
		} catch (Exception $e){
			echo $e->getMessage();
            //exit();
			return false;
		}finally{
            Conexion::cerrarConexion();
        }
	}

	
	/**
     * Agrega un nuevo producto de acuerdo al objeto recibido como parámetro
     */
    public function agregar($objProducto)
	{
        $clave=0;
		try 
		{
            $sql = "insert into Producto (idProducto,
                                        codigoBarras, 
                                        descripcion, 
                                        stock, 
                                        precio, 
                                        marca, 
                                        categoria, 
                                        idProveedor)  
                     values(null,?, ?, ?, ?, ?, ?, ?)";
            
            $this->conectar();
            $this->conexion->prepare($sql)
                 ->execute(
                    array(
                        $objProducto->codigoBarras,	
                        $objProducto->descripcion,
                        $objProducto->stock,
                        $objProducto->precio,
						$objProducto->marca,
						$objProducto->categoria,
                        $objProducto->idProveedor));

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
