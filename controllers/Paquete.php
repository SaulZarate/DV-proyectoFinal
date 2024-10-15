<?


class Paquete{

    public static function getById($id){
        return DB::getOne("SELECT * FROM paquetes WHERE idPaquete = {$id}");
    }

    public static function getAll(){
        return DB::getAll("SELECT * FROM paquetes WHERE eliminado = 0");
    }

    public static function deleteFechaSalida($idPaquete, $fecha){
        $result = DB::delete("paquetes_fechas_salida", "idPaquete = {$idPaquete} AND fecha = '{$fecha}'");
        return true;
    }
}