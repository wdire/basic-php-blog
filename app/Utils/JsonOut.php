<?php 

    namespace app\Utils;

    class JsonOut{

        public static function do($value){
            return json_encode($value,JSON_UNESCAPED_UNICODE);
        }

        public static function echo($value){
            echo self::do($value);
        }

    }

?>