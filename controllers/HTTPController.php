<?

class HTTPController{

    public static function getCurrenURL(){
        return (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    public static function response($data, $type = "json"){
        if($type == "json"){
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
            header("Content-Type: application/json; charset=utf-8");
        }
        die($data);
    }

    public static function getLoginAdmin(){
        header("Location: ".DOMAIN_ADMIN);
        die();
    }


    public static function get404(){
        http_response_code(404);
        header("Location: ".DOMAIN_NAME."404.php");
        die();
    }

    public static function get401(){
        http_response_code(401);
        header("Location: ".DOMAIN_NAME."401.php");
        die();
    }

    public static function get500(){
        http_response_code(500);
        header("Location: ".DOMAIN_NAME."500.php");
        die();
    }
}