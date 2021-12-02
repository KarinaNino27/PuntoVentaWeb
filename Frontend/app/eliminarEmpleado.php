<?php
    if(isset($_POST["idEmpleado"]) && is_numeric($_POST["idEmpleado"])){
        
        require_once '../../Backend/DAOS/DAOempleado.php';
        $dao=new DAOempleado();
        $resultado=$dao->eliminar($_POST["idEmpleado"]);
        
        session_start();
        if(!$resultado){
            //Si hubo error al eliminar mostrar un mensaje
            $_SESSION["error"]="No se pudo eliminar el empleado, asegúrese de que este empleado no tiene ventas";
        }else{
            $_SESSION["mensaje"]="Eliminación exitosa";
        }
        
         header("Location:ventanaEmpleados.php");
         exit();
        
    }else{
        //Si no se recibe el id regresamos a la lista
        header("Location:ventanaEmpleados.php");
    }
?>