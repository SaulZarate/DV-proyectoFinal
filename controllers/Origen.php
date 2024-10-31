<?

class Origen{
    public static function getById($id){
        return DB::getOne("SELECT * FROM origenes WHERE idOrigen = {$id}");
    }
    
    public static function getAll($option = []){
        $sqlWhere = "eliminado = 0";
        $sqlOrder = "ORDER BY created_at DESC";
        
        if(isset($option["where"]) && $option["where"]) $sqlWhere .= " AND " . $option["where"];
        if(isset($option["order"]) && $option["order"]) $sqlOrder = " ORDER BY {$option["order"]}";

        return DB::getAll("SELECT * FROM origenes WHERE {$sqlWhere} {$sqlOrder}");
    }
}