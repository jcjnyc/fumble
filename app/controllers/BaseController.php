<?php 

class Base {

  public function __construct(){

  }

  
  public function getCache($k){
    if($this->debug > 0) error_log(__METHOD__);
    $memcache = new Memcache;
    $memcache->connect('localhost', 11211) or die ("Could not connect");
    return ( $memcache->get( $k ) );
  }

  public function setCache($k,$v){
    if($this->debug > 0) error_log(__METHOD__);
    $memcache = new Memcache;
    $memcache->connect('localhost', 11211) or die ("Could not connect");
    return ( $memcache->set( $k, $v,false, CACHE_EXPIRE ) );
  }

}