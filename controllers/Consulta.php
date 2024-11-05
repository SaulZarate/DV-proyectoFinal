<?


class Consulta{

    public static function getById($id){
        return DB::getOne("SELECT * FROM consultas WHERE idConsulta = {$id}");
    }

    public static function getAllByIdClient($idCliente){
        return DB::getAll("SELECT * FROM consultas WHERE idCliente = {$idCliente} AND eliminado = 0");
    }

    public static function getAllDatosDeContactoAdicional($idConsulta){
        return DB::getAll("SELECT * FROM consulta_contacto_adicional  WHERE idConsulta = {$idConsulta}");
    }

    public static function getAllPasajeros($idConsulta){
        return DB::getAll("SELECT * FROM consulta_pasajeros  WHERE idConsulta = {$idConsulta}");
    }

    public static function getAllInfo($idConsulta){
        $consulta = DB::getOne(
            "SELECT 
                c.*,
                ps.fecha as fechaSalida
            FROM 
                consultas c,
                paquetes_fechas_salida ps
            WHERE 
                c.idConsulta = {$idConsulta} AND 
                c.idPaqueteFechaSalida = ps.id
        ");

        $consulta->contactosAdicionales = self::getAllDatosDeContactoAdicional($idConsulta);
        $consulta->pasajeros = self::getAllPasajeros($idConsulta);
        
        $consulta->cliente = Cliente::getById($consulta->idCliente);
        $consulta->asignado = Usuario::getById($consulta->idUsuario);
        $consulta->paquete = Paquete::getAllInfo($consulta->idPaquete);

        return $consulta;
    }
}