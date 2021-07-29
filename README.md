# CoverageMap  

## Preview  
![image](https://user-images.githubusercontent.com/1327440/127419381-dc8a7dcb-7190-469e-9766-f700e614aea2.png)


## Prerequisites  
- PHP 5 or higher  

## Installation  
1. Clone repository  
1. Copy config file `cp config.example.php config.php`  
1. Fill out `config.php`  
1. Copy areas file `cp areas.example.json areas.json`
1. Fill out `areas.json` with available areas in geojson format  
1. Copy favicon (or replace with own) `cp favicon.example.ico favicon.ico`  

## Notes  
- Omitting geojson properties will result in default values being used.  
- Not specifying a color value in the geojson properties will default to a random color generated.  
