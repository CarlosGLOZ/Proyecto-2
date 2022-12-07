<?php
require_once '../../config/conexion.php';
require_once '../../models/empleado.php';
require_once '../../proc/utils.php';

$params = [];
$nueva_contra = false;
foreach ($_POST as $key => $value) {
    if (in_array($key, BD['EMPLEADO']) || $key == 'prev_'.BD['EMPLEADO']['DNI']) {
        if ($key == BD['EMPLEADO']['PASSWORD']) {
            if (!empty($value)) {
                $nueva_contra = true;
                $params[$key] = sha1(trim(strip_tags($value)));
            }
        } elseif ($key == BD['EMPLEADO']['DNI']) {
            if (Empleado::dniValido($pdo, $value)) {
                $params[$key] = trim(strip_tags($value));
            } else {
                echo "NOT OK - DNI no és válido";
                die();
            }
        } elseif ($key == BD['EMPLEADO']['EMAIL']) {
            if (Empleado::emailValido($pdo, $value)) {
                $params[$key] = trim(strip_tags($value));
            } else {
                echo "NOT OK - Email no és válido";
                die();
            }
        } else {
            $params[$key] = trim(strip_tags($value));
        }
    }
}


$result = Empleado::modEmpleado($pdo, $params, $nueva_contra);

if ($result) {
    echo "OK";
} else {
    echo "NOT OK - $result";
}
die();