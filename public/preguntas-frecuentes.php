<? 
require_once __DIR__ . "/../config/init.php"; 
$title = "Preguntas frecuentes";

$preguntasFrecuentes = Util::arrayToObject(array(
    array(
        "title" => "¿Porqué elegirnos?",
        "content" => "Somos una empresa de viajes y turismo (EVT) inscripta en la secretaría de turismo de la nación como Ascenso 23 EVT lEG: 16396 - dISP 1532/15, dedicada pura y exlusivamente al turismo deportivo, alternativo y de montaña desde hace más de 15 años. "
    ),
    array(
        "title" => "¿Los guías son profesionales?",
        "content" => "Todos/as nuestros guías son matriculados, profesionales y certificados con título oficial, tanto de montaña, de turismo aventura, de mountain bike o de trekking en cordillera. Poseen certificaciones correspondientes en primeros auxilios en zonas agrestes y capacitaciones en evacuaciones y rescates técnicos. Por eso te sugerimos que antes de contratar un tour, salida, aventura o similares, siempre consultes si vas a ir con guías profesionales matriculados (certificados con título oficial) y con una empresa inscripta y regulada. "
    ),
    array(
        "title" => "¿Las habitaciones son a compartir?",
        "content" => "NO. Todas las Habitaciones de ".APP_NAME." (dobles en adelante) son exclusivas para el número de pasajeros que reserva."
    ),
    array(
        "title" => "¿Viajando solo/a es el mismo precio?",
        "content" => "SI. En este caso ".APP_NAME." no opera hab singles. Viajando solo/a es habitación a compartir con otros pasajeros de la empresa del mismo sexo. ",
    ),
    array(
        "title" => "¿Los menores pagan lo mismo?",
        "content" => "Menores de 3 años (inclusive) acompañados por al menos 2 mayores NO abonan.<br>Menores con 4 años cumplidos en adelante abonan como adulto.",
    ),
    array(
        "title" => "¿Qué empresas de Micros utiliza ".APP_NAME."?",
        "content" => "Todas flotas de primera línea: Masterbus | Cita | Marcopolo | Ciucio | Cachi | Transporte del Plata … etc",
    ),
    array(
        "title" => "¿Aceptan dietas especiales: vegetarianos, veganos, etc?",
        "content" => "SI. Debés informarlas al momento de reservar.",
    ),
    array(
        "title" => "¿Si quiero pagar en efectivo donde pago?",
        "content" => "Una vez recibidos los datos solicitados para reservar, emitimos una CONFIRMACION DE RESERVA para que pases por nuestras Oficinas.",
    ),
    array(
        "title" => "".APP_NAME." está registrada en el Ministerio de Turismo, es una empresa habilitada?",
        "content" => "SI. Buscanos como TRADE MARK ".APP_NAME." | Legajo 15972",
    ),
    array(
        "title" => "¿Qué debo hacer si no viene el traslado a buscarme?",
        "content" => "Antes que nada, es muy importante tener presente que USTED debe buscar al trasladista y no EL a usted, ya que no lo conoce, solo tiene un cartel con su nombre, salen miles de pasajeros de los aeropuertos y hay solo algunos guías con carteles. Busque con calma su nombre o el logo de la empresa, a veces se encuentran tapados por pasajeros, familiares, otros carteles, negocios, etc. Si usted está viajando a países no hispanos y mas aun en aquellos donde la escritura no es alfabética, puede suceder que su  nombre en el cartel esté mal escrito, busque algo familiar o parecido. Una vez más, el pasajero debe ser PRO-ACTIVO en la búsqueda del traslado, el error más común es no moverse de la salida y esperar a que alguien lo encuentre. En caso de no hacer contacto busque en sus vouchers el teléfono del operador local para llamar y organizar el traslado. Este paso es imprescindible antes de tomar un taxi o algún otro tipo de movilidad hacia su hotel.",
    ),
    array(
        "title" => "¿Cual es la mejor fecha para viajar",
        "content" => "Esto va a depender del destino de su viaje, pero hay lineamientos generales que puede tener en cuenta al momento de elegir la fecha. Siempre será mejor viajar en una temporada intermedia para Argentina y para el destino. Será más conveniente por una cuestión económica, porque las temporadas intermedias ofrecen climas más moderados y por ultimo y no menos importante, porque no habrá tanta gente en los sitios a visitar, evitando demoras, tráfico, colas, etc. En nuestra sección de Informacion de destinos van a encontrar sugerencias en cuanto a temporadas de los destinos que trabajamos. ",
    ),
    array(
        "title" => "Seguros de cancelación",
        "content" => "Dado que al momento de contratar un viaje es imposible preveer si existirá alguna causa relevante que le impida realizar el viaje y a fin de contemplar todos los gastos que pudieran surgir por pérdidas de tickets aéreos, noches de hotel canceladas…. Recomendamos emitir  SEGUROS DE CANCELACIÒN para poder recuperar los gastos retenidos en conceptos de penalidad.<br><br>
        Este seguro debe ser contratado en el momento de realizar sus reservaciones!<br><br>
        Los seguros de cancelación pueden ser TODA CAUSA o ANY REASONS. Los toda causa tienen un listado de posibles causas que pueden impedir su viaje por fuerza mayor y solo cubren esos items.<br><br>
        Los any reasons cubren todas las causas.<br><br>
        <br>En todos los casos se debe informar que no realizará el viaje al menos 24 horas de la fecha de salida"
    ),
));

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <? require_once PATH_SERVER . "public/head.php"; ?>
</head>

<body class="<?=$title?>">

  <? require_once PATH_SERVER . "public/nav.php"; ?>

  <main class="container mt-3 mb-4">
    <h1 class="fw-bold fs-2 text-primary mb-3">Preguntas frecuentes</h1>

    <section class="accordion accordion-flush border" id="accordionPreguntasFrecuentes">
        <? foreach ($preguntasFrecuentes as $index => $pregunta): ?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading<?=$index?>">
                    <button class="accordion-button collapsed fw-bold text-6" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?=$index?>" aria-expanded="false" aria-controls="collapse<?=$index?>">
                        <?=$index+1?>. <?=$pregunta->title?>
                    </button>
                </h2>
                <div id="collapse<?=$index?>" class="accordion-collapse collapse" aria-labelledby="heading<?=$index?>" data-bs-parent="#accordionPreguntasFrecuentes">
                    <div class="accordion-body">
                        <p class="text-6"><?=$pregunta->content?></p>
                    </div>
                </div>
            </div>
        <? endforeach; ?>
    </section>

  </main>

  <? require_once PATH_SERVER . "public/footer.php"; ?>
  <? require_once PATH_SERVER . "public/script.php"; ?>
</body>
</html>