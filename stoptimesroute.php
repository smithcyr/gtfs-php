<?php

function get_route_stop_times($agency, $route) {
  $uri = 'http://webservices.nextbus.com/service/xmlFeed?command=schedule&a=';
  $uri .= $agency;
  $uri .= '&r=';
  $uri .= $route;

  $xml_request = file_get_contents($uri);

  $xml = simplexml_load_string($xml_request);

  if($xml->route) {
    $data = "";
    $trip_count = 1;

    $tag_array = get_tags($agency, $route);

    foreach ($xml->route->tr as $index => $trip) {
      $sequence = 10;
      foreach ($trip->stop as $stop) {

        //echo '<pre>';
        //print_r($stop);
        //echo '</pre>';
        $tag = htmlentities((string) $stop['tag']);
        if(array_key_exists($tag, $tag_array)) {
          $tag = $tag_array[$tag];
        }
        $time = htmlentities((string) $stop);
        if($tag && $time != "--") {
          $data .= $agency . '_' . $route . '_' . $trip_count . ',';
          $data .= $time . ',';
          $data .= $time . ',';
          $data .= $tag . ',';
          $data .= $sequence . "\n";

          $sequence = $sequence + 10;
        }

      }
      $trip_count = $trip_count + 1;

    }
    return $data;
  }
}

/**
 * Create a tags to stopid array.
 * @param  [string] $agency
 * @param  [string] $route
 * @return [array]
 */
function get_tags($agency, $route) {
  $uri = 'http://webservices.nextbus.com/service/xmlFeed?command=routeConfig&a=';
  $uri .= $agency;
  $uri .= '&r=';
  $uri .= $route;

  $xml_request = file_get_contents($uri);

  $xml = simplexml_load_string($xml_request);

  $tag_array = array();

  if($xml->route){
    foreach ($xml->route->stop as $stop) {
      $tag = htmlentities((string) $stop['tag']);
      $stopId = htmlentities((string) $stop['stopId']);

      $tag_array[$tag] = $stopId;

    }
    return $tag_array;
  }
}

?>
