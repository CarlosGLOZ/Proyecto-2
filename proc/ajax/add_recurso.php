<?php
require_once '../../config/conexion.php';
require_once '../../models/mesa.php';

$params = [];
foreach ($_POST as $key => $value) {
    if (in_array($key, ADD_FORM)) {
        $params[$key] = trim(strip_tags($value));
    }
}

$result = Mesa::addRecurso($pdo, $params);

if ($result) {
    echo "OK";
} else {
    echo "NOT OK";
}
die();