<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: index.php
 * Date: 26/10/14
 * Time: 14:17
 */
session_start();
define("SCRIPT_START_TIME", microtime(true));
//Define __DIR__ if magic_constant __DIR__ is not set (Supported only from PHP 5.3)
(@__DIR__ == '__DIR__') && define('__DIR__', realpath(dirname(__FILE__)));

include(__DIR__ . "/config.php");

if(DEBUG_MODE){
    ini_set("display_errors", 1);
    error_reporting(E_ALL ^ E_NOTICE);

}
else   error_reporting(0);

include(APP_DIR . '/config/database.config.php');

//Autoload the classes we are going to use.
spl_autoload_register(function($class){

    if(strpos($class, 'Controller')>-1 && file_exists(APP_DIR.'/controllers/'.str_replace('controller', '',strtolower($class)).'.controller.php')){
        include(APP_DIR.'/controllers/'.str_replace('controller', '', strtolower($class)).'.controller.php');
    }elseif(file_exists(SYS_DIR.'/modules/'.$class.'.class.php')){
        include(SYS_DIR.'/modules/'.$class.'.class.php');
    }
});


$core=new Core();
if(DEBUG_MODE){


}
?>