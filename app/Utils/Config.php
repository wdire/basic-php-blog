<?php 

namespace app\Utils;

    class Config{

        private static $config = null;

        public static function get($key){
            if(!self::$config){
                self::$config = require_once __PATH__ . "/app/config.php";
            }
            return isset(self::$config[$key]) ? self::$config[$key] : null;
        }

    }

?>