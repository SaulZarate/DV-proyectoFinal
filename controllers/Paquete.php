<?


class Paquete{

    public static function getById($id){
        return DB::getOne("SELECT * FROM paquetes WHERE idPaquete = {$id}");
    }

    public static function getAll(){
        return DB::getAll("SELECT * FROM paquetes WHERE eliminado = 0");
    }

}