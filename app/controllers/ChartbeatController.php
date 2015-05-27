<?php 

class Chartbeat extends Base {

  public    $debug = 0;
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
    raw( $this->model->showTrending( $site, $diff ) );
  }


  }