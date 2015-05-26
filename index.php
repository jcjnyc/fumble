<?php
require_once(__DIR__.'/lib/config.php');
require_once(__DIR__.'/lib/bootstrap.php');

// CHECK RAW OUTPUT WITH ?raw=james
if (@$_GET['raw'] == 'james') raw();

// PARSE REQUEST
$request = parseRequest();

if ( isValidController( $request['controller'] ) ) {
  
  //  INSTANTIATE THE CONTROLLER AND PASS IT DATA - IT SHOUDL STORE
  //   IN INSTANCE STRUCTURE
  $controller = new $request['controller']( @$request );
  
  if ( method_exists($controller, $request['action'] ) ){
    // CALL THE PRESCRIBED ACTION METHOD 
    $controller->{$request['action']}();
  }
  
}else{
  raw();
  print "no valid controller found\n";
  print_r($request);
  
} 
