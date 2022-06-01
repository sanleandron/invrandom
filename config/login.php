<?php 

include 'globals.php';

$conexion = mysqli_connect(SERVIDOR, USUARIO, PASSWORD, BD);
if (!$conexion) {
	echo "error al conectar con la base de datos";
}
else {

	session_start();

	$usuario=strtoupper($_POST["usuario"]);
	$clave=$_POST["clave"];


	$sql=" SELECT a.ope_numero as id, a.ope_nombre as usuario, a.ope_clave as clave, a.ope_web as admin FROM sistemasadn.`adn_usuarios` as a where a.ope_nombre = '$usuario' and a.ope_clave = '$clave'";
	$resultado=mysqli_query($conexion, $sql);
	$datos=mysqli_fetch_assoc($resultado);
	$filas=mysqli_num_rows($resultado);

	//dep($datos); die();

		if($filas > 0  ){

			if($datos['admin'] == 1){
				$_SESSION['USUARIO']=$datos['usuario'];
				$_SESSION['ID_USUARIO']=$datos['id'];
				header("location:../src/admin/index.php"); 	
			} else {
				$_SESSION['USUARIO']=$datos['usuario'];
				$_SESSION['ID_USUARIO']=$datos['id'];
				header("location:../src/index.php"); 		
			}
			
			

		} else {
			echo '<script>
			alert("Error al autenticar, verifique el usuario o la clave")
			window.history.go(-1);
			</script>';
		}

}
	
	mysqli_free_result($resultado);
	mysqli_close($conexion);

	?>
