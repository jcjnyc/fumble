<?php
require_once(BASE_DIR.'/vendor/autoload.php');

function __autoload($in){
  error_log(__METHOD__."( $in )");
  
  // DIRS TO AUTOLOAD - YOU CAN ADD DIRS AND SPECIAL SUFFIX IF YOU LIKE 
  $dir_list = array('lib'             => '',
		    'app/controllers' => 'Controller', 
		    'app/models'      => '', 
		    'app/views'       => ''
		    );
  
  // SEARCH PATHS
  $list = array();
  foreach($dir_list as $dir => $role ){
    $list[] = BASE_DIR.'/'.$dir.'/'.$in.$role.'.php';
    if ( file_exists( BASE_DIR.'/'.$dir.'/'.$in.$role.'.php') ){
      require_once(BASE_DIR.'/'.$dir.'/'.$in.$role.'.php');
      return;
    }
  }

  error_log("No such file: ".print_r($list,true) );
  
}



// ROUTE REQUEST 
function parseRequest(){
  
  // SETUP DEFAULT STUFF 
  $data = array('controller' => DEFAULT_CONTROLLER,
		'action'     => 'index',
		'data'       => null );

  // $in HOLDS THE PATH INFO FROM REDIR
  if( isset( $_GET['in'] )  ){

    if($_GET['in'] == '/index.html'){
      return($data); // RETURN DEFAULT ROUTE IF SOMEONE HITS DIRECTLY
    }else{      
      $tmp = explode('/',$_GET['in'],4);    

      !empty($tmp[1]) ?  $data['controller']   = ucfirst($tmp[1]) : $data['controller'] = DEFAULT_CONTROLLER;
      !empty($tmp[2]) ?  $data['action']       = $tmp[2] : $data['action']     = 'index';
      !empty($tmp[3]) ?  $data['data']         = $tmp[3] : $data['data']       = null;
    }
  }
  
  // WE CAN CLEAN AND VALIDATE THIS DATA SOMEHOW
  $data['get']  = $_GET;
  $data['post'] = $_POST;

  return($data);
  
}


function isValidController( $in ){
  error_log(__METHOD__."( $in ) ".VALID_CONTROLLERS );
  return( in_array($in, explode(',',VALID_CONTROLLERS) ) );
}

function raw($in=null){
  print "<pre>";
  if(isset($in)){
    print json_encode($in,JSON_PRETTY_PRINT);
  }else{
    var_export($_SERVER);
  }
  exit;
}

spl_autoload_register('__autoload');