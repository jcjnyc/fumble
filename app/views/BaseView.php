<?php

/** 
 * BaseView - stuff setup for all/most templates
 **/
class BaseView {
      
      protected $twig;
      protected $loader;
      
      public function __construct(){

	$this->loader = new Twig_Loader_Filesystem(BASE_DIR.'/templates');
	$this->twig   = new Twig_Environment($this->loader,
					     array('debug' => true,
						   'cache' => BASE_DIR.'/templates/cache')
					     );
      }

      // GIVEN AN ID NUMBER RETURN THE LINE ITEM LINK TO GOOGLE 
      public function lineItemLink($in){
	return( '<a href="https://www.google.com/dfp/'.DFP_NETWORK_ID.'?#delivery/LineItemDetail/lineItemId='.$in.'" target="_new">'.$in.'</a>' );
      }


      // GIVEN AN ID NUMBER RETURN THE LINE ITEM LINK TO GOOGLE 
      public function orderLink($in){
	return( '<a href="https://www.google.com/dfp/'.DFP_NETWORK_ID.'?#delivery/OrderDetail/orderId='.$in.'" target="_new">'.$in.'</a>' );
      }

      // NEEDS LINEITEM ID, ORDER ID, AND CREATIVE ID 
      public function creativeLink($in){
	return( '<a href="https://www.google.com/dfp/'.DFP_NETWORK_ID.'?#delivery/PreviewCreative/orderId='.$in['orderId'].'&lineItemId='.$in['lineItemId'].'&creativeId='.$in['creativeId'].'" target="_new">'.$in['creativeId'].'</a>' );
      }	
	

  // RETURN ROW CREATED FROM ASSOC ARRAY KEYS 
  public function tableHeaderRow($in){
    $out = '';
    $cols = array_keys($in);
    foreach($cols as $k){
      $out .= '<td>'.$k.'</td>';
    }
    return('<tr>'.$out.'</tr>');
  }



  // RETURN HTML TR ROW OF VALUES 
  public function fullTableRow($in){
    $out = '';
    $cols = array_keys($in);
    foreach($in as $k => $v){
      if(!isset($v)) $v = 'n/a';
      $out .= '<td><a title="'.$k.'">'.$v.'</a></td>';
    }
    return('<tr>'.$out.'</tr>');
  }

  // VERTICAL TABLE .. SO A STACK OF | KEY | VALUE | ROWS
  public function verticalTable($in){
    $out = '';
    foreach($in as $k => $v){
      $out .= '<tr><td style="text-align: left;">'.$k.'</td><td style="text-align: left">'.$v.'</td></tr>';
    }
    return($out);
  }

  // KEY => VALUE TO KEY => <TD>VALUE</TD>
  public function tableDataArray($in){
    $out = '';
    foreach($in as $k => $v){
      $out[$k] = '<td>'.$v.'</td>';
    }
    return($out);
  }

  // WRAP INPUT IN TEXTAREA TAG 
  public function textareaDetail($in=null, $x=10, $y=10){
    
    return( '<textarea rows='.$x.' cols='.$y.'>Details'."\n".$in.'</textarea>' );

  }


  // GENERATES JAVASCRIPT ARRAY DATA FOR GOOGLE CHARTS 
  // SHOULD LOOK LIKE THIS 
  // 
  public function generateGoogleChartData( $in, $header_row = null){
      $out = array();

      // BUILD A HEADER ROW, QUOTED, IF EXISTS
      if(!empty($header_row)){ 
          $out[] = "['".implode("','", $header_row)."']";
      }

      // 
      foreach($in as $row){
	
	$new_row = array();
	foreach($row as $k => $item){ 
	  if( preg_match('/\D/',$item) ){
	    $item = "'".$item."'";
	  }else{
	    $item = sprintf('%.2f',($item * .000001));
	  }
	  $new_row[] = $item;
	}
	$out[]  = "[".implode(",", $new_row)."]";
      }
      return( implode(",\n",$out) );
      
  }




      
}