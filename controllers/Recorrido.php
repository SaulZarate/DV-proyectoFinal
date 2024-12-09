<?

class Recorrido{
    public static function getById($id){
        return DB::getOne("SELECT * FROM recorridos WHERE idRecorrido = {$id}");
    }

    public static function getByIdAllInfo($id){
        $salida = self::getById($id);

        $salida->paquete = Paquete::getById($salida->idPaquete);
        $salida->usuario = Usuario::getById($salida->idUsuario);
        $salida->usuarioCreador = Usuario::getById($salida->created_by_idUsuario);
        $salida->tramos = self::getAllTramos($id);

        return $salida;
    }

    public static function getAll($option = []){
        $sqlWhere = "TRUE";
        $sqlOrder = "ORDER BY created_at DESC";
        
        if(isset($option["where"]) && $option["where"]) $sqlWhere .= " AND " . $option["where"];
        if(isset($option["order"]) && $option["order"]) $sqlOrder = " ORDER BY {$option["order"]}";

        return DB::getAll("SELECT * FROM recorridos WHERE {$sqlWhere} {$sqlOrder}");
    }

    public static function getConsultasByRecorrido($id){
        $dataRecorrido = self::getById($id);
        return Consulta::getAllByPaqueteAndFecha($dataRecorrido->idPaquete, $dataRecorrido->fecha);
    }

    /**
     * Devuelve un array de tramos con sus respectivos pasajeros
     */
    public static function getAllTramos($idRecorrido){
        $tramos = array();
        foreach (DB::getAll("SELECT rt.*, a.nombre, a.direccion, a.descripcion, a.latitud, a.longitud FROM recorrido_tramos rt LEFT JOIN alojamientos a ON rt.idAlojamiento = a.idAlojamiento WHERE rt.idRecorrido = {$idRecorrido} ORDER BY rt.orden ASC") as $tramo) {
            $pasajeros = array();
            foreach (DB::getAll("SELECT p.* FROM recorrido_tramo_pasajeros pt, consulta_pasajeros p WHERE pt.idRecorridoTramo = {$tramo->idRecorridoTramo} AND pt.idConsultaPasajero = p.idPasajero ORDER BY p.idConsulta, p.nombre") as $pasajero) {
                $pasajeros[] = $pasajero;
            }
            $tramo->pasajeros = $pasajeros;
            $tramo->totalPasajeros = count($pasajeros);
            $tramo->alojamiento = $tramo->idAlojamiento != 0 ? Alojamiento::getById($tramo->idAlojamiento) : null;
            $tramos[] = $tramo;
        }
        return $tramos;
    }

    

    /**
     * Actualiza los siguientes puntos de un recorrido
     * 
     * Tabla: Recorrido:
     * - total
     * - pasajeros (cantidad de pasajeros)
     * - totalAlojamientoConsulta
     * 
     * Tablas: Tramos | Pasajeros por tramo: 
     * - Re armar todo
     * 
     * @param int $idRecorrido 
     * @return void
     */
    public static function update($idRecorrido){
        $recorrido = self::getByIdAllInfo($idRecorrido);

        $total = 0;
        $totalPasajeros = 0;
        $totalAlojamientos = [];

        /* ----------------------------- */
        /*      Armo tabla recorridos    */
        /* ----------------------------- */
        $alojamientos = array();
        foreach (self::getConsultasByRecorrido($idRecorrido) as $consulta) {
            if($consulta->idAlojamiento != 0) $totalAlojamientos[] = $consulta->idAlojamiento;

            $totalPasajeros += $consulta->pax;
            $total += $consulta->total;

            if($consulta->traslado == 0 || $recorrido->paquete->traslado == 0) $consulta->idAlojamiento = 0;

            // Agrego los pasajeros al alojamiento
            if(!isset($alojamientos[$consulta->idAlojamiento])) $alojamientos[$consulta->idAlojamiento] = array();
            $alojamientos[$consulta->idAlojamiento] = array_merge($alojamientos[$consulta->idAlojamiento], Consulta::getAllPasajeros($consulta->idConsulta));
        }
        $totalAlojamientos = count(array_unique($totalAlojamientos));

        // Actualizo la tabla
        DB::update("recorridos", ["total" => $total, "totalAlojamientoConsulta" => $totalAlojamientos, "pasajeros" => $totalPasajeros]);
        

        /* ------------------------------------------------------------------------- */
        /*          Armo tabla recorrido_tramos y recorrido_tramo_pasajeros          */
        /* ------------------------------------------------------------------------- */
        DB::delete("recorrido_tramos", "idRecorrido = {$idRecorrido}");
        DB::delete("recorrido_tramo_pasajeros", "idRecorrido = {$idRecorrido}");
        $indexAlojamiento = 1;
        sort($alojamientos);
        foreach ($alojamientos as $idAlojamiento => $pasajeros) {
            $dataInsertTramo = array(
                "idRecorrido" => $idRecorrido, 
                "idAlojamiento" => $idAlojamiento, 
                "pax" => count($pasajeros), 
                "orden" => $indexAlojamiento, 
                "tipo" => ($idAlojamiento === 0 ? "O" : "P")
            );
            $idTramo = DB::insert("recorrido_tramos", $dataInsertTramo);
            
            // Inserto los pasajeros
            $dataInsertPasajeros = array();
            foreach ($pasajeros as $pasajero) $dataInsertPasajeros[] = [$idRecorrido, $idTramo, $pasajero->idPasajero];
            if($dataInsertPasajeros) DB::insertMult("recorrido_tramo_pasajeros", ["idRecorrido", "idRecorridoTramo", "idConsultaPasajero"], $dataInsertPasajeros);
            
            $indexAlojamiento++;
        }

        // Por si no hay alojamientos
        if(!$alojamientos){
            DB::insert("recorrido_tramos", ["idRecorrido" => $idRecorrido, "idAlojamiento" => 0, "pax" => $totalPasajeros, "orden" => $indexAlojamiento, "tipo" => "O"]);
            $indexAlojamiento++;
        }
        DB::insert("recorrido_tramos", ["idRecorrido" => $idRecorrido, "idAlojamiento" => 0, "pax" => 0, "orden" => $indexAlojamiento, "tipo" => "D"]);

    }

    public static function getAllMessage($idRecorrido, $orderCreatedASC = true){
        $order = $orderCreatedASC ? 'ASC' : 'DESC';
        
        return DB::getAll(
            "SELECT 
                rm.*, 
                CONCAT(u.nombre, ' ', u.apellido) as usuario, 
                CONCAT(c.nombre, ' ', c.apellido) as cliente
            FROM 
                recorrido_mensajes rm
            LEFT JOIN
                usuarios u
            ON
                rm.idUsuario = u.idUsuario
            LEFT JOIN
                clientes c
            ON
                rm.idUsuario = c.idCliente
            WHERE 
                rm.idRecorrido = {$idRecorrido}
            ORDER BY 
                rm.idMensaje {$order}
        ");
    }
}