<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro-Edicion</title>
     
    <!--<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/autofill/2.3.7/css/autoFill.bootstrap5.min.css">
    <!--CSS Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

</head>
<body>
    <?php
        function validar($objProveedor){
            var_dump($objProveedor);
            
            
            if (strlen(trim($objProveedor->nombre))>70 ||
                strlen(trim($objProveedor->nombre))<3)
                return false;
            
            
            if (strlen(trim($objProveedor->numCuenta))!=16)
                return false;

            if (!filter_var($objProveedor->correo,FILTER_VALIDATE_EMAIL))
                return false;

            if (strlen(trim($objProveedor->telefono))!=10)
             return false;   

            return true;
        }



        session_start();

        require_once '../../Backend/DAOS/DAOproveedor.php';
        require_once '../../Backend/Modelo/Proveedor.php';
        $proveedor=new Proveedor();
        $dao=new DAOproveedor();
        if(isset($_POST["idProveedor"]) && count($_POST)==1){
            echo "Carga para Modificar<br>";
            $proveedor=$dao->obtenerUno($_POST["idProveedor"]);
            if($proveedor==null){
                $_SESSION["error"]="El usuario no se ha encontrado, no se puede continuar con la operación";
                header("Location:ventanaProveedor.php");
                exit();
            }
        }else if(count($_POST)>0){
            echo "Modificar o agregar<br>";
            $proveedor->idProveedor=$_POST["idProveedor"];
            $proveedor->nombre=$_POST["nombre"];
            $proveedor->numCuenta=$_POST["numCuenta"];
            $proveedor->correo=$_POST["correo"];
            $proveedor->telefono=$_POST["telefono"];

           
            if(validar($proveedor)){

                $resultado=false;
                if($proveedor->idProveedor>0){
                    echo "antes editar";
                    $resultado=$dao->editar($proveedor);
                }else{
                    $resultado=$dao->agregar($proveedor);
                }
                
                if(!$resultado){
                    //Si hubo error al editar o añadir
                    echo "<div style='color: red'>No se pudo realizar la operación</div>";
                }else{
                    $_SESSION["mensaje"]="La operación se ha realizado exitósamente";
        
                    header("Location:ventanaProveedores.php");
                    exit();
                }
            }else{
                echo "<div style='color: red'>Los datos están incorrectos y/o incompletos</div>";
            }
        }else{
            echo "mostrar para agregar";
        }
        
    ?>
    <?php
    
    ?>

<div class="container">
        <form method="post" class="row g-3">
            <input type="hidden" name="idProveedor" value="<?= $proveedor->idProveedor?>">


            <div class="col-md-6">
                <label for="txtNombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="txtnombre" name="nombre" placeholder="Nombre" value="<?=$proveedor->nombre?>">
            </div>

            <div class="col-md-6">
                <label for="txtnumCuenta" class="form-label">Numero de cuenta</label>
                <input type="text" class="form-control" id="txtnumCuenta" name="numCuenta" placeholder="numCuenta" value="<?=$proveedor->numCuenta?>">
            </div>
            
            <div class="col-12">
                <label for="txtCorreo" class="form-label">Correo</label>
                <input type="mail" class="form-control" id="txtCorreo" name="correo" placeholder="direccion@dominio.com" value="<?=$proveedor->correo?>">
            </div>

            <div class="col-12">
                <label for="txtTelefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="txtTelefono" name="telefono" placeholder="Teléfono" value="<?=$proveedor->telefono?>">
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary" >Aceptar</button>
            </div>
        </form>
    </div>



    
    <script src="../../Backend/scripts/scripts.js"></script>

     <!-- Option 1: Bootstrap Bundle with Popper -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
    <!--Tabla-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
</body>
</html>