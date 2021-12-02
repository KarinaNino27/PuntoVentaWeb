<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar venta</title>
    <!--CSS Bootstrap-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/autofill/2.3.7/css/autoFill.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  
</head>
<body>
    <?php
        require_once '../../Backend/DAOS/DAOCliente.php';
        require_once '../../Backend/DAOS/DAOProducto.php';
        require_once '../../Backend/DAOS/DAOventa.php';
        require_once '../../Backend/DAOS/DAODetalleDeVenta.php';
        require_once '../../Backend/Modelo/ElementoCarrito.php';
        $daoCliente = new DAOCliente();
        $objCliente = new Cliente();
        $objProducto = new Producto();
        $daoVenta = new DAOventa();
        $daoDetalleDeVenta = new DAOdetalleDeVenta();
        $daoProducto = new DAOProducto();
       
        session_start();
        if(!isset($_SESSION["objProductoSession"])){
            $idc=
            $_SESSION["objProductoSession"]=$daoProducto->obtenerUno($daoProducto->obtenerIDUltimo());
        }
        if(!isset($_SESSION["objClienteSession"])){
            $_SESSION["objClienteSession"]=$daoCliente->obtenerUno($daoCliente->obtenerIDUltimo());
        }
        

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
        if(isset($_REQUEST["error"])){
            echo "<div style='color: red'>{$_SESSION["error"]}</div>";
        }
        

        //revisar post para verificar si vamos a limpiar o a cargar un CLIENTE
        if(isset($_POST["idCliente"])&& $_POST["idCliente"]){
           $objCliente=$daoCliente->obtenerUno($_POST["idCliente"]);
           if($objCliente==NULL){
                $_SESSION["error"]="Cliente no encontrado";
                header("Location:ventanaRealizarVenta.php");
                exit();
           }else{
                $_SESSION["objClienteSession"]=$objCliente;
           }
        }
        if(isset($_POST["limpiarCliente"])&& $_POST["limpiarCliente"]){
            $_SESSION["objClienteSession"]=new Cliente();

        }

        //revisar post para verificar si vamos a cargar  o a limpiar un PRODUCTO
        if(isset($_POST["idProducto"])&& $_POST["idProducto"]){
            $objProducto=$daoProducto->obtenerUno($_POST["idProducto"]);
            if($objProducto==NULL){
                $_SESSION["error"]="Producto no encontrado";
                header("Location:ventanaRealizarVenta.php");             
                exit();
            }else{
                $_SESSION["objProductoSession"]=$objProducto;
               
                 
            }
        }else if(isset($_POST["codigoBarras"])&& $_POST["codigoBarras"]){
            $objProducto=$daoProducto->obtenerUnoCodigoBarras($_POST["codigoBarras"]);
           if($objProducto==NULL){
                $_SESSION["error"]="Producto no encontrado";
                header("Location:ventanaRealizarVenta.php");
                exit();
           }else{
                $_SESSION["objProductoSession"]=$objProducto;
           }
        }
        if(isset($_POST["limpiarProducto"])&& $_POST["limpiarProducto"]){
            $_SESSION["objProductoSession"]=new Producto();
        }

        //eliminar ELEMENTO del CARRITO
        if(isset($_POST["eliminarDelCarrito"])){
            foreach ($_SESSION["carrito"] as $obj){
                if($obj->idProducto==$_POST["eliminarDelCarrito"]){
                    unset($_SESSION["carrito"][$_POST["eliminarDelCarrito"]]);
                    header("Location:ventanaRealizarVenta.php");
                    exit();
                }
            }
        }

        //Eliminar carrito
        //unset($_SESSION["carrito"]);

        //crear CARRITO si no existe
        if(!isset($_SESSION["carrito"])){
            $_SESSION["carrito"]=array();
            $_SESSION["totalCarrito"]=0;
        }

        //CARRITO
        
        if(($_SESSION["objProductoSession"])&&isset($_POST["agregarCarrito"])){
            //crear CARRITO si no existe

            $idProductoCarrito=$_SESSION["objProductoSession"]->idProducto;
            $cantidad=$_POST["cantidad"];
            $descuento=$_POST["descuento"];
            $precio=$_SESSION["objProductoSession"]->precio;
    
            //revisar post para verificar si vamos a cargar un PRODUCTO al CARRITO
            foreach ($_SESSION["carrito"] as $obj){
                if($obj->idProducto==$idProductoCarrito){
                    if(($obj->cantidad+$_POST["cantidad"])>$_SESSION["objProductoSession"]->stock){
                        $_SESSION["error"]="Stock no suficiente";
                        header("Location:ventanaRealizarVenta.php");
                        exit();
                    }
                    $cantidad=$obj->cantidad+$_POST["cantidad"];
                }
            }
            //crear elementoCarrito
            $ElmentoCarrito=new ElementoCarrito();
            $ElmentoCarrito->idProducto=$idProductoCarrito;
            $ElmentoCarrito->codigoBarras=$_SESSION["objProductoSession"]->codigoBarras;
            $ElmentoCarrito->descripcion=$_SESSION["objProductoSession"]->descripcion;
            $ElmentoCarrito->cantidad=$cantidad;
            $ElmentoCarrito->precio=$precio;
            $ElmentoCarrito->descuento=$descuento;
            $ElmentoCarrito->importe=(($precio*$cantidad)-(($precio*$cantidad)*$descuento));
            //añadir a carrito
            $_SESSION["carrito"][$_SESSION["objProductoSession"]->idProducto]=$ElmentoCarrito;

            
        }
        
        //TOTAL
        if(isset($_SESSION["totalCarrito"])){
            $aux=0;
            foreach ($_SESSION["carrito"] as $obj){
                    $aux=$aux+($obj->importe);
            }
            $_SESSION["totalCarrito"]=$aux;
        }
        //PAGAR e insertar venta y detalle de venta 
        if(isset($_POST["efectivo"])&&$_SESSION["totalCarrito"]){
            if(($_POST["efectivo"]-$_SESSION["totalCarrito"])>0){

                if(($_SESSION["objClienteSession"]->nombre!=NULL)&&isset($_SESSION["carrito"])){
                    $insertVenta=$daoVenta->insertarVenta($_SESSION["objClienteSession"]->idCliente,$_SESSION["idEmpleado"]);
                    if($insertVenta){
                        $idVenta=$daoVenta->obtenerUltimaVenta();
                        if($idVenta){
                            $daoDetalleDeVenta->insertDetalleVenta($idVenta,$_SESSION["carrito"]);
                            if($daoDetalleDeVenta){
                                $_SESSION["mensaje"]="Transacción exitosa, su cambio: ".$_POST["efectivo"]-$_SESSION["totalCarrito"];
                                unset($_SESSION["carrito"]);
                                unset($_SESSION["totalCarrito"]);
                                $_SESSION["carrito"]=array();
                                $_SESSION["totalCarrito"]=0;
                                
                                
                            }else{
                                $_SESSION["error"]="Algo salio mal cuando se intentaron cargar los detalles";
                            }
                        }else{
                            $_SESSION["error"]="Algo salio mal, intente de nuevo";
                        }
                        
                    }else{
                        $_SESSION["error"]="No se pudo insertar la venta, intente de nuevo";
                    }
                    
                }else{
                    $_SESSION["error"]="Seleccione un cliente y/o productos";
                }   

            }else{
                $_SESSION["error"]="Efectivo ingresado insuficiente";
            }
            
        }

    ?>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="ventanaRealizarVenta.php">Realizar venta</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="MenuPrincipal.php">Inicio</a>
            </li>
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
            </li>-->
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
    <br> <h2>Datos de venta</h2> <br>
    <div class="container">
        <form id="frmCliente" class="row" method="POST">
            <div class="col-auto">
                <label for="txtidCliente" class="form-label">ID_Cliente:</label>
            </div>
            <div class="col-auto">
                <input type="number" class="form-control" id="txtIdCliente" placeholder="ID Cliente" name="idCliente" min=1>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3">Buscar</button>
            </div>
            <div class="col">
                <input type="text" class="form-control" id="txtCliente" placeholder="Nombre cliente" readonly value="<?=$_SESSION["objClienteSession"]->nombre." ".$_SESSION["objClienteSession"]->apellidos?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3 btn-warning" name="limpiarCliente"value="1">Limpiar</button>
            </div>
        </form>
    </div>
    <div class="container">
        <form id="frmProductoBuscar"class="row" method="POST">
            <div class="col-auto">
                <label for="txtidProducto" class="form-label">ID_Producto:</label>
            </div>
            <div class="col-auto">
                <input type="number" class="form-control" id="txtidProducto" placeholder="ID Producto" name="idProducto" min=1>
            </div>
            <div class="col-auto">
                <input type="text" class="form-control" id="txtcodigoBarras" placeholder="Codigo de barras" name="codigoBarras">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3">Buscar</button>
            </div>
            <div class="col">

                <input type="text" class="form-control" id="txtCliente" placeholder="Nombre Producto" readonly value="<?=$_SESSION["objProductoSession"]->descripcion?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3 btn-warning" name="limpiarProducto"value="1">Limpiar</button>
            </div>
        </form>
    </div>
    <div class="container">
        <form id="frmProductoAgregar" class="row" method="POST">
            <div class="col-auto">
                <label for="txtPrecio" class="form-label">Precio:</label>
            </div>
            <div class="col-auto">
                <input type="number" class="form-control" id="txtPrecio" placeholder="Precio" readonly value="<?=$_SESSION["objProductoSession"]->precio?>">
            </div>
            <div class="col-auto">
                <label for="txtStock" class="form-label">Stock:</label>
            </div>
            <div class="col-auto">
                <input type="text" class="form-control" id="txtcodigoBarras" placeholder="Stock"readonly value="<?=$_SESSION["objProductoSession"]->stock?>">
            </div>
            <div class="col-auto">
                <label for="txtCantidad" class="form-label">Cantidad:</label>
            </div>
            <div class="col-auto">
                <input type="number" class="form-control" id="txtCantidad" placeholder="Cantidad" name="cantidad" value="1" min="1" max=<?=$_SESSION["objProductoSession"]->stock?>>
            </div>
            <div class="col-auto">
                <label for="slcDescuento" class="form-label">Descuento:</label>
            </div>
            <div class="col-auto">
                <select name="descuento" id="slcDescuento" form="frmProductoAgregar" class="form-control" required>
                    <option value="0"selected>0%</option>
                    <option value="0.05">5 %</option>
                    <option value="0.10">10 %</option>
                    <option value="0.15">15%</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3 btn-success" name="agregarCarrito" value="1">Agregar al carrito</button>
            </div>
        </form>
    </div>

    <div class="container">
        <table id="tblRegistros" class="table table-bordered table table-striped table-hover">
            <thead>
                <tr>
                    
                    <th>ID Producto</th>
                    <th>codigoBarras</th>
                    <th>descripcion</th>
                    <th>cantidad</th>
                    <th>precio</th>
                    <th>descuento</th>
                    <th>importe</th>
                    <th>Operaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($_SESSION["carrito"] as $obj){
                    echo"<tr><td>".$obj->idProducto."</td>".
                            "<td>".$obj->codigoBarras."</td>".
                            "<td>".$obj->descripcion."</td>".
                            "<td>".$obj->cantidad."</td>".
                            "<td>".$obj->precio."</td>".
                            "<td>".$obj->descuento."</td>".
                            "<td>".$obj->importe."</td>"."
                            <td>
                                
                                
                                <form method='POST'>
                                    <button formaction='ventanaRealizarVenta.php' name='eliminarDelCarrito' value='$obj->idProducto' class='btn btn-danger'>Eliminar</button>
                                </form>

                            </td>
                        </tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
   
    <div class="container">
        <form id="frmRealizarVenta"class="row" method="POST">
            <div class="col-auto">
                <label for="txtTotal" class="form-label">Total:</label>
            </div>
            <div class="col-auto">
                <input type="number" class="form-control" id="txtTotal" placeholder="$0,000.00" readonly value="<?=$_SESSION["totalCarrito"]?>">
            </div>
            <div class="col-auto">
                <label for="txtEfectivo" class="form-label">Efectivo:</label>
            </div>
            <div class="col-auto">
                <input type="number" class="form-control" id="txtEfectivo" placeholder="$0,000.00" name="efectivo" min=1 value=1>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3 btn-success">Pagar</button>
            </div>
        </form>
    </div>



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

<script src="../../Backend/scripts/scripts.js"></script>

</body>
</html>