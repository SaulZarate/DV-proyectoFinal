<?


class Paquete{

    public static function getById($id){
        return DB::getOne("SELECT * FROM paquetes WHERE idPaquete = {$id}");
    }

    public static function getAll(){
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
            ORDER BY p.created_at DESC
        ");
    }
    
    public static function getAllGalery($idPaquete){
        return DB::getAll("SELECT * FROM paquetes_galeria WHERE idPaquete = {$idPaquete} ORDER BY orden ASC, created_at ASC");
    }

    public static function delete($idPaquete){
        $result = DB::update("paquetes", ["eliminado" => 1], "idPaquete = {$idPaquete}");
        return $result;
    }

    public static function getAllFechasSalida($idPaquete){
        return DB::getAll("SELECT * FROM paquetes_fechas_salida WHERE idPaquete = {$idPaquete} ORDER BY fecha");
    }
    public static function deleteFechaSalida($idPaquete, $fecha){
        $result = DB::delete("paquetes_fechas_salida", "idPaquete = {$idPaquete} AND fecha = '{$fecha}'");
        return $result;
    }
}