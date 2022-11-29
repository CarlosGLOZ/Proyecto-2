<?php
require_once '../../config/conexion.php';
require_once '../../models/mesa.php';

$filtros = [];
foreach ($_POST as $key => $value) {
    // echo "$key - $value; ";
    if (array_key_exists($key, FILTROS['BD']) && in_array(FILTROS['BD'][$key], Mesa::getFiltrosMesas($pdo))) {
        try {
            $filtros[$key] = intval($value);
        } catch (Exception $e) {
            $filtros[$key] = $value;
        }
    }
}

$mesas = Mesa::getMesas($pdo, $filtros);

$cont=0;
foreach ($mesas as $mesa) {
    // 4 mesas por region
    if ($cont == 0) {
        echo "<div class='region'>";
    }
    if ($cont == 4) {
    $cont = 0;
    echo "</div>$cont<div class='region'>";
    }

    echo "
    <div class='bloque'>
    <h2 class='text-center'>MESA ".$mesa[BD['MESA']['NUMERO']]."</h2>
            <div class='lightbox-gallery'>
                <div class='text-center'>
                <a href='registros_controller.php?filtro_mesa=".$mesa[BD['MESA']['ID']]."'><img class='mesa' src='../static/img/mesa-".$mesa[BD['MESA']['CAPACIDAD']]."-".COLORES_MESAS[$mesa[BD['MESA']['ESTADO']]].".png'></a>
                </div>
            </div>";

    // Mostrar botones según estado de la mesa
    echo "<div class='text-center github-link'>";
    if ($mesa[BD['MESA']['ESTADO']] == 0) {
    echo "<button class='btn btn-outline-danger' onclick='abrirModalOcupado(".$mesa[BD['MESA']['ID']].")'>Ocupar</button> ";
    echo "<button class='btn btn-outline-secondary' onclick='abrirModalMantenimiento(".$mesa[BD['MESA']['ID']].")'>Mantenimiento</button>";
    } elseif ($mesa[BD['MESA']['ESTADO']] == 1) {
    echo "<button class='btn btn-outline-success' onclick='abrirModalLiberar(".$mesa[BD['MESA']['ID']].")'>Liberar</button> ";
    echo "<button class='btn btn-outline-secondary' onclick='abrirModalMantenimiento(".$mesa[BD['MESA']['ID']].")'>Mantenimiento</button>";
    } elseif ($mesa[BD['MESA']['ESTADO']] == 2) {
    echo "<button class='btn btn-outline-danger' onclick='abrirModalOcupado(".$mesa[BD['MESA']['ID']].")'>Ocupar</button> ";
    echo "<button class='btn btn-outline-success' onclick='abrirModalLiberar(".$mesa[BD['MESA']['ID']].")'>Liberar</button>";
    }
    
    echo "</div></div>
    ";

    $cont++;
}