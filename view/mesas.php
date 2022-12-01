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
    <link rel="stylesheet" href="../static/css/extra.css">

    <title>Recursos</title>
</head>
<body>

  <?php
    if (!$entrada_valida) {
        echo "<script>window.location.href = '../controller/mesas_controller.php';</script>";
    }
  ?>

  <script src="../static/js/function_logout.js"></script>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><img class="foto" src="../static/img/logores.png" alt=""></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
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
            <select class="form-select form-select-md" id="<?php echo FILTROS['SALA'];?>" onchange="listarRecursos()">
            <option value=''>Sala</option>
                <?php
                    $salas = Mesa::getSalas($pdo);
                    foreach ($salas as $sala) {
                        echo "<option value=".$sala[BD['SALA']['ID']].">".explode(' - ', $sala[BD['SALA']['NOMBRE']])[0]."</option>";
                    }
                ?>
            </select>
        </div>

        <div class="nav-item">
            <select class="form-select form-select-md" id="<?php echo FILTROS['NUMERO'];?>" onchange="listarRecursos()">
                    <option value=''>Mesa</option>
                    <?php
                    foreach ($mesas_no as $numero) {
                        echo "<option value=".$numero.">".$numero."</option>";
                    }
                    ?>
            </select>
        </div>

        <div class="nav-item">
            <select class="form-select form-select-md" id="<?php echo FILTROS['CAPACIDAD'];?>" onchange="listarRecursos()">
            <option value=''>Capacidad</option>
                <?php
                    foreach ($capacidades as $capacidad) {
                        echo "<option value=$capacidad>$capacidad</option>";
                    }
                ?>
            </select>
        </div>
        
        <div class="nav-item">
            <select class="form-select form-select-md" id="<?php echo FILTROS['DISPONIBILIDAD'];?>" onchange="listarRecursos()">
            <option value=''>Disponibilidad</option>
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
        <form action="../proc/cambiar_estado_mesa.php" method="post" class="modal-form" id='modal-form-ocupar'>
            <input type="hidden" name="<?php echo BD['MESA']['NUMERO']?>">

            <h3 for="<?php echo BD['MESA']['SALA']?>">Sala</h3>
            <select name="<?php echo BD['MESA']['SALA']?>">
                    <?php
                        foreach (Mesa::getSalas($pdo) as $sala) {
                            echo "<option value=".$sala[BD['SALA']['ID']].">".explode(' - ', $sala[BD['SALA']['NOMBRE']])[0]."</option>";
                        }
                    ?>
            </select>

            <h3 for="<?php echo BD['MESA']['CAPACIDAD']?>">Capacidad</h3>
            <input type="number" name="<?php echo BD['MESA']['CAPACIDAD']?>" placeholder='Comensales' style="width:50%" max="10" min="1" style="width: 100px;">
            
            <button class="btn btn-outline-success" id="modal-form-boton-mod-guardar">Guardar</button>
        </form>
        <button class="btn btn-outline-danger" onclick="cerrarModales()">Cancelar</button>
    </div>
</div>
<!-- /Modal Comensales -->

<!-- Formulario Crear -->
<div id="form-crear-recurso-container">
    <form action="" method="post" id="form-crear-recurso">
        <select class="form-select form-select-md" name="<?php echo ADD_FORM['SALA']?>">
            <?php
                foreach ($salas as $sala) {
                    echo "<option value=".$sala[BD['SALA']['ID']].">".explode(' - ', $sala[BD['SALA']['NOMBRE']])[0]."</option>";
                }
            ?>
        </select>
        <input type="number" name="<?php echo ADD_FORM['CAPACIDAD']?>" placeholder="Capacidad" min="1" max="10" id="form_crear_<?php echo BD['MESA']['CAPACIDAD']?>" onchange="validarFormularioCrearRecurso()">
        <button class="btn btn-success" id="boton-form-crear-recurso">+</button>
        <div id="error-val-form" style="display: none;"><p>Datos Invalidos</p></div>
    </form>
</div>
<!-- /Formulario Crear -->

<!-- Loop -->
<div id="cont-mesas">
    <table class="table table-striped">
        <thead>
            <tr>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
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
<script src="../static/js/ajax_default.js"></script>
<script src="../static/js/validaciones_crud.js"></script>
</body>
</html>