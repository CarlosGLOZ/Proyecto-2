<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="../static/img/logores.png" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../static/css/mostrar.css">
    <link rel="stylesheet" href="../static/css/modal_mesas.css">
    <link rel="stylesheet" href="../static/css/filtros.css">

    <title>Mesas</title>
    
</head>
<body>

  <?php
    if (!$entrada_valida) {
        echo "<script>window.location.href = '../controller/index_controller.php';</script>";
    }
  ?>

  <script src="../static/js/function_logout.js"></script>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><img class="foto" src="../static/img/logores.png" alt=""></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <?php
                    foreach (Mesa::getSalas($pdo) as $sala) {
                        // <a class='nav-link' href='?".FILTROS['SALA']."=".$sala[BD['SALA']['ID']]."'>".explode(' - ', $sala[BD['SALA']['NOMBRE']])[0]."</a>
                        echo "
                        <li class='nav-item'>
                            <button class='nav-link link-filtro-sala' value=".$sala[BD['SALA']['ID']].">".explode(' - ', $sala[BD['SALA']['NOMBRE']])[0]."</button>
                        </li>
                        ";
                    }
                ?>
            </ul>
        </div>
        <div class="navbar-nav">
            <a onclick="aviso3();" class="nav-link bg-light" aria-pressed='true' aria-current="page" role='button'>Log out</a>
        </div>
    </div>
</nav>
<div class="area" >
    <ul class="circles">
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>

<!-- Filtros -->
<br>
<div id="filtros-container" class="navbar-nav">
    <form id="form-filtros">
        <div class="nav-item">

            <input type="hidden" name=<?php echo FILTROS['SALA'];?> value="1" onchange="listarMesas()">

            <select class="form-select form-select-md" id="<?php echo FILTROS['CAPACIDAD'];?>" onchange="listarMesas()">
            <option value=''>-</option>
                <?php
                    foreach ($capacidades as $capacidad) {
                        echo "<option value=$capacidad>$capacidad</option>";
                    }
                ?>
            </select>
        </div>
        
        <div class="nav-item">
            <select class="form-select form-select-md" id="<?php echo FILTROS['DISPONIBILIDAD'];?>" onchange="listarMesas()">
            <option value=''>-</option>
                <?php
                    foreach (BD['MESA']['ESTADOS'] as $estado) {
                        echo "<option value=".array_search($estado, BD['MESA']['ESTADOS']).">$estado</option>";
                    }
                ?>
            </select>
        </div>
        
        <div class="nav-item">
            <!-- <button class="btn btn-outline-success" id="boton-filtrar">Filtrar</button> -->
            <button class="btn btn-outline-danger" id="boton-limpiar-filtros">Limpiar Filtros</button>
        </div>
    </form>
</div>
<!-- /Filtros -->


<!-- Modal Comensales -->
<div id="modal-comensales-container" class="modal-container">
    <div class="modal-box">
        <form action="../proc/cambiar_estado_mesa.php" method="post">
            <h3 style="text-align: center">Introduce los comensales:</h3>
            <input type="hidden" name="<?php echo BD['MESA']['ID']?>" id="id_mesa_modal_comensales">
            <input type="hidden" name="<?php echo BD['MESA']['ESTADO']?>" value="1">
            <input type="number" name="<?php echo BD['REGISTRO']['COMENSALES']?>" placeholder='Comensales' style="width:50%" max="10" min="1">
            <input type="submit" class="btn btn-outline-success" value="Guardar">
        </form>
        <button class="btn btn-outline-danger" onclick="cerrarModales()">Cancelar</button>
    </div>
</div>
<!-- /Modal Comensales -->

<!-- Modal Mantenimiento-->
<div id="modal-mantenimiento-container" class="modal-container">
    <div class="modal-box">
        <form action="../proc/cambiar_estado_mesa.php" method="post">
        <h3 style="text-align: center">Introduce el motivo de la incidencia:</h3>
            <input type="hidden" name="<?php echo BD['MESA']['ID']?>" id="id_mesa_modal_mantenimineto">
            <input type="hidden" name="<?php echo BD['MESA']['ESTADO']?>" value="2">
            <input type="text" name="<?php echo BD['INCIDENCIA']['NOMBRE']?>" placeholder="Descripcion incidencia">
            <input type="submit" class="btn btn-outline-success" value="Guardar">
        </form>
        <button class="btn btn-outline-danger" onclick="cerrarModales()">Cancelar</button>
    </div>
</div>
<!-- /Modal Mantenimiento-->

<!-- Modal Liberar-->
<div id="modal-liberar-container" class="modal-container">
    <div class="modal-box">
        <form action="../proc/cambiar_estado_mesa.php" method="post">
            <input type="hidden" name="<?php echo BD['MESA']['ID']?>" id="id_mesa_modal_liberar">
            <input type="hidden" name="<?php echo BD['MESA']['ESTADO']?>" value="0">
            <h3 style="text-align: center">¿Estás seguro?</h3>
            <input type="submit" class="btn btn-outline-success" value="Sí">
        </form>
        <button class="btn btn-outline-danger" onclick="cerrarModales()">Cancelar</button>
    </div>
</div>
<!-- /Modal Liberar-->

<!-- Loop -->
<div id="cont-mesas">

</div>
<!-- /Loop -->
</ul> 
             </div>
<script src="../static/js/function_logout.js"></script>
<script src="../static/js/styles.js"></script>
<script src="../static/js/filtros.js"></script>
<script src="../static/js/modales_mesas.js"></script>
<script src="../static/js/cargar_ajax.js"></script>
<!-- <script src="../static/js/ajax/crear.js"></script>
<script src="../static/js/ajax/listar.js"></script> -->
<script src="../static/js/establecer_filtro_sala.js"></script>
</body>
</html>