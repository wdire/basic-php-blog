<?php 

    namespace app\Utils;

    class Session{

        public static function set($key, $value){
            $_SESSION[$key] = $value;
        }

        public static function exists($key){
            return isset($_SESSION[$key]);
        }

        public static function delete($key){
            if(self::exists($key)){
                unset($_SESSION[$key]);
            }
        }

        public static function get($key){
            if(self::exists($key)){
                return $_SESSION[$key];
            }
            return false;
        }

    }

?>