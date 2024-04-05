(function (Drupal, once, drupalSettings) {
  Drupal.behaviors.storesMapBehavior = {
    attach(context) {
      once('storesMapBehavior', '.leaflet__map', context).forEach(
        function (element) {
          const displayId = element.getAttribute('data-display-id');
          const mapstores = drupalSettings.mapstores[displayId];
          const coordinates = drupalSettings.coordinates[displayId];
          const color = mapstores.color;
          const size = mapstores.size;
          const zoom = mapstores.zoom;
          const map = L.map(element).setView([48.01552, 37.888863], zoom);
          L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
          }).addTo(map);

          const myIcon = L.icon({
            iconUrl: '/modules/custom/stores/icons/shit.svg',
            iconSize: [50, 81],
          });

          const marker = L.marker([48.01552, 37.888863], {icon: myIcon}).addTo(map);
          marker.bindPopup('Макіївський роднічок');

          coordinates.forEach(function (coord) {
            const latitude = coord.location[0].lat;
            const longitude = coord.location[0].lon;
            let circle = L.circleMarker([latitude, longitude], {
              radius: size,
              color: color,
            }).addTo(map);

            circle.bindPopup(coord.title);
          })
        }
      )
    }
  }
}(Drupal, once, drupalSettings))
