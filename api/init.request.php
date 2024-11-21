<?
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, PATCH, DELETE');

$request = new stdClass();
$request->uri = $_SERVER["REQUEST_URI"];
$request->method = $_SERVER['REQUEST_METHOD'];
$request->headers = getallheaders();
$request->body = array_merge($_GET, json_decode(file_get_contents('php://input'), JSON_UNESCAPED_UNICODE));

