<?
require_once __DIR__ . "/../config/includes.php";
require_once "./init.request.php";

// Valido que sea del tipo json
if($request->method != "GET") HTTPController::validateHeaderContentType();












HTTPController::response($request);