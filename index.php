<?php
 define('DEBUG', false);   

if (DEBUG){
    define('DB_HOST', '127.0.0.1');
    define('DB_PORT', '');
    define('DB_USER', 'test');
    define('DB_PASS', 'test88');
    define('DB_DBASE', 'publisher');
}
else
{
    define('DB_HOST', 'localhost');
    define('DB_PORT', '');
    define('DB_USER', 'test');
    define('DB_PASS', 'test88');
    define('DB_DBASE','publisher');
}

require_once 'restler/restler.php';
require_once 'error.php';

#set autoloader
#do not use spl_autoload_register with out parameter
#it will disable the autoloading of formats
spl_autoload_register('spl_autoload');
$r = new Restler();
$r->addAPIClass('Book');
$r->handle();
