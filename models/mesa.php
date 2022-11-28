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

    // Metodos
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

        
        $sql = $sql.";";
        // foreach ($params as $key => $value) {
        //     echo "$key - [$value]<br>";
        // }

        $consulta = $pdo -> prepare($sql);
        $consulta -> execute($params);
        // $consulta -> debugDumpParams();
        $result = $consulta -> fetchAll(PDO::FETCH_ASSOC);
        // echo $sql;
        // die();

        // foreach ($result as $key => $mesa) {
        //     foreach ($mesa as $key => $value) {
        //         echo "$key -> $value<br>";
        //     }
        //     echo "<br>";
        // }
        // die();
        if ($json) {
            return json_encode($result);
        } else {
            return $result;
        }

    }

    public static function getRegistros($pdo, $filtros)
    {
        // Recoger todas las mesas
        $sql = "SELECT * FROM ".BD['REGISTRO']['TABLA']." WHERE 1=1";

        // aplicar filtros
        foreach ($filtros as $key => $value) {
            $sql = $sql." AND ".FILTROS['BD'][$key]." = $value";
        }

        $sql = $sql." ORDER BY ".BD['REGISTRO']['ID']." DESC;";

        return mysqli_query($pdo, $sql);
    }

    public static function mesaExiste($pdo, $mesa)
    {
        $sql = "SELECT COUNT(1) AS num_mesas FROM ".BD['MESA']['TABLA']." WHERE ".BD['MESA']['ID']." = $mesa;";

        if (mysqli_fetch_assoc(mysqli_query($pdo, $sql))['num_mesas'] < 1) {
            return false;
        }
        return true;
    }

    public static function cambiarEstadoMesa($pdo, $id_mesa, $estado_mesa)
    {
        //  Cambiar el estado de la mesa en la tabla mesa
        $sql = "UPDATE ".BD['MESA']['TABLA']." SET ".BD['MESA']['ESTADO']." = '$estado_mesa' WHERE ".BD['MESA']['ID']." = $id_mesa";
        return mysqli_query($pdo, $sql);
    }

    public static function crearRegistroMesa($pdo, $id_mesa, $comensales)
    {
        session_start();
        $sql = "
            INSERT INTO ".BD['REGISTRO']['TABLA']." 
            (".BD['REGISTRO']['ID'].", ".BD['REGISTRO']['FECHAENTRADA'].", ".BD['REGISTRO']['FECHASALIDA'].", ".BD['REGISTRO']['MESA'].", ".BD['REGISTRO']['CAMARERO'].", ".BD['REGISTRO']['COMENSALES'].")
            VALUES(NULL, '".date('Y-m-d H:i:s')."', NULL, $id_mesa, ".$_SESSION['USER'][BD['EMPLEADO']['ID']].", $comensales)
        ;";
        
        return mysqli_query($pdo, $sql);
    }

    public static function cerrarRegistroMesa($pdo, $id_mesa)
    {
        $id_registro = Mesa::getRegistrosAbiertos($pdo, $id_mesa)[0][BD['REGISTRO']['ID']];

        $sql = "
            UPDATE ".BD['REGISTRO']['TABLA']."
            SET ".BD['REGISTRO']['FECHASALIDA']." = '".date('Y-m-d H:i:s')."'
            WHERE ".BD['REGISTRO']['ID']." = $id_registro
        ;";

        return mysqli_query($pdo, $sql);
    }

    public static function crearIncidenciaMesa($pdo, $id_mesa, $descripcion)
    {
        $sql = "
            INSERT INTO ".BD['INCIDENCIA']['TABLA']."(".BD['INCIDENCIA']['ID'].", ".BD['INCIDENCIA']['NOMBRE'].", ".BD['INCIDENCIA']['ESTADO'].", ".BD['INCIDENCIA']['MESA'].")
            VALUES (NULL, '".mysqli_real_escape_string($pdo, trim(strip_tags($descripcion)))."', 1, $id_mesa)
        ;";
        
        return mysqli_query($pdo, $sql);
    }

    public static function cerrarIncidenciaMesa($pdo, $id_mesa)
    {
        $id_incidencia = Mesa::getIncidenciaActivaDeMesa($pdo, $id_mesa)[BD['INCIDENCIA']['ID']];

        $sql = "UPDATE ".BD['INCIDENCIA']['TABLA']." SET ".BD['INCIDENCIA']['ESTADO']." = 0 WHERE ".BD['INCIDENCIA']['ID']." = $id_incidencia;";

        return mysqli_query($pdo, $sql);
    }

    public static function getMaxComensales($pdo, $id_mesa)
    {
        $sql = "SELECT ".BD['MESA']['CAPACIDAD']." AS capacidad FROM ".BD['MESA']['TABLA']." WHERE ".BD['MESA']['ID']." = $id_mesa;";

        return mysqli_fetch_assoc(mysqli_query($pdo, $sql))['capacidad'];
    }

    public static function getRegistrosAbiertos($pdo, $id_mesa)
    {
        $sql = "SELECT * FROM ".BD['REGISTRO']['TABLA']." WHERE ".BD['REGISTRO']['MESA']." = $id_mesa AND ".BD['REGISTRO']['FECHASALIDA']." IS NULL ORDER BY ".BD['REGISTRO']['ID']." DESC;";

        $array = [];
        foreach (mysqli_query($pdo, $sql) as $key => $value) {
            array_push($array, $value);
        }
        
        return $array;
    }

    public static function getEstadoMesa($pdo, $id_mesa) 
    {
        $sql = "SELECT ".BD['MESA']['ESTADO']." AS estado FROM ".BD['MESA']['TABLA']." WHERE ".BD['MESA']['ID']." = $id_mesa;";

        return mysqli_fetch_assoc(mysqli_query($pdo, $sql))['estado'];
    }

    public static function getIncidenciaActivaDeMesa($pdo, $id_mesa)
    {
        $sql = "SELECT * FROM ".BD['INCIDENCIA']['TABLA']." WHERE ".BD['INCIDENCIA']['MESA']." = $id_mesa AND ".BD['INCIDENCIA']['ESTADO']." = 1;";
        // echo $sql;
        // die();
        return mysqli_fetch_assoc(mysqli_query($pdo, $sql));
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

}
