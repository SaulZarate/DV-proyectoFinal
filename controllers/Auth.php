<?

class Auth{
    public static function isLogged(){
        return isset($_SESSION["user"]) && $_SESSION["user"];
    }

    public static function getRoleName(){
        switch ($_SESSION["user"]["tipo"]) {
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