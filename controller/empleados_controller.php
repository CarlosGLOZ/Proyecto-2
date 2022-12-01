<?php

require_once '../config/conexion.php';
require_once '../proc/utils.php';
require_once '../models/empleado.php';

// Validar sesion
if (!validar_sesion()) {
    redirect('login_controller.php?val=false');
}

$cargos = Empleado::getCargos($pdo);

// Llamar a pagina
$entrada_valida = true;

require_once '../view/empleados.php';