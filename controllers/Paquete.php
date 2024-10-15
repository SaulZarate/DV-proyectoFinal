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

    public static function deleteFechaSalida($idPaquete, $fecha){
        $result = DB::delete("paquetes_fechas_salida", "idPaquete = {$idPaquete} AND fecha = '{$fecha}'");
        return true;
    }
}