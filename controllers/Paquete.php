<?


class Paquete{

    public static function getById($id){
        return DB::getOne("SELECT p.*, prov.nombre as provincia FROM paquetes p, provincias prov WHERE p.idProvincia = prov.idProvincia AND p.idPaquete = {$id}");
    }

    public static function getAllInfo($id){
        $data = DB::getOne("SELECT p.*, prov.nombre as provincia FROM paquetes p, provincias prov WHERE p.idProvincia = prov.idProvincia AND p.idPaquete = {$id}");
        $data->fechasSalida = self::getAllFechasSalida($id);
        return $data;
    }

    public static function getAll($option = []){
        $sqlWhere = "";
        $sqlOrder = "ORDER BY created_at DESC";
        
        if(isset($option["where"]) && $option["where"]) $sqlWhere .= " AND " . $option["where"];
        if(isset($option["order"]) && $option["order"]) $sqlOrder = " ORDER BY {$option["order"]}";

        return DB::getAll(
            "SELECT 
                p.*, 
                prov.nombre as provincia 
            FROM 
                paquetes p,
                provincias prov
            WHERE 
                p.idProvincia = prov.idProvincia AND 
                p.estado = 'A' AND  
                p.eliminado = 0 
                {$sqlWhere}
            {$sqlOrder}
        ");
    }
    
    public static function delete($idPaquete){
        $result = DB::update("paquetes", ["eliminado" => 1], "idPaquete = {$idPaquete}");
        return $result;
    }


    public static function getAllGalery($idPaquete){
        return DB::getAll("SELECT * FROM paquetes_galeria WHERE idPaquete = {$idPaquete} ORDER BY orden ASC, created_at ASC");
    }
    public static function deleteFileGalery($idFile){
        $data = DB::getOne("SELECT path FROM paquetes_galeria WHERE id = {$idFile}");

        // Elimino el archivo
        if(file_exists(PATH_SERVER.$data->path)) unlink(PATH_SERVER.$data->path);

        $result = DB::delete("paquetes_galeria", "id = {$idFile}");
        return $result;
    }


    public static function getAllFechasSalida($idPaquete, $minFecha = ""){
        $sqlMinFecha = "";
        if($minFecha) $sqlMinFecha = " AND DATE(fecha) >= '".date("Y-m-d", strtotime($minFecha))."'";
        return DB::getAll("SELECT * FROM paquetes_fechas_salida WHERE idPaquete = {$idPaquete} {$sqlMinFecha} ORDER BY fecha");
    }
    public static function getAllFechasDisponibles($idPaquete){
        $currentDate = date("Y-m-d");

        $results = array();

        foreach (DB::getAll("SELECT ps.*, p.capacidad as cupos FROM paquetes_fechas_salida ps, paquetes p WHERE ps.idPaquete = p.idPaquete AND ps.idPaquete = {$idPaquete} AND DATE(ps.fecha) >= '{$currentDate}' ORDER BY ps.fecha") as $fecha) {
            $fecha->disponibles = self::getCuposDisponibles($idPaquete, $fecha->fecha);;

            if($fecha->disponibles == 0) continue;
            $fecha->consultas = count(Consulta::getAllByPaqueteAndFecha($idPaquete, $fecha->fecha));
            
            $results[] = $fecha;
        }

        return $results;
    }
    public static function deleteFechaSalida($idPaquete, $fecha){
        $result = DB::delete("paquetes_fechas_salida", "idPaquete = {$idPaquete} AND fecha = '{$fecha}'");
        return $result;
    }

    // Para la vista del panel
    public static function getAllMessage($idConsulta){
        $mensajes = array();

        foreach (DB::getAll("SELECT * FROM consulta_mensajes WHERE idConsulta = {$idConsulta} ORDER BY created_at DESC, idMensaje DESC") as $mensaje) {
            
            $nombreUsuarioMensaje = "Sistema";
            $nombreTipoUsuario = "Sistema";
            if($mensaje->tipo == "C"){
                $cliente = Cliente::getById($mensaje->idUsuarioMensaje);
                $nombreUsuarioMensaje = ucfirst($cliente->nombre)." ". ucfirst($cliente->apellido);
                $nombreTipoUsuario = "Cliente";
            }
            if($mensaje->tipo == "U" && $mensaje->idUsuarioMensaje){
                $usuario = Usuario::getById($mensaje->idUsuarioMensaje);
                $nombreUsuarioMensaje = ucfirst($usuario->nombre)." ". ucfirst($usuario->apellido);
                $nombreTipoUsuario = $usuario->tipo == 0 ? "administrador" : "vendedor";
            }
            $mensaje->nombreUsuarioMensaje = $nombreUsuarioMensaje;
            $mensaje->nombreTipoUsuarioMensaje = $nombreTipoUsuario;
            
            $mensajes[] = $mensaje;
        }

        return $mensajes;
    }
    // Para la vista que ve el cliente
    public static function getAllMessagePublic($idConsulta){
        $mensajes = array();

        foreach (DB::getAll("SELECT * FROM consulta_mensajes WHERE idConsulta = {$idConsulta} AND tipo IN ('C', 'U') ORDER BY created_at ASC, idMensaje ASC") as $mensaje) {
            
            $nombreUsuarioMensaje = "Sistema";
            $nombreTipoUsuario = "Sistema";
            if($mensaje->tipo == "C"){
                $cliente = Cliente::getById($mensaje->idUsuarioMensaje);
                $nombreUsuarioMensaje = ucfirst($cliente->nombre)." ". ucfirst($cliente->apellido);
                $nombreTipoUsuario = "Cliente";
            }
            if($mensaje->tipo == "U" && $mensaje->idUsuarioMensaje){
                $usuario = Usuario::getById($mensaje->idUsuarioMensaje);
                $nombreUsuarioMensaje = ucfirst($usuario->nombre)." ". ucfirst($usuario->apellido);
                $nombreTipoUsuario = $usuario->tipo == 0 ? "administrador" : "vendedor";
            }
            $mensaje->nombreUsuarioMensaje = $nombreUsuarioMensaje;
            $mensaje->nombreTipoUsuarioMensaje = $nombreTipoUsuario;
            
            $mensajes[] = $mensaje;
        }

        return $mensajes;
    }

    public static function getCuposVendidos($idPaquete, $fecha){
        $totalVentas = 0;
        foreach (DB::getAll("SELECT c.idConsulta FROM consultas c, paquetes_fechas_salida ps WHERE c.idPaqueteFechaSalida = ps.id AND c.idPaquete = {$idPaquete} AND ps.fecha = '{$fecha}' AND c.estado = 'V' AND c.eliminado = 0") as $venta) {
            $totalVentas += COUNT(DB::getAll("SELECT * FROM consulta_pasajeros WHERE idConsulta = {$venta->idConsulta}"));
        }
        return $totalVentas;
    }
    public static function getCuposDisponibles($idPaquete, $fecha) {
        $dataPaquete = self::getById($idPaquete);
        $cuposVendidos = self::getCuposVendidos($idPaquete, $fecha);

        return $dataPaquete->capacidad - $cuposVendidos;
    }
}