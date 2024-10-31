<?

require_once __DIR__ . "/config/init.php";

$response["results"] = [
    ["value" => '', "label" => 'Crear cliente'],
    ["value" => 'One', "label" => 'Label One', "customProperties" => ["description" => "asd", "random" => 123]],
    ["value" => 'Two', "label" => 'Label Two'],
    ["value" => 'Three', "label" => 'Label Three'],
];

HTTPController::response($response);