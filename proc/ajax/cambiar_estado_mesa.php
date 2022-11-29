<?php

require_once '../../config/conexion.php';
require_once '../../models/mesa.php';
require_once '../utils.php';
//No hacemos require de config porque ya lo contiene conexion.php

$id_mesa = trim(strip_tags($_POST[BD['MESA']['ID']])); //Recupera el valor del id dentro de la const mesa 
$estado_mesa = trim(strip_tags($_POST[BD['MESA']['ESTADO']]));
$comensales = null;
$desc_inc = null;
$estado_actual = Mesa::getEstadoMesa($pdo, $id_mesa);

// Validaciones

    // Si la mesa no es valida
    if (!Mesa::mesaExiste($pdo, $id_mesa)) {
        echo "NOT OK";
            die();
    } 
    
    // Si el estado no es valido
    if (!array_key_exists($estado_mesa, BD['MESA']['ESTADOS'])) {
        echo "NOT OK";
            die();
    }
    // die();
    
    // Si el estado de la mesa es el mismo, devolver a index
    if ($estado_mesa == $estado_actual) {
        echo "NOT OK";
            die();
    }

    if ($estado_mesa == 1) {

        // Si el estado es ocupado y no esta seteado el valor de comensales o es un valor invalido
        if (!isset($_POST[BD['REGISTRO']['COMENSALES']])) {
            echo "NOT OK";
            die();
        }
        if (!Mesa::validarComensales($pdo, $id_mesa, $_POST[BD['REGISTRO']['COMENSALES']])) {
            echo "NOT OK";
            die();
        } 

        $comensales = trim(strip_tags($_POST[BD['REGISTRO']['COMENSALES']]));
    } elseif ($estado_mesa == 2) {
        
        // Si el estado es incidencia y no está seteado el valor de desc_inc
        if (!isset($_POST[BD['INCIDENCIA']['NOMBRE']])) {
            echo "NOT OK";
            die();
        } 
        
        if (trim(strip_tags($_POST[BD['INCIDENCIA']['NOMBRE']])) == '') {
            echo "NOT OK";
            die();
        }

        $desc_inc = trim(strip_tags($_POST[BD['INCIDENCIA']['NOMBRE']]));

    }

// Acciones
    if ($estado_actual == 0) {
        if ($estado_mesa == 1) {
            Mesa::crearRegistroMesa($pdo, $id_mesa, $comensales);
        } elseif ($estado_mesa == 2) {
            Mesa::crearIncidenciaMesa($pdo, $id_mesa, $desc_inc);
        } else {
            echo "NOT OK";
            die();
        }
    } elseif ($estado_actual == 1) {
        if ($estado_mesa == 0) {
            Mesa::cerrarRegistroMesa($pdo, $id_mesa);
        } elseif ($estado_mesa == 2) {
            Mesa::cerrarRegistroMesa($pdo, $id_mesa);
            Mesa::crearIncidenciaMesa($pdo, $id_mesa, $desc_inc);
        } else {
            echo "NOT OK";
            die();
        }
    } elseif ($estado_actual == 2) {
        if ($estado_mesa == 0) {
            Mesa::cerrarIncidenciaMesa($pdo, $id_mesa);
        } elseif ($estado_mesa == 1) {
            
            Mesa::cerrarIncidenciaMesa($pdo, $id_mesa);
            Mesa::crearRegistroMesa($pdo, $id_mesa, $comensales);
        } else {
            echo "NOT OK";
            die();
        }
    } else {
        echo "NOT OK";
            die();
    }

//llamar a la funcion de mesa
if (!Mesa::cambiarEstadoMesa($pdo, $id_mesa, $estado_mesa)) {
    echo "NOT OK";
    die();
}


echo "OK";