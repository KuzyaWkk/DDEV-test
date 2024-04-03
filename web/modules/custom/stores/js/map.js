(function (Drupal, once, drupalSettings) {
  Drupal.behaviors.storesMapBehavior = {
    attach(context) {
      once('storesMapBehavior', '#map', context).forEach(
        function () {
          const mapstores = drupalSettings.mapstores;
          const coordinates = drupalSettings.coordinates;
          const color = mapstores.color;
          const size = mapstores.size;
          const zoom = mapstores.zoom;
          const map = L.map('map').setView([48.01552, 37.888863], zoom);
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
             let circle = L.circleMarker([coord.latitude, coord.longitude], {
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
