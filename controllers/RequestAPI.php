<?

class RequestAPI {

    public $uri = null;
    public $method = null;
    public $headers = null;
    public $endpoint = [];
    public $params = [];
    public $body = null;

    public function __construct() {
        $this->uri = $_SERVER["REQUEST_URI"];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->headers = (object) getallheaders();
        
        $basePath = URI_BASE.'api/';
        $path = str_replace($basePath, '', $this->uri);
        $path = strtok($path, '?');

        $this->endpoint = explode('/', trim($path, '/')); // Divide la ruta en partes
        $this->params = $_GET;
        $this->body = json_decode(file_get_contents('php://input'), JSON_UNESCAPED_UNICODE);
    }
}
