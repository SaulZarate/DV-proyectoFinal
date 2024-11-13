<?

class Recorrido{
    public static function getById($id){
        return DB::getOne("SELECT * FROM recorridos WHERE idRecorrido = {$id}");
    }
    
    

    public static function getAll($option = []){
        $sqlWhere = "TRUE";
        $sqlOrder = "ORDER BY created_at DESC";
        
        if(isset($option["where"]) && $option["where"]) $sqlWhere .= " AND " . $option["where"];
        if(isset($option["order"]) && $option["order"]) $sqlOrder = " ORDER BY {$option["order"]}";

        return DB::getAll("SELECT * FROM recorridos WHERE {$sqlWhere} {$sqlOrder}");
    }

    public static function getByIdAllInfo($id){
        $salida = self::getById($id);

        $salida->paquete = Paquete::getById($salida->idPaquete);
        $salida->usuario = Usuario::getById($salida->idUsuario);
        $salida->usuarioCreador = Usuario::getById($salida->created_by_idUsuario);

        return $salida;
    }
}