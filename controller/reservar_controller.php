<?php
require_once '../config/conexion.php';
require_once '../proc/utils.php';
require_once '../models/mesa.php';

// Variables constantes para JS
echo "<script>const BD = [];</script>";
foreach (BD['RESERVA'] as $key => $value) {
    echo "<script>BD['$key'] = '$value';</script>";
}



$entrada_valida = true;
require_once '../view/reservar.php';