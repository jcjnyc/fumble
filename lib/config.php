<?php

error_reporting(E_ALL);

// DB 
define('DB_HOST', 'localhost');
define('DB_USER', 'test');
define('DB_PASS', 'test');
define('DB_NAME', 'cb');
define('DB_PORT', 3306);

// PATHS 
define('BASE_DIR',   dirname(__DIR__));
define('LIB_DIR',    BASE_DIR.'/lib');
define('APP_DIR',    BASE_DIR.'/app');
define('FILES_DIR',  BASE_DIR.'/files');
define('ASSETS_DIR', BASE_DIR.'/assets');

// CONTROLLERS 
define('VALID_CONTROLLERS',  'Chartbeat');
define('DEFAULT_CONTROLLER', 'Chartbeat');
define('DEFAULT_ACTION',     'index');

// CHARTBEAT STUFF 
define('SOURCE_URLS', '[ {"gizmodo": "http://api.chartbeat.com/live/toppages/?apikey=317a25eccba186e0f6b558f45214c0e7&host=gizmodo.com&limit=100" }
                       ]' );
define('MAX_BATCH',    30);
define('TRENDING_RESULT_SET', 10);
define('SITE_LIST',    'gizmodo');
define('CACHE_EXPIRE', 60);











