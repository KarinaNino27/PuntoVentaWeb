<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
    <!--CSS Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="../../Frontend/css/estilos.css">

</head>
<body>
    <?php
        session_start();
            
        if(isset($_POST["user"])&&isset($_POST["pass"])){
            require_once '../../Backend/DAOS/DAOempleado.php';
            //Poner código para buscar
            $daoEmpleado=new DAOempleado();
            $objEmpleado=$daoEmpleado->login($_POST["user"],$_POST["pass"]);
            //var_dump($objEmpleado);
    
            if($objEmpleado->user==NULL){          
                $_SESSION["error"]="No se pudo encontrar el usuario, asegurese de que sus credenciales sean validas";
                header("Location:Login.php");
            }else{
                $_SESSION["mensaje"]="Inicio de sesión exitoso, Bienvenido ";
                $_SESSION["usuarioLogeado"]="".$objEmpleado->nombre." ".$objEmpleado->apellido;
                $_SESSION["idEmpleado"]="".$objEmpleado->idEmpleado;
                unset($_SESSION["error"]);
                header("Location:MenuPrincipal.php");
            }
            //exit();
    
        }

        if(isset($_SESSION["error"]) && $_SESSION["error"]){
            echo "<div style='color: red'>{$_SESSION["error"]}</div>";
        }
    ?>
    <div class="container" id="divFrm">
        <h1>Inicio de sesión</h1>
        <form method="POST" name="form-login" class="row g-3">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Usuario</label>
                <input type="text" class="form-control" name="user" required placeholder="Usuario">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" name="pass" required placeholder="Contraseña">
            </div>
            <button type="submit" class="btn btn-primary">Log In</button>
        </form>
    </div>
   

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
