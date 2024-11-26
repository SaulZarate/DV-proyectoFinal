<? 

if (!function_exists('getallheaders')) {
    function getallheaders() {
        $headers = [];
        
        if(isset($_SERVER["CONTENT_TYPE"])) $headers["Content-Type"] = $_SERVER["CONTENT_TYPE"];

        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}