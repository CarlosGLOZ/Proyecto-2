<?php

class Mesa
{
    // Atributos
        private $id;
        private $estado;
        private $sala;
        private $capacidad;

    // Constructor
        public function __construct($id, $estado, $sala, $capacidad)
        {
            $this -> id = $id;
            $this -> estado = $estado;
            $this -> sala = $sala;
            $this -> capacidad = $capacidad;
        }

    // Getters y Setters
        public function getId()
        {
            return $this -> id;
        }
        public function getEstado()
        {
            return $this -> estado;
        }
        public function getSala()
        {
            return $this -> sala;
        }
        public function getCapacidad()
        {
            return $this -> capacidad;
        }

        public function setId($val)
        {
            $this -> id = $val;
            return $this -> id == $val;
        }
        public function setEstado($val)
        {
            $this -> estado = $val;
            return $this -> estado == $val;
        }
        public function setSala($val)
        {
            $this -> sala = $val;
            return $this -> sala == $val;
        }
        public function setCapacidad($val)
        {
            $this -> capacidad = $val;
            return $this -> capacidad == $val;
        }


    // Metodos
    public static function getFiltrosMesas($pdo)
    {
        // Recoger los nombres de los filtros de las mesas
        $sql = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='".BD['BD']."' AND `TABLE_NAME`='".BD['MESA']['TABLA']."';";
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

    public static function getMesas($pdo, $filtros, $campo='*', $json = false)
    {
        // Recoger todas las mesas
        $sql = "SELECT $campo FROM ".BD['MESA']['TABLA']." WHERE 1=1";

        // aplicar filtros y añadirlos a la lista de parametros de la query
        $params = [];
        foreach ($filtros as $key => $value) {
            if (gettype($value) != 'string') {
                $sql = $sql." AND ".FILTROS['BD'][$key]." = :$key";
                $params[$key] = $value;
            } else {
                $sql = $sql." AND ".FILTROS['BD'][$key]." LIKE :$key";
                $params["'".$key."%'"] = $value;
            }
        }

        
        $sql = $sql." ORDER BY ".BD['MESA']['NUMERO'].";";


        $consulta = $pdo -> prepare($sql);
        $consulta -> execute($params);
        $result = $consulta -> fetchAll(PDO::FETCH_ASSOC);

        if ($json) {
            return json_encode($result);
        } else {
            return $result;
        }

    }

    public static function getRecursos($pdo, $filtros)
    {
        // Recoger todas las mesas
        $sql = "
            SELECT ".BD['MESA']['NUMERO']." as ".VARNAMES_QUERY_RECURSOS['NUMERO'].",
            ".BD['SALA']['TABLA'].".".BD['SALA']['NOMBRE']." as ".VARNAMES_QUERY_RECURSOS['SALA'].",
            ".BD['MESA']['CAPACIDAD']." as ".VARNAMES_QUERY_RECURSOS['CAPACIDAD'].",
            ".BD['MESA']['ESTADO']." as ".VARNAMES_QUERY_RECURSOS['ESTADO']."
            FROM ".BD['MESA']['TABLA']." INNER JOIN
            ".BD['SALA']['TABLA']." ON ".BD['MESA']['SALA']." = ".BD['SALA']['ID']."
            WHERE 1=1
        ";

        // aplicar filtros y añadirlos a la lista de parametros de la query
        $params = [];
        foreach ($filtros as $key => $value) {
            try {
                $value = intval($value);
                $sql = $sql." AND ".FILTROS['BD'][$key]." = :$key";
                $params[$key] = $value;
            } catch (\Throwable $th) {
                $sql = $sql." AND ".FILTROS['BD'][$key]." LIKE :$key";
                $params[$key] = $value."%";
            }
            // if (gettype($value) != 'string') {
            // } else {
            // }
        }
        $sql = $sql." ORDER BY ".BD['MESA']['TABLA'].".".BD['MESA']['NUMERO']." ASC;";

        $consulta = $pdo -> prepare($sql);
        $consulta -> execute($params);
        // $consulta -> debugDumpParams();
        // die();
        $result = $consulta -> fetchAll(PDO::FETCH_ASSOC);

        return json_encode($result);
    }

    public static function getRegistros($pdo, $filtros)
    {
        // Recoger todas las mesas
        $sql = "SELECT * FROM ".BD['REGISTRO']['TABLA']." WHERE 1=1";

        // aplicar filtros y añadirlos a la lista de parametros de la query
        $params = [];
        foreach ($filtros as $key => $value) {
            if (gettype($value) != 'string') {
                $sql = $sql." AND ".FILTROS['BD'][$key]." = :$key";
                $params[$key] = $value;
            } else {
                $sql = $sql." AND ".FILTROS['BD'][$key]." LIKE :$key";
                $params["'".$key."%'"] = $value;
            }
        }

        $sql = $sql." ORDER BY ".BD['REGISTRO']['ID']." DESC;";

        $consulta = $pdo -> prepare($sql);
        $consulta -> execute($params);
        // $consulta -> debugDumpParams();
        $result = $consulta -> fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public static function mesaExiste($pdo, $mesa)
    {
        $sql = "SELECT COUNT(1) AS num_mesas FROM ".BD['MESA']['TABLA']." WHERE ".BD['MESA']['ID']." = :id_mesa;";

        $consulta = $pdo -> prepare($sql);
        $consulta -> execute(['id_mesa' => $mesa]);
        $result = $consulta -> fetch(PDO::FETCH_ASSOC)['num_mesas'];

        if ($result < 1) {
            return false;
        }
        return true;
    }

    public static function cambiarEstadoMesa($pdo, $id_mesa, $estado_mesa)
    {
        //  Cambiar el estado de la mesa en la tabla mesa
        $sql = "UPDATE ".BD['MESA']['TABLA']." SET ".BD['MESA']['ESTADO']." = :estado_mesa WHERE ".BD['MESA']['ID']." = :id_mesa";
        
        $consulta = $pdo -> prepare($sql);
        $result = $consulta -> execute([
            'id_mesa' => $id_mesa,
            'estado_mesa' => $estado_mesa
        ]);
        
        return $result;
    }

    public static function crearRegistroMesa($pdo, $id_mesa, $comensales)
    {
        session_start();
        $sql = "
            INSERT INTO ".BD['REGISTRO']['TABLA']." 
            (".BD['REGISTRO']['ID'].", ".BD['REGISTRO']['FECHAENTRADA'].", ".BD['REGISTRO']['FECHASALIDA'].", ".BD['REGISTRO']['MESA'].", ".BD['REGISTRO']['CAMARERO'].", ".BD['REGISTRO']['COMENSALES'].")
            VALUES(NULL, :fecha, NULL, :id_mesa, :id_empleado, :comensales)
        ;";

        $consulta = $pdo -> prepare($sql);
        $result = $consulta -> execute([
            'fecha' => date('Y-m-d H:i:s'),
            'id_mesa' => $id_mesa,
            'id_empleado' => $_SESSION['USER'][BD['EMPLEADO']['ID']],
            'comensales' => $comensales
        ]);
        
        return $result;
    }

    public static function cerrarRegistroMesa($pdo, $id_mesa)
    {
        $id_registro = Mesa::getRegistrosAbiertos($pdo, $id_mesa)[BD['REGISTRO']['ID']];

        $sql = "
            UPDATE ".BD['REGISTRO']['TABLA']."
            SET ".BD['REGISTRO']['FECHASALIDA']." = '".date('Y-m-d H:i:s')."'
            WHERE ".BD['REGISTRO']['ID']." = :id_registro
        ;";

        $consulta = $pdo -> prepare($sql);
        $result = $consulta -> execute([
            'id_registro' =>$id_registro,
        ]);
        
        return $result;
    }

    public static function crearIncidenciaMesa($pdo, $id_mesa, $descripcion)
    {
        $sql = "
            INSERT INTO ".BD['INCIDENCIA']['TABLA']."(".BD['INCIDENCIA']['ID'].", ".BD['INCIDENCIA']['NOMBRE'].", ".BD['INCIDENCIA']['ESTADO'].", ".BD['INCIDENCIA']['MESA'].")
            VALUES (NULL, :nombre, 1, :id_mesa)
        ;";

        $consulta = $pdo -> prepare($sql);
        $result = $consulta -> execute([
            'nombre' => $descripcion,
            'id_mesa' => $id_mesa,
        ]);
        
        return $result;
    }

    public static function cerrarIncidenciaMesa($pdo, $id_mesa)
    {
        $id_incidencia = Mesa::getIncidenciaActivaDeMesa($pdo, $id_mesa)[BD['INCIDENCIA']['ID']];

        $sql = "UPDATE ".BD['INCIDENCIA']['TABLA']." SET ".BD['INCIDENCIA']['ESTADO']." = 0 WHERE ".BD['INCIDENCIA']['ID']." = :id_incidencia;";

        $consulta = $pdo -> prepare($sql);
        $result = $consulta -> execute([
            'id_incidencia' =>$id_incidencia,
        ]);
        
        return $result;
    }

    public static function getMaxComensales($pdo, $id_mesa)
    {
        $sql = "SELECT ".BD['MESA']['CAPACIDAD']." AS capacidad FROM ".BD['MESA']['TABLA']." WHERE ".BD['MESA']['ID']." = :id_mesa;";

        $consulta = $pdo -> prepare($sql);
        $consulta -> execute(['id_mesa' => $id_mesa]);
        $result = $consulta -> fetch(PDO::FETCH_ASSOC)['capacidad'];

        return $result;
    }

    public static function getRegistrosAbiertos($pdo, $id_mesa)
    {
        $sql = "SELECT * FROM ".BD['REGISTRO']['TABLA']." WHERE ".BD['REGISTRO']['MESA']." = :id_mesa AND ".BD['REGISTRO']['FECHASALIDA']." IS NULL ORDER BY ".BD['REGISTRO']['ID']." DESC;";

        $consulta = $pdo -> prepare($sql);
        $consulta -> execute(['id_mesa' => $id_mesa]);
        // Hacer fetch() para que devuelva el último registro en forma de array único
        $result = $consulta -> fetch();

        return $result;
    }

    public static function getEstadoMesa($pdo, $id_mesa) 
    {
        $sql = "SELECT ".BD['MESA']['ESTADO']." AS estado FROM ".BD['MESA']['TABLA']." WHERE ".BD['MESA']['ID']." = :id_mesa;";
        
        $consulta = $pdo -> prepare($sql);
        $consulta -> execute(['id_mesa' => $id_mesa]);
        // $consulta ->debugDumpParams();
        // die();

        $result = $consulta -> fetch(PDO::FETCH_ASSOC)['estado'];

        return $result;
    }

    public static function getIncidenciaActivaDeMesa($pdo, $id_mesa)
    {
        $sql = "SELECT * FROM ".BD['INCIDENCIA']['TABLA']." WHERE ".BD['INCIDENCIA']['MESA']." = :id_mesa AND ".BD['INCIDENCIA']['ESTADO']." = 1;";
        
        $consulta = $pdo -> prepare($sql);
        $consulta -> execute([
            'id_mesa' => $id_mesa,
        ]);
        // $consulta ->debugDumpParams();
        // die();
        
        // Hacer fetch() para que devuelva el último registro en forma de array único
        $result = $consulta -> fetch();
        return $result;
    }

    public static function validarComensales($pdo, $id_mesa, $comensales)
    {
        if ($comensales < 1 || $comensales > Mesa::getMaxComensales($pdo, $id_mesa)) {
            return false;
        }
        return true;
    }

    public static function getSalas($pdo)
    {
        $sql = "SELECT * FROM ".BD['SALA']['TABLA'].";";

        $consulta = $pdo -> prepare($sql);
        $consulta -> execute();

        $result = $consulta -> fetchAll();

        return $result;
    }

    public static function getCapacidades($pdo)
    {
        $sql = "SELECT ".BD['MESA']['CAPACIDAD']." AS cap FROM ".BD['MESA']['TABLA']." GROUP BY ".BD['MESA']['CAPACIDAD'].";";

        $consulta = $pdo -> prepare($sql);
        $consulta -> execute();

        $result = $consulta -> fetchAll();
        $return = [];

        foreach ($result as $value) {
            array_push($return, $value['cap']);
        }

        return $return;
    }

    public static function getNextInsertNumero($pdo)
    {
        $sql = "SELECT ".BD['MESA']['NUMERO']." as num FROM ".BD['MESA']['TABLA']." ORDER BY ".BD['MESA']['NUMERO']." ASC;";

        $consulta = $pdo -> prepare($sql);
        $consulta -> execute();

        $result = $consulta -> fetchAll();
        
        // Iterar sobre todos los numeros para encontrar el primer numero sin usar
        // Comparando un contador con los valores del numero de la mesa
        // pillaremos el primer numero que no coincida con el contador y,
        // por tanto, el primer numero disponible (no funcionará bien si hay mesas repetidas)
        $currno = 1;
        foreach ($result as $arr) {
            if ($arr['num'] != $currno) {
                break;
            }
            $currno++;
        }
        return $currno;
    }

    public static function addRecurso($pdo, $params)
    {
        $params[BD['MESA']['NUMERO']] = Mesa::getNextInsertNumero($pdo);

        $sql = "INSERT INTO ".BD['MESA']['TABLA']."(".BD['MESA']['ID'].", ".BD['MESA']['NUMERO'].", ".BD['MESA']['ESTADO'].", ".BD['MESA']['SALA'].", ".BD['MESA']['CAPACIDAD'].")
        VALUES(NULL, :".BD['MESA']['NUMERO'].", '0', :".BD['MESA']['SALA'].", :".BD['MESA']['CAPACIDAD'].");";

        $consulta = $pdo -> prepare($sql);
        $result = $consulta -> execute($params);
        return $result;
    }

    public static function deleteRecurso($pdo, $num)
    {
        $sql = "DELETE FROM ".BD['MESA']['TABLA']." WHERE ".BD['MESA']['NUMERO']." = :num;";

        $consulta = $pdo -> prepare($sql);
        $result = $consulta -> execute(['num' => $num]);

        return $result;
    }

    public static function modRecurso($pdo, $params)
    {
        $sql = "UPDATE ".BD['MESA']['TABLA']." 
        SET ".BD['MESA']['SALA']." = :".BD['MESA']['SALA'].", 
        ".BD['MESA']['CAPACIDAD']." = :".BD['MESA']['CAPACIDAD']."
        WHERE ".BD['MESA']['NUMERO']." = :".BD['MESA']['NUMERO'].";";

        $consulta = $pdo -> prepare($sql);
        $result = $consulta -> execute($params);

        return $result;
    }

}
