<?php

include_once('util.php');
include('stoptimesroute.php');

unlink('exports/trips/coralville_trips.csv');
$tripsCoralvilleWriter = new \EasyCSV\Writer('exports/trips/coralville_trips.csv');

$uri = 'http://api.ebongo.org/routelist';

$xml_request = file_get_contents($uri);

$xml = simplexml_load_string($xml_request);

if ($xml->route) {
  foreach ($xml->route as $route) {
    $agency = $route->agency;
    $tag = $route->tag;

    if($agency == "coralville") {
      $data = get_route_stop_times($agency, $tag);
      if(isset($data['stoptimes'])) {
        unlink('exports/stoptimes/' . $agency . "_" . $tag . '_stoptimes.csv');
        $stopTimesWriter = new \EasyCSV\Writer('exports/stoptimes/' . $agency . "_" . $tag . '_stoptimes.csv');
        $stopTimesWriter->writeFromArray($data['stoptimes']);
        $tripsCoralvilleWriter->writeFromArray($data['trips']);
      }
    }
  }
}



?>
