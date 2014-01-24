<?php

require './vendor/autoload.php';

include_once("util.php");
include_once("trips.php");

/**
 * Get stop times and trips for a route.
 * @param  [string] $agency     [description]
 * @param  [string] $route_name [description]
 * @return [array]             [an array with stoptimes and trips keys]
 */
function get_route_stop_times($agency, $route_name) {
  // Silly Mac.
  ini_set("auto_detect_line_endings", true);
  switch ($agency) {
    case 'coralville':
      $reader = new \EasyCSV\Reader('./imports/coralville.csv');
      break;
    case 'iowa-city':
      $reader = new \EasyCSV\Reader('./imports/iowacity.csv');
      break;
    case 'uiowa':
      $reader = new \EasyCSV\Reader('./imports/uiowa.csv');
      break;
    default:
      # code...
      break;
  }


  // Get the stops array for this route so we can determine what is a normal
  // trip.
  $stop_array = getStopList($agency, $route_name);

  // Get the tag array from nextbus, so we can change it to stopids.
  $tag_array = get_tags($agency, $route_name);

  // Create empty array to hold our data.
  $route_data = array();

  // Set trip iterator to 0.
  $trip = 0;

  // Set stop sequence. @TODO look into if we actually need to increment this
  // by 10 anymore, considering we're pulling all the stops in from the CSV.
  $sequence = 10;

  $previous_time = 1000000;

  // Iterate over each row in the CSV.
  while ($row = $reader->getRow()) {
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
  }
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
 * @return array
 */
function getStopList($agency, $route_name) {
  $route = getRoute($agency, $route_name);
  $stops = array();
  if($route) {
    foreach ($route->directions->direction as $direction) {
      foreach ($direction->stop as $stop) {
        $stopnumber = htmlentities((string) $stop['number']);
        // Convert to XXXX format.
        $format = '%1$04d';
        $stopnumber = sprintf($format, $stopnumber);
        $stops[] = $stopnumber;
      }
    }
  }
  return $stops;
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
