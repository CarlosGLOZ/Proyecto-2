<?php

require_once '../config/conexion.php';
require_once '../proc/utils.php';
require_once '../models/mesa.php';

// Validar sesion
if (!validar_sesion()) {
    redirect('login_controller.php?val=false');
}

// Generar url base para filtros
// if (!isset($_GET[FILTROS['SALA']])) {
//     echo "<script>window.location.href='./index_controller.php?".FILTROS['SALA']."=1'</script>";
// } else {
//     $url_raw = getURL();
//     $url_base = explode('?', $url_raw)[0];
// }

// Comprobar si hay algun get vacío
// if (hayGetsVacios()) {
//     // Generamos una URL sin las variables GET vacías para hacerlo más limpio
//     $nueva_url = eliminarVariablesGetVacias();
//     // echo $nueva_url;
//     echo "<script>window.location.href = '$nueva_url';</script>";
//     exit();
// }

// Recoger filtros
// $filtros = [];
// foreach ($_POST as $key => $value) {
//     if (in_array($key, FILTROS)) { //comprobar que variable esté dentro de los filtros
//         $filtros[$key] = trim(strip_tags($value));
//     }
// }
// Crear una variable en JS con el nombre de los filtros de sala para filtrado
echo "<script>const FILTROS = {}</script>";
echo "<script>FILTROS['FILTRO_SALA_VARNAME'] = '".FILTROS['SALA']."'</script>";
echo "<script>FILTROS['FILTRO_CAPACIDAD_VARNAME'] = '".FILTROS['CAPACIDAD']."'</script>";
echo "<script>FILTROS['FILTRO_DISPONIBILIDAD_VARNAME'] = '".FILTROS['DISPONIBILIDAD']."'</script>";

// $mesas = Mesa::getMesas($pdo, $filtros);
$capacidades = Mesa::getCapacidades($pdo);


// Llamar a pagina
$entrada_valida = true;

require_once '../view/index.php';