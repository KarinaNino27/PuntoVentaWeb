<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu principal</title>
    <!--CSS Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
</head>
<body>
    <?php
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
        <a class="navbar-brand" href="MenuPrincipal.php">Menu Principal</a>
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
            </li>
            </li>
            <li class="nav-item">
            <a class="nav-link active btn btn-info"><?php echo($_SESSION["usuarioLogeado"]);?></a>
            </li>
            <li class="nav-item">
            <a class="nav-link active btn btn-warning" href="MenuPrincipal.php?cerrarsesion">Cerrar Sesión</a>
        </li>
        </ul>
        </div>
    </div>
    </nav>
    <div>
        <p>Instancia de:<br>
         Susana Karina Paramo Niño - S18120206</div></p>
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
        

    <script src="../../Backend/scripts/scripts.js"></script>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


</body>
</html>