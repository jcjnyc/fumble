<?php
require_once(__DIR__.'/../lib/config.php');
require_once(__DIR__.'/../lib/bootstrap.php');


if(!empty( $argv[1]) ) {
  $site_list = [ $argv[1] ];
}else{
  $site_list = explode(',',SITE_LIST);
}

  
$cb = new ChartbeatModel();

$cb->importData( $site_list );


