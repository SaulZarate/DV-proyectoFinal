<?

class Auth{
    public static function isLogged(){
        return isset($_SESSION["user"]) && $_SESSION["user"];
    }
}