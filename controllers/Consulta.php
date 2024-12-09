<?
class Consulta{

    public static function getById($id){
        return DB::getOne("SELECT * FROM consultas WHERE idConsulta = {$id}");
    }

    public static function getAll($option = []){
        $sqlWhere = "eliminado = 0";
        $sqlOrder = "ORDER BY created_at DESC";
        
        if(isset($option["where"]) && $option["where"]) $sqlWhere .= " AND " . $option["where"];
        if(isset($option["order"]) && $option["order"]) $sqlOrder = " ORDER BY {$option["order"]}";

        return DB::getAll("SELECT * FROM consultas WHERE {$sqlWhere} {$sqlOrder}");
    }

    public static function getAllByIdClient($idCliente){
        return DB::getAll("SELECT * FROM consultas WHERE idCliente = {$idCliente} AND eliminado = 0");
    }

    public static function getAllDatosDeContactoAdicional($idConsulta){
        return DB::getAll("SELECT * FROM consulta_contacto_adicional  WHERE idConsulta = {$idConsulta}");
    }

    public static function getAllPasajeros($idConsulta){
        return DB::getAll("SELECT * FROM consulta_pasajeros  WHERE idConsulta = {$idConsulta}");
    }

    public static function getAllInfo($idConsulta){
        $consulta = DB::getOne(
            "SELECT 
                c.*,
                ps.fecha as fechaSalida
            FROM 
                consultas c
            LEFT JOIN 
                paquetes_fechas_salida ps
            ON 
                c.idPaqueteFechaSalida = ps.id
            WHERE 
                c.idConsulta = {$idConsulta} 
        ");

        $consulta->contactosAdicionales = self::getAllDatosDeContactoAdicional($idConsulta);
        $consulta->pasajeros = self::getAllPasajeros($idConsulta);
        
        $consulta->cliente = Cliente::getById($consulta->idCliente);
        $consulta->asignado = Usuario::getById($consulta->idUsuario);
        $consulta->paquete = Paquete::getAllInfo($consulta->idPaquete);
        $consulta->origen = Origen::getById($consulta->idOrigen);
        $consulta->alojamiento = Alojamiento::getById($consulta->idAlojamiento);

        return $consulta;
    }

    public static function getDataVentas($idUsuario = null, $minDate = null, $maxDate = null){
        $data = array(
            "ventas" => 0,
            "total" => 0,
        );

        $filtroFechas = [];
        if($minDate) $filtroFechas[] = "'{$minDate}' <= DATE(updated_at)";
        if($maxDate) $filtroFechas[] = "DATE(updated_at) <= '{$maxDate}'";

        if($filtroFechas) $filtroFechas = " AND ".implode(" AND ", $filtroFechas);
        else $filtroFechas = "";
        
        $filtroUsuario = $idUsuario ? " AND idUsuario = {$idUsuario}" : "";

        foreach (DB::getAll("SELECT * FROM consultas WHERE eliminado = 0 AND estado = 'V' {$filtroUsuario} {$filtroFechas}") as $venta) {
            $data["ventas"] ++;
            $data["total"] += $venta->total;
        }

        return (object) $data;
    }

    public static function getTopVendedores($minDate = null, $maxDate = null){
        $filtroFechas = [];
        if($minDate) $filtroFechas[] = "'{$minDate}' <= DATE(c.updated_at)";
        if($maxDate) $filtroFechas[] = "DATE(c.updated_at) <= '{$maxDate}'";

        if($filtroFechas) $filtroFechas = " AND ".implode(" AND ", $filtroFechas);
        else $filtroFechas = "";

        return DB::getAll(
            "SELECT 
                u.nombre, 
                u.apellido, 
                u.email, 
                IFNULL(SUM(c.total), 0) as total, 
                COUNT(c.idConsulta) as ventas 
            FROM 
                usuarios u 
            LEFT JOIN 
                consultas c 
            ON 
                u.idUsuario = c.idUsuario AND 
                c.estado = 'V' AND 
                c.eliminado = 0 
                {$filtroFechas}
            WHERE 
                u.tipo IN (0,1) AND
                u.eliminado = 0  
            GROUP BY 
                u.idUsuario
            ORDER BY 
                SUM(c.total) DESC,
                COUNT(c.idConsulta)
        ");
    }
    
    public static function getAllByPaqueteAndFecha($idPaquete, $fecha){
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
                c.idPaquete = {$idPaquete} AND 
                pf.fecha = '{$fecha}'
            GROUP BY 
                c.idConsulta
            ORDER BY 
                c.updated_at
        ");
    }
}