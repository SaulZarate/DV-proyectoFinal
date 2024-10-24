<?

class Calendario{
    public static function getAllEvents($filter = ""){
        $eventos = DB::getAll("SELECT e.*, u.* FROM eventos e, usuarios u WHERE e.eliminado = 0 AND e.idUsuario = u.idUsuario ORDER BY e.fechaInicio ASC");
        return $eventos;
    }
    public static function getAllEventsByUser($idUser, $desde, $hasta, $filter = ""){
        $eventos = DB::getAll("SELECT e.*, u.* FROM eventos e, usuarios u WHERE e.eliminado = 0 AND e.idUsuario = u.idUsuario AND u.tipo = {$idUser} AND DATE('{$desde}') <= DATE(e.fechaInicio) AND DATE(e.fechaInicio) <= DATE('{$hasta}') {$filter} ORDER BY e.fechaInicio ASC");
        return $eventos;
    }
    public static function getAllEventsByUserAdmin($idUser, $desde, $hasta){
        return DB::getAll(
            "SELECT 
                u.*,  
                e.*
            FROM 
                eventos e, 
                usuarios u 
            WHERE 
                e.idUsuario = u.idUsuario AND 
                (u.tipo != 0 OR u.idUsuario = {$idUser}) AND 
                DATE('{$desde}') <= DATE(e.fechaInicio) AND 
                DATE(e.fechaInicio) <= DATE('{$hasta}') AND 
                e.eliminado = 0
            ORDER BY 
                e.fechaInicio ASC
        ");
    }
}