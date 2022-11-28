<?php
require_once '../config/conexion.php';
require_once 'utils.php';

// Validar entrada
if (!isset($_POST[LOGIN_FORM['SEND']])) {
    die();
    redirect('../controller/login_controller.php');
}

// Recoger y encriptar datos de POST, guardarlos en array $params
$params = [];
foreach ($_POST as $key => $value) {
    if ($key == LOGIN_FORM['PASSWORD']) {
        $params[$key] = sha1(trim(strip_tags($value)));
    } else {
        $params[$key] = trim(strip_tags($value));
    }
    // echo "$key -> $value<br>";
}

// Validar datos con la BDD
$sql = "SELECT * FROM ".BD['EMPLEADO']['TABLA']." WHERE ".BD['EMPLEADO']['EMAIL']." = :".LOGIN_FORM['USER'].";";
$consulta = $pdo -> prepare($sql);
$consulta -> execute([LOGIN_FORM['USER'] => $params[LOGIN_FORM['USER']]]);
$result = $consulta -> fetch(PDO::FETCH_ASSOC);


if ($result == null) {
    redirect('../controller/login_controller.php?val=false');
}

if ($params[BD['EMPLEADO']['EMAIL']] != $result[BD['EMPLEADO']['EMAIL']]) {
    redirect('../controller/login_controller.php?val=false');
    die();
}

if ($params[BD['EMPLEADO']['PASSWORD']] != $result[BD['EMPLEADO']['PASSWORD']]) {
    redirect('../controller/login_controller.php?val=false');
    die();
}

// Iniciar sesión
if (registrar_array_en_sesion($result, 'USER', BD['EMPLEADO']['PASSWORD'])) { // Registra los valores de un array en la sesión (utiliza las keys del array) en un subarray 'user'
    redirect('../controller/index_controller.php');
    die();
}

exit();