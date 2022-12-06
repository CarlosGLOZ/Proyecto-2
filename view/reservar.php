<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Reserva</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
  <link rel="shortcut icon" href="../static/img/logores.png" type="image/x-icon">
  <script src="https://kit.fontawesome.com/20a538d92d.js" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css?family=Asap" rel="stylesheet">
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!--  <link rel="stylesheet" href="./css/style.css">  -->
  <script type="text/javascript" src="../static/js/validacion-login.js"></script>
  <link rel="stylesheet" href="../static/css/login.css">
  <script src="../static/js/function_login.js"></script>

  <link rel="stylesheet" href="../static/css/extra.css">
</head>

<body>

<?php
// Validación de entrada valida

if (!$entrada_valida) {
    echo "<script>window.location.href = '../controller/login_controller.php';</script>";
    die();
}
?>
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
            </ul>
    </div >
<div class="region">
    
  <div class="widget">
    <div class="form">
      <div class="login" id="reserva-box">
        <h5 id="mensaje-error"></h5>
        <form action="../proc/login_proc.php" method="post" id="form-reserva">
          <div id="reserva-input-comensales">
            <h3 for="<?php echo BD['RESERVA']['COMENSALES']?>">Comensales</h3>
            <input type="number" name="<?php echo BD['RESERVA']['COMENSALES']?>" min="1" max="10">
          </div>
          
          <div id="reserva-input-fecha_inicio">
            <h3 for="<?php echo BD['RESERVA']['FECHA']?>">Fecha</h3>
            <input type="date" name="<?php echo BD['RESERVA']['FECHA']?>" style="width: 140px;" onchange="validarFecha(event);">
            <div>
              <h3 for="<?php echo BD['RESERVA']['HORA_INICIO']?>">¿Desde...</h3>
              <select name="<?php echo BD['RESERVA']['HORA_INICIO']?>">
              </select>
            </div>
            <div>
              <h3 for="<?php echo BD['RESERVA']['HORA_FINAL']?>">...hasta?</h3>
              <select name="<?php echo BD['RESERVA']['HORA_FINAL']?>"></select>
            </div>
          </div>
          
          <!-- <div id="reserva-input-horas">
          </div> -->
          
          <div id="reserva-input-nombre">
            <h3 for="<?php echo BD['RESERVA']['NOMBRE']?>">¿A nombre de quien?</h3>
            <input type="text" name="<?php echo BD['RESERVA']['NOMBRE']?>">
          </div>

        </form>  
        <div id="botones-cambio">
          <div>
            <button class="btn btn-outline-danger" id="boton-cambio-atras">< Atrás</button>
          </div>
          <div>
            <button class="btn btn-outline-success" id="boton-cambio-siguiente">Siguiente ></button>
          </div>
        </div>
      </div>  
    </div>
  </div>
</div>

<script src="../static/js/validaciones_reserva.js"></script>
</body>
</html>

