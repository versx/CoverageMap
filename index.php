<?php include './config.php'; ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title><?=$config['title']?></title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>
    <script src="http://leaflet.github.io/Leaflet.draw/src/ext/GeometryUtil.js"></script>
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

var info = L.control();
info.onAdd = function (map) {
  this._div = L.DomUtil.create('div', 'info');
  this.update();
  return this._div;
};
info.update = function (props) {
  this._div.innerHTML = '<h4>Area Selected:</h4>' + (props ?
    '<b>' + props.name + '</b> ' + props.density + ' m<sup>2</sup>'
    : 'Hover over a city');
};

info.addTo(map);

var geojson;

var areas = <?=json_encode($config['areas'])?>;
var legend = L.control({position: 'topright'});
legend.onAdd = function (map) {
  var div = L.DomUtil.create('div', 'info legend');
  for (var i = 0; i < areas.length; i++) {
    var area = areas[i];
    var color = getRandomColor();
    var polygon = L.polygon(area.polygons, {
        fillColor: color,
        weight: 0.5,
        color: 'black'
    });

/*
    var areaSize = L.GeometryUtil.geodesicArea(polygon.getLatLngs());
    console.log("Area:", areaSize);
*/

    geojson = L.geoJson(polygon.toGeoJSON(), {
		  style: style,
		  onEachFeature: function (feature, layer) {
        feature.properties = {
          name: area.city,
          color: color
        };
        layer.on({
          mouseover: highlightFeature,
          mouseout: resetHighlight,
          click: zoomToFeature
        });
      }
	  }).addTo(map);

    div.innerHTML += '<span>&ndash; ' + area.city + '</span><br>';
  }
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
    fillColor: getRandomColor()
	};
}

function highlightFeature(e) {
  var layer = e.target;
  layer.setStyle({
    weight: 5,
    color: '#666',
    dashArray: '',
    fillOpacity: 0.7
  });

  if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
    layer.bringToFront();
  }

  //console.log(geojson);
  info.update(layer.feature.properties);
}

function resetHighlight(e) {
  geojson.resetStyle(e.target);//.feature.properties.color);
  info.update();
}

function zoomToFeature(e) {
  map.fitBounds(e.target.getBounds());
}

/*
function onEachFeature(feature, layer) {
  layer.on({
    mouseover: highlightFeature,
    mouseout: resetHighlight,
    click: zoomToFeature
  });
}
*/

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