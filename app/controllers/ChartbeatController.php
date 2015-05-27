<?php 

class Chartbeat extends Base {

  public    $debug = 1;
  protected $request; 
  protected $model;
  protected $view; 


  public function __construct($in=array()){
    parent::__construct();
    $this->request = $in;
    $this->model = new ChartbeatModel($this->request);
    $this->view  = new HomeView('index.html'); 
  }

  public function index(){
    if ( $this->debug > 0 ) error_log( __METHOD__ );
    $this->view->render( $this->model->doSomething() );
  }

  public function trending(){
    list( $site, $diff ) = explode( '/', $this->request['data'] ); 

    if( $out = $this->getCache( $site.'/'.$diff ) ){
      echo $out;
    }else{
      $out = json_encode( $this->model->showTrending( $site, $diff ) );
      $this->setCache( $site.'/'.$diff, $out );
      echo $out; 
    }
  }


  }