<?php 
    
    ob_start();
    session_start();
    
    //mb_internal_encoding('UTF-8');
    date_default_timezone_set("Europe/Istanbul");
    
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //require __PATH__ . '/vendor/autoload.php';

    spl_autoload_register(function($class){
        $class_path = __PATH__ . "\\" . $class . ".php";
        $class_path = str_replace("\\", DIRECTORY_SEPARATOR, $class_path);
        if(file_exists($class_path)){
            require_once $class_path;
        }
    });

    set_error_handler(function($no, $msg, $file, $line){
        app\Utils\Log::do("[Type: $no] - Message: $msg - File $file - Line: $line");
        echo "Error No:$no";
    });

    register_shutdown_function(function(){
        $err = error_get_last();
        if(isset($err)){
            app\Utils\Log::do("\n---------- ".($err["type"] == 1 ? "[Fatal Error]" : "[Shutdown Error, Type: $err[type]]")." ----------\n" . "Message: " . $err["message"] . " - File: $err[file] - Line $err[line]\n---------- END ----------");
            echo "Fatal Error!";
        }
    });
    
    app\Utils\Routes::load();

?>