<?php
    if(isset($_POST["idProveedor"]) && is_numeric($_POST["idProveedor"])){
        
        require_once '../../Backend/DAOS/DAOproveedor.php';
        $dao=new DAOproveedor();
        $resultado=$dao->eliminar($_POST["idProveedor"]);
        
        session_start();
        if(!$resultado){
            $_SESSION["error"]="No se pudo eliminar el proveedor";
        }else{
            $_SESSION["mensaje"]="Eliminación exitosa";
        }
        
         header("Location:ventanaProveedores.php");
         exit();
        
    }else{
        //Si no se recibe el id regresamos a la lista
        header("Location:ventanaProveedores.php");
    }
?>