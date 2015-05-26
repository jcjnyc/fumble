<?php 

class HomeView extends BaseView {

  // TEMPLATE TO USE
  public $template;

  // TWIG STUFF
  protected $loader; 
  protected $twig;
  protected $renderData = array();

  public function __construct($template){
    parent::__construct();
    $this->template = $template;
    $this->loader = new Twig_Loader_Filesystem(BASE_DIR.'/templates');
    $this->twig   = new Twig_Environment($this->loader,
					 array('debug' => true,
					       'cache' => BASE_DIR.'/templates/cache')
					 );
    
  }
  
  
  public function render($in=null){

    $this->renderData['TEST_DATA_STRING'] = json_encode($in);

    echo $this->twig->render( $this->template , $this->renderData);
    
    
  }
  
  }