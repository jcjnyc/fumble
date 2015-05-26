<?php

$pid = getmypid();

// SHARED UTIL FUNCTIONS AND THEN A 
// LIST OF CREATE AND DROP SETS FOR TABLES


// VERSION TABLES
function create_version(){
    $sql['drop']   = "DROP TABLE IF EXISTS `version`";
    $sql['create'] = "CREATE TABLE IF NOT EXISTS `version` (
  `version` int(11) NOT NULL AUTO_INCREMENT,
  `update_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`version`)
) ENGINE=MyISAM";
    return($sql);
}

function updateVersion(){
  global $dbc;
  $sql = 'insert into version (update_timestamp) value (now())';
  return ( $dbc->doAction($sql) );
}

function getVersion(){
    global $dbc;
    $sql = 'show tables like "version"';
    $out = $dbc->runQuery($sql);
    if( empty( $out[0] ) ){ 
        print "creating version table\n";
        foreach( create_version() as $sql) { $dbc->runQuery($sql); }
        return 0;
    }
    

    $sql = 'select max(version) as version from version';
    $out = $dbc->runQuery($sql);    
    return $out[0]['version'];
}


// **************************************************** //
// TABLE DEFINIITONS THAT SHOULD BE APP SPECIFIC        //
// **************************************************** //

// ARTICLES - TOP LEVEL ORGANIZATIONAL STRUCTURE FOR CONTENT 
function create_articles(){
  $sql['drop']   = "DROP TABLE IF EXISTS articles";
  $sql['create'] = "CREATE TABLE `articles` (
  `article_id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int not null default 0,
  `owner_id` int(11) not null, 
  `title` varchar(200),
  `byline` varchar(400),
  `last_modified` timestamp default current_timestamp on update current_timestamp,
  `create_date` timestamp,
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8
";
  
  return($sql);
}

function create_article_pages(){
  $sql['drop']   = "DROP TABLE IF EXISTS article_pages";
  $sql['create'] = "CREATE TABLE `article_page` (
  `page_id`  int(11) not null, 
  `article_id` int(11) not null
)";
  return($sql);
}

// GROUP STRUCTURE FOR BLOBS
function create_article_page(){
  $sql['drop']   = "DROP TABLE IF EXISTS article_page";
  $sql['create'] = "CREATE TABLE `article_page` (
  `page_id`  int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) not null default 0,
  `blob_body` text(10000),
  `last_modified` timestamp default current_timestamp on update current_timestamp,
  `create_date` timestamp
) ENGINE=InnoDB DEFAULT CHARSET=utf8
";
  
  return($sql);
}

function create_page_blobs(){
  $sql['drop']   = "DROP TABLE IF EXISTS article_pages";
  $sql['create'] = "CREATE TABLE `article_page` (
  `page_id`  int(11) not null, 
  `article_id` int(11) not null
)";
  return($sql);
}


//
function create_article_blob(){
  $sql['drop']   = "DROP TABLE IF EXISTS article_blob";
  $sql['create'] = "CREATE TABLE `article_blob` (
  `blob_id`  int(11) NOT NULL AUTO_INCREMENT,
  `blob_type` int(5) not null default 0,
  `blob_body` text(10000),
  `last_modified` timestamp default current_timestamp on update current_timestamp,
  `create_date` timestamp,
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8
";

    return($sql);
}








