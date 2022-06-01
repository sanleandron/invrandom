<?php

session_start();
include '../../config/parametros.php';
include '../../config/helpers.php';
require_once('../../config/validar_session_admin.php');

$id = $_SESSION['ID_USUARIO'];
$fecha = $_GET['fecha'];
$cajero = $_GET['cajero'];

$nombre_cajero = "SELECT OPE_NOMBRE FROM sistemasadn.ADN_USUARIOS WHERE OPE_NUMERO = $cajero";
$sentencia=$pdo->prepare($nombre_cajero);
$sentencia->execute();
$nombre=$sentencia->fetch(PDO::FETCH_ASSOC); 

//consulta pedidos

$sql="SELECT 
      PDT_CODIGO,
      PDT_DESCRIPCION,
      EXISTENCIA,
      CONTEO,
      FECHA_CONTEO
      FROM control_inventario
      WHERE FECHA_ASIGNACION = '$fecha'
      AND ID_USUARIO = $cajero";  
$sentencia=$pdo->prepare($sql);
$sentencia->execute();
$listaDetalle=$sentencia->fetchAll(PDO::FETCH_ASSOC); 

headerAdmin();
?>

<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl position-sticky blur shadow-blur mt-4 left-auto top-1 z-index-sticky" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="index.php"></a>Inicio</li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Fechas</li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Cajeros</li>
          </ol>
          
        </nav>

      </div>
    </nav>


    
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row">
        
       <div class="row my-4">
        
        <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-12 col-12" style="display: flex;">
                  <h6>Productos Asignados a &nbsp;&nbsp;</h6><h5><?= implode($nombre) ?></h5>
                  <p class="text-sm mb-0">
                    <!-- <i class="fa fa-check text-info" aria-hidden="true"></i> -->
                    <!-- <span class="font-weight-bold ms-1">Última Asignación</span>  -->
                  </p>
                </div>
              </div>
            </div>
            <div class="card-body">
              
              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Código</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Descripción</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Existencia</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Conteo</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha Conteo</th>
                    </tr>
                  </thead>
                  <tbody>

                  <?php foreach ($listaDetalle as $key => $value) { ?>

                    <tr>
                      <td class="align-middle text-center text-sm">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm"><?= $value['PDT_CODIGO'] ?></h6>
                          </div>
                      </td>
                      <td class="align-middle  text-sm">
                        <h6 class="mb-0 text-sm"><?= $value['PDT_DESCRIPCION'] ?></h6>
                      </td>

                      <td class="align-middle text-center text-sm">
                        <h6 class="mb-0 text-sm"><?= $value['EXISTENCIA'] ?></h6>
                      </td>

                      <td class="align-middle text-center text-sm">
                        <h6 class="mb-0 text-sm"><?= $value['CONTEO'] ?></h6>
                      </td>

                      <td class="align-middle text-center text-sm">
                        <h6 class="mb-0 text-sm"><?= $value['FECHA_CONTEO'] ?></h6>
                      </td>
                    </tr>
                          
                    <?php } ?>              

                  </tbody>
                </table>
              </div>

              

            </div>
          </div>
        </div>

      </div>
    <?php 

    footerAdmin();

    ?>
