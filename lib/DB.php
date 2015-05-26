<?php

class DB {


  private $_db;
  public static $_instance;
  public $lastId;
  public $debug = 0;

  
  private function __construct($in=0){
    if ( $in != 0 ) $this->debug = $in;

    $this->_db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';port='.DB_PORT,
			 DB_USER,
			 DB_PASS);

    if ($this->debug != 0) print_r($this->_db, true);
    
  }

  public static function getConnect(){
    if(!self::$_instance){
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  
  public function doAction($sql=null, $in=array()){
    $stmt = $this->_db->prepare($sql);

    if( count($in) > 0 ){

      try {
	$out =  $stmt->execute($in);
	$this->checkStmtError($stmt);
	$this->checkStmtError($stmt);
      } catch (PDOException $e){
	die($e->getMessage());
      } catch (Exception $e) {
	echo "General Error: ".$e->getMessage();
      }

      if($this->_db->lastInsertId()){
	return $this->_db->lastInsertId();
      }else{
	return $out;
      }


    }else{

      try {
	$out =  $stmt->execute();
      } catch (PDOException $e){
	die($e->getMessage());
      }
      if($this->_db->lastInsertId()){
	return $this->_db->lastInsertId();
      }else{
	return $out;
      }
    }
  }

  // SIMPLE QUERY - IF THERE ARE IN I EXPECT THE QUERY HAS PLACEHOLDERS
  // @param string the query itself 
  // @param array the argument list
  public function runQuery($sql=null, $in=array()){

    if ($this->debug != 0) error_log('SQL: '.$sql);

    $stmt = $this->_db->prepare($sql);
    if( isset($in[0]) ){
      try {
	$stmt->execute($in);
	$this->checkStmtError($stmt);
      } catch (PDOException $e){
	die($e->getMessage());
      }
    }else{
      try {
	$out =  $stmt->execute();
	$this->checkStmtError($stmt);
      } catch (PDOException $e){
	die($e->getMessage());
      }
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);

  }

  // @param string name of table 
  // @param array list of table columns
  // @param array AoH array( array(col_name => col_val), array(col_name => col_val2) ) 
  public function replaceRows($table, $colList = array(), $data){
    return ( $this->placeholderQuery ( 'replace into',  $table, $colList, $data) );
  }

  // @param string name of table 
  // @param array list of table columns
  // @param array AoH array( array(col_name => col_val), array(col_name => col_val2) ) 
  public function insertRows($table, $colList = array(), $data){
    return ( $this->placeholderQuery ( 'insert into', $table, $colList, $data) );
  }

  // @param string name of table 
  // @param array list of table columns
  // @param array AoH array( array(col_name => col_val), array(col_name => col_val2) ) 
  public function updateRows($table, $colList = array(), $data){
    return ( $this->placeholderQuery ( 'update', $table, $colList, $data) );
  }

  // @param string name of table 
  // @param array list of table columns
  // @param array AoH array( array(col_name => col_val), array(col_name => col_val2) ) 
  public function deleteRows($table, $colList = array(), $data){
    return ( $this->placeholderQuery ( 'delete', $table, $colList, $data) );
  }
	
  // REPLACE USING PLACE HOLDERS 
  // @param string table name 
  // @param array list of columns 
  // @param array AoH of data - one containing array, one row of data per line
  public function placeholderQuery($action, $table, $colList = array(), $data){
    if($this->debug != 0){
      print $action." ".$table." ".implode(',',$colList)." ".print_r($data,true)."\n";
    }
    $x = 0;
    $c = count($colList);
    $k = array_keys($colList);

    foreach($colList as $col){
      $q[] = ':'.$col;
    }
  
    $placeHolder = implode(',',$q);

    $baseSql = $action.' '.$table.' ( '.implode(',', $colList).' ) values ( '.$placeHolder.' )';

    if ($this->debug != 0) print $baseSql ."\n";

    try {
      $stmt = $this->_db->prepare($baseSql);

      foreach($data as $row){
	++$x;
	$out = $stmt->execute($row);
	$this->checkStmtError($stmt);
      }

    }catch(PDOException $e){
      print "$e";
      exit;
    }
    
    return($x);

  }
   
  // thank you - 
  // http://stackoverflow.com/questions/173400/php-arrays-a-good-way-to-check-if-an-array-is-associative-or-sequential
  function isAssoc($arr)  {
    return array_keys($arr) !== range(0, count($arr) - 1);
  }


  // GAG LOUDLY IF THERE IS ANY SORT OF DATA ERROR
  protected function checkStmtError($x){
    if($x->errorCode() > 0){
      raw($x->errorInfo());
    }
  }


  // CREATE A 'SNAPSHOT TABLE' FROM AN EXISTING TABLE DEFINITION 
  // - so find an existing table, get the definiitons and create a new table with 
  //   the columns run_id, run_date, run_time prefixed :)
  // @param string existing table name 
  function create_snapshot_table($in){
    
    $snapshot = 'snapshot_'.$in;
    
    // MAKE SURE TABLE DOESN'T ALREADY EXIST
    $this->drop_table_by_name($snapshot);
    
    $prefix = array( array('col' => 'run_id',
			   'definition' => 'bigint' ),
		     array('col' => 'run_date',
			   'definition' => 'date' ),
		     array('col' => 'run_time',
			   'definition' => 'time' )
		     );
    
    // GET THE EXISTING TABLE DEFINITION AND MERGEE IN PREFIX COLS
    $existing_table = $this->get_table_def($in);
    
    $table_def = array_merge($prefix, $existing_table );
    
    // CREATE THE NEW 'SNAPSHOT_' table 
    return ( $this->create_table_from_def($snapshot, $table_def) );
    
  }  


  // RETURN INFO ABOUT TABLE FROM INFORMATION_SCHEMA 
  function get_table_def($in){
    
    $sql = 'select column_name as col, column_type as definition from information_schema.columns  where table_name = ? and table_schema = ? ';

    // returns an AoH    
    $out = $this->runQuery($sql, array( $in, DB_NAME) ) ;

    if(count( $out) > 0  ){
      return($out);
    }else{
      raw('no such table '.$in.' in '.DB_NAME);
    }
    
  }
  
  // BUILD CREATE TABLE FROM 
  // @param string table name
  // @param array associative array of column names => definitions -- add single index sets here
  // @param string db engine 
  // @param string character set
  // @param array any multi-column or primary key sets -- not yet in use
  function create_table_from_def($table, $col_def, $engine = 'InnoDB', $charset='utf8', $index = ''){
    $cols = array();
    foreach($col_def as $column){
      $cols[] = implode(' ', $column);
    }
    
    $sql = 'CREATE TABLE '.$table.
      '( '.implode(',',$cols).','.
      ' index (run_id) , index (run_date) , index (run_time) '.
      ' ) '.
      ' ENGINE = '.$engine.
      ' DEFAULT CHARSET = '.$charset;
    
		
    if ( $this->doAction($sql) ){
      return (1);
    }else{
      error_log("Issue with SQL:\n");
      raw($sql);
    }
  }
  
  // DROP TABLE BY NAME
  // @param string table name to drop 
  function drop_table_by_name($table){
    $sql = 'DROP TABLE IF EXISTS '.$table;
    if ( $this->doAction($sql) ){
      return (1);
    }else{
      error_log("Issue with SQL:\n");
      raw($sql);
    }
  }
  
  
  
  }
