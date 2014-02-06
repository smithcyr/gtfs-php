<?php

require '../vendor/autoload.php';

include_once("util.php");
//include_once("trips.php");

//$agency = "coralville";
//$route_name = "10thst";

//get_route_stop_times($agency, $route_name);

/**
 * Get stop times and trips for a route.
 * @param  [string] $agency     [description]
 * @param  [string] $route_name [description]
 * @param  [string] $startid [the starting trip sequence for this variant]
 * @return [array]             [an array with stoptimes and trips keys]
 */
function get_route_stop_times($agency, $route_name, $route_variant, $service_id, $startid) {


  // Silly Mac.
  ini_set("auto_detect_line_endings", true);

  // Get the stops array for this route so we can determine what is a normal
  // trip.
  $stops_array = getStopList($agency, $route_variant);
  $length = count($stops_array);
  debug("length: " . $length);
  // Get the trips array so we can iterate through them.
  $trips_obj = getTripList($agency, $route_variant);
  //debug($trips_obj->getAll());

  // Create empty array to hold our data.
  $route_data = array();

  // Set trip iterator to 0.
  $trip_i = $startid;

  // Iterate over each row in the CSV.
  while ($trip = $trips_obj->getRow()) {

    $sequence = 1;
    $start_time = strtotime($trip['start_time']);
    $start_stop_id = $trip['stop_id'];
    $key = search($stops_array, 'stop_id', $start_stop_id);

    $key = $key[0]['id'];
    $trip_initial_data = array_slice($stops_array, $key);

    //debug(count($trip_initial_data));
    $trip_data = array();
    foreach ($trip_initial_data as $stopkey => $stop) {
      $stop_data = array();
      //debug($stop);
      // Format the stop id into four digits.
      $stop_number_format = '%1$04d';
      $stop['stop_id'] = sprintf($stop_number_format, $stop['stop_id']);

      $time_increment = strtotime($stop['relative_time']) - strtotime('today');

      if($stopkey == 0 && $key != 0) {
        $time_increment = 0;
      }

      $time = $start_time + $time_increment;

      $stop_data['trip_id'] = $route_name . '_' . $trip_i;
      $stop_data['arrival_time'] = date("H:i:s", $time);
      $stop_data['departure_time'] = date("H:i:s", $time);
      $stop_data['stop_id'] = $stop['stop_id'];
      $stop_data['stop_sequence'] = $sequence;
      $stop_data['stop_headsign'] = $stop['head_sign'];

      $route_data['stoptimes'][] = $stop_data;

      $sequence = $sequence + 1;
      $start_time = $time;
    }

    $trip_data['route_id'] = $route_name;
    $trip_data['service_id'] = $service_id;
    $trip_data['trip_id'] = $route_name . '_' . $trip_i;
    $trip_data['shape_id'] = "shape_" . $route_name;

    $route_data['trips'][] = $trip_data;

    $trip_i = $trip_i + 1;
  }

  //debug($route_data);
    // Iterate over each row in the CSV.
  /*while ($row = $trips_obj->getRow()) {
      // If the row's Route column matches our route.
      if($row['Route'] == $route_name) {
        // Create empty array to hold our route data.
        $row_data = array();

        // Get the route "tag" or stop name.
        $tag = $row['Stop Tag'];

        // If the tag is available in our tag array.
        if(array_key_exists($tag, $tag_array)) {
          // Set the Stop Tag value to the Stop ID.
          $row['Stop Tag'] = $tag_array[$tag];
        }

        // Get the row's Time.
        $time = $row['Time (hh:mm:ss)'];

        // If the time is not "empty" from Nextbus
        if($time != "   ") {
          // Create a new variable with the set time.
          $time_fill = $time;
          // Clean up the value by removing the spaces around it.
          $row['Time (hh:mm:ss)'] = getTime($row['Time (hh:mm:ss)'], 0);
        }
        // If time is "empty" from NextBus.
        else {
          // Add a second to time fill.
          $time_fill = getTime($time_fill, 1);
          // Set time row to the new value.
          $row['Time (hh:mm:ss)'] = $time_fill;
          // Set the timepoint type to AutoFill.
          // Right now this isn't actually used anywhere.
          // @TODO add it to the CSV files the agencies get and remove that
          // column from it when we build the final file.
          $row['Timepoint Type'] = "AutoFill";
        }

        $diff_time = time_differential($row['Time (hh:mm:ss)'], $previous_time);

        // If the Stop ID is the first stop in a route.
        if(($row['Stop Tag'] == $stop_array[0]) || ($diff_time)) {

          // Increase the trip value, indicating a new route has started.
          $trip = $trip + 5;
          // Create a new trip.
          $route_data['trips'][] = setTrip($trip, $route_name);
          // Reset the stop sequence for the next trip.
          $sequence = 10;

          if($row['Stop Tag'] == $stop_array[0]) {
          }
          elseif($diff_time) {
            debug("Route: " . $row['Route'] . " Trip: " . $trip . " diff time: " . $diff_time);
          }
        }

        // Increase sequence by 10.
        $sequence = $sequence + 10;

        // If the row has a valid stopid- essentially not a hidden stop.
        if($row['Stop Tag']) {
          // Set GTFS trip_id value.
          $row_data['trip_id'] = $route_name . "_" . $trip;
          // Set GTFS arrival_time value.
          $row_data['arrival_time'] = $row['Time (hh:mm:ss)'];
          // Set GTFS departure_time value.
          $row_data['departure_time'] = $row['Time (hh:mm:ss)'];
          // Set GTFS stop_id value.
          $row_data['stop_id'] = $row['Stop Tag'];
          // Set GTFS stop_sequence value.
          $row_data['stop_sequence'] = $sequence;
          // Set GTFS stop_headings value.
          $row_data['stop_headsign'] = $row['Direction'];
          // Create a new stoptimes value.
          $route_data['stoptimes'][] = $row_data;

          $previous_time = strtotime($row['Time (hh:mm:ss)']);
        }
      }
    debug($row->start_time);
  }*/

  // Return all the route data, including stop_times and trips.
  return $route_data;
}

/**
 * Determines if two times are within just over an hour.
 * @param  [string] $time
 * @param  [string] $previous_time
 * @return [bool]
 */
function time_differential($time, $previous_time) {
    if ($previous_time >= strtotime($time)) {
      $time_difference = $previous_time - strtotime($time);
    } elseif ($previous_time <= strtotime($time)) {
      $time_difference = strtotime($time) - $previous_time;
    }

    debug($time_difference);
    debug(date("H:i:s", $time_difference));
    if($time_difference >= 3661) {
      return TRUE;
    }
    else {
      return FALSE;
    }
}

/**
 * Converts time to seconds and increments by options number of seconds.
 * @param  [string] $time_initial
 * @param  [string] $add
 * @return [string]
 */
function getTime($time_initial, $add) {
  // Convert time to seconds.
  $time = strtotime($time_initial);
  // Re-convert time value + additional seconds to 00:00:00 format.
  $time_final = date("H:i:s", ($time + $add));
  return $time_final;
}

/**
 * Get route from Bongo API.
 * @param  [string] $agency
 * @param  [string] $route
 * @return [array]
 */
function getRoute($agency, $route) {
  $uri = 'http://api.ebongo.org/route';
  $uri .= '?agency=' . $agency;
  $uri .= '&route=' . $route;

  $xml_request = file_get_contents($uri);

  $xml = simplexml_load_string($xml_request);

  return $xml;
}

/**
 * Get stops for a route.
 * @param  string $agency
 * @param  string $route_name
 * @return object
 */
function getStopList($agency, $route_variant) {
  $reader = new \EasyCSV\Reader('../imports/stoptimes/' . $agency . '/' . $route_variant . '.csv');
  /*foreach ($reader->getRow() as $row) {
    debug("stop: " . $row);
  }*/
  $data = array();
    // Iterate over each row in the CSV.

  $i = 0;
  while ($row = $reader->getRow()) {
    //debug($key);
    $data[] = array(
      'id' => $i,
      'stop_id' => $row['stop_id'],
      'time' => $row['time'],
      'relative_time' => $row['relative_time'],
      'head_sign' => $row['head_sign'],
    );
    $i = $i + 1;
    //$time = $row['start_time'];
  }

  //debug($key[0]['id']);
  //debug($data);


  return $data;
}

function search($array, $key, $value) {
  $results = array();

  if (is_array($array))
  {
      if (isset($array[$key]) && $array[$key] == $value)
          $results[] = $array;

      foreach ($array as $subarray)
          $results = array_merge($results, search($subarray, $key, $value));
  }

  return $results;
}

/**
 * Create a trips object.
 * @param  [string] $agency
 * @param  [string] $route
 * @return object
 */
function getTripList($agency, $route_variant) {
  $reader = new \EasyCSV\Reader('../imports/trips/' . $agency . '/' . $route_variant . '_trips.csv');
  //debug($reader->getAll());
  return $reader;
}

?>
