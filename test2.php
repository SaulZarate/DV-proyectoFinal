<?
require_once __DIR__ . "/config/init.php";
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Add Search Box to a Web Map</title>
    <!-- Default styling. Feel free to remove! -->
    <link href="https://api.mapbox.com/mapbox-assembly/v1.3.0/assembly.min.css" rel="stylesheet">
    <script id="search-js" defer="" src="https://api.mapbox.com/search-js/v1.0.0-beta.22/web.js"></script>
</head>

<body>
    <!-- Load Mapbox GL JS -->
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>

    <div id="map" style="position: absolute; width: 100%; height: 100%"></div>
    <script>
        const ACCESS_TOKEN = "pk.eyJ1Ijoic2F1bC16YXJhdGUtZGF2aW5jaS05NyIsImEiOiJjbTJud2FjbXkwN3E3MmtvcWw0bXBlb2xnIn0.Fdo8ldAFVVnOAwzJtW5-IQ"
        let geocoder = null
        let marker = null

        // TO MAKE THE MAP APPEAR YOU MUST
        // ADD YOUR ACCESS TOKEN FROM
        // https://account.mapbox.com
        mapboxgl.accessToken = ACCESS_TOKEN;
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v12',
            center: [-58.44475890709006, -34.61455610933701],
            zoom: 13,
            language: 'es',
        });

        const searchJS = document.getElementById('search-js');
        searchJS.onload = function() {
            geocoder = new MapboxGeocoder();
            geocoder.accessToken = ACCESS_TOKEN;
            geocoder.options = {
                proximity: [-73.99209, 40.68933],
                language: 'es',
                country: 'AR',
            };
            geocoder.marker = true;
            geocoder.mapboxgl = mapboxgl;

            map.addControl(geocoder);

        };

        // Evento de clic en el mapa
        var clickInMarker = false
        map.on('click', (e) => {

            // Por defecto en el mapa
            let coordinates = e.lngLat;

            // Si ya existe un marcador en el mapa, lo removemos
            if (marker){
                marker.remove();
                marker = null;
            }

            if(clickInMarker){
                clickInMarker = false
                return
            }


            /* ----------------------------- */
            /*          MARKER NOMBRES       */
            /* ----------------------------- */
            const features = map.queryRenderedFeatures(e.point, {
                layers: ['poi-label'] // Especificamos la capa de puntos de interés con nombres
            });

            if (features.length > 0) {
                const place = features[0];
                const coords = place.geometry.coordinates
                const name = place.properties.name
                coordinates = coords

                // Nombre del lugar con la lat y long
                console.log(`Lugar seleccionado: ${name}\nLatitud: ${coords[1]}, Longitud: ${coords[0]}`);

                /* // Por ejemplo, aquí podríamos resaltar el lugar seleccionado o realizar alguna otra acción
                console.log(place.properties); // Muestra todas las propiedades del lugar */
            }
            
            // Creamos un nuevo marcador en la ubicación seleccionada
            marker = new mapboxgl.Marker().setLngLat(coordinates).addTo(map);

            marker.getElement().addEventListener('click', () => {
                // Reiniciamos la variable para que pueda añadirse un nuevo marcador en el próximo click
                if (marker){
                    marker.remove();
                    marker = null;
                }

                // Para eliminarlo
                clickInMarker = true
            });
            
            // Opcionalmente, puedes agregar un popup para mostrar las coordenadas o información adicional
            marker.setPopup().togglePopup();
            
        });
    </script>

</body>

</html>