<?php

include('stoptimesroute.php');

$data_coralville = "";
$data_iowacity = "";
$data_uiowa = "";

$uri = 'http://api.ebongo.org/routelist';

$xml_request = file_get_contents($uri);

$xml = simplexml_load_string($xml_request);

if ($xml->route) {
  foreach ($xml->route as $route) {
    $agency = $route->agency;
    $tag = $route->tag;
    echo $agency . ": " . $tag . "\n";
    $route_data = get_route_stop_times($agency, $tag);

    switch ($agency) {
      case 'coralville':
        $data_coralville .= $route_data;
        break;
      case 'iowa-city':
        $data_iowacity .= $route_data;
        break;
      case 'uiowa':
        $data_uiowa .= $route_data;
        break;
      default:
        # code...
        break;
    }
  }

  echo '<pre>';
  print_r($data);
  echo '</pre>';
  $file = file_put_contents('exports/coralville_stop_times.txt', $data_coralville);
  $file = file_put_contents('exports/iowacity_stop_times.txt', $data_iowacity);
  $file = file_put_contents('exports/uiowa_stop_times.txt', $data_uiowa);
}



?>
