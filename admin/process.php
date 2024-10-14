<? 
require_once __DIR__ . "/../config/init.php"; 

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');

// Para sobres escribir y visualizar con el Util::printVar que esta al final del codigo
$addicional = null;


/* ----------------------------------------- */
/*                                           */
/*       Recibir FormData | NO un JSON       */
/*                                           */
/* ----------------------------------------- */


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
    
    $_POST["email"] = strtolower($_POST["email"]);

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
        "redirection" => DOMAIN_ADMIN."dashboard/"
    ));
}

// Logout
if($_POST["action"] == "logout"){
    
    unset($_SESSION["user"]);
    session_destroy();

    HTTPController::response(array(
        "status" => "OK",
        "redirection" => DOMAIN_ADMIN
    ));
}

// Perfil | update user
if($_POST["action"] == "usuario_update"){
    unset($_POST["action"]);
    unset($_POST["table"]);
    unset($_POST["pk"]);

    $_POST["descripcion"] = htmlentities($_POST["descripcion"]);

    $_POST['email'] = strtolower($_POST['email']);

    // Valido que el email sea único
    if(DB::select("usuarios", "idUsuario", "email = '{$_POST['email']}' AND eliminado = 0 AND idUsuario != {$_SESSION['user']['idUsuario']}")){
        HTTPController::response(array(
            "status"    => "ERROR",
            "title"     => "Email existente!",
            "message"   => "El email ingresado esta siendo utilizado por otro usuario, cambielo y vuelva a intentarlo.",
            "type"      => "warning"
        ));
    }
    
    // Valido que el telefono sea único
    if(DB::select("usuarios", "idUsuario", "CONCAT(codPais, codArea, telefono) = '{$_POST['codPais']}{$_POST['codArea']}{$_POST['telefono']}' AND eliminado = 0 AND idUsuario != {$_SESSION['user']['idUsuario']}")){
        HTTPController::response(array(
            "status"    => "ERROR",
            "title"     => "Teléfono existente!",
            "message"   => "El teléfono ingresado esta siendo utilizado por otro usuario, cambielo y vuelva a intentarlo.",
            "type"      => "warning"
        ));
    }


    if(DB::update("usuarios", $_POST, "idUsuario = {$_SESSION['user']['idUsuario']}")){
        // Actualiza la variable de sessión
        $_SESSION["user"] = array_merge($_SESSION["user"], $_POST);

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

// Perfil | Change password
if($_POST["action"] == "usuario_changePassword"){

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

if($_POST["action"] == "usuario_delete"){

    if(DB::update("usuarios", ["eliminado" => 1], "idUsuario = {$_POST['idUsuario']}")){
        $response = array(
            "status" => "OK", 
            "title" => "Usuario eliminado!", 
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

if($_POST["action"] == "usuario_save"){
    
    $ignore = ["action"];
    $_POST['email'] = strtolower($_POST['email']);
    if($_POST["password"]){
        $_POST["password"] = sha1($_POST["password"]);
    }else{
        $ignore[] = "password";
    }

    $whereIdUser = $_POST["idUsuario"] ? " AND idUsuario != {$_POST['idUsuario']}" : "";
    
    // Valido que el email sea único
    if(DB::select("usuarios", "idUsuario", "email = '{$_POST['email']}' AND eliminado = 0 {$whereIdUser}")){
        HTTPController::response(array(
            "status"    => "ERROR",
            "title"     => "Email existente!",
            "message"   => "El email ingresado esta siendo utilizado por otro usuario, cambielo y vuelva a intentarlo.",
            "type"      => "warning"
        ));
    }
    
    // Valido que el telefono sea único
    if(DB::select("usuarios", "idUsuario", "CONCAT(codPais, codArea, telefono) = '{$_POST['codPais']}{$_POST['codArea']}{$_POST['telefono']}' AND eliminado = 0 {$whereIdUser}")){
        HTTPController::response(array(
            "status"    => "ERROR",
            "title"     => "Teléfono existente!",
            "message"   => "El teléfono ingresado esta siendo utilizado por otro usuario, cambielo y vuelva a intentarlo.",
            "type"      => "warning"
        ));
    }

    $idUser = DB::save($_POST, $ignore);

    HTTPController::response(array(
        "status"    => "OK",
        "title"     => "Usuario " . ($_POST["idUsuario"] ? "modificado!" : "creado!"),
        "message"   => "",
        "type"      => "success",
        "idUsuario" => $idUser
    ));
}

if($_POST["action"] == "paquete_save"){

    $isNew = $_POST["idPaquete"] == "";
    $ignore = ["action", "fechas"];

    $_POST["traslado"] = $_POST["traslado"] == "on" ? 1 : 0;
    if(!$_POST["noches"]) $_POST["noches"] = 0;

    // Nueva excursión
    // Valido que suba las imagenes
    if($isNew && (!$_FILES["image"]["name"] || !$_FILES["banner"]["name"])){
        HTTPController::response(array(
            "status" => "ERROR",
            "title" => "Faltan imagenes!", 
            "message" => "La imagen principal y el banner son obligatorios, seleccione los archivos para continuar.",
            "type" => "warning"
        ));
    }

    // Guarda/Actualiza
    $idPaquete = DB::save($_POST, $ignore);

    // NUEVO PAQUETE
    if($isNew){ // Valido y subo las imagenes

        $updatePaquete = array();

        // Guardo la imagen principal
        $fileImagePrincipal = new FileController($_FILES["image"], "paquetes/{$idPaquete}", array("typeValidate" => "image"));
        $resultUploadImage = $fileImagePrincipal->save();
        
        if($resultUploadImage["status"] != "OK"){
            // Elimino el paquete
            DB::delete($_POST["table"], "{$_POST[$_POST['pk']]} = {$idPaquete}");

            // Elimino la carpeta 
            FileController::deleteFolder(PATH_SERVER."uploads/paquetes/{$idPaquete}");

            HTTPController::response(array(
                "status" => "ERROR_UPLOAD",
                "title" => "Error con la imagen principal!", 
                "message" => $resultUploadImage["error"]["message"], 
                "type" => "warning", 
                "idPaquete" => $idPaquete
            ));
        }
        $updatePaquete = array_merge($updatePaquete,["imagen" => $resultUploadImage["path"]]);

        // Guardo el banner
        $fileBannerPrincipal = new FileController($_FILES["banner"], "paquetes/{$idPaquete}", array("typeValidate" => "image"));
        $resultUploadBanner = $fileBannerPrincipal->save();

        if($resultUploadBanner["status"] != "OK"){
            // Elimino el paquete
            DB::delete($_POST["table"], "{$_POST['pk']} = {$idPaquete}");

            // Elimino la carpeta 
            FileController::deleteFolder(PATH_SERVER."uploads/paquetes/{$idPaquete}");

            HTTPController::response(array(
                "status" => "ERROR_UPLOAD",
                "title" => "Error con el banner!", 
                "message" => $resultUploadBanner["error"]["message"], 
                "type" => "warning", 
                "idPaquete" => $idPaquete
            ));
        }
        $updatePaquete = array_merge($updatePaquete, ["banner" => $resultUploadBanner["path"]]);

        // Guardo el path de las imagenes en la base de datos
        DB::update($_POST["table"], $updatePaquete, "{$_POST['pk']} = {$idPaquete}");

        $addicional[] = $updatePaquete;

        /* Util::printVar([$resultUploadImage, $resultUploadBanner]); */
    }

    HTTPController::response(array(
        "status" => "OK",
        "title" => $isNew ? "Excursión creada!" : "Cambios guardados!", 
        "message" => "", 
        "type" => "success", 
        "idPaquete" => $idPaquete
    ));

}

Util::printVar([$_REQUEST, $_FILES, $addicional]);