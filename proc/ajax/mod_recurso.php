<?php
require_once '../../config/conexion.php';
require_once '../../models/mesa.php';

$params = [];
foreach ($_POST as $key => $value) {
    if (in_array($key, BD['MESA'])) {
        $params[$key] = trim(strip_tags($value));
    }
}

$result = Mesa::modRecurso($pdo, $params);

if ($result) {
    echo "OK";
} else {
    echo "NOT OK - $result";
}
die();