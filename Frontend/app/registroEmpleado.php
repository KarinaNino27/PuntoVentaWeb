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
        function validar($objEmpleado){
            var_dump($objEmpleado);
            
            if (strlen(trim($objEmpleado->user))<3 ||
                strlen(trim($objEmpleado->user))>15)
                return false;
            
            if (strlen(trim($objEmpleado->nombre))>45 ||
                strlen(trim($objEmpleado->nombre))<3)
                return false;
            
                
            if (strlen(trim($objEmpleado->apellido))>45 ||
                strlen(trim($objEmpleado->apellido))<2)
                return false;
            
            if (strlen(trim($objEmpleado->telefono))!=10)
                return false;

            if (strlen(trim($objEmpleado->direccion))>50 ||
                strlen(trim($objEmpleado->direccion))<5)
                return false;

            if (!filter_var($objEmpleado->correo,FILTER_VALIDATE_EMAIL))
                return false;

            
            //"^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
            //8 caracteres al menos una letra mayúscula, minúscula y un número
            if(empty($_POST) || 
              (count($_POST)>1 && isset($_POST["idEmpleado"]) && $_POST["idEmpleado"]=="")){
                if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/",$objEmpleado->password))
                    return false;
            }

            return true;
        }

        session_start();

        require_once '../../Backend/DAOS/DAOempleado.php';
        require_once '../../Backend/Modelo/Empleado.php';
        $empleado=new Empleado();
        $dao=new DAOempleado();
        //Es modificar porque se recibió un id
        if(isset($_POST["idEmpleado"]) && count($_POST)==1){
            echo "Carga para Modificar<br>";
            $empleado=$dao->obtenerUno($_POST["idEmpleado"]);
            if($empleado==null){
                $_SESSION["error"]="El usuario no se ha encontrado, no se puede continuar con la operación";
                header("Location:ventanaEmpleados.php");
                exit();
            }
        }else if(count($_POST)>0){
            echo "Modificar o agregar<br>";
            $empleado->idEmpleado=$_POST["idEmpleado"];
            $empleado->user=$_POST["user"];
            $empleado->nombre=$_POST["nombre"];
            $empleado->apellido=$_POST["apellido"];
            $empleado->correo=$_POST["correo"];
            $empleado->telefono=$_POST["telefono"];
            $empleado->direccion=$_POST["direccion"];
            $empleado->rol=$_POST["rol"];

            if(isset($_POST["password"])){
                $empleado->password=$_POST["password"];
            }

            if(validar($empleado)){

                $resultado=false;
                if($empleado->idEmpleado>0){
                    echo "antes editar";
                    $resultado=$dao->editar($empleado);
                }else{
                    $resultado=$dao->agregar($empleado);
                }
                
                if(!$resultado){
                    //Si hubo error al editar o añadir
                    echo "<div style='color: red'>No se pudo realizar la operación, el usuario ingresado está duplicado</div>";
                }else{
                    $_SESSION["mensaje"]="La operación se ha realizado exitósamente";
        
                    header("Location:ventanaEmpleados.php");
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
            <input type="hidden" name="idEmpleado" value="<?= $empleado->idEmpleado?>">
            <div class="col-md-6">
                <label for="txtUser" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="txtUser" name="user" placeholder="Usuario" value="<?=$empleado->user?>">
            </div>
            <?php
            //La contraseña no se podrá editar
            //Solo será visible cuando la operación es Agregar
            //tanto si se acaba de cargar la página como si se ha 
            //recargado desde ella misma (por dar click en aceptar)
            if(empty($_POST) || 
            (count($_POST)>1 && isset($_POST["idEmpleado"]) && $_POST["idEmpleado"]=="")){
            ?>
                <div class="col-md-6">
                    <label for="txtPass" class="form-label">Password</label>
                    <input type="password" class="form-control" id="txtPass" name="password" placeholder="Password" value="<?=$empleado->password?>">
                    <div id="emailHelp" class="form-text">8 caracteres al menos una letra mayúscula, minúscula, un número y sin caracteres especiales.</div>
                </div>
            <?php
                }
            ?>
            
            <div class="col-md-6">
                <label for="txtNombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="txtnombre" name="nombre" placeholder="Nombre" value="<?=$empleado->nombre?>">
            </div>
            <div class="col-md-6">
                <label for="txtApellido" class="form-label">Apellido(s)</label>
                <input type="text" class="form-control" id="txtApellido" name="apellido" placeholder="Apellido(s)" value="<?=$empleado->apellido?>">
            </div>
            <div class="col-12">
                <label for="txtCorreo" class="form-label">Correo</label>
                <input type="mail" class="form-control" id="txtCorreo" name="correo" placeholder="direccion@dominio.com" value="<?=$empleado->correo?>">
            </div>
            <div class="col-12">
                <label for="txtTelefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="txtTelefono" name="telefono" placeholder="Teléfono" value="<?=$empleado->telefono?>">
            </div>
            <div class="col-md-6">
                <label for="txtDireccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="txtDireccion" name="direccion" placeholder="Calle #Numero" value="<?=$empleado->direccion?>">
            </div>
            <div class="col-md-6">
                <label for="selectRol" class="form-label">Rol</label>
                <select id="selectRol" class="form-select" name="rol">
                <?php
                    if($empleado->value=="vendedor"){

                    
                ?>
                <option selected value="vendedor">vendedor</option>
                <option value="administrador">administrador</option>
                <?php
                    }else{

            
                ?>
                <option value="vendedor">vendedor</option>
                <option selected value="administrador">administrador</option>
                <?php
                    }
                ?>
                </select>
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