<?php
require_once(__DIR__.'/../lib/config.php');
require_once(__DIR__.'/../lib/bootstrap.php');

$cb = new ChartbeatModel();
$cb->importData( $argv[1] );


//$cb->updateTrending( $argv[1], $argv[2] );