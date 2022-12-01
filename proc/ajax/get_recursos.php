<?php
require_once '../../config/conexion.php';
require_once '../../models/mesa.php';

$filtros = [];
foreach ($_POST as $key => $value) {
    // echo "$key - $value; ";
    if (array_key_exists($key, FILTROS['BD']) && in_array(FILTROS['BD'][$key], Mesa::getFiltrosMesas($pdo))) {
        $filtros[$key] = $value;
    }
}

$mesas = Mesa::getRecursos($pdo, $filtros, '*', true);

echo json_encode($mesas);