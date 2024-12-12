<?
require_once __DIR__ . "../../config/init.php";

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');

// Valido el formato para peticiones que no son por GET
if ($_SERVER['REQUEST_METHOD'] != "GET") HTTPController::validateHeaderContentType("multipart/form-data");

// Se printean al final del código
$requestHeader = getallheaders();
$requestBody = ["data" => $_REQUEST, "files" => $_FILES];
$addicional = array();


// Generico
if($_REQUEST["action"] == "save"){
    $pk = DB::save($_REQUEST, ["action", "response_title", "response_message"]);
    HTTPController::response(array(
        "status" => "OK",
        "title" => isset($_REQUEST["response_title"]) ? $_REQUEST["response_title"] : "Guardado!",
        "message" => isset($_REQUEST["response_message"]) ? $_REQUEST["response_message"] : "",
        "type" => "success",
        "pk" => $pk
    ));
}

// Login
if ($_REQUEST["action"] == "login") {
    
    $response = array(
        "status"    => "ERROR",
        "title"     => "Datos incompletos!",
        "message"   => "",
        "type"      => "warning",
        "request"   => $_REQUEST
    );

    // Datos incompletos
    if (!$_REQUEST["email"] || !$_REQUEST["password"]) HTTPController::response($response);

    $_REQUEST["email"] = strtolower($_REQUEST["email"]);

    // Busco el email en los usuarios
    $user = DB::getOne("SELECT * FROM usuarios WHERE email = '{$_REQUEST['email']}' AND eliminado = 0");
    $passToSha1 = sha1($_REQUEST["password"]);

    // Email no encontrado
    if (!$user) {
        $response["title"] = "Email no encontrado!";
        $response["message"] = "Revise el email ingresado y vuelva a intentarlo";
        HTTPController::response($response);
    }

    // Valido el estado
    if ($user->estado != 'A') {
        $response["title"] = "Usuario inhabilitado!";
        $response["message"] = "Contacte con un administrador para consultar el problema";
        HTTPController::response($response);
    }

    // Valido el rol (solo guías)
    if ($user->tipo != 2) {
        $response["title"] = "Usuario no permitido!";
        $response["message"] = "Solo se permiten usuarios del tipo Guía en esta aplicación.";
        HTTPController::response($response);
    }


    // Contraseña incorrecta
    if ($passToSha1 != $user->password) {
        $response["title"] = "Contraseña incorrecta!";
        HTTPController::response($response);
    }

    HTTPController::response(array(
        "status" => "OK",
        "user" => $user
    ));
}

// Perfil | edición
if($_REQUEST["action"] == "perfil_edit"){

    // Valido la contraseña.
    // Tiene que tener una minuscula, una mayuscula, un numero, un caracter especial y 8 dígitos
    $password = isset($_REQUEST["password"]) && $_REQUEST["password"] ? $_REQUEST["password"] : null;


    if($password && (strlen($password) < 8 || !preg_match("#[0-9]+#",$password) || !preg_match("#[A-Z]+#",$password) || !preg_match("#[a-z]+#",$password) || !preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST["password"]))){
        HTTPController::response(array(
            "title" => "Contraseña inválida!",
            "message" => "Recuerde que debe tener una mayúscula, una minúscula, un número, un caracter especial y como mínimo 8 dígitos.",
            "type" => "warning"
        ));
    }
    if(!$password) unset($_REQUEST["password"]);

    DB::save($_REQUEST, ["action", "response_title", "response_message"]);
    HTTPController::response(array(
        "status" => "OK",
        "title" => "Perfil actualizado!",
        "message" => "",
        "type" => "success",
        "user" => Usuario::getById($_REQUEST["idUsuario"])
    ));
}

// Recorridos
if($_REQUEST["action"] == "recorrido_salidas"){

    $fechaDeSalida = isset($_REQUEST["fechaSalida"]) && $_REQUEST["fechaSalida"] ? $_REQUEST["fechaSalida"] : "";

    $sqlLimit = $fechaDeSalida ? "" : " LIMIT 10";
    $filtroFecha = $fechaDeSalida ? " AND r.fecha = '{$fechaDeSalida}'" : " AND r.fecha >= DATE(NOW())";

    // Busco las salidas
    $salidas = DB::getAll(
        "SELECT 
            r.*,
            p.imagen,
            p.titulo,
            prov.nombre as provincia,
            p.destino,
            p.horaSalida
        FROM 
            recorridos r,
            paquetes p,
            provincias prov
        WHERE 
            r.idPaquete = p.idPaquete AND 
            p.idProvincia = prov.idProvincia AND 
            r.idUsuario = {$_REQUEST['idUsuario']}
            {$filtroFecha}
        ORDER BY 
            r.idRecorrido
        {$sqlLimit}
    ");

    HTTPController::response(array(
        "status" => "OK",
        "salidas" => $salidas
    ));
}

if($_REQUEST["action"] == "recorrido_getInfo"){
    HTTPController::response(array(
        "recorrido" => Recorrido::getByIdAllInfo($_REQUEST["idRecorrido"])
    ));
}