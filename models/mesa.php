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

    public static function getCapacidades($pdo, $max=false)
    {
        // Si max=true, recuperar el número máximo de comensales
        if ($max) {
            $sql = "SELECT ".BD['MESA']['CAPACIDAD']." AS cap FROM ".BD['MESA']['TABLA']." GROUP BY ".BD['MESA']['CAPACIDAD']." ORDER BY ".BD['MESA']['CAPACIDAD']." DESC;";
            
            $consulta = $pdo -> prepare($sql);
            $consulta -> execute();
            
            $result = $consulta -> fetch();
            $return = [];

            foreach ($result as $value) {
                array_push($return, $value['cap']);
            }
            
            return $return;
        } else {
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

    // Validaciones de reserva

    // Comprobar si hay mesas libres para los comensales especificados
    public static function getMesasConComensales($pdo, $comensales, $margen=2)
    {
        // Recogeremos el numero de mesas con almenos la dada cantidad de comensales
        $sql = "SELECT * FROM ".BD['MESA']['TABLA']." 
        WHERE ".BD['MESA']['CAPACIDAD']." >= :comensales 
        AND ".BD['MESA']['CAPACIDAD']." <= :margen 
        ORDER BY ".BD['MESA']['CAPACIDAD']." ASC;";

        $consulta = $pdo -> prepare($sql);
        $margen = $comensales + $margen;
        $consulta -> execute([
            'comensales' => $comensales,
            'margen' => $margen,
        ]);

        $result = $consulta -> fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
    }

    // Recuperar las horas disponibles en una fecha
    public static function getHorasDisponiblesEnFecha($pdo, $fecha, $comensales)
    {
        $comensales_margen = $comensales + 2;

        $sql = "SELECT ".BD['RESERVA']['HORA_INICIO']." as hora_inicio, ".BD['RESERVA']['HORA_FINAL']." as hora_final
        FROM ".BD['RESERVA']['TABLA']." INNER JOIN ".BD['MESA']['TABLA']." ON ".BD['MESA']['ID']." = ".BD['RESERVA']['MESA']."
        WHERE ".BD['RESERVA']['FECHA']." = :fecha
        AND ".BD['MESA']['CAPACIDAD']." >= :com
        AND ".BD['MESA']['CAPACIDAD']." <= :com_margen;";

        $consulta = $pdo -> prepare($sql);
        $consulta -> execute([
            'fecha' => $fecha,
            'com' => $comensales,
            'com_margen' => $comensales_margen,
        ]);

        $result = $consulta -> fetchAll(PDO::FETCH_ASSOC);

        $horas = getHorasActivas();

        // Hacer un array associativo que relacione cada hora con la cantidad de mesas libres en ella
        $array_horas_disponibilidad = [];
        foreach ($horas as $hora) {
            $array_horas_disponibilidad[$hora] = 0;
        }

        // Hacer un array de las horas en las que hay una mesa disponible
        $horas_disponibles = [];

        
        // Iteramos sobre cada reserva y añadimos +1 a la hora en la que se haya reservado (y a las 2 medias horas siguientes)
        foreach ($result as $reserva) {

            // Cogemos el indice de las horas de inicio y final para usarlo como maximo y minimo
            $hora_inicio_index = array_search($reserva['hora_inicio'], $horas);
            $hora_final_index = array_search($reserva['hora_final'], $horas);

            // Por cada hora entre la hora de inicio y la final, añadir +1 a la hora en array_horas_disponibilidad
            for ($i=$hora_inicio_index; $i < $hora_final_index; $i++) { 
                // Sumar +1
                $array_horas_disponibilidad[$horas[$i]] += 1;
                // Sumar uno a la media hora anterior también
                $array_horas_disponibilidad[$horas[$i-1]] += 1;
            }
        }

        // Si alguna hora en el array array_horas_disponibilidad tiene un numero de reservas 
        // menor a la cantidad de mesas disponibles para esos comensales,
        // añadimos la hora al array de horas disponibles
        $mesas = Mesa::getMesasConComensales($pdo, $comensales);

        $cantidad_mesas = 0;
        foreach ($mesas as $mesa) {
            $cantidad_mesas++;
        }

        foreach ($array_horas_disponibilidad as $hora => $reservas) {
            if ($reservas < $cantidad_mesas) {
                array_push($horas_disponibles, $hora);
            }
        }

        return $horas_disponibles;
    }

    public static function getIdMesaPorNumero($pdo, $mesa)
    {
        $sql = "SELECT ".BD['MESA']['ID']." as id FROM ".BD['MESA']['TABLA']." WHERE ".BD['MESA']['NUMERO']." = :numero;";
        $consulta = $pdo -> prepare($sql);
        $consulta -> execute(['numero' => $mesa]);

        $result = $consulta -> fetch(PDO::FETCH_ASSOC);

        $consulta -> debugDumpParams();

        return $result['id'];
    }

    public static function mesaEstaLibreEnHora($pdo, $mesa, $fecha, $hora_inicio, $hora_final)
    {
        // $mesa = Mesa::getIdMesaPorNumero($pdo, $mesa);

        // SQL para ver si hay una reserva que coincida con la que se pide

        // ESTA QUERY DA ERROR
        // $sql = "SELECT count(1) as cont FROM ".BD['RESERVA']['TABLA']." WHERE ".BD['RESERVA']['MESA']." = :mesa
        // AND ".BD['RESERVA']['FECHA']." = :fecha
        // AND max(".BD['RESERVA']['HORA_INICIO'].", :hora_inicio) < min(".BD['RESERVA']['HORA_FINAL'].", :hora_final)";

        // ESTA QUERY ESTÁ ESCRITA POR CHAT GPT
        $sql = "SELECT count(*) as cont
            FROM ".BD['RESERVA']['TABLA']."
            WHERE ".BD['RESERVA']['MESA']." = :mesa
            AND ".BD['RESERVA']['FECHA']." = :fecha
            AND (
                (".BD['RESERVA']['HORA_INICIO']." >= :hora_inicio AND ".BD['RESERVA']['HORA_INICIO']." < :hora_final)
                OR (".BD['RESERVA']['HORA_FINAL']." > :hora_inicio AND ".BD['RESERVA']['HORA_FINAL']." <= :hora_final)
        );";
        
        $consulta = $pdo -> prepare($sql);
        $consulta -> execute([
            'mesa' => $mesa,
            'fecha' => $fecha,
            'hora_inicio' => $hora_inicio,
            'hora_final' => $hora_final,
        ]);


        $reservas = $consulta -> fetch(PDO::FETCH_ASSOC);

        if ($reservas['cont'] > 0) {
            return false;
        } else {
            return true;
        }
    }

    public static function getMesaParaReserva($pdo, $comensales, $fecha, $hora_inicio, $hora_final)
    {
        // Hacer una lista de las mesas posibles en base a sus comensales
        $sql = "SELECT ".BD['MESA']['ID']." as num FROM ".BD['MESA']['TABLA']."
        WHERE ".BD['MESA']['CAPACIDAD']." = :comensales
        ORDER BY ".BD['MESA']['CAPACIDAD']." ASC";

        $consulta = $pdo -> prepare($sql);
        $consulta -> execute(['comensales' => $comensales]);

        $mesas = $consulta -> fetchAll(PDO::FETCH_ASSOC);

        foreach ($mesas as $mesa) {
            if (Mesa::mesaEstaLibreEnHora($pdo, $mesa['num'], $fecha, $hora_inicio, $hora_final)) {
                return $mesa['num'];
            }
        }
    }

    public static function hacerReserva($pdo, $params)
    {
        $params[BD['RESERVA']['MESA']] = Mesa::getMesaParaReserva($pdo, $params[BD['RESERVA']['COMENSALES']], $params[BD['RESERVA']['FECHA']], $params[BD['RESERVA']['HORA_INICIO']], $params[BD['RESERVA']['HORA_FINAL']]);
        
        $sql = "INSERT INTO ".BD['RESERVA']['TABLA']."
        VALUES(NULL, :".BD['RESERVA']['MESA'].", :".BD['RESERVA']['NOMBRE'].", :".BD['RESERVA']['COMENSALES'].",
        :".BD['RESERVA']['FECHA'].", :".BD['RESERVA']['HORA_INICIO'].", :".BD['RESERVA']['HORA_FINAL'].");";

        $consulta = $pdo -> prepare($sql);
        $result = $consulta -> execute($params);

        return $result;
    }

}
