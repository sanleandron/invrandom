<?php

session_start();
include '../config/parametros.php';
include '../config/helpers.php';
require_once('../config/validar_session.php');

$id = $_SESSION['ID_USUARIO'];
//consulta pedidos
$sql="SELECT 
      I.ID,P.PDT_CODIGO,
      P.PDT_DESCRIPCION,
      I.CONTEO,
      MAX(I.FECHA_ASIGNACION) AS MAX_FECHA,
      DATEDIFF(CURDATE(),MAX(I.FECHA_ASIGNACION)) AS DIFF
      FROM adn_productos P
      LEFT JOIN control_inventario I ON P.PDT_CODIGO  = I.PDT_CODIGO
      WHERE PDT_ESTADO = 1
      AND I.ID_USUARIO = $id
      AND I.CONTEO IS  NULL
      GROUP BY I.ID
";  
$sentencia=$pdo->prepare($sql);
$sentencia->execute();
$listaProd=$sentencia->fetchAll(PDO::FETCH_ASSOC); 

$aux = 0;
for ($i=0; $i < count($listaProd) ; $i++) { 
  if($listaProd[$i]['CONTEO'] !== NULL){
    $aux++;
  }
}

$totalProd = $INV_MAX_VENTAS+$INV_MIN_VENTAS;

headerCajero();
?>
    <div id="divLoading" >
      <div>
        <img src="<?= media(); ?>/img//loading.svg" alt="Loading"> &nbsp;&nbsp;&nbsp;
      </div>
      <div>&nbsp;&nbsp;&nbsp;<h6>Asignando Por Favor espere</h6></div>
    </div>
    
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
                    <?php if(!empty($listaProd)) { ?>
                    <span class="font-weight-bold ms-1">Última Asignación</span> <?= $listaProd[0]['MAX_FECHA'] ?>
                    <?php } ?>
                  </p>
                </div>
              </div>
            </div>
            <div class="card-body">

              <?php  

              if(empty($listaProd) || ($aux == $totalProd && $listaProd[0]['DIFF'] >= $INV_DIAS_CONTEO )){ ?>

                <form action="" id="formAsignacion">  
                <input  id="id" type="hidden" name="id" value="<?=$id?>">
                <button type="submit" class="alert alert-warning" style="color:white; width: 100%;" role="alert">
                  Aun no tiene Asignado Productos. Haz Click Aquí para Asignar
                </button>
                </form>

              <?php } else { ?>
              
              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Código</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Descripcion</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cant.</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Procesar</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php foreach ($listaProd as $key => $value) { ?>

                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div>
                            <!-- <i style="color: green;" class="fa-solid fa-circle-check"></i>&nbsp;&nbsp; -->
                          </div>
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm"><?= $value['PDT_CODIGO'] ?></h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <h6 class="mb-0 text-sm"><?= $value['PDT_DESCRIPCION'] ?></h6>
                      </td>
                      <form action="contar.php" method="post">
                      <td style="width: 15%;" class="align-middle text-center text-sm">
                        <div class="input-group">
                          <?php if($value['CONTEO'] !== NULL) { ?>  
                            <input type="number" name="conteo" class="form-control" value="<?= $value['CONTEO'] ?>" disabled>
                          <?php }else { ?>
                            <input type="number" name="conteo" class="form-control" value="<?= $value['CONTEO'] ?>" required>
                          <?php } ?>  
                        </div>
                      </td>
                      <td style="width: 5%;" class="align-middle text-center text-sm">
                        <?php if($value['CONTEO'] !== NULL) { ?>
                          <i style="color: green;" class="fa-solid fa-circle-check"></i>
                        <?php }else { ?>
                            <input type="hidden" name="id" value="<?= $value['ID'] ?>">
                            <input type="hidden" name="id_user" value="<?= $id ?>">
                            <input type="hidden" name="pdt_codigo" value="<?= $value['PDT_CODIGO'] ?>">
                            <input type="hidden" name="pdt_descripcion" value="<?= $value['PDT_DESCRIPCION'] ?>">
                            <button type="submit" class="btn btn-outline-success" style="margin: auto;" type="button" id="">✔️</button>      
                          </form>
                        <?php } ?>
                      </td>
                    </tr>
                          
                    <?php } ?>               

                  </tbody>
                </table>
              </div>

              <?php } ?>

            </div>
          </div>
        </div>

      </div>
    <?php 

    footerCajero();

    ?>

    <script type="text/javascript">
    let divLoading = document.querySelector("#divLoading");

    $('#formAsignacion').submit(function(e) {
        e.preventDefault();
        let id = document.querySelector('#id').value;
        var params = {
        "id" : id
        };

        $.ajax({
            type: "POST",
            url: 'asignacion.php',
            data: params,
            beforeSend: function () {
            
              divLoading.style.display = "flex";
            
            },
            success: function(response)
            {
                var jsonData = JSON.parse(response);
                if (jsonData.success == "1")
                {
                    divLoading.style.display = "none";
                    location.reload();
                }
                else
                {
                    alert('No se pudo asignar!');
                }
           }
       });
     });
    </script>
