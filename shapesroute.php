<?php

function get_shape($agency, $route_id) {
  $uri = 'http://api.ebongo.org/route';

  $uri .= '?agency=' . $agency;

  $uri .= '&route=' . $route_id;

  $xml_request = file_get_contents($uri);

  $xml = simplexml_load_string($xml_request);

  if($xml) {
    $data = array();

    //debug($xml);
    $i = 0;
    foreach ($xml->paths->path as $key => $path) {
      //debug($path);
      foreach ($path->point as $point) {
        $lat = htmlentities((string) $point['lat']);
        $lon = htmlentities((string) $point['lng']);
        //debug($lat . ", " . $lng);
        $point_array = array();
        $point_array['shape_id'] = "shape_" . $route_id;
        $point_array['shape_pt_lat'] = $lat;
        $point_array['shape_pt_lon'] = $lon;
        $point_array['shape_pt_sequence'] = $i;
        $i = $i + 1;
        $data[] = $point_array;
      }
    }
    debug($route_id);
    return $data;
  }
}


?>
