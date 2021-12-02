<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedores</title>
    
     <!--<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">-->
     <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/autofill/2.3.7/css/autoFill.bootstrap5.min.css">
    <!--CSS Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

</head>
</head>
<body>
    <?php
        require_once '../../Backend/DAOS/DAOproveedor.php';
        $dao=new DAOproveedor();
        $listaProveedores=$dao->obtenerTodos();

        session_start();
        //Revisamos si cerro sesión
        if(isset($_REQUEST["cerrarsesion"])){
            unset($_SESSION["usuarioLogeado"]);
        }
        //Revisamos al usuario logeado
        if(isset($_SESSION["usuarioLogeado"])){
            //echo "<div style='color: green'>{$_SESSION["usuarioLogeado"]}</div>";
            //la variable usuario se elimina cuando se cierra la sesion
        }else{
            header("Location:Login.php");
        }

    ?>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand " href="MenuPrincipal.php">Punto de Venta</a>
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

    <div id="divContenedorTabla" class="container">
        <?php
            if(isset($_SESSION["error"])){
                echo "<div style='color: red'>{$_SESSION["error"]}</div>";
                unset($_SESSION["error"]);
               
            }
            if(isset($_SESSION["mensaje"])){
                echo "<div style='color: green'>{$_SESSION["mensaje"]}</div>";
                unset($_SESSION["mensaje"]);
            }
        ?>
        
        <div>
            <a href="registroProveedor.php" class="btn btn-success">Agregar</a>
            <p></p>
        </div>
        <table id="tblRegistros" class="table table-bordered table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Numero cuenta</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($listaProveedores as $objProveedor){
                    echo"<tr><td>".$objProveedor->idProveedor."</td>".
                            "<td>".$objProveedor->nombre."</td>".
                            "<td>".$objProveedor->numCuenta."</td>".
                            "<td>".$objProveedor->correo."</td>".
                            "<td>".$objProveedor->telefono."</td>"."
                            <td>
                                
                                <form method='POST'>
                                    <button formaction='registroProveedor.php' name='idProveedor' value='$objProveedor->idProveedor' class='btn btn-warning'>Editar</button>
                                    <button formaction='eliminarProveedor.php' name='idProveedor' value='$objProveedor->idProveedor' class='btn btn-danger'>Eliminar</button>
                                </form>

                            </td>
                        </tr>";
                    }
                ?>
            </tbody>
        </table>
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