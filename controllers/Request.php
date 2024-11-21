<?

class Request {

    public $uri = null;
    public $method = null;
    public $headers = null;
    public $body = null;

    public function __construct() {
        $this->uri = $_SERVER["REQUEST_URI"];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->headers = getallheaders();
        $this->body = array_merge($_GET, json_decode(file_get_contents('php://input'), JSON_UNESCAPED_UNICODE));
    }
}
