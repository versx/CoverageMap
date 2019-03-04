<?php include './config.php'; ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title><?=$config['title']?></title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>
  </head>
  <body>
    <div id="mapid" style="top: 0; left: 0; position: absolute; height: 100%; width: 100%;"></div>
  </body>
</html>
<script>
var map = L.map('mapid').setView([<?=$config['startLat']?>, <?=$config['startLon']?>], <?=$config['startZoom']?>);

L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
  maxZoom: 18,
  id: 'mapbox.<?=$config['mapStyle']?>'
}).addTo(map);

var areas = <?=json_encode($config['areas'])?>;
for (var i = 0; i < areas.length; i++) {
  var area = areas[i];
  var polygon = L.polygon(area.polygons, {
      fillColor: getRandomColor(),
      weight: 0.5,
      color: "black"
  }).addTo(map);
  polygon.bindPopup(area.city);
}

function getRandomColor() {
  var letters = '0123456789ABCDEF';
  var color = '#';
  for (var i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}
</script>