<?php

class ChartbeatModel extends BaseModel {

  protected $dbc;
  protected $request; 
  protected $sourceUrl = array();

  public function __construct($in=null){
    parent::__construct();
    $this->request = $in;
    $this->dbc = DB::getConnect();    
  }

  // RETURN THE TRENDING DATA 
  public function showTrending( $site, $interval ){
    $intervals = array();

    $max = $this->dbc->runQuery('select max(id) as id from run')[0]['id'];

    // join to self on batch 
    $sql = ' select t1.site, t1.path, t1.i, (t2.visitors - t1.visitors) as increase '.
      ' from (select site, path, i, visitors from toppages where batch =  ? ) t1 '.
      ' join (select site, path, i, visitors from toppages where batch =  ? ) t2 '.
      ' on (t1.site = t2.site and t1.path = t2.path ) where t2.visitors > t1.visitors '.
      ' order by increase desc limit '.TRENDING_RESULT_SET;
    
    $out = $this->dbc->runQuery( $sql, [$max, ($max - $interval) ] );
    return ( $out ); 

  }


  // METHOD TO IMPORT THE CHARTBEAT DATA SET
  public function importData( $site_list ){

    // GET THE RUN BATCH ID
    $batch = $this->dbc->doAction('insert into run (run_date) values( now() )');

    // BATCH,SITE,PATH IS KEY THAT WE WORK AROUND
    foreach( $site_list as $site ){

      // DB COLUMNS -- NEED TO ADD SITE DATA AND MAKE AoH
      foreach( json_decode( file_get_contents( $this->getSource($site) ) ) as $row ){
	$d = array();
	$d['batch'] = $batch;
	$d['site']  = $site; 
	$d['i']     = $row->i; 
	$d['path']  = $row->path;
	$d['visitors'] = $row->visitors;
	$data[] = $d;
      }
      
      // THIS SHOULD INSERT ONE ROW FOR EACH SITE+PATH FOR EACH RUN 
      $this->dbc->insertRows( 'toppages', array_keys($data[0]), $data );
      
      // KEEP THE LAST 'MAX BATCH' RUNS
      if( ( $batch - MAX_BATCH ) > 0){
	$this->dbc->runQuery('delete from toppages where batch < ?', [ $batch - MAX_BATCH ]);
      }
    }

  }
  

  private function getSource($site){
    foreach(json_decode( SOURCE_URLS,true) as $entry ){
      if ( @$entry[ $site ] ) return $entry[ $site ] ;
    }
    die('ERROR: no site defined in SOURCE_URLS: '.$site."\n");
  }


  }


