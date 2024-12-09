<?
require_once __DIR__ . "/../../config/init.php";

$coordenadas = array();
foreach (Recorrido::getAllTramos($_GET["idRecorrido"]) as $tramo) {
    if ($tramo->tipo != "P") continue;
    $coordenadas[] = $tramo;
}
$coordenadas = json_encode($coordenadas, JSON_UNESCAPED_UNICODE);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title></title>
    <link href="https://api.mapbox.com/mapbox-assembly/v1.3.0/assembly.min.css" rel="stylesheet">
    <script id="search-js" defer="" src="https://api.mapbox.com/search-js/v1.0.0-beta.22/web.js"></script>
    <style>
        .contentMarker .title{
            font-size: 1.5em;
        }
        .contentMarker .description{
            font-size: 1.2em;
        }
        .contentMarker .pax{
            border-top: 1px solid #ddd;
            font-size: 1.2em;
            text-transform: uppercase;
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- Load Mapbox GL JS -->
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>

    <div id="map" style="position: absolute; width: 100%; height: 100%"></div>

    <script type="text/javascript">
        const ACCESS_TOKEN = "<?= MAPBOX_TOKEN ?>";
        const coordenadas = JSON.parse('<?= $coordenadas ?>');
        console.log(coordenadas)

        // Creamos el mapa
        mapboxgl.accessToken = ACCESS_TOKEN;
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v12',
            center: [coordenadas[0].longitud, coordenadas[0].latitud],
            zoom: <?= $_GET["zoom"] ?? 10 ?>,
            language: 'es',
        });

        // Creamos un nuevo marcador en la ubicación seleccionada
        for (const itemCoordenada of coordenadas) {
            let marker = new mapboxgl.Marker()
            .setLngLat({
                lat: itemCoordenada.latitud,
                lng: itemCoordenada.longitud,
            }).setPopup(
                new mapboxgl.Popup({
                    offset: 25,
                    closeButton: false
                }) // add popups
                .setHTML(
                    `<div class="contentMarker">
                        <h2 class="title">${itemCoordenada.nombre}</h2>
                        <p class="direction">${itemCoordenada.direccion}</p>
                        <p class="pax">${itemCoordenada.totalPasajeros} pasajero${itemCoordenada.totalPasajeros == 1 ? "" : "s"}</p>
                    </div>`
                )
            ).addTo(map);
            
        }
    </script>

</body>

</html>