<?


class Usuario{

    public static function getByEmail($email){
        return DB::getOne("SELECT * FROM usuarios WHERE email = '{$email}'");
    }

    public static function getById($id){
        return DB::getOne("SELECT * FROM usuarios WHERE idUsuario = {$id}");
    }

    public static function getAll($option = []){
        $sqlWhere = "eliminado = 0";
        $sqlOrder = "ORDER BY created_at DESC";
        
        if(isset($option["where"]) && $option["where"]) $sqlWhere .= " AND " . $option["where"];
        if(isset($option["order"]) && $option["order"]) $sqlOrder = " ORDER BY {$option["order"]}";

        return DB::getAll("SELECT * FROM usuarios WHERE {$sqlWhere} {$sqlOrder}");
    }

    public static function getAllGuias(){
        return self::getAll(["where" => "tipo = 2", "order" => "nombre ASC"]);
    }

}