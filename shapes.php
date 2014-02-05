<?php

include("inc/util.php");
include("shapesroute.php");
require ('./vendor/autoload.php');


$shapes = get_shapes();


if($shapes) {

}

function get_shapes() {
  unlink('exports/shapes_initial.csv');
  $shapesWriter = new \EasyCSV\Writer('exports/shapes_initial.csv');
  $shapesWriter->writeRow('shape_id, shape_pt_lat, shape_pt_lon,shape_path_nextbus');

  $uri = 'http://api.ebongo.org/routelist';

  $xml_request = file_get_contents($uri);

  $xml = simplexml_load_string($xml_request);

  if($xml) {
    foreach ($xml->route as $route) {
      //debug($route);
      $route_id = $route->tag;
      $route_name = $route->name;
      $agency = $route->agency;
      //debug($agency);
      //
      $route_shape = get_shape($agency, $route_id);
      $shapesWriter->writeFromArray($route_shape);
    }
  }
}


?>
