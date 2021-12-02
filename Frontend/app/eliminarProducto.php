<?php
    if(isset($_POST["idProducto"])){
        
        require_once '../../Backend/DAOS/DAOproducto.php';
        $dao=new DAOproducto();
        $resultado=$dao->eliminar($_POST["idProducto"]);
        
        session_start();
        if(!$resultado){
            //Si hubo error al eliminar mostrar un mensaje
            $_SESSION["error"]="No se pudo eliminar el producto";
        }else{
            $_SESSION["mensaje"]="Eliminación exitosa";
        }
        
         header("Location:ventanaProductos.php");
         exit();
        
    }else{
        //Si no se recibe el id regresamos a la lista
        header("Location:ventanaProductos.php");
    }
?>
