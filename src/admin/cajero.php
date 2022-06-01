<?php

session_start();
include '../../config/parametros.php';
include '../../config/helpers.php';
require_once('../../config/validar_session_admin.php');

$id = $_SESSION['ID_USUARIO'];
$fecha = $_GET['fecha'];
//consulta pedidos

$sql="SELECT 
      A.OPE_NUMERO, 
      A.OPE_NOMBRE,

      (SELECT COUNT(B.PDT_CODIGO)
       FROM CONTROL_INVENTARIO B
       WHERE A.OPE_NUMERO = B.ID_USUARIO
       AND B.FECHA_ASIGNACION = '$fecha'
      ) AS TOTAL_ASIGNADOS,

      (SELECT COUNT(B.PDT_CODIGO)
       FROM CONTROL_INVENTARIO B
       WHERE B.CONTEO IS NOT NULL
       AND A.OPE_NUMERO = B.ID_USUARIO
       AND B.FECHA_ASIGNACION = '$fecha'
      ) AS TOTAL_CONTADOS,
      (SELECT COUNT(PDT_CODIGO)
       FROM CONTROL_INVENTARIO B
       WHERE B.CONTEO IS NULL
       AND A.OPE_NUMERO = B.ID_USUARIO
       AND B.FECHA_ASIGNACION = '$fecha'
      ) AS POR_CONTAR
      FROM sistemasadn.ADN_USUARIOS A
      WHERE A.OPE_WEB = 0";  
$sentencia=$pdo->prepare($sql);
$sentencia->execute();
$listaCajeros=$sentencia->fetchAll(PDO::FETCH_ASSOC); 

headerAdmin();
?>
  <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl position-sticky blur shadow-blur mt-4 left-auto top-1 z-index-sticky" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="index.php">Inicio</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Fechas</li>
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
                <div class="col-lg-12 col-12">
                  <h6>Asignacion de Productos <?= $fecha ?></h6>
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
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cajero</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Asignados</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Contados</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Por Contar</th>
                    </tr>
                  </thead>
                  <tbody>

                  <?php foreach ($listaCajeros as $key => $value) { ?>

                    <tr onclick="window.location=`<?= base_url(); ?>/src/admin/detalle.php?fecha=<?=$fecha?>&cajero=<?=$value['OPE_NUMERO'] ?>`" style="cursor:pointer;">
                      <td class="align-middle text-center text-sm">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm"><?= $value['OPE_NOMBRE'] ?></h6>
                          </div>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <h6 class="mb-0 text-sm"><?= $value['TOTAL_ASIGNADOS'] ?></h6>
                      </td>
                        <td class="align-middle text-center text-sm">
                          <div style="display: flex;justify-content: center;align-items: center;">
                            <?php if($value['TOTAL_CONTADOS'] == $value['TOTAL_ASIGNADOS'] && $value['TOTAL_ASIGNADOS'] != 0) { ?>
                            <i style="color: green;" class="fa-solid fa-circle-check"></i>&nbsp;&nbsp;
                            <?php } ?>
                            <h6 class="mb-0 text-sm"><?= $value['TOTAL_CONTADOS'] ?></h6>
                          </div>
                        </td>

                        <td class="align-middle text-center text-sm">
                          <div style="display: flex;justify-content: center;align-items: center;">
                            <?php if($value['TOTAL_CONTADOS'] == $value['TOTAL_ASIGNADOS'] && $value['TOTAL_ASIGNADOS'] != 0) { ?>
                              <i style="color: green;" class="fa-solid fa-circle-check"></i>&nbsp;&nbsp;
                            <?php } else { ?>
                              <i style="color:red" class="fa-solid fa-circle-exclamation"></i>&nbsp;&nbsp;
                            <?php } ?>
                            <h6 class="mb-0 text-sm"><?= $value['POR_CONTAR'] ?></h6>
                          </div>
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
