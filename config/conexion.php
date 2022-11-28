<?php
require_once 'config.php';

try {
    $pdo = new PDO(BD['SERVER'], BD['USER'], BD['PASSWORD'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
} catch (PDOException $e) {
    //throw $th;
    echo "<script>alert('Error conectando con la base de datos! ".$e->getMessage()."')</script>";
    echo "<script>window.location.href = '../controller/error_controller.php'</script>";
    exit();
}