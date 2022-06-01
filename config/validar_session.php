<?php 

$conexion = mysqli_connect(SERVIDOR, USUARIO, PASSWORD, BD);

$consulta=" SELECT a.ope_nombre as usuario, a.ope_clave as clave, a.ope_web as admin FROM sistemasadn.`adn_usuarios` as a WHERE a.ope_nombre =  '$_SESSION[USUARIO]'";
$resultado=mysqli_query($conexion, $consulta);
$datos=mysqli_fetch_assoc($resultado);
$filas=mysqli_num_rows($resultado);


		if($filas == 0){

			header("location:../index.php");

		} 

		if($datos['admin'] != 0){
			
			header("location:../index.php");
						
		}


 ?>