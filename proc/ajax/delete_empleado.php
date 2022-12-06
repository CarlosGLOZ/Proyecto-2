<?php

require_once '../../config/conexion.php';
require_once '../../models/empleado.php';

$dni = $_POST[BD['EMPLEADO']['DNI']];

$result = Empleado::deleteEmpleado($pdo, $dni);

if ($result) {
    echo "OK";
} else {
    echo $result;
}
die();