<?

class Cliente{

    public static function getById($id){
        return DB::getOne("SELECT * FROM clientes WHERE idCliente = {$id}");
    }

    public static function getAll(){
        return DB::getAll("SELECT * FROM clientes WHERE eliminado = 0");
    }

}