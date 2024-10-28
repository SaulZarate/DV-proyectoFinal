<?


class Alojamiento{
    public static function getAll(){
        return DB::getAll("SELECT * FROM alojamientos WHERE eliminado = 0 ORDER BY created_at DESC");
    }
    public static function getById($id){
        return DB::getOne("SELECT * FROM alojamientos WHERE idAlojamiento = {$id}");
    }
}