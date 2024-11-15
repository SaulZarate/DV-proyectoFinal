<? 
require_once __DIR__ . "/../config/init.php"; 

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');

// Valido el formato para peticiones que no son por GET
if($_SERVER['REQUEST_METHOD'] != "GET") HTTPController::validateHeaderContentType("multipart/form-data");

// Se printean al final del código
$requestHeader = getallheaders();
$requestBody = ["data" => $_REQUEST, "files" => $_FILES];
$addicional = array();



// Login del administrador => /admin
if($_REQUEST["action"] == "login"){
    
    $response = array(
        "status"    => "ERROR",
        "title"     => "Datos incompletos!", 
        "message"   => "",
        "type"      => "warning",
        "request"   => $_REQUEST
    );

    // Datos incompletos
    if(!$_REQUEST["email"] || !$_REQUEST["password"]) HTTPController::response($response);
    
    $_REQUEST["email"] = strtolower($_REQUEST["email"]);

    // Busco el email en los usuarios
    $result = DB::getOne("SELECT * FROM usuarios WHERE email = '{$_REQUEST['email']}' AND eliminado = 0");
    $passToSha1 = sha1($_REQUEST["password"]);

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
if($_REQUEST["action"] == "logout"){
    
    unset($_SESSION["user"]);
    session_destroy();

    HTTPController::response(array(
        "status" => "OK",
        "redirection" => DOMAIN_ADMIN
    ));
}


/* --------------------------------- */
/*                                   */
/*               GENERAL             */
/*                                   */
/* --------------------------------- */
if($_REQUEST["action"] == "delete"){
    $result = DB::update($_REQUEST["table"], ["eliminado" => 1], "{$_REQUEST['pk']} = {$_REQUEST[$_REQUEST['pk']]}");

    HTTPController::response(array(
        "status" => $result ? "OK" : "ERROR",
        "title" => $result ? "Eliminado correctamente!" : "Error!",
        "message" => $result ? "" : "Vuelva a intentarlo más tarde o contacte con soporte.", 
        "type" => $result ? "success" : "warning"
    ));
}
if($_REQUEST["action"] == "hardDelete"){
    $result = DB::delete($_REQUEST["table"], "{$_REQUEST['pk']} = {$_REQUEST[$_REQUEST['pk']]}");

    HTTPController::response(array(
        "status" => $result ? "OK" : "ERROR",
        "title" => $result ? "Eliminado!" : "Error!",
        "message" => $result ? "" : "Vuelva a intentarlo más tarde o contacte con soporte.", 
        "type" => $result ? "success" : "warning"
    ));
}
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


/* --------------------------------- */
/*                                   */
/*               USUARIO             */
/*                                   */
/* --------------------------------- */
if($_REQUEST["action"] == "usuario_update"){
    unset($_REQUEST["action"]);
    unset($_REQUEST["table"]);
    unset($_REQUEST["pk"]);

    $_REQUEST["descripcion"] = htmlentities($_REQUEST["descripcion"]);

    $_REQUEST['email'] = strtolower($_REQUEST['email']);

    // Valido que el email sea único
    if(DB::select("usuarios", "idUsuario", "email = '{$_REQUEST['email']}' AND eliminado = 0 AND idUsuario != {$_SESSION['user']['idUsuario']}")){
        HTTPController::response(array(
            "status"    => "ERROR",
            "title"     => "Email existente!",
            "message"   => "El email ingresado esta siendo utilizado por otro usuario, cambielo y vuelva a intentarlo.",
            "type"      => "warning"
        ));
    }
    
    // Valido que el telefono sea único
    if(DB::select("usuarios", "idUsuario", "CONCAT(codPais, codArea, telefono) = '{$_REQUEST['codPais']}{$_REQUEST['codArea']}{$_REQUEST['telefono']}' AND eliminado = 0 AND idUsuario != {$_SESSION['user']['idUsuario']}")){
        HTTPController::response(array(
            "status"    => "ERROR",
            "title"     => "Teléfono existente!",
            "message"   => "El teléfono ingresado esta siendo utilizado por otro usuario, cambielo y vuelva a intentarlo.",
            "type"      => "warning"
        ));
    }


    if(DB::update("usuarios", $_REQUEST, "idUsuario = {$_SESSION['user']['idUsuario']}")){
        // Actualiza la variable de sessión
        $_SESSION["user"] = array_merge($_SESSION["user"], $_REQUEST);

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
if($_REQUEST["action"] == "usuario_changePassword"){

    if(DB::update("usuarios", ["password" => sha1($_REQUEST["newPassword"])], "idUsuario = {$_SESSION['user']['idUsuario']}")){
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
if($_REQUEST["action"] == "usuario_delete"){

    if(DB::update("usuarios", ["eliminado" => 1], "idUsuario = {$_REQUEST['idUsuario']}")){
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
if($_REQUEST["action"] == "usuario_save"){
    
    $ignore = ["action"];
    $_REQUEST['email'] = strtolower($_REQUEST['email']);
    if($_REQUEST["password"]){
        $_REQUEST["password"] = sha1($_REQUEST["password"]);
    }else{
        $ignore[] = "password";
    }

    $whereIdUser = $_REQUEST["idUsuario"] ? " AND idUsuario != {$_REQUEST['idUsuario']}" : "";
    
    // Valido que el email sea único
    if(DB::select("usuarios", "idUsuario", "email = '{$_REQUEST['email']}' AND eliminado = 0 {$whereIdUser}")){
        HTTPController::response(array(
            "status"    => "ERROR",
            "title"     => "Email existente!",
            "message"   => "El email ingresado esta siendo utilizado por otro usuario, cambielo y vuelva a intentarlo.",
            "type"      => "warning"
        ));
    }
    
    // Valido que el telefono sea único
    /* if(DB::select("usuarios", "idUsuario", "CONCAT(codPais, codArea, telefono) = '{$_REQUEST['codPais']}{$_REQUEST['codArea']}{$_REQUEST['telefono']}' AND eliminado = 0 {$whereIdUser}")){
        HTTPController::response(array(
            "status"    => "ERROR",
            "title"     => "Teléfono existente!",
            "message"   => "El teléfono ingresado esta siendo utilizado por otro usuario, cambielo y vuelva a intentarlo.",
            "type"      => "warning"
        ));
    } */

    $idUser = DB::save($_REQUEST, $ignore);

    HTTPController::response(array(
        "status"    => "OK",
        "title"     => "Usuario " . (!!$_REQUEST["idUsuario"] ? "modificado!" : "creado!"),
        "message"   => "",
        "type"      => "success",
        "idUsuario" => $idUser
    ));
}
if($_REQUEST["action"] == "usuario_consulta_getAll"){ // GET
    HTTPController::response(array(
        "results" => Usuario::getAll(["where" => "estado = 'A' AND tipo IN (0,1)", "order" => "nombre ASC, apellido ASC"])
    ));
}
if($_REQUEST["action"] == "usuario_topVendedores"){
    HTTPController::response(Consulta::getTopVendedores($_REQUEST["min"], $_REQUEST["max"]));
}



/* --------------------------------- */
/*                                   */
/*               PAQUETE             */
/*                                   */
/* --------------------------------- */
if($_REQUEST["action"] == "paquete_save"){

    $isNew = $_REQUEST["idPaquete"] == "";
    $ignore = ["action", "fechasSalida"];

    $_REQUEST["traslado"] = $_REQUEST["traslado"] == "on" ? 1 : 0;
    if(!$_REQUEST["noches"]) $_REQUEST["noches"] = 0;

    // Nueva excursión
    if($isNew){
        // Valido que suba las imagenes
        if(!$_FILES["image"]["name"] || !$_FILES["banner"]["name"]){
            HTTPController::response(array(
                "status" => "ERROR",
                "title" => "Faltan imagenes!", 
                "message" => "La imagen principal y el banner son obligatorios, seleccione los archivos para continuar.",
                "type" => "warning"
            ));
        }
        
        // Valido que haya fechas de salidas
        if(!$_REQUEST["fechasSalida"]){
            HTTPController::response(array(
                "status" => "ERROR",
                "title" => "Sin fechas de salidas!", 
                "message" => "Indique la o las fechas de salidas de la excursión y vuelva a intentarlo",
                "type" => "warning"
            ));
        }

    }

    // Guarda/Actualiza
    $idPaquete = DB::save($_REQUEST, $ignore);

    // NUEVO PAQUETE
    if($isNew){ 
        
        // Valido y subo las imagenes
        $updatePaquete = array();

        // Guardo la imagen principal
        $fileImagePrincipal = new FileController($_FILES["image"], "paquetes/{$idPaquete}", array("typeValidate" => "image"));
        $resultUploadImage = $fileImagePrincipal->save();
        
        if($resultUploadImage["status"] != "OK"){
            // Elimino el paquete
            DB::delete($_REQUEST["table"], "{$_REQUEST[$_REQUEST['pk']]} = {$idPaquete}");

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
            DB::delete($_REQUEST["table"], "{$_REQUEST['pk']} = {$idPaquete}");

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
        DB::update($_REQUEST["table"], $updatePaquete, "{$_REQUEST['pk']} = {$idPaquete}");
    }else{

        // Fechas de salida
        if($_REQUEST["fechasSalida"]){

            
            
            $sqlInsertFechas = array();
            $fechasInsert = array();
            $_REQUEST["fechasSalida"] = str_replace("/", "-", $_REQUEST["fechasSalida"]);
            foreach (explode(",", $_REQUEST["fechasSalida"]) as $fecha) {
                $nuevaFecha = date("Y-m-d", strtotime($fecha));
                $fechasInsert[] = "'{$nuevaFecha}'";
                $sqlInsertFechas[] = [$idPaquete, $nuevaFecha];
            }

            // Valido que no haya fechas repetidas
            if($fechasDuplicadas = DB::getOne("SELECT GROUP_CONCAT(DATE_FORMAT(fecha, '%d/%m/%Y')) as fechas FROM paquetes_fechas_salida WHERE idPaquete = {$idPaquete} AND fecha IN (" . implode(",",$fechasInsert) . ") GROUP BY fecha")){
                HTTPController::response(array(
                    "status" => "ERROR_FECHAS_SALIDA_DUPLICADA",
                    "title" => "Fechas repetidas!", 
                    "message" => "Las siguientes fechas ya fueron agregadas con anterioridad, quitelas y vuelva a intentarlo:<br>{$fechasDuplicadas->fechas}", 
                    "type" => "warning"
                ));
            }

            // Guardo las fechas
            DB::insertMult("paquetes_fechas_salida", ["idPaquete", "fecha"], $sqlInsertFechas);
        }
    }

    HTTPController::response(array(
        "status" => "OK",
        "title" => $isNew ? "Excursión creada!" : "Cambios guardados!", 
        "message" => "", 
        "type" => "success", 
        "idPaquete" => $idPaquete
    ));

}
if($_REQUEST["action"] == "paquete_deleteFechaSalida"){
    if(Paquete::deleteFechaSalida($_REQUEST["idPaquete"], $_REQUEST["fecha"])){
        $response = array(
            "status" => "OK", 
            "title" => "Fecha eliminada!",
            "message" => "", 
            "type" => "success"
        );
    }else{
        $response = array(
            "status" => "ERROR", 
            "title" => "Error",
            "message" => "No pudimos eliminar la fecha seleccionada, intentelo más tarde o contacte a soporte", 
            "type" => "warning"
        );
    }
    HTTPController::response($response);
}
if($_REQUEST["action"] == "paquete_uploadImagenBanner"){

    $idPaquete = $_REQUEST[$_REQUEST['pk']];

    $dataPaquete = DB::getOne("SELECT {$_REQUEST['destino']} FROM {$_REQUEST['table']} WHERE {$_REQUEST['pk']} = {$idPaquete}");

    // Guardo la imagen principal
    $fileImagePrincipal = new FileController($_FILES["image"], "paquetes/{$idPaquete}", array("typeValidate" => "image"));
    $resultUpload = $fileImagePrincipal->save();
    
    if($resultUpload["status"] != "OK"){
        HTTPController::response(array(
            "status" => "ERROR_UPLOAD",
            "title" => "Error con la imagen principal!", 
            "message" => $resultUpload["error"]["message"], 
            "type" => "warning"
        ));
    }else{
        // Elimino el archivo
        if(file_exists(PATH_SERVER.($dataPaquete->{$_REQUEST['destino']}))){
            unlink(PATH_SERVER.($dataPaquete->{$_REQUEST['destino']}));
        }
    }
    
    // Actualizo el path de la imagen en la tabla
    DB::update(
        $_REQUEST["table"], 
        array("{$_REQUEST['destino']}" => "{$resultUpload['path']}"), 
        "{$_REQUEST['pk']} = {$idPaquete}"
    );


    HTTPController::response(array(
        "status" => "OK",
        "title" => "Imagen modificado!", 
        "message" => "", 
        "type" => "success"
    ));

}
if($_REQUEST["action"] == "paquete_delete"){
    Paquete::delete($_REQUEST["idPaquete"]);
    HTTPController::response(array(
        "status" => "OK", 
        "title" => "Excursión eliminada!", 
        "message" => "", 
        "type" => "success"
    ));
}
// Textos grandes
if($_REQUEST["action"] == "paquete_updateText"){
    if(trim($_REQUEST["texto"]) == "<p></p>") $_REQUEST["texto"] = "";

    DB::update("paquetes", ["{$_REQUEST["tipo"]}" => $_REQUEST["texto"]], "idPaquete = {$_REQUEST['idPaquete']}");

    HTTPController::response(array(
        "status" => "OK", 
        "title" => "Texto modificado!",
        "message" => "", 
        "type" => "success"
    ));
}
// Galería
if($_REQUEST["action"] == "paquete_uploadsGalery"){
    $errors = array();
    $sqlInsert = array();

    foreach ($_FILES as $file) {
        $file["name"] = explode(":", $file["name"])[0];
        
        $file = new FileController($file, "paquetes/{$_REQUEST['idPaquete']}/galeria");
        $result = $file->save();

        if($result["status"] != "OK"){
            $errors[] = $result["error"]["fileName"];
        }else{
            $sqlInsert[] = [$_REQUEST["idPaquete"], $result["path"]];
        }
    }
    
    if(count($_FILES) == count($errors)){
        HTTPController::response(array(
            "status" => "ERROR",
            "title" => "Error!",
            "message" => "No se pudo guardar ningún archivo, por favor vuelva a intentarlo más tarde o contacte a soporte",
            "type" => "warning"
        ));
    }

    // Guardo en la base de datos los paths de los archivos
    DB::insertMult("paquetes_galeria", ["idPaquete", "path"], $sqlInsert);

    $response = array(
        "status" => "OK",
        "title" => "Archivos subidos a la galería!",
        "message" => "",
        "type" => "success"
    );

    if($errors){
        $response["title"] = "Archivos subidos!";
        $response["message"] = "No pudimos subir los siguientes archivos: " . implode(", ", $errors) . ". <br>Intente más tarde o contacte a soporte.";
        $response["type"] = "info";
    }
    
    HTTPController::response($response);

}
if($_REQUEST["action"] == "paquete_deleteArchivoGalery"){
    Paquete::deleteFileGalery($_REQUEST["idFile"]);
    HTTPController::response(array(
        "status" => "OK", 
        "title" => "Archivo eliminado!", 
        "message" => "", 
        "type" => "success"
    ));
}
if($_REQUEST["action"] == "paquete_setOrderGalery"){
    
    foreach (explode(",", $_REQUEST["orderGalery"]) as $index => $idPaqueteGalery) {
        DB::update("paquetes_galeria", ["orden" => $index+1], "id = {$idPaqueteGalery}");
    }
    
    HTTPController::response(array(
        "status" => "OK", 
        "title" => "Orden modificado!", 
        "message" => "", 
        "type" => "success"
    ));
}
if($_REQUEST["action"] == "paquete_getAllFechaDisponibles"){ // GET
    $fechas = $_REQUEST["id"] ? Paquete::getAllFechasDisponibles($_REQUEST["id"], date("Y-m-d")) : array();

    HTTPController::response(array(
        "fechas" => $fechas
    ));
}



/* --------------------------------- */
/*                                   */
/*               CLIENTE             */
/*                                   */
/* --------------------------------- */
if($_REQUEST["action"] == "cliente_save"){

    $ignore = ["action"];
    
    if($_REQUEST["email"]) $_REQUEST["email"] = strtolower($_REQUEST["email"]);
    if($_REQUEST["password"]) {
        $_REQUEST["password"] = sha1($_REQUEST["password"]);
    }else{
        $ignore[] = "password";
    }

    // Valido que el email sea único
    $whereIdClient = $_REQUEST["idCliente"] ? " AND idCliente != {$_REQUEST['idCliente']}" : "";
    if(DB::select("clientes", "idCliente", "email = '{$_REQUEST['email']}' AND eliminado = 0 {$whereIdClient}")){
        HTTPController::response(array(
            "status"    => "ERROR",
            "title"     => "Email existente!",
            "message"   => "El email ingresado esta siendo utilizado por otro usuario, cambielo y vuelva a intentarlo.",
            "type"      => "warning"
        ));
    }

    /* if(DB::select("clientes", "idCliente", "CONCAT(codPais, codArea, telefono) = '{$_REQUEST['codPais']}{$_REQUEST['codArea']}{$_REQUEST['telefono']}' AND eliminado = 0 {$whereIdClient}")){
        HTTPController::response(array(
            "status"    => "ERROR",
            "title"     => "Teléfono existente!",
            "message"   => "El teléfono ingresado esta siendo utilizado por otro usuario, cambielo y vuelva a intentarlo.",
            "type"      => "warning"
        ));
    } */

    $result = DB::save($_REQUEST, $ignore);
    
    HTTPController::response(array(
        "status" => "OK",
        "title" => !!$_REQUEST["idCliente"] ? "Cliente creado!" : "Cambios guardados!",
        "message" => "",
        "type" => "success",
        "idCliente" => $result
    ));
}
if($_REQUEST["action"] == "cliente_consulta_getAll"){ // GET
    HTTPController::response(array(
        "results" => Cliente::getAll(["where" => "estado = 'A'", "order" => "nombre ASC, apellido ASC"])
    ));
}


/* ------------------------- */
/*                           */
/*          CALENDARIO       */
/*                           */
/* ------------------------- */
if($_REQUEST["action"] == "calendario_create"){
    $_REQUEST["titulo"] = trim($_REQUEST["titulo"]);
    $_REQUEST["descripcion"] = trim($_REQUEST["descripcion"]);

    if(!$_REQUEST["titulo"] || !$_REQUEST["horaDesde"] || !$_REQUEST["horaHasta"] || !$_REQUEST["rangoEvento"] || !$_REQUEST["descripcion"]){
        HTTPController::response(array(
            "status" => "ERROR_DATOS_INCOMPLETOS",
            "title" => "Campos incompletos!",
            "message" => "Revise todos los campos y vuelva a intentarlo o contacte a soporte.",
            "type" => "warning"
        ));
    }

    $rangoFechas = explode(",", str_replace("/", "-", $_REQUEST["rangoEvento"]));

    $_REQUEST["fechaInicio"] = date("Y-m-d", strtotime($rangoFechas[0])) . " " . $_REQUEST["horaDesde"].":00";
    $_REQUEST["fechaFin"] = date("Y-m-d", strtotime($rangoFechas[0])) . " " . $_REQUEST["horaHasta"].":00";
    if(count($rangoFechas) == 2) $_REQUEST["fechaFin"] = date("Y-m-d", strtotime($rangoFechas[1])) . " " . $_REQUEST["horaHasta"].":00";
    

    $idEvent = DB::save($_REQUEST, ["action", "rangoEvento", "horaDesde", "horaHasta"]);

    HTTPController::response(array(
        "status" => "OK", 
        "title" => "Evento creado!",
        "message" => "", 
        "type" => "success",
        "idEvent" => $idEvent
    ));
}
if($_REQUEST["action"] == "calendar_events"){
    $events = array();

    $eventsDB = Auth::isAdmin() ? Calendario::getAllEventsByUserAdmin($_SESSION["user"]["idUsuario"], $_REQUEST["start"], $_REQUEST["end"]) : Calendario::getAllEventsByUser($_SESSION["user"]["idUsuario"], $_REQUEST["start"], $_REQUEST["end"]);

    foreach ($eventsDB as $event) {

        $event->titulo = ucfirst($event->titulo);
        $event->descripcion = html_entity_decode($event->descripcion);
        
        // Convierto la duración en string
        $stringDuracion = array();
        $objDiffDuracion = (new DateTime($event->fechaFin))->diff(new DateTime($event->fechaInicio));
        if($duracionDias = $objDiffDuracion->d) $stringDuracion[] = $duracionDias == 1 ? "1 día" : $duracionDias." días";
        if($duracionHoras = $objDiffDuracion->h) $stringDuracion[] = $duracionHoras == 1 ? "1 hora" : $duracionHoras." horas";
        if($duracionMinutos = $objDiffDuracion->i) $stringDuracion[] = $duracionMinutos." min.";
        $event->duracion = array(
            "str" => implode(", ", $stringDuracion),
            "milisegundos" => ($duracionDias*24*60*60) + ($duracionHoras*60*60) + ($duracionMinutos*60)
        );

        // Formateo las fechas
        $fechaInicioInArray = explode(" ", (date("d/m/Y H:i", strtotime($event->fechaInicio))));
        $fechaFinInArray = explode(" ", (date("d/m/Y H:i", strtotime($event->fechaFin))));
        $event->fechasFormateadas = array(
            "desde" => array(
                "fecha" => $fechaInicioInArray[0],
                "hora" => $fechaInicioInArray[1],
                "full" => implode(" ", $fechaInicioInArray),
                "origin" => $event->fechaInicio
            ),
            "hasta" => array(
                "fecha" => $fechaFinInArray[0],
                "hora" => $fechaFinInArray[1],
                "full" => implode(" ", $fechaFinInArray),
                "origin" => $event->fechaFin
            )
        );


        $events[] = array(
            "id" => $event->idEvento,
            "start" => $event->fechaInicio,
            "end" => $event->fechaFin,
            "title" => $event->titulo,
            "classNames" => (Auth::isAdmin() && $_SESSION["user"]["idUsuario"] == $event->idUsuario) || !Auth::isAdmin() ? ["bg-primary", "text-white"] : ["bg-success", "text-white"],
            "editable" => true,
            "extendedProps" => $event
        );
    }

    HTTPController::response($events);
}


/* ---------------------------- */
/*                              */
/*          ALOJAMIENTOS        */
/*                              */
/* ---------------------------- */
if($_REQUEST["action"] == "alojamiento_consulta_getAll"){ // GET
    HTTPController::response(array(
        "results" => Alojamiento::getAll(["order" => "nombre ASC"])
    ));
}


/* ------------------------ */
/*                          */
/*          ORIGENES        */
/*                          */
/* ------------------------ */
if($_REQUEST["action"] == "origen_consulta_getAll"){ // GET
    HTTPController::response(array(
        "results" => Origen::getAll(["where" => "estado = 'A'", "order" => "nombre ASC"])
    ));
}


/* ------------------------ */
/*                          */
/*          CONSULTA        */
/*                          */
/* ------------------------ */
if($_REQUEST["action"] == "consulta_create"){

    $idConsulta = DB::save($_POST, ["pax", "contactoAdicional", "action"]);
    
    // Agrego los datos de contacto adicional
    if(isset($_POST["contactoAdicional"]) && $_POST["contactoAdicional"]["descripcion"]){
        $contactos = array();
        for ($i=0; $i < count($_POST["contactoAdicional"]["descripcion"]); $i++) { 
            $contactos[] = [$idConsulta, $_POST["contactoAdicional"]["descripcion"][$i], $_POST["contactoAdicional"]["contacto"][$i]];
        }
        DB::insertMult("consulta_contacto_adicional", ["idConsulta", "descripcion", "contacto"], $contactos);
    }

    // Agrego los pax
    if(isset($_POST["pax"]) && $_POST["pax"]["nombre"]){
        $pax = array();
        for ($j=0; $j < count($_POST["pax"]["nombre"]); $j++) { 
            $pax[] = [$idConsulta, $_POST["pax"]["nombre"][$j], $_POST["pax"]["apellido"][$j], $_POST["pax"]["sexo"][$j], $_POST["pax"]["fechaDeNacimiento"][$j], ($_POST["pax"]["observaciones"][$j] ?? "")];
        }
        $addicional = $pax;
        DB::insertMult("consulta_pasajeros", ["idConsulta", "nombre", "apellido", "sexo", "fechaDeNacimiento", "observaciones"], $pax);
    }

    HTTPController::response(array(
        "status" => "OK", 
        "title" => "Consulta creada!", 
        "message" => "", 
        "type" => "success", 
        "idConsulta" => $idConsulta
    ));
}
if($_REQUEST["action"] == "consulta_reporteVenta"){ // GET
    $results = array();

    $filtroUsuario = "";

    if(!Auth::isAdmin()) $filtroUsuario = " AND idUsuario = {$_SESSION['user']['idUsuario']}";

    foreach (DB::getAll("SELECT * FROM consultas WHERE eliminado = 0 AND DATE(updated_at) = '{$_REQUEST["fecha"]}' {$filtroUsuario} ORDER BY updated_at ASC") as $consulta) {
        @$results[date("Y-m-d H:i", strtotime($consulta->updated_at))][$consulta->estado] += 1;
    }

    HTTPController::response(array(
        "data" => $results
    ));
}
if($_REQUEST["action"] == "consulta_detalle_cambioDeEstado"){

    // Valido la disponibilidad
    if($_REQUEST["estado"] == "V"){
        $pasajeros = Consulta::getAllPasajeros($_REQUEST["idConsulta"]);
        $totalPasajeros = count($pasajeros);

        if($totalPasajeros == 0){
            HTTPController::response(array(
                "status" => "SIN_PASAJEROS", 
                "title" => "Sin pasajeros!",
                "message" => "Debe agregar por lo menos un pasajero.", 
                "type" => "warning"
            ));
        }

        $consulta = Consulta::getAllInfo($_REQUEST["idConsulta"]);
        $cuposDisponibles = Paquete::getCuposDisponibles($consulta->idPaquete, $consulta->fechaSalida);

        if($cuposDisponibles + $totalPasajeros > $consulta->paquete->capacidad){
            HTTPController::response(array(
                "status" => "SIN_CUPOS", 
                "title" => "Sin disponibilidad!",
                "message" => "La fecha seleccionada no tiene suficientes cupos para la cantidad de pasajeros agregados. Cupos disponibles: ".$cuposDisponibles, 
                "type" => "warning"
            ));
        }
    }

    $pk = DB::save($_REQUEST, ["action", "response_title", "response_message", "consulta_detalle_cambioDeEstado"]);
    HTTPController::response(array(
        "status" => "OK",
        "title" => isset($_REQUEST["response_title"]) ? $_REQUEST["response_title"] : "Guardado!",
        "message" => isset($_REQUEST["response_message"]) ? $_REQUEST["response_message"] : "",
        "type" => "success",
        "pk" => $pk
    ));
}

/* ------------------------ */
/*                          */
/*         RECORRIDOS       */
/*                          */
/* ------------------------ */
if($_REQUEST["action"] == "recorrido_isGuiaDisponible"){ // GET
    $response["status"] = "OK";
    
    $filtroRecorrido = $_REQUEST["idRecorrido"] ? " AND idRecorrido != {$_REQUEST['idRecorrido']}" : "";
    if(Recorrido::getAll(["where" => "idUsuario = {$_REQUEST['idUsuario']} AND fecha = '{$_REQUEST['fecha']}' {$filtroRecorrido}"])) $response["status"] = "NO_DISPONIBLE";

    HTTPController::response($response);
}
if($_REQUEST["action"] == "recorrido_save"){

    // Si es un recorrido nuevo valido que tenga consultas vendidas
    if(!$_REQUEST["idRecorrido"] && !Consulta::getAllByPaqueteAndFecha($_REQUEST["idPaquete"], $_REQUEST["fecha"])){
        HTTPController::response(array(
            "status" => "SIN_CONSULTAS_VENDIDAS", 
            "title" => "Fecha del paquete sin ventas!", 
            "message" => "No puede armar el recorrido hasta que haya por lo menos una venta", 
            "type" => "warning"
        ));
    }

    $pk = DB::save($_REQUEST, ["action", "response_title", "response_message"]);

    // Nuevo
    if(!$_REQUEST["idRecorrido"]) DB::update("paquetes_fechas_salida", ["hasRecorrido" => 1], "idPaquete = {$_REQUEST["idPaquete"]} AND fecha = '{$_REQUEST["fecha"]}'");

    Recorrido::update($pk);

    HTTPController::response(array(
        "status" => "OK",
        "title" => $_REQUEST["idRecorrido"] ? "Guardado!" : "Recorrido creado!",
        "message" => "",
        "type" => "success",
        "pk" => $pk
    ));
}
if($_REQUEST["action"] == "recorrido_delete"){
    $dataRecorrido = Recorrido::getById($_REQUEST["idRecorrido"]);

    // Elimino el recorrido
    $result = DB::delete($_REQUEST["table"], "{$_REQUEST['pk']} = {$_REQUEST[$_REQUEST['pk']]}");

    // Habilito la fecha 
    DB::update("paquetes_fechas_salida", ["hasRecorrido" => 0], "idPaquete = {$dataRecorrido->idPaquete} AND fecha = '{$dataRecorrido->fecha}'");
    
    HTTPController::response(array(
        "status" => $result ? "OK" : "ERROR",
        "title" => $result ? "Eliminado!" : "Error!",
        "message" => $result ? "" : "Vuelva a intentarlo más tarde o contacte con soporte.", 
        "type" => $result ? "success" : "warning"
    ));
}
if($_REQUEST["action"] == "recorrido_update"){
    Recorrido::update($_REQUEST["idRecorrido"]);
    HTTPController::response(array(
        "status" => "OK",
        "title" => "Recorrido actualizado!",
        "message" => "", 
        "type" => "success"
    ));
}



Util::printVar(["header" => $requestHeader, "body" => $requestBody, "printData" => $addicional]);