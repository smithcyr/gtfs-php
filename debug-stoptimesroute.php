<?php
ini_set("auto_detect_line_endings", true);
include("stoptimesroute.php");

$agency = 'iowa-city';
$route = 'eventshuttle';

$data = get_route_stop_times($agency, $route);

debug($data['trips']);

debug($data['stoptimes']);

if(isset($data['stoptimes'])) {
  unlink('exports/test/' . $route . '_stoptimes.csv');
  $writer = new \EasyCSV\Writer('exports/test/' . $route . '_stoptimes.csv');
  $writer->writeFromArray($data['stoptimes']);

  unlink('exports/test/' . $agency . '_trips.csv');
  $writer = new \EasyCSV\Writer('exports/test/' . $agency . '_trips.csv');
  $writer->writeRow('route_id, service_id, trip_id');
  $writer->writeFromArray($data['trips']);
}



?>
