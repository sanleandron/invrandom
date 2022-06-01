<?php

session_start();
include '../../config/parametros.php';
include '../../config/helpers.php';
require_once('../../config/validar_session_admin.php');

$id = $_SESSION['ID_USUARIO'];
//consulta pedidos
$sql="SELECT 
      A.FECHA_ASIGNACION, 
      COUNT(A.PDT_CODIGO)AS TOTAL_PRODUCTOS,
      (SELECT COUNT(PDT_CODIGO)
       FROM CONTROL_INVENTARIO B
       WHERE B.CONTEO IS NOT NULL
       AND B.FECHA_ASIGNACION = A.FECHA_ASIGNACION
       ) AS TOTAL_CONTADOS,
      (SELECT COUNT(PDT_CODIGO)
       FROM CONTROL_INVENTARIO B
       WHERE B.CONTEO IS NULL
       AND B.FECHA_ASIGNACION = A.FECHA_ASIGNACION
       ) AS POR_CONTAR
      FROM CONTROL_INVENTARIO A
      GROUP BY FECHA_ASIGNACION";  
$sentencia=$pdo->prepare($sql);
$sentencia->execute();
$listaAsignacion=$sentencia->fetchAll(PDO::FETCH_ASSOC); 

headerAdmin();
?>
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl position-sticky blur shadow-blur mt-4 left-auto top-1 z-index-sticky" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="index.php">Inicio</a></li>
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
                  <h6>Asignacion de Productos</h6>
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
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha Asignación</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Productos</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Contados</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Por Contar</th>
                    </tr>
                  </thead>
                  <tbody>

                  <?php foreach ($listaAsignacion as $key => $value) { ?>

                    <tr onclick="window.location=`<?= base_url(); ?>/src/admin/cajero.php?fecha=<?=$value['FECHA_ASIGNACION']?>`" style="cursor:pointer;">
                      <td class="align-middle text-center text-sm">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm"><?= $value['FECHA_ASIGNACION'] ?></h6>
                          </div>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <h6 class="mb-0 text-sm"><?= $value['TOTAL_PRODUCTOS'] ?></h6>
                      </td>
                        <td class="align-middle text-center text-sm">
                          <div style="display: flex;justify-content: center;align-items: center;">
                            <i style="color: green;" class="fa-solid fa-circle-check"></i>&nbsp;&nbsp;
                            <h6 class="mb-0 text-sm"><?= $value['TOTAL_CONTADOS'] ?></h6>
                          </div>
                        </td>

                        <td class="align-middle text-center text-sm">
                          <div style="display: flex;justify-content: center;align-items: center;">
                            <i style="color:red" class="fa-solid fa-circle-exclamation"></i>&nbsp;&nbsp;
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
