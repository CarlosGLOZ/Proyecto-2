<?php

require_once '../../config/conexion.php';
require_once '../../models/mesa.php';
require_once '../../proc/utils.php';

$instruccion = strip_tags(trim($_POST['INSTRUCCION']));

if ($instruccion == BD['RESERVA']['COMENSALES']) {
    $comensales = strip_tags(trim($_POST[BD['RESERVA']['COMENSALES']]));
    $mesas_libres = Mesa::getMesasLibresConComensales($pdo, $comensales);

    // Contar las mesas libres
    $cont_mesas = 0;
    foreach ($mesas_libres as $mesa) {
        $cont_mesas++;
    }



    if ($cont_mesas > 0) {
        echo "LIBRES";
    } else {
        echo "NO LIBRES";
    }
    die();
} elseif ($instruccion == BD['RESERVA']['FECHA']) {
    $fecha = $_POST[BD['RESERVA']['FECHA']];
    $comensales = $_POST[BD['RESERVA']['COMENSALES']];

    // Recuperar horas disponibles
    $horas = Mesa::getHorasDisponiblesEnFecha($pdo, $fecha, $comensales);

    if (count($horas) > 0) {
        echo json_encode($horas);
    } else {
        echo 'NINGUNA';
    }
    die();

}