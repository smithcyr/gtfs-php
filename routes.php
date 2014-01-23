<?php

include("util.php");

$routes = get_routes();

if($routes) {
  $data = "route_id,agency_id,route_short_name,route_long_name,route_type,route_url\n";

  $data .= $routes;

  $file = file_put_contents('build/bin/routes.txt', $data);
}

function get_routes() {
  $uri = 'http://api.ebongo.org/routelist';

  $xml_request = file_get_contents($uri);

  $xml = simplexml_load_string($xml_request);

  if($xml) {
    $data = "";

    foreach ($xml->route as $route) {
      debug($route);
      $route_id = $route->tag;
      $route_name = $route->name;
      $agency = $route->agency;
      debug($agency);

      // Route ID/
      $data .= $route_id . ',';
      // Route Agency.
      $data .= $agency . ',';
      // Route Short Name.
      $data .= ',';
      // Route Long Name.
      $data .= $route_name . ',';
      // Route Type. All buses.
      $data .= 3 . ',';
      // Route URL.
      $data .= "http://ebongo.org/route/" . $route_id . "\n";

    }

    return $data;
  }
}


?>
