<?php 

class Home extends Base {

  public    $debug = 0;
  protected $request; 
  protected $model;
  protected $view; 


  public function __construct($in=array()){
    parent::__construct();
    $this->request = $in;
    $this->model = new HomeModel($this->request);
    $this->view  = new HomeView('index.html');
  }

  public function index(){
    if ( $this->debug > 0 ) error_log( __METHOD__ );

    $this->view->render( $this->model->doSomething() );

  }

  }