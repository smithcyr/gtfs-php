<?php

include_once('util.php');
//include('tripsroute.php');

function setTrip($trip, $route_name) {
  $data = array();

  $data['route_id'] = $route_name;
  $data['service_id'] = "year_round";
  $data['trip_id'] = $route_name . "_" . $trip;

  //debug($data);
  return $data;
}



?>
