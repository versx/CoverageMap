<?php include './config.php'; ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title><?=$config['title']?></title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>
  </head>
  <body><div id="mapid" style="top: 0; left: 0; position: absolute; height: 100%; width: 100%;"></div></body>
</html>
<script>
// Map
var map = L.map('mapid').setView([<?=$config['startLat']?>, <?=$config['startLon']?>], <?=$config['startZoom']?>);
L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
  minZoom: <?=$config['minZoom']?> || 10,
  maxZoom: <?=$config['maxZoom']?> || 18,
  id: 'mapbox.<?=$config['mapStyle']?>'
}).addTo(map);

// Information
var info = L.control();
info.onAdd = function (map) {
  this._div = L.DomUtil.create('div', 'info');
  this.update();
  return this._div;
};
info.update = function (props) {
  this._div.innerHTML = '<h4>Area Selected:</h4>' + (props ? 
    '<b>' + props.name + '</b> (' + props.size + ' km<sup>2</sup>)' :
    'Hover over a city');
};
info.addTo(map);

// Legend
var geojson;
var areas = <?=json_encode($config['areas'])?>;
var legend = L.control({position: 'topright'});
legend.onAdd = function (map) {
  var div = L.DomUtil.create('div', 'info legend');
  var html = '';
  html += '<span><b>' + areas.length + ' total cities</b></span><hr>';
  for (var i = 0; i < areas.length; i++) {
    var area = areas[i];
    var color = area.color || getRandomColor();
    var polygon = L.polygon(area.polygons, {
      fillColor: color,
      weight: 0.5,
      color: 'black'
    });
    var size = 0;
    var latLngs = polygon.getLatLngs();
    if (latLngs.length > 0) {
      var areaSize = geodesicArea(latLngs[0]);
      size = convertAreaToSqkm(areaSize);
    }
    var properties = {
      name: area.city,
      color: color,
      size: size.toFixed(2)
    };

    var polygonGeoJson = polygon.toGeoJSON(properties);
    geojson = L.geoJson(polygonGeoJson, {
		  style: style,
		  onEachFeature: function (feature, layer) {
        feature.properties = properties;
        layer.on({
          mouseover: highlightFeature,
          mouseout: resetHighlight,
          click: zoomToFeature
        });
      }
	  }).addTo(map);
    geojson.setStyle({
      weight: 2,
      opacity: 1,
      color: 'white',
      dashArray: '3',
      fillOpacity: 0.7,
      fillColor: properties.color
    });
    html += '<span>&ndash; ' + area.city + '</span><br>';

  }
  div.innerHTML += html;
  return div;
};
legend.addTo(map);

function style(feature) {
  return {
    weight: 2,
    opacity: 1,
    color: 'white',
    dashArray: '3',
    fillOpacity: 0.7,
    fillColor: feature.properties.color
	};
}

function highlightFeature(e) {
  var layer = e.target;
  layer.setStyle({
    weight: 4,
    color: '#666',
    dashArray: '',
    fillOpacity: 0.7
  });
  if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
    layer.bringToFront();
  }
  info.update(layer.feature.properties);
}

function resetHighlight(e) {
  geojson.resetStyle(e.target);
  info.update();
}

function zoomToFeature(e) {
  map.fitBounds(e.target.getBounds());
}

function geodesicArea(latLngs) {
  var pointsCount = latLngs.length,
    area = 0.0,
    d2r = Math.PI / 180,
    p1, p2;
  if (pointsCount > 2) {
    for (var i = 0; i < pointsCount; i++) {
      p1 = latLngs[i];
      p2 = latLngs[(i + 1) % pointsCount];
      area += ((p2.lng - p1.lng) * d2r) *
          (2 + Math.sin(p1.lat * d2r) + Math.sin(p2.lat * d2r));
    }
    area = area * 6378137.0 * 6378137.0 / 2.0;
  }

  return Math.abs(area);
}

function convertAreaToSqkm(value) {
  return value * 1.0E-6;
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
<style>
.legend {
  text-align: left;
  line-height: 18px;
  background: gray;
  color: darkgray;
}
.legend span {
  color: black;
  width: 18px;
  height: 18px;
  margin-right: 8px;
  opacity: 0.7;
}
.info { 
  padding: 6px 8px; 
  font: 14px/16px Arial, Helvetica, sans-serif; 
  background: white; 
  background: rgba(255,255,255,0.8); 
  box-shadow: 0 0 15px rgba(0,0,0,0.2); 
  border-radius: 5px;
}
.info h4 {
  margin: 0 0 5px;
  color: #777;
}
</style>