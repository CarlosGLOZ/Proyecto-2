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

    <title>Empleados</title>
</head>
<body>

  <?php
    if (!$entrada_valida) {
        echo "<script>window.location.href = '../controller/empleados_controller.php';</script>";
    }
  ?>

  <script src="../static/js/function_logout.js"></script>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index_controller.php"><img class="foto" src="../static/img/logores.png" alt=""></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <!-- Botones para ver los recursos y los empleados -->
                <li class='nav-item'>
                    <button class='btn bt-outline' onclick="window.location.href = 'mesas_controller.php';">Recursos</button>
                </li>
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
            <input type="text" class="form-control" id="<?php echo FILTROS['DNI'];?>" oninput="listarEmpleados()" placeholder="<?php echo VARNAMES_QUERY_EMPLEADOS['DNI'];?>">
        </div>
        
        <div class="nav-item">
            <input type="text" class="form-control" id="<?php echo FILTROS['NOMBRE'];?>" oninput="listarEmpleados()" placeholder="<?php echo VARNAMES_QUERY_EMPLEADOS['NOMBRE'];?>">
        </div>

        <div class="nav-item">
            <input type="text" class="form-control" id="<?php echo FILTROS['APELLIDO'];?>" oninput="listarEmpleados()" placeholder="<?php echo VARNAMES_QUERY_EMPLEADOS['APELLIDO'];?>">
        </div>

        <div class="nav-item">
            <input type="text" class="form-control" id="<?php echo FILTROS['EMAIL'];?>" oninput="listarEmpleados()" placeholder="<?php echo VARNAMES_QUERY_EMPLEADOS['EMAIL'];?>">
        </div>

        <div class="nav-item">
            <select class="form-select form-select-md" id="<?php echo FILTROS['CARGO'];?>" onchange="listarEmpleados()">
                    <option value=''>Cargo</option>
                    <?php
                    foreach ($cargos as $id => $nombre) {
                        echo "<option value=".$id.">".$nombre."</option>";
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


<!-- Modal Modificar -->
<div id="modal-comensales-container" class="modal-container container-modificar">
    <div class="modal-box">
        <form action="../proc/cambiar_estado_mesa.php" method="post" class="modal-form" id='modal-form-ocupar'>
            <input type="hidden" name="prev_<?php echo ADD_FORM['EMPLEADO']['DNI']?>">

            <h3 for="<?php echo ADD_FORM['EMPLEADO']['DNI']?>"><?php echo VARNAMES_QUERY_EMPLEADOS['DNI'];?></h3>
            <input type="text" name="<?php echo ADD_FORM['EMPLEADO']['DNI']?>" oninput="validarFormularioModificarEmpleado()">

            
            <h3 for="<?php echo ADD_FORM['EMPLEADO']['NOMBRE']?>"><?php echo VARNAMES_QUERY_EMPLEADOS['NOMBRE'];?></h3>
            <input type="text" name="<?php echo ADD_FORM['EMPLEADO']['NOMBRE']?>" oninput="validarFormularioModificarEmpleado()">

                        
            <h3 for="<?php echo ADD_FORM['EMPLEADO']['APELLIDO']?>"><?php echo VARNAMES_QUERY_EMPLEADOS['APELLIDO'];?></h3>
            <input type="text" name="<?php echo ADD_FORM['EMPLEADO']['APELLIDO']?>" oninput="validarFormularioModificarEmpleado()">

            
            <h3 for="<?php echo ADD_FORM['EMPLEADO']['EMAIL']?>"><?php echo VARNAMES_QUERY_EMPLEADOS['EMAIL'];?></h3>
            <input type="email" name="<?php echo ADD_FORM['EMPLEADO']['EMAIL']?>" oninput="validarFormularioModificarEmpleado()">

            
            <h3 for="<?php echo ADD_FORM['EMPLEADO']['PASSWORD']?>"><?php echo VARNAMES_QUERY_EMPLEADOS['PASSWORD'];?></h3>
            <input type="password" name="<?php echo ADD_FORM['EMPLEADO']['PASSWORD']?>" oninput="validarFormularioModificarEmpleado()" placeholder="Nueva contraseña">

            <h3 for="<?php echo ADD_FORM['EMPLEADO']['CARGO']?>"><?php echo VARNAMES_QUERY_EMPLEADOS['CARGO'];?></h3>
            <select class="form-select form-select-md" name="<?php echo ADD_FORM['EMPLEADO']['CARGO'];?>" onchange="validarFormularioCrearEmpleado()">
                <option value=''>Cargo</option>
                <?php
                foreach ($cargos as $id => $nombre) {
                    echo "<option value=".$id.">".$nombre."</option>";
                }
                ?>
            </select>
          
            <button class="btn btn-outline-success" id="modal-form-boton-mod-guardar">Guardar</button>
        </form>
        <button class="btn btn-outline-danger" onclick="cerrarModales()">Cancelar</button>
    </div>
</div>
<!-- /Modal Modificar -->

<!-- Formulario Crear -->
<div id="form-crear-empleado-container">
    <form action="" method="post" id="form-crear-empleado">
        <div class="nav-item">
            <input type="text" class="form-control form-dni" id="<?php echo ADD_FORM['EMPLEADO']['DNI'];?>" oninput="validarFormularioCrearEmpleado()" placeholder="<?php echo VARNAMES_QUERY_EMPLEADOS['DNI'];?>">
        </div>
        
        <div class="nav-item">
            <input type="text" class="form-control form-name" id="<?php echo ADD_FORM['EMPLEADO']['NOMBRE'];?>" oninput="validarFormularioCrearEmpleado()" placeholder="<?php echo VARNAMES_QUERY_EMPLEADOS['NOMBRE'];?>">
        </div>

        <div class="nav-item">
            <input type="text" class="form-control form-name" id="<?php echo ADD_FORM['EMPLEADO']['APELLIDO'];?>" oninput="validarFormularioCrearEmpleado()" placeholder="<?php echo VARNAMES_QUERY_EMPLEADOS['APELLIDO'];?>">
        </div>

        <div class="nav-item">
            <input type="email" class="form-control form-email" id="<?php echo ADD_FORM['EMPLEADO']['EMAIL'];?>" oninput="validarFormularioCrearEmpleado()" placeholder="<?php echo VARNAMES_QUERY_EMPLEADOS['EMAIL'];?>">
        </div>

        <div class="nav-item">
            <input type="password" class="form-control form-name" id="<?php echo ADD_FORM['EMPLEADO']['PASSWORD'];?>" oninput="validarFormularioCrearEmpleado()" placeholder="<?php echo VARNAMES_QUERY_EMPLEADOS['PASSWORD'];?>">
        </div>

        <div class="nav-item">
            <select class="form-select form-select-md" id="<?php echo ADD_FORM['EMPLEADO']['CARGO'];?>" onchange="validarFormularioCrearEmpleado()">
                    <option value=''>Cargo</option>
                    <?php
                    foreach ($cargos as $id => $nombre) {
                        echo "<option value=".$id.">".$nombre."</option>";
                    }
                    ?>
            </select>
        </div>
        <div class="nav-item">
            <button class="btn btn-success" id="boton-form-crear-empleado">+</button>
        </div>
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