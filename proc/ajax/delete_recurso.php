<?php

require_once '../../config/conexion.php';
require_once '../../models/mesa.php';

$numero = $_POST[BD['MESA']['NUMERO']];

$result = Mesa::deleteRecurso($pdo, $numero);

if ($result) {
    echo "OK";
} else {
    echo $result;
}
die();