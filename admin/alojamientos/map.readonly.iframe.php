<? require_once __DIR__."/../../config/constants.php"; ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title></title>
    <link href="https://api.mapbox.com/mapbox-assembly/v1.3.0/assembly.min.css" rel="stylesheet">
    <script id="search-js" defer="" src="https://api.mapbox.com/search-js/v1.0.0-beta.22/web.js"></script>
</head>

<body>
    <!-- Load Mapbox GL JS -->
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>

    <div id="map" style="position: absolute; width: 100%; height: 100%"></div>

    <script type="text/javascript">
        const ACCESS_TOKEN = "<?=MAPBOX_TOKEN?>";
        const coordinates = {
            lat: <?=$_GET["latitud"]?>,
            lng: <?=$_GET["longitud"]?>
        }

        // Creamos el mapa
        mapboxgl.accessToken = ACCESS_TOKEN;
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v12',
            center: [<?=$_GET["longitud"]?>, <?=$_GET["latitud"]?>],
            zoom: 13,
            language: 'es',
        });

        // Creamos un nuevo marcador en la ubicación seleccionada
        let marker = new mapboxgl.Marker().setLngLat(coordinates).addTo(map);
        marker.setPopup().togglePopup();
    </script>

</body>

</html>