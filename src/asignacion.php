<?php 
include '../config/helpers.php';
include '../config/parametros.php';


$user_id = $_POST['id'];
$arrData = array();


$sqlMax = "SELECT 
			PDT_CODIGO, 
			PDT_DESCRIPCION,

			(SELECT COUNT(CODIGO)
			 FROM MOV_PRODUCTOS
			 WHERE CODIGO = ADN_PRODUCTOS.PDT_CODIGO
			 AND FECHA BETWEEN DATE_SUB(CURDATE(), INTERVAL $INV_RANGO_VENTAS DAY) AND CURDATE()
			 AND TIPODOC = 'FAV'
			) AS CONTADOR,

			(SELECT 
			 MAX(control_inventario.FECHA_ASIGNACION)
			 FROM control_inventario
			 WHERE control_inventario.PDT_CODIGO = ADN_PRODUCTOS.PDT_CODIGO
			) AS MAX_FECHA

			FROM ADN_PRODUCTOS 
			WHERE PDT_CODIGO  NOT IN

			(SELECT 
			 adn_productos.PDT_CODIGO
			 FROM ADN_PRODUCTOS 
			 INNER JOIN control_inventario ON adn_productos.PDT_CODIGO = control_inventario.PDT_CODIGO
			 WHERE control_inventario.CONTEO IS NULL
			)
			AND PDT_CODIGO NOT IN('FC01308')
			HAVING MAX_FECHA IS NULL OR MAX_FECHA <= DATE_SUB(CURDATE(), INTERVAL $INV_DIAS_CONTEO DAY)
			ORDER BY 3 DESC
			LIMIT $INV_MAX_VENTAS";

$sentencia=$pdo->prepare($sqlMax);
$sentencia->execute();
$result=$sentencia->fetchAll(PDO::FETCH_ASSOC);		

$sqlMin = "SELECT 
			PDT_CODIGO, 
			PDT_DESCRIPCION,

			(SELECT COUNT(CODIGO)
			 FROM MOV_PRODUCTOS
			 WHERE CODIGO = ADN_PRODUCTOS.PDT_CODIGO
			 AND FECHA BETWEEN DATE_SUB(CURDATE(), INTERVAL $INV_RANGO_VENTAS DAY) AND CURDATE()
			 AND TIPODOC = 'FAV'
			) AS CONTADOR,

			(SELECT 
			 MAX(control_inventario.FECHA_ASIGNACION)
			 FROM control_inventario
			 WHERE control_inventario.PDT_CODIGO = ADN_PRODUCTOS.PDT_CODIGO
			) AS MAX_FECHA

			FROM ADN_PRODUCTOS 
			WHERE PDT_CODIGO  NOT IN

			(SELECT 
			 adn_productos.PDT_CODIGO
			 FROM ADN_PRODUCTOS 
			 INNER JOIN control_inventario ON adn_productos.PDT_CODIGO = control_inventario.PDT_CODIGO
			 WHERE control_inventario.CONTEO IS NULL
			)
			HAVING MAX_FECHA IS NULL OR MAX_FECHA <= CURDATE()-$INV_DIAS_CONTEO
			ORDER BY 3 ASC
			LIMIT $INV_MIN_VENTAS";

//echo $sqlMax;die();			
	
$sentencia=$pdo->prepare($sqlMin);
$sentencia->execute();
$result2=$sentencia->fetchAll(PDO::FETCH_ASSOC);		

$arrData = array_merge($result,$result2);

$values = '';
$insert = "INSERT INTO control_inventario (ID_USUARIO,PDT_CODIGO,PDT_DESCRIPCION,FECHA_ASIGNACION)VALUES";

for($i = 0; $i < count($arrData); $i++){

	$pdt_codigo = $arrData[$i]['PDT_CODIGO'];
	$pdt_descripcion = $arrData[$i]['PDT_DESCRIPCION'];
	$values .= "($user_id, '$pdt_codigo', '$pdt_descripcion', CURDATE())";

	if ($i < count($arrData) - 1) {
            $values =$values.",";
        }
}
$insert = $insert.$values;

$sentencia=$pdo->prepare($insert);
$sentencia->execute();




 echo json_encode(array('success' => 1));

//header("location:".base_url()."/src/index.php");



		





?>