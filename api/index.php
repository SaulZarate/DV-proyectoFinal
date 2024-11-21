<?
require_once __DIR__ . "/../config/includes.php";

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, PATCH, DELETE');

$request = new RequestAPI();

// Validación de segmentos mínimos (2 => version y modulo)
if(count($request->endpoint) < 1) HTTPController::response(404);

// Extraigo los principales datos del endpoint
$version = $request->endpoint[0];
$modulo = "/".($request->endpoint[1] ?? "");
$id = $request->endpoint[2] ?? "";


// Valido la versión
if($version != "v1") HTTPController::response(["status" => "VERSION_INCORRECTA", "content" => ["message" => "Versión no reconocida, revise la documentación y vuelva a intentarlo!"]]);

// Valido que sea del tipo json
if($request->method != "GET") HTTPController::validateHeaderContentType();



/* ----------------------------- */
/*          AUTENTICACIÓN        */
/* ----------------------------- */
// Valido el tipo de autenticación
[$typeAuth, $token] = explode(" ", $request->headers->Authorization);
if($typeAuth !== "Basic") HTTPController::response(["status" => "ERROR", "content" => ["message" => "Tipo de autenticación incorrecto. Solo se permite el tipo 'Basic'"]]);
if(!trim($token)) HTTPController::response(["status" => "SIN_TOKEN", "content" => ["message" => "Envíe el token para poder loguearse de forma correcta"]]);

// Valido el usuario
[$email, $pass] = explode(":", base64_decode($token));
$user = Usuario::getByEmail(strtolower($email));
if(!$user) HTTPController::response(["status" => "EMAIL_NO_ENCONTRADO", "content" => ["message" => "El email ingresado no se encuentra registrado en nuestro sistema"]]);
if($user->password !== sha1($pass)) HTTPController::response(["status" => "CONTRASEÑA_INCORRECTA", "content" => ["message" => "El email está registrado en nuestro sistema pero la contraseña no es correcta."]]);
if($user->estado != "A") HTTPController::response(["status" => "USUARIO_INACTIVO", "content" => ["message" => "Lo sentimos, su usuario se encuentra inactivo en el sistema. Contacte con el administrador para habilitarlo nuevamente"]]);



/* ------------------------ */
/*                          */
/*          ROUTER          */
/*                          */
/* ------------------------ */
if($request->method === "GET"){



}

if($request->method === "POST"){

    if($modulo === "/login" && !$id) $response = $user;

}








// Response
if(isset($response) && $response) HTTPController::response($response);


// 404
HTTPController::response(404);