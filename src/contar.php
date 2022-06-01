<?php 
include '../config/conexion.php';
include '../config/helpers.php';

if($_POST){

$id 			 = $_POST['id'];
$id_usuario 	 = $_POST['id_user'];
$pdt_codigo 	 = $_POST['pdt_codigo'];
$pdt_descripcion = $_POST['pdt_descripcion'];
$conteo 		 = $_POST['conteo'];


$update = "UPDATE control_inventario SET 
			ID_USUARIO 		= $id_usuario, 
			PDT_CODIGO 		= '$pdt_codigo',
			PDT_DESCRIPCION = '$pdt_descripcion',
			EXISTENCIA		= INV_S2('$pdt_codigo','F'),
			CONTEO			= $conteo,
			FECHA_CONTEO	= NOW()
			WHERE ID = $id	";


$sentencia=$pdo->prepare($update);
$sentencia->execute();

header("location:".base_url()."/src/index.php");

}

?>