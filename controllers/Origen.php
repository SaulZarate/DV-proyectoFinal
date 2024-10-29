<?

class Origen{
    public static function getById($id){
        return DB::getOne("SELECT * FROM origenes WHERE idOrigen = {$id}");
    }
    
    public static function getAll(){
        return DB::getAll("SELECT * FROM origenes WHERE eliminado = 0 ORDER BY created_at DESC");
    }
}