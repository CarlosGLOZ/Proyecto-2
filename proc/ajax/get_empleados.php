<?php
require_once '../../config/conexion.php';
require_once '../../models/empleado.php';

$filtros = [];
foreach ($_POST as $key => $value) {
    if (array_key_exists($key, FILTROS['BD']) && in_array(FILTROS['BD'][$key], Empleado::getFiltrosEmpleados($pdo))) {
        // echo "$key - $value; ";
        $filtros[$key] = $value;
    }
}

$empleados = Empleado::getEmpleados($pdo, $filtros);

echo json_encode($empleados);