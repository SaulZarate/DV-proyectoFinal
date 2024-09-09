<?

class HTTPController{
    public static function response($data, $type = "json"){
        if($type == "json") header("Content-Type: application/json; charset=utf-8");
        die($data);
    }

    public static function get404(){
        http_response_code(404);
        header("Location: ".DOMAIN_NAME."404.php");
        die();
    }

    public static function get500(){
        http_response_code(500);
        header("Location: ".DOMAIN_NAME."500.php");
        die();
    }
}