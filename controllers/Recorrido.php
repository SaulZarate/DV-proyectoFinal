<?

class Recorrido{
    public static function getById($id){
        return DB::getOne("SELECT * FROM recorridos WHERE idRecorrido = {$id}");
    }

    public static function getAll($option = []){
        $sqlWhere = "TRUE";
        $sqlOrder = "ORDER BY created_at DESC";
        
        if(isset($option["where"]) && $option["where"]) $sqlWhere .= " AND " . $option["where"];
        if(isset($option["order"]) && $option["order"]) $sqlOrder = " ORDER BY {$option["order"]}";

        return DB::getAll("SELECT * FROM recorridos WHERE {$sqlWhere} {$sqlOrder}");
    }

    public static function getByIdAllInfo($id){
        $salida = self::getById($id);

        $salida->paquete = Paquete::getById($salida->idPaquete);
        $salida->usuario = Usuario::getById($salida->idUsuario);
        $salida->usuarioCreador = Usuario::getById($salida->created_by_idUsuario);

        return $salida;
    }

    public static function getConsultasByRecorrido($id){
        $dataRecorrido = self::getById($id);
        return DB::getAll("SELECT 
                c.*,
                COUNT(cp.idPasajero) as pax, 
                a.nombre as alojamiento, 
                a.direccion, 
                a.longitud, 
                a.latitud, 
                a.descripcion
            FROM 
                consulta_pasajeros cp, 
                paquetes p, 
                paquetes_fechas_salida pf, 
                provincias prov, 
                consultas c
            LEFT JOIN
                alojamientos a 
            ON 
                a.idAlojamiento = c.idAlojamiento 
            WHERE 
                c.idPaquete = p.idPaquete AND 
                c.idConsulta = cp.idConsulta AND 
                c.idPaqueteFechaSalida = pf.id AND 
                p.idProvincia = prov.idProvincia AND 
                c.estado = 'V' AND 
                c.eliminado = 0 AND 
                c.idPaquete = {$dataRecorrido->idPaquete} AND 
                pf.fecha = '{$dataRecorrido->fecha}'
            GROUP BY 
                c.idConsulta
            ORDER BY 
                c.updated_at
        ");
    }

    
    public static function getAllTramos($idRecorrido){
        $tramos = array();
        foreach (DB::getAll("SELECT * FROM recorrido_tramos WHERE idRecorrido = {$idRecorrido} ORDER BY orden ASC") as $tramo) {
            $pasajeros = array();
            foreach (DB::getAll("SELECT p.* FROM recorrido_tramo_pasajeros pt, consulta_pasajeros p WHERE pt.idRecorridoTramo = {$tramo->idRecorridoTramo} AND pt.idConsultaPasajero = p.idPasajero") as $pasajero) {
                $pasajeros[] = $pasajero;
            }
            $tramo->pasajeros = $pasajeros;
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
     * @return bool
     */
    /* TODO: Desarrollar */
    public static function update($idRecorrido){
        return DB::getAll("SELECT * FROM recorrido_tramos WHERE idRecorrido = {$idRecorrido} ORDER BY orden ASC");
    }
}