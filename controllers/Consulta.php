<?


class Consulta{

    public static function getById($id){
        return DB::getOne("SELECT * FROM consultas WHERE idConsulta = {$id}");
    }

    public static function getAllByIdClient($idCliente){
        return DB::getAll("SELECT * FROM consultas WHERE idCliente = {$idCliente} AND eliminado = 0");
    }
}