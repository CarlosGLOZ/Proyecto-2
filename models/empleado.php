<?php

class Empleado {

    public static function getFiltrosEmpleados($pdo)
    {
        // Recoger los nombres de los filtros de los empleados
        $sql = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='".BD['BD']."' AND `TABLE_NAME`='".BD['EMPLEADO']['TABLA']."';";
        $consulta = $pdo -> prepare($sql);
        $consulta -> execute();
        $filtros_nombres_query = $consulta -> fetchAll(PDO::FETCH_ASSOC);
    
        $filtros_nombres = [];
        foreach ($filtros_nombres_query as $key => $value) {
            // echo $value['COLUMN_NAME']."<br>";
            array_push($filtros_nombres, $value['COLUMN_NAME']);
        }

        return $filtros_nombres;
    }

    public static function getEmpleados($pdo, $filtros)
    {
        // Recoger todas los empleados
        $sql = "
            SELECT ".BD['EMPLEADO']['DNI']." as ".VARNAMES_QUERY_EMPLEADOS['DNI'].",
            ".BD['EMPLEADO']['NOMBRE']." as ".VARNAMES_QUERY_EMPLEADOS['NOMBRE'].",
            ".BD['EMPLEADO']['APELLIDO']." as ".VARNAMES_QUERY_EMPLEADOS['APELLIDO'].",
            ".BD['EMPLEADO']['EMAIL']." as ".VARNAMES_QUERY_EMPLEADOS['EMAIL'].",
            ".BD['CARGO']['TABLA'].".".BD['CARGO']['NOMBRE']." as ".VARNAMES_QUERY_EMPLEADOS['CARGO']."
            FROM ".BD['EMPLEADO']['TABLA']." INNER JOIN
            ".BD['CARGO']['TABLA']." ON ".BD['EMPLEADO']['CARGO']." = ".BD['CARGO']['ID']."
            WHERE 1=1
        ";

        // aplicar filtros y añadirlos a la lista de parametros de la query
        $params = [];
        foreach ($filtros as $key => $value) {
            // echo "$key -> ".gettype($value)."$value\n";
            try {
                $value = int($value);
                $sql = $sql." AND ".FILTROS['BD'][$key]." = :$key";
                $params[$key] = $value;
            } catch (\Throwable $th) {
                $sql = $sql." AND ".FILTROS['BD'][$key]." LIKE :$key";
                $params[$key] = $value."%";
            }
        }
        $sql = $sql." ORDER BY ".BD['EMPLEADO']['TABLA'].".".BD['EMPLEADO']['NOMBRE']." ASC;";

        foreach ($params as $key => $value) {
        }

        $consulta = $pdo -> prepare($sql);
        $consulta -> execute($params);
        // $consulta -> debugDumpParams();
        // die();
        $result = $consulta -> fetchAll(PDO::FETCH_ASSOC);

        return json_encode($result);
    }

    public static function getCargos($pdo)
    {
        $sql = "SELECT * FROM ".BD['CARGO']['TABLA'].";";

        $consulta = $pdo -> prepare($sql);
        $consulta -> execute();

        $result = $consulta -> fetchAll();

        $cargos = [];
        foreach ($result as $arr) {
            $cargos[$arr[BD['CARGO']['ID']]] = $arr[BD['CARGO']['NOMBRE']];
        }

        return $cargos;
    }
}