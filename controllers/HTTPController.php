<?

class HTTPController{

    public static function validateHeaderContentType($type = "application/json", $return = false){
        $requestHeader = getallheaders();

        $isValid = strpos($requestHeader["Content-Type"] ?? "", $type) !== false;

        if($return) return $isValid;

        if(!$isValid){
            self::response(array(
                "status" => "ERROR_REQUEST",
                "title" => "Formato de petición invalida!", 
                "message" => "Solo se permiten peticiones del tipo: ".$type, 
                "type" => "warning"
            ));
        }
    }

    public static function getCurrentURL(){
        return (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    public static function response($data, $type = "json"){

        if($data === 401){
            http_response_code(401);
            $data = array(
                "status" => "SIN_PERMISOS",
                "content" => [
                    "message" => "No tienes permisos de realizar la acción!"
                ]
            );
        }

        if($data === 404){
            http_response_code(404);
            $data = array(
                "status" => "RECURSO_NO_ENCONTRADO",
                "content" => [
                    "message" => "endpoint no encontrado, revise la documentación y verifique su endpoint!"
                ]
            );
        }

        if($data === 500){
            http_response_code(500);
            $data = array(
                "status" => "ERROR_EN_EL_SERVIDOR",
                "content" => [
                    "message" => "error en el sistema!"
                ]
            );
        }

        if($type == "json" || !$type){
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
        header("Location: ".DOMAIN_NAME."404");
        die();
    }

    public static function get401($withButtonBack=true){
        $btn = $withButtonBack ? "?btn=1": "";
        http_response_code(401);
        header("Location: ".DOMAIN_NAME."401{$btn}");
        die();
    }

    public static function get500(){
        http_response_code(500);
        header("Location: ".DOMAIN_NAME."500");
        die();
    }
}