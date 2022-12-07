<?php

require_once '../../config/conexion.php';
require_once '../../models/mesa.php';
require_once '../../proc/utils.php';

$params = [];

foreach ($_POST as $key => $value) {
    $params[$key] = $value;
}

$reserva = Mesa::hacerReserva($pdo, $params);

if ($reserva) {
    echo "OK";
    die();
} else {
    echo "NOT OK";
}