<?php
require_once '../../config/conexion.php';
require_once '../../models/empleado.php';

$params = [];
foreach ($_POST as $key => $value) {
    if (in_array($key, ADD_FORM['EMPLEADO'])) {
        if ($key == ADD_FORM['EMPLEADO']['PASSWORD']) {
            $params[$key] = sha1(trim(strip_tags($value)));
        }else {
            $params[$key] = trim(strip_tags($value));
        }
    }
}

// Comprobar que no exista el empleado (distinguido por DNI y email)
$disponible = Empleado::comprobarDisponibilidad($pdo, $params);

if ($disponible) {
    $result = Empleado::addEmpleado($pdo, $params);
} else {
    $result = false;
}

if ($result) {
    echo "OK";
} else {
    echo "NOT OK";
}
die();