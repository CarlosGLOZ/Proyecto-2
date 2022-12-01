<?php

require_once '../config/conexion.php';
require_once '../proc/utils.php';
require_once '../models/mesa.php';

// Validar sesion
if (!validar_sesion()) {
    redirect('login_controller.php?val=false');
}

echo "<script>const SALA_BD_VARNAME = '".BD['MESA']['SALA']."';</script>";
echo "<script>const NUMERO_BD_VARNAME = '".BD['MESA']['NUMERO']."';</script>";
echo "<script>const CAPACIDAD_BD_VARNAME = '".BD['MESA']['CAPACIDAD']."';</script>";
echo "<script>const SALA_VARNAME = '".VARNAMES_QUERY_RECURSOS['SALA']."';</script>";
echo "<script>const NUMERO_VARNAME = '".VARNAMES_QUERY_RECURSOS['NUMERO']."';</script>";
echo "<script>const ESTADO_VARNAME = '".VARNAMES_QUERY_RECURSOS['ESTADO']."';</script>";
echo "<script>const CAPACIDAD_VARNAME = '".VARNAMES_QUERY_RECURSOS['CAPACIDAD']."';</script>";
echo "<script>const ESTADOS = {};</script>";
foreach (BD['MESA']['ESTADOS'] as $key => $value) {
    echo "<script>ESTADOS[$key] = '$value';</script>";
}

// $mesas = Mesa::getMesas($pdo, $filtros);
$capacidades = Mesa::getCapacidades($pdo);

$mesas_no_raw = Mesa::getMesas($pdo, [], BD['MESA']['NUMERO']);
$mesas_no = [];
foreach ($mesas_no_raw as $mesa) {
    foreach ($mesa as $key => $value) {
        // echo "$key -> $value<br>";
        array_push($mesas_no, $value);
    }
}


// Llamar a pagina
$entrada_valida = true;

require_once '../view/mesas.php';