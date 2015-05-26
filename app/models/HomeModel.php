<?php

class HomeModel extends BaseModel {

  protected $dbc;
  protected $request; 

  public function __construct($in=null){
    parent::__construct();
    $this->request = $in;
  }

  public function doSomething( ){
    return array(__METHOD__, 
		 'here i am ',
		 $this->request);
  }
  

  }


