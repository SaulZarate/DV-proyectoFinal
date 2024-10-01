<? 
require_once __DIR__ . "/../config/init.php"; 


/*      Recibir FormData | NO un JSON       */


/* Util::printVar([$_REQUEST, $_FILES]); */

// Login del administrador => /admin
if($_POST["action"] == "login"){
    
    $response = array(
        "status"    => "ERROR",
        "title"     => "Datos incompletos!", 
        "message"   => "",
        "type"      => "warning",
        "request"   => $_POST
    );

    // Datos incompletos
    if(!$_POST["email"] || !$_POST["password"]) HTTPController::response($response);

    // Busco el email en los usuarios
    $result = Database::getOne("SELECT * FROM usuarios WHERE email = '{$_POST['email']}' AND eliminado = 0 AND estado = 'A'");
    $passToSha1 = sha1($_POST["password"]);

    // Email no encontrado
    if(!$result){
        $response["title"] = "Email no encontrado!";
        $response["message"] = "Revise el email ingresado y vuelva a intentarlo";
        HTTPController::response($response);
    }

    // Contraseña incorrecta
    if($passToSha1 != $result->password){
        $response["title"] = "Contraseña incorrecta!";
        HTTPController::response($response);
    }

    $_SESSION["user"] = $result;

    HTTPController::response(array(
        "status" => "OK",
        "redirection" => DOMAIN_NAME."admin/dashboard"
    ));
}

// Logout
if($_POST["action"] == "logout"){
    
    unset($_SESSION["user"]);
    session_destroy();

    HTTPController::response(array(
        "status" => "OK",
        "redirection" => DOMAIN_NAME."admin"
    ));
}
