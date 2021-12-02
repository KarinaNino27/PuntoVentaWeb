<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/autofill/2.3.7/css/autoFill.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Registro-Edicion-Producto</title>
</head>
<body>

    <?php
        function validar($objProducto){
            //var_dump($objProducto);
            
            if (strlen(trim($objProducto->descripcion))<3 ||
                strlen(trim($objProducto->descripcion))>40)
                return false;

            if(($objProducto->stock)<0)
                return false;
                           
            if (($objProducto->precio)>100000 ||
                ($objProducto->precio)<0)
                return false;
            
            if (strlen(trim($objProducto->marca))>45 ||
                strlen(trim($objProducto->marca))<3)
                return false;

            if (strlen(trim($objProducto->categoria))>35 ||
                strlen(trim($objProducto->categoria))<3)
                return false;
            if(($objProducto->idProveedor)<0)
                return false;

            return true;
            
        }

        session_start();

        require_once '../../Backend/DAOS/DAOproducto.php';
        require_once '../../Backend/Modelo/Producto.php';
        $producto=new Producto();
        $dao=new DAOproducto();
    
        //Editar
        if(isset($_POST["idProducto"]) && count($_POST)==1){
            echo " <br> <h1>Editar datos o stock  </h1> <br>";
            $producto=$dao->obtenerUno($_POST["idProducto"]);
            if($producto==null){
                $_SESSION["error"]="El producto no se ha encontrado, no se puede continuar con la operación";
                header("Location:ventanaProductos.php");
                exit();
            }
        }else if(count($_POST)>0){
            echo "<br> <h1>Datos Producto </h1> <br>";
                    
            $producto->idProducto=$_POST["idProducto"];
            $producto->codigoBarras=$_POST["codigoBarras"];
            $producto->descripcion=$_POST["descripcion"];
            $producto->stock=$_POST["stock"];
            $producto->precio=$_POST["precio"];
            $producto->marca=$_POST["marca"];
            $producto->categoria=$_POST["categoria"];
            $producto->idProveedor=$_POST["proveedor"];
            
            
            
            if(validar($producto)){
                    
                $resultado=false;
                if($producto->idProducto>0){
                    echo "antes editar";
                    $resultado=$dao->editar($producto);
                }else{
                    $resultado=$dao->agregar($producto);
                }
                
                if(!$resultado){
                    //Si hubo error al editar o añadir
                    echo "<div style='color: red'>No se pudo realizar la operación, el usuario ingresado está duplicado</div>";
                }else{
                    $_SESSION["mensaje"]="La operación se ha realizado exitósamente";
        
                    header("Location:ventanaProductos.php");
                    exit();
                }
            }else{
                echo "<div style='color: red'>Los datos están incorrectos y/o incompletos</div>";
            }
        }else{
            echo "<br><h1>Datos Producto</h1><br>";
        }
        
    ?>
    <?php
    
    ?>
    <div class="container">
        <form method="post" class="row g-3">
        <input type="hidden" name="idProducto" value="<?= $producto->idProducto?>">
        <div class="col-md-6">
                <label for="txtCodigoBarras" class="form-label">Codigo de Barras</label>
                <input type="text" class="form-control" id="txtCodigoBarras" name="codigoBarras" placeholder="codigoBarras" value="<?=$producto->codigoBarras?>">
            </div>
            <div class="col-md-6">
                <label for="txtDescripcion" class="form-label">Descripcion</label>
                <input type="text" class="form-control" id="txtDescripcion" name="descripcion" placeholder="Descripcion" value="<?=$producto->descripcion?>">
            </div>
            <?php
            ?>
            <div class="col-md-6">
                <label for="txtStock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="txtStock" name="stock" placeholder="stock" min =1 value="<?=$producto->stock?>">
            </div>
            <div class="col-md-6">
                <label for="txtPrecio" class="form-label">Precio</label>
                <input type="number" class="form-control" id="txtPrecio" name="precio" placeholder="Precio" min= 1 value="<?=$producto->precio?>">
            </div>
            <div class="col-12">
                <label for="txtMarca" class="form-label">Marca</label>
                <input type="text" class="form-control" id="txtMarca" name="marca" placeholder="marca" value="<?=$producto->marca?>">
            </div>
            <div class="col-12">
                <label for="txtCategoria" class="form-label">Categoria</label>
                <input type="text" class="form-control" id="txtCategoria" name="categoria" placeholder="categoria" value="<?=$producto->categoria?>">
            </div>
            <div class="col-md-6">
                <label for="txtProveedor" class="form-label">Proveedor</label>
                <input type="number" class="form-control" id="txtProveedor" name="proveedor" placeholder="Proveedor" value="<?=$producto->idProveedor?>">
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