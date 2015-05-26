<?php

  // THINGS THAT ARE SHARED ACROSS MOST MODELS
class BaseModel {


  public function __construct(){


  }


  // INSERTS AN INT AND SHOULD RETURN THE AUTO INCREMENT ID
  public function startRun($name){
    if(empty($name)) $name = 'null';
    $sql = 'insert into run_log (run_start, process_name) values(now(), "'.$name.'")';
    return ( $this->dbc->doAction($sql) );
  }

  // EXPECTS AN INT OF RUN ID FROM run_log TABLE 
  public function endRun($in){
    //error_log(__METHOD__." ".$in);
    $sql = ' update run_log set line_items_updated='.$this->counts['line_items_updated'].', orders_updated='.$this->counts['orders_updated'].', run_end=now() where id = '.$in;
    return ( $this->dbc->doAction($sql) );
  }






  }

