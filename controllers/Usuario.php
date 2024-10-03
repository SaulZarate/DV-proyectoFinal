<?


class Usuario{

    public static function getById($id){
        return DB::getOne("SELECT * FROM usuarios WHERE idUsuario = {$id}");
    }

    public static function getAll($withUserLogged = true){
        $whereUser = $withUserLogged ? "" : " AND idUsuario != {$_SESSION['user']['idUsuario']}";
        return DB::getAll("SELECT * FROM usuarios WHERE eliminado = 0 {$whereUser}");
    }

}