<? require_once __DIR__."/../../config/constants.php"; ?>

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

    <script type="text/javascript">
        var dataMarker = null

        const ACCESS_TOKEN = "<?=MAPBOX_TOKEN?>"
        const dataMarkerDefault = {
            name: '',
            direction: '',
            coordinates: {
                lat: '',
                lng: ''
            }
        }

        var marker = null // Marcador en el mapa
        let clickInMarker = false // Para eliminar un marker
        setDefaultDataMarker()

        // Creamos el mapa
        mapboxgl.accessToken = ACCESS_TOKEN;
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v12',
            center: [-58.44475890709006, -34.61455610933701],
            zoom: 11,
            language: 'es',
        });

        // Agregamos el buscador
        const searchJS = document.getElementById('search-js');
        searchJS.onload = function() {
            searchBox = new MapboxSearchBox();
            searchBox.accessToken = ACCESS_TOKEN;
            searchBox.options = {
                types: 'address,poi,place,neighborhood',
                proximity: [-73.99209, 40.68933]
            };
            searchBox.marker = false;
            searchBox.mapboxgl = mapboxgl;

            // Agrego el buscador al mapa
            map.addControl(searchBox);

            // Ejecuta si se selecciona una opción del buscador
            searchBox.addEventListener('retrieve', (event) => {
                setDefaultDataMarker()
                deleteMarker()

                const place = event.detail.features[0]
                const coords = place.geometry.coordinates
                const coordinates = {
                    lat: coords[1],
                    lng: coords[0]
                }
                const data = place.properties

                dataMarker.coordinates = coordinates
                dataMarker.direction = data.full_address

                // Agrego el marker al mapa
                addMarkerToMap(coordinates)
            });

        };

        // Agregamos el evento
        map.on('click', async (e) => {
            setDefaultDataMarker()
            deleteMarker() // Si existe un marcador en el mapa, lo removemos

            if (clickInMarker) {
                clickInMarker = false
                return
            }

            // Por defecto en el mapa
            let coordinates = e.lngLat;

            // Chequeamos si le hizo click a un label del mapa
            // Especificamos la capa de puntos de interés con nombres
            const features = map.queryRenderedFeatures(e.point, {
                layers: ['poi-label']
            });
            if (features.length > 0) {
                const place = features[0];
                const coords = place.geometry.coordinates
                coordinates = {
                    lat: coords[1],
                    lng: coords[0]
                }
                dataMarker.name = place.properties.name
            }

            // Voy a buscar la dirección usando la api
            const responseAPIMapbox = await fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${coordinates.lng},${coordinates.lat}.json?access_token=${ACCESS_TOKEN}`)
                const response = await  responseAPIMapbox.json()
                if(response.features && response.features.length > 0){
                    const addressCoords = response.features[0].place_name
                    dataMarker.direction = addressCoords
                }

            dataMarker.coordinates = coordinates

            // Agrego el marker al mapa
            addMarkerToMap(coordinates)

            // Elimino el contenido del buscador
            if (searchBox) searchBox.search("")
        });

        function setDefaultDataMarker() {
            dataMarker = {
                ...dataMarkerDefault
            };
        }

        function deleteMarker() {
            if (!marker) return

            marker.remove();
            marker = null;
            
            <? if (isset($_GET["handlerDeleteMarker"]) && $_GET["handlerDeleteMarker"]): ?>
                parent.<?=$_GET["handlerDeleteMarker"]?>(dataMarker)
            <? endif; ?>
        }

        function addMarkerToMap(coordinadas) {
            // Creamos un nuevo marcador en la ubicación seleccionada
            marker = new mapboxgl.Marker().setLngLat(coordinadas).addTo(map);

            // Agregamos el evento click para ser eliminado
            marker.getElement().addEventListener('click', () => {
                deleteMarker() // Seteamos el object marker

                clickInMarker = true // Seteamos para eliminarlo
            });

            // Opcionalmente, puedes agregar un popup para mostrar las coordenadas o información adicional
            marker.setPopup().togglePopup();

            <? if (isset($_GET["handlerNewMarker"]) && $_GET["handlerNewMarker"]): ?>
                parent.<?=$_GET["handlerNewMarker"]?>(dataMarker)
            <? endif; ?>
        }
        
    </script>

</body>

</html>