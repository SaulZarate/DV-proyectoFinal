<?
require_once __DIR__ . "/../config/includes.php";

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, PATCH, DELETE');

$request = new Request();

// Valido que sea del tipo json
if($request->method != "GET") HTTPController::validateHeaderContentType();












HTTPController::response($request);