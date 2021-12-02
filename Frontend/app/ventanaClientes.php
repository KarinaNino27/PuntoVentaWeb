<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" 
    crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/autofill/2.3.7/css/autoFill.bootstrap5.min.css">  
</head>
<body>
<?php session_start();?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand " href="ventanaClientes.php">Clientes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="MenuPrincipal.php">Inicio</a>
                </li>
                <li class="nav-item">
                <a class="nav-link active" href="ventanaRealizarVenta.php">Realizar venta</a>
                <!--
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Mantenimiento
                </a>
                
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <li><a class="dropdown-item" href="ventanaEmpleados.php">Empleados</a></li>
                    <li><a class="dropdown-item" href="ventanaClientes.php">Clientes</a></li>
                    <li><a class="dropdown-item" href="ventanaProveedores.php">Proveedores</a></li>
                    <li><a class="dropdown-item" href="ventanaProductos.php">Productos</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="ventanaReportes.php">Reportes</a></li>
                </ul>
                -->
                </li>
                </li>
                <li class="nav-item">
                <a class="nav-link active btn btn-info" ><?php echo($_SESSION["usuarioLogeado"]);?></a>
                </li>
                <li class="nav-item">
                <a class="nav-link active btn btn-warning" href="MenuPrincipal.php?cerrarsesion">Cerrar Sesión</a>
            </li>
            </ul>
            </div>
        </div>
    </nav>


<?php
        require_once '../../Backend/DAOS/DAOcliente.php';
        $dao=new DAOcliente();
        $listaClientes=$dao->obtenerTodos();


        if(isset($_SESSION["error"])){
            echo "<div style='color: red'>{$_SESSION["error"]}</div>";
            unset($_SESSION["error"]);
            
        }
        if(isset($_SESSION["mensaje"])){
            echo "<div style='color: green'>{$_SESSION["mensaje"]}</div>";
            unset($_SESSION["mensaje"]);
        }
    

        require_once '../../Backend/DAOS/DAOcliente.php';
        require_once '../../Backend/Modelo/cliente.php';
        $cliente=new Cliente();
        $dao=new DAOcliente();
        //Es modificar porque se recibió un id
        if(isset($_POST["idCliente"]) && count($_POST)==1){
            echo "Carga para Modificar<br>";
            //var_dump($_POST);
            $cliente=$dao->obtenerUno($_POST["idCliente"]);
            //var_dump($cliente);
            if($cliente==null){
                $_SESSION["error"]="El usuario no se ha encontrado, no se puede continuar con la operación";
                header("Location:ventanaClientes.php");
                //exit();
            }
        }else if(count($_POST)>0){
            var_dump($_POST);
            echo "Modificar o agregar<br>";
            $cliente->idCliente=$_POST["idCliente"];
            $cliente->nombre=$_POST["nombre"];
            $cliente->apellidos=$_POST["apellidos"];
            $cliente->direccion=$_POST["direccion"];
            $cliente->telefono=$_POST["telefono"];


            $resultado=false;
            if($cliente->idCliente>0){
                echo "antes editar";
                $resultado=$dao->editar($cliente);
            }else{
                echo "antes editar";
                $resultado=$dao->agregar($cliente);
            }
            
            if(!$resultado){
                //Si hubo error al editar o añadir
                echo "<div style='color: red'>No se pudo realizar la operación, el usuario ingresado está duplicado</div>";
            }else{
                $_SESSION["mensaje"]="La operación se ha realizado exitósamente";
    
                header("Location:ventanaClientes.php");
                
            }
        }
    ?>

    <p style="text-align:center; font-size: 45px">Clientes</p>


    <form method='POST'>
        <div class="input-group input-group-sm mb-3">
           
            <input type="hidden" name="idCliente" value="<?= $cliente->idCliente?>">
            <span class="input-group-text" id="inputGroup-sizing-sm">Nombre</span>
            <input type="text" name="nombre" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" value="<?=$cliente->nombre?>">
            <span class="input-group-text" id="inputGroup-sizing-sm">Apellido</span>
            <input type="text" name="apellidos" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" value="<?=$cliente->apellidos?>">
            <span class="input-group-text" id="inputGroup-sizing-sm">Direccion</span>
            <input type="text" name="direccion" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" value="<?=$cliente->direccion?>">
            <span class="input-group-text" id="inputGroup-sizing-sm">Telefono</span>
            <input type="text" name="telefono" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" value="<?=$cliente->telefono?>">
            

            <?php
            //La contraseña no se podrá editar
            //Solo será visible cuando la operacion es agregar
            if(!isset($_REQUEST["idCliente"])){}
            ?>
            <button type="submit" class="btn btn-primary">Aceptar</button>
        </div>
    </form> 
    
    <!--
    <div class="vstack gap-2 col-md-5 mx-auto">
        <button onclick="location='login.php'" class="btn btn-secondary">Regresar</button>
    </div>
    <br><br> id="tblRegistros" class="table table-bordered table table-striped table-hover"
    -->

    <table id="tblRegistros" class="table table-dark table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Direccion</th>
                <th>Telefono</th>
                <th>Operaciones</th>
            </tr>
        </thead>
        <tbody>
        <?php
                    foreach ($listaClientes as $objCliente){
                    echo"<tr><td>".$objCliente->idCliente."</td>".
                            "<td>".$objCliente->nombre."</td>".
                            "<td>".$objCliente->apellidos."</td>".
                            "<td>".$objCliente->direccion."</td>".
                            "<td>".$objCliente->telefono."</td>"."
                            <td>
                                
                                <form method='POST'>
                                    <button formaction='ventanaClientes.php' name='idCliente' value='$objCliente->idCliente' class='btn btn-warning'>Editar</button>
                                    <button formaction='eliminarCliente.php' name='idCliente' value='$objCliente->idCliente' class='btn btn-danger'>Eliminar</button>
                                </form>

                            </td>
                        </tr>";
                    }
                ?>
        </tbody>
    </table>

    <
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