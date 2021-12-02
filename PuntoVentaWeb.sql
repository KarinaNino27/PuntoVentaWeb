-- ---------------------------------------------- DATABASE ----------------------------------------------
drop database if exists PuntoVentaWeb;
create database PuntoVentaWeb;
use PuntoVentaWeb;

-- ---------------------------------------------- TABLES ----------------------------------------------
create table Cliente (
	idCliente int unsigned not null auto_increment primary key,
    nombre varchar(45) not null,
    apellidos varchar(45) not null,
    direccion varchar(50) not null,     
    telefono varchar(20) not null
);

create table Empleado(
	idEmpleado int unsigned not null auto_increment primary key,
    user varchar(15) not null unique,
    password varchar(40) not null,
    nombre varchar(45) not null,
    apellido varchar(45) not null,
    correo varchar(45) not null,
    telefono varchar(20) not null,
    direccion varchar(50) not null,
    rol enum('administrador','vendedor') not null
);

create table Venta(
	idVenta int unsigned not null auto_increment primary key,
    fecha datetime not null,
    idCliente int unsigned not null,
    idEmpleado int unsigned not null,
    constraint foreign key (idCliente) references Cliente (idCliente) on update cascade, 
    constraint foreign key (idEmpleado) references Empleado (idEmpleado) on update cascade
);

create table Proveedor (
	idProveedor int unsigned not null auto_increment primary key,
    nombre varchar(30) not null,
    numCuenta text not null,
    correo varchar(45) not null,
    telefono varchar(20) null
);
create table Producto(
	idProducto int unsigned not null auto_increment primary key,
    codigoBarras varchar(35) unique not null,
    descripcion varchar(100) not null,
    stock int unsigned not null,
    precio decimal(10,2) not null,
    marca varchar(85) not null,
    categoria varchar(85) not null,
    idProveedor int unsigned not null,
    constraint foreign key (idProveedor) references Proveedor(idProveedor) on update cascade
);
 create table DetalleDeVenta(
	idProducto int unsigned not null,
    idVenta int unsigned not null,
    cantidad tinyint not null,
	precio decimal(10,2) not null,
    descuento float not null,
    primary key(idProducto,idVenta),
    constraint foreign key (idProducto) references Producto(idProducto) on update cascade,
    constraint foreign key (idVenta) references Venta(idVenta) on update cascade
 );
 
 -- ---------------------------------------------- TESTDAT ----------------------------------------------

INSERT INTO `cliente` VALUES (null,'Juan Jesus','Rocha','16 de septiembre #32','4451230987')
							,(null,'Renata','Rivas','Juarez #25','4441269712');

INSERT INTO `empleado` VALUES (null,'admin',sha1('admin'),'César Antonio','Navarro Sosa','cesaaar26@gmail.com','4451455052','Ponciano Vega #670','administrador');


INSERT INTO `proveedor` VALUES (null,'Serviplus SA de CV','1234567890123456','serviplus@gmail.com','12345656543')
							  ,(null,'Whirlpool SA de CV','1234567890123457','whirlpool@gmail.com','45556772829')
							  ,(null,'Mabe SA de CV','1234567890123458','mabe@gmail.com','0987654321')
                              ,(null,'Ryse SA de CV','1234567890123458','ryse@gmail.com','0987654321');
                              
INSERT INTO `producto` VALUES (null,'189D1903P001','Rotula superior Olympia',20,25.00,'Mabe','Lavadoras',1)
							 ,(null,'W10530058','Gas refrigerante R 134 1kg',75,252,'Genetron','Refrigeradores',4);  
                             
-- ------------------------------------------------- StorageProcedures -------------------------------------------------

-- Almacena una venta
DELIMITER $$
DROP PROCEDURE IF EXISTS insertVenta$$
CREATE PROCEDURE insertVenta(
    in clientee int,
    in empleadoo int)
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
	-- ERROR, WARNING
	ROLLBACK;
END;
	START TRANSACTION;
    insert into venta (idVenta, fecha, idCliente,idEmpleado)
                        values (null,now(),clientee,empleadoo);
	COMMIT;
END
$$
DELIMITER ;



-- Inserta los productos de la venta en la tabla detalle de venta
DELIMITER $$
DROP PROCEDURE IF EXISTS insertDetalleVenta$$
CREATE PROCEDURE insertDetalleVenta(
	in idp varchar(35),
    in idv int,
    in cantida int,
    in preci decimal(10,2),
    in descuent float)
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
	-- ERROR, WARNING
	ROLLBACK;
END;
	START TRANSACTION;
	insert into detalledeventa values(idp,idv,cantida,preci,descuent);
    update producto set descripcion=descripcion,
						stock=(stock-cantida),
                        precio=precio,
                        marca=marca,
                        categoria=categoria,
                        idproveedor=idproveedor where idProducto=idp;
	COMMIT;
END
$$
DELIMITER ;

-- Obtenemos una lista de las ventas en un rago de fechas
DELIMITER $$
DROP PROCEDURE IF EXISTS ventasPorFecha$$
CREATE PROCEDURE ventasPorFecha(fechaInicial datetime, fechaFinal datetime)
BEGIN
	SELECT v.idventa AS ID
		 , v.fecha AS Fecha 
		 , p.idProducto AS IDProducto
         , p.codigoBarras AS CodigoBarras
		 , p.descripcion AS Descripcion
		 , ddv.cantidad AS Cantidad
		 , ddv.precio AS Precio
		 , ddv.descuento AS descuento
		 , (((ddv.cantidad*ddv.precio)-((ddv.cantidad*ddv.precio)*ddv.descuento))) AS Importe
		 , (SELECT concat(e.nombre, " ", e.apellido) FROM empleado e WHERE e.idEmpleado=v.idEmpleado ) AS Empleado
		 , (SELECT concat(c.nombre," ",c.apellidos) FROM cliente c WHERE c.idCliente=v.idCliente) AS Cliente	
	FROM venta v JOIN detalledeventa ddv JOIN producto p
	ON v.idVenta = ddv.idVenta AND ddv.idProducto=p.idProducto
	WHERE DATE(v.fecha) BETWEEN fechaInicial AND fechaFinal;
END
$$  
DELIMITER ;

use PuntoVentaWeb;
describe producto;

select * from empleado;

insert into Empleado (idEmpleado, user, password, nombre, apellido, correo, telefono,direccion,rol) 
values (null,'Karina',sha1('Kary1234'),'Susana Karina','Paramo Niño', 'Karypnino@gmail.com','4451817925', 'Estacion #1', 'administrador');


select * from venta;
select * from detalledeventa;
call ventasPorFecha('2021-12-01','2021-12-01');