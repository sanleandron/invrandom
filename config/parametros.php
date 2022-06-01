<?php  
include 'conexion.php';

 /******************INV_RANGO_VENTAS******************* */
    $sql="SELECT valor FROM adn_config WHERE codigo = 'INV_RANGO_VENTAS'";  
    $sentencia=$pdo->prepare($sql);
    $sentencia->execute();
    $result=$sentencia->fetch(PDO::FETCH_ASSOC);
        
    $INV_RANGO_VENTAS = empty($result['valor'])? 30 :$result['valor'];

    /**************INV_DIAS_CONTEO************* */
    $sql="SELECT valor FROM adn_config WHERE codigo = 'INV_DIAS_CONTEO'";  
    $sentencia=$pdo->prepare($sql);
    $sentencia->execute();
    $result=$sentencia->fetch(PDO::FETCH_ASSOC);

    $INV_DIAS_CONTEO = empty($result['valor'])? 1 :$result['valor'];

    /**************INV_MAX_VENTAS************* */
    $sql="SELECT valor FROM adn_config WHERE codigo = 'INV_MAX_VENTAS'";  
    $sentencia=$pdo->prepare($sql);
    $sentencia->execute();
    $result=$sentencia->fetch(PDO::FETCH_ASSOC);
   
    $INV_MAX_VENTAS = empty($result['valor'])?30:$result['valor'];
   
    /******************INV_MIN_VENTAS************** */
    $sql="SELECT valor FROM adn_config WHERE codigo = 'INV_MIN_VENTAS'";  
    $sentencia=$pdo->prepare($sql);
    $sentencia->execute();
    $result=$sentencia->fetch(PDO::FETCH_ASSOC);

    $INV_MIN_VENTAS = empty($result['valor'])?10:$result['valor'];

?>