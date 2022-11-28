<?php
require_once '../config/conexion.php';
require_once '../proc/utils.php';

if (!validar_sesion()) {
    redirect('login_controller.php');
}

$entrada_valida = true;
require_once '../view/index.php';