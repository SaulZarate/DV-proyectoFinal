<? 

if (!function_exists('getallheaders')) {
    function getallheaders() {
        $headers = [];
        
        if(isset($_SERVER["CONTENT_TYPE"])) $headers["Content-Type"] = $_SERVER["CONTENT_TYPE"];
        if(!isset($_SERVER["Authorization"]) && isset($_SERVER["PHP_AUTH_USER"]) && isset($_SERVER["PHP_AUTH_PW"])) $headers["Authorization"] = "Basic ".base64_encode($_SERVER["PHP_AUTH_USER"].":".$_SERVER["PHP_AUTH_PW"]);

        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}