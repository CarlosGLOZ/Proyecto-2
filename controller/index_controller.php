<?php

require_once '../config/conexion.php';
require_once '../proc/utils.php';
require_once '../models/mesa.php';

// Validar sesion
if (!validar_sesion()) {
    redirect('login_controller.php?val=false');
}

echo "<script>const FILTROS = {}</script>";
echo "<script>FILTROS['FILTRO_SALA_VARNAME'] = '".FILTROS['SALA']."'</script>";
echo "<script>FILTROS['FILTRO_CAPACIDAD_VARNAME'] = '".FILTROS['CAPACIDAD']."'</script>";
echo "<script>FILTROS['FILTRO_DISPONIBILIDAD_VARNAME'] = '".FILTROS['DISPONIBILIDAD']."'</script>";

// $mesas = Mesa::getMesas($pdo, $filtros);
$capacidades = Mesa::getCapacidades($pdo);


// Llamar a pagina
$entrada_valida = true;

require_once '../view/index.php';