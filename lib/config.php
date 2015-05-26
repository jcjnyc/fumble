<?php

error_reporting(E_ALL);

// DB 
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASS', 'password');
define('DB_NAME', 'db_name');
define('DB_PORT', 3306);

// PATHS 
define('BASE_DIR',   dirname(__DIR__));
define('LIB_DIR',    BASE_DIR.'/lib');
define('APP_DIR',    BASE_DIR.'/app');
define('FILES_DIR',  BASE_DIR.'/files');
define('ASSETS_DIR', BASE_DIR.'/assets');

// CONTROLLERS 
define('VALID_CONTROLLERS',  'Home,User');
define('DEFAULT_CONTROLLER', 'Home');
define('DEFAULT_ACTION',     'index');











