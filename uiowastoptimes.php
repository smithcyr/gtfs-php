<?php

include_once('inc/util.php');
include('inc/stoptimesroute.php');

//unlink('exports/trips/uiowa_trips.csv');
$tripsUiowaWriter = new \EasyCSV\Writer('exports/trips/uiowa_trips.csv');

$uri = 'http://api.ebongo.org/routelist';

$xml_request = file_get_contents($uri);

$xml = simplexml_load_string($xml_request);

if ($xml->route) {
  foreach ($xml->route as $route) {
    $agency = $route->agency;
    $tag = $route->tag;

    if($agency == "uiowa") {
      $data = get_route_stop_times($agency, $tag);
      if(isset($data['stoptimes'])) {
        //unlink('exports/stoptimes/' . $agency . "_" . $tag . '_stoptimes.csv');
        $stopTimesWriter = new \EasyCSV\Writer('exports/stoptimes/' . $agency . "_" . $tag . '_stoptimes.csv');
        $stopTimesWriter->writeFromArray($data['stoptimes']);
        $tripsUiowaWriter->writeFromArray($data['trips']);
      }
    }
  }
}



?>
