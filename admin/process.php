<? 
require_once __DIR__ . "/../config/init.php"; 


/*      Recibir FormData | NO un JSON       */


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
    $result = DB::getOne("SELECT * FROM usuarios WHERE email = '{$_POST['email']}' AND eliminado = 0");
    $passToSha1 = sha1($_POST["password"]);

    // Email no encontrado
    if(!$result){
        $response["title"] = "Email no encontrado!";
        $response["message"] = "Revise el email ingresado y vuelva a intentarlo";
        HTTPController::response($response);
    }

    // Valido el estado
    if($result->estado != 'A'){
        $response["title"] = "Usuario inhabilitado!";
        $response["message"] = "Contacte con un administrador para consultar el problema";
        HTTPController::response($response);
    }


    // Contraseña incorrecta
    if($passToSha1 != $result->password){
        $response["title"] = "Contraseña incorrecta!";
        HTTPController::response($response);
    }

    $_SESSION["user"] = (array) $result;

    HTTPController::response(array(
        "status" => "OK",
        "redirection" => DOMAIN_NAME."admin/dashboard/"
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

if($_POST["action"] == "savePerfil"){
    unset($_POST["action"]);

    $_SESSION["user"] = array_merge($_SESSION["user"], $_POST);

    if(DB::update("usuarios", $_POST, "idUsuario = {$_SESSION['user']['idUsuario']}")){
        $response = array(
            "status" => "OK", 
            "title" => "Datos modificados!", 
            "message" => "", 
            "type" => "success"
        );
    }else{
        $response = array(
            "status" => "Error", 
            "title" => "Lo sentimos!", 
            "message" => "Ocurrio un error, vuelva a intentarlo o contacte con soporte", 
            "type" => "danger"
        );
    }

    HTTPController::response($response);
}

if($_POST["action"] == "changePassword"){

    if(DB::update("usuarios", ["password" => sha1($_POST["newPassword"])], "idUsuario = {$_SESSION['user']['idUsuario']}")){
        $response = array(
            "status" => "OK", 
            "title" => "Contraseña modificada!", 
            "message" => "", 
            "type" => "success"
        );
    }else{
        $response = array(
            "status" => "Error", 
            "title" => "Lo sentimos!", 
            "message" => "Ocurrio un error, vuelva a intentarlo o contacte con soporte", 
            "type" => "danger"
        );
    }

    HTTPController::response($response);
}


Util::printVar([$_REQUEST, $_FILES]);

