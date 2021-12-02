<?php
    if(isset($_POST["idCliente"]) && is_numeric($_POST["idCliente"])){
        
        require_once '../../Backend/DAOS/DAOcliente.php';
        $dao=new DAOcliente();
        $resultado=$dao->eliminar($_POST["idCliente"]);
        
        session_start();
        if(!$resultado){
            //Si hubo error al eliminar mostrar un mensaje
            $_SESSION["error"]="No se pudo eliminar el cliente";
        }else{
            $_SESSION["mensaje"]="Eliminación exitosa";
        }
        
         header("Location:ventanaClientes.php");
         exit();
        
    }else{
        //Si no se recibe el id regresamos a la lista
        header("Location:ventanaClientes.php");
    }
?>