<?php
ini_set("auto_detect_line_endings", true);
include("inc/util.php");
require ('./vendor/autoload.php');


$shapes = get_shapes();

function get_shapes() {
  unlink('build/shapes.csv');
  $shapesReader = new \EasyCSV\Reader('exports/shapes.csv');
  $shapesWriter = new \EasyCSV\Writer('build/shapes.csv');
  $shapesWriter->writeRow('shape_id, shape_pt_lat, shape_pt_lon,shape_pt_sequence');

  debug("test");

  $i = 0;
  $previous_shape = "";
  //$data = array();
  while ($row = $shapesReader->getRow()) {
    debug($row);
    $row_data['shape_id'] = $row['shape_id'];
    $row_data['shape_pt_lat'] = $row['shape_pt_lat'];
    $row_data['shape_pt_lon'] = $row['shape_pt_lon'];
    if($row_data['shape_id'] != $previous_shape) {
      $i = 0;
    }

    $row_data['shape_pt_sequence'] = $i;

    $shapesWriter->writeRow($row_data);
    $previous_shape = $row_data['shape_id'];
    $i = $i + 1;

  }

}
?>
