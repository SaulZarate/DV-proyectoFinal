<?

class Auth{
    public static function isLogged(){
        return isset($_SESSION["user"]) && $_SESSION["user"];
    }
    public static function isAdmin(){
        return self::isLogged() && $_SESSION["user"]["tipo"] === 0;
    }
    public static function isVendedor(){
        return self::isLogged() && $_SESSION["user"]["tipo"] === 1;
    }
    public static function isGuia(){
        return self::isLogged() && $_SESSION["user"]["tipo"] === 1;
    }

    public static function getRoleName($tipo = ""){
        if(!$tipo) $tipo = $_SESSION["user"]["tipo"];
        switch ($tipo) {
            case '0':
                $name = "administrador";
                break;

            case '1':
                $name = "vendedor";
                break;

            case '2':
                $name = "guia";
                break;
            
            default:
                $name = "-|-";
                break;
        }
        
        return $name;
    }
}