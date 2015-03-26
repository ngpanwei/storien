<?php
$s = "2015-03-05-13-24-56" ;
$format = 'Y-m-d-H-i-s' ;
$date = date_create_from_format('Y-m-d-H-i-s', $s);
echo $date->format($format) . PHP_EOL ;
echo date($format) . PHP_EOL ;
