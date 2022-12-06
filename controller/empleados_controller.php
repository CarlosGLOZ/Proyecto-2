<?php

require_once '../config/conexion.php';
require_once '../proc/utils.php';
require_once '../models/empleado.php';

// Validar sesion
if (!validar_sesion()) {
    redirect('login_controller.php?val=false');
}

echo "<script>const DNI_VARNAME = '".VARNAMES_QUERY_EMPLEADOS['DNI']."';</script>";
echo "<script>const NOMBRE_VARNAME = '".VARNAMES_QUERY_EMPLEADOS['NOMBRE']."';</script>";
echo "<script>const APELLIDO_VARNAME = '".VARNAMES_QUERY_EMPLEADOS['APELLIDO']."';</script>";
echo "<script>const EMAIL_VARNAME = '".VARNAMES_QUERY_EMPLEADOS['EMAIL']."';</script>";
echo "<script>const PASSWORD_VARNAME = '".VARNAMES_QUERY_EMPLEADOS['PASSWORD']."';</script>";
echo "<script>const CARGO_VARNAME = '".VARNAMES_QUERY_EMPLEADOS['CARGO']."';</script>";
echo "<script>const DNI_BD_VARNAME = '".BD['EMPLEADO']['DNI']."';</script>";
echo "<script>const NOMBRE_BD_VARNAME = '".BD['EMPLEADO']['NOMBRE']."';</script>";
echo "<script>const APELLIDO_BD_VARNAME = '".BD['EMPLEADO']['APELLIDO']."';</script>";
echo "<script>const EMAIL_BD_VARNAME = '".BD['EMPLEADO']['EMAIL']."';</script>";
echo "<script>const PASSWORD_BD_VARNAME = '".BD['EMPLEADO']['PASSWORD']."';</script>";
echo "<script>const CARGO_BD_VARNAME = '".BD['EMPLEADO']['CARGO']."';</script>";

$cargos = Empleado::getCargos($pdo);

// Llamar a pagina
$entrada_valida = true;

require_once '../view/empleados.php';