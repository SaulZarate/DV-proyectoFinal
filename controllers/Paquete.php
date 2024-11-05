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
    public static function deleteFechaSalida($idPaquete, $fecha){
        $result = DB::delete("paquetes_fechas_salida", "idPaquete = {$idPaquete} AND fecha = '{$fecha}'");
        return $result;
    }
}