<?php

include("inc/util.php");

$stops = get_stops();

if($stops) {
  $data = "stop_id,stop_name,stop_lat,stop_lon,stop_url\n";

  $data .= $stops;

  $file = file_put_contents('build/bin/stops.txt', $data);
}

function get_stops() {
  $uri = 'http://api.ebongo.org/stoplist';

  $xml_request = file_get_contents($uri);

  $xml = simplexml_load_string($xml_request);

  if($xml) {
    $data = "";

    foreach ($xml->stop as $stop) {

      $stopnumber = $stop->stopnumber;

      if($stopnumber) {
        $format = '%1$04d';
        $stopnumber = sprintf($format, $stopnumber);
        debug($stopnumber);
      }


      $stoptitle = $stop->stoptitle;
      $stoplat = $stop->stoplat;
      $stoplng = $stop->stoplng;

      $data .= $stopnumber . ',';
      $data .= '"' . $stoptitle . '",';
      $data .= $stoplat . ',';
      $data .= $stoplng . ',';
      $data .= "http://ebongo.org/stop/" . $stopnumber . "\n";

    }

    return $data;
  }
}


?>
