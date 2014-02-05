<?php
ini_set("auto_detect_line_endings", true);
include("../inc/stoptimesroute.php");

$agency = 'coralville';
$route = 'lantern';
$service_id = "coralville_weekday";
$startid = 1000;
$route_variant = "lantern";

$data = get_route_stop_times($agency, $route, $route_variant, $service_id, $startid);

debug($data['trips']);



//debug($data['stoptimes']);

if(isset($data['stoptimes'])) {
  unlink('../exports/stoptimes/' . $agency . "_" . $route_variant . '_stoptimes.csv');
  $writer = new \EasyCSV\Writer('../exports/stoptimes/' . $agency . "_" . $route_variant . '_stoptimes.csv');
  //$writer->writeRow('trip_id, arrival_time, departure_time, stop_id, stop_sequence, stop_headsign');
  $writer->writeFromArray($data['stoptimes']);

  unlink('../exports/trips/' . $agency . "_" . $route_variant. '_trips.csv');
  $writer = new \EasyCSV\Writer('../exports/trips/' . $agency . "_" . $route_variant. '_trips.csv');
  //$writer->writeRow('route_id, service_id, trip_id');
  $writer->writeFromArray($data['trips']);
}



?>
