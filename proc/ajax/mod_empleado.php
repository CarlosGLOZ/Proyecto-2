<?php
require_once '../../config/conexion.php';
require_once '../../models/empleado.php';

$params = [];
$nueva_contra = false;
foreach ($_POST as $key => $value) {
    if (in_array($key, BD['EMPLEADO']) || $key == 'prev_'.BD['EMPLEADO']['DNI']) {
        if ($key == BD['EMPLEADO']['PASSWORD']) {
            if (!empty($value)) {
                $nueva_contra = true;
                $params[$key] = sha1(trim(strip_tags($value)));
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